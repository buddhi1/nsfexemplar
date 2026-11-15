<?php
/* =============================================================================
   Download statistics — private, password protected.

   Reads data/downloads.csv (written by download.php) and shows downloads per
   version, by country and by region, and the most recent downloads with their
   time, address and location. "Export CSV" gives the whole log with location
   columns added, ready for Excel. Visits are only a tally — no addresses are
   kept for them.

   Setup (once): create data/admin.php in cPanel's File Manager, holding
       <?php return ['password' => 'choose-a-long-password'];
   data/admin_example.php shows the same thing. data/ is never served, and the
   file is ignored by git, so the password stays on the server.

   Locations: each new address is looked up once through ip-api.com's free
   batch service (country, region, city) and cached in data/geo-cache.json, so
   later visits to this page are instant and an address is never sent twice.
   Downloads themselves never wait on the lookup — it happens only here.
   ========================================================================== */

require __DIR__ . '/lib/counters.php';

const ADMIN_CONFIG = COUNTER_DIR . '/admin.php';
const GEO_CACHE    = COUNTER_DIR . '/geo-cache.json';
/** ip-api allows 15 batch calls a minute on the free tier; stay well under. */
const GEO_MAX_BATCHES_PER_LOAD = 5;

header('X-Robots-Tag: noindex, nofollow');
header('Cache-Control: no-store');

function h(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

/* ----------------------------------------------------------------- login -- */

session_set_cookie_params(['lifetime' => 0, 'httponly' => true, 'samesite' => 'Strict',
                           'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off']);
@session_start();

$config = is_file(ADMIN_CONFIG) ? (include ADMIN_CONFIG) : null;
$configured = is_array($config) && (!empty($config['password']) || !empty($config['password_hash']));

function stats_password_ok(array $config, string $given): bool {
    if (!empty($config['password_hash'])) return password_verify($given, (string) $config['password_hash']);
    return hash_equals((string) $config['password'], $given);
}

if (isset($_GET['logout'])) {
    $_SESSION = [];
    session_destroy();
    header('Location: ?', true, 303);
    exit;
}

$loginError = '';
if ($configured && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['password'])) {
    if (stats_password_ok($config, (string) $_POST['password'])) {
        session_regenerate_id(true);
        $_SESSION['stats_admin'] = true;
        header('Location: ?', true, 303);
        exit;
    }
    sleep(2); /* slows guessing; the page is not worth a lockout system */
    $loginError = 'That password is not right.';
}

if (!$configured || empty($_SESSION['stats_admin'])) {
    stats_page_start('Download statistics');
    if (!$configured) {
        echo '<p>Statistics are not set up yet. Create <code>data/admin.php</code> on the server containing:</p>'
           . "<pre>&lt;?php return ['password' =&gt; 'choose-a-long-password'];</pre>"
           . '<p class="muted">See <code>data/admin_example.php</code>.</p>';
    } else {
        echo '<form method="post" action="" class="login">'
           . '<label for="pw">Password</label>'
           . '<input id="pw" name="password" type="password" autocomplete="current-password" autofocus required>'
           . '<button type="submit">Sign in</button>'
           . ($loginError ? '<p class="error">' . h($loginError) . '</p>' : '')
           . '</form>';
    }
    stats_page_end();
    exit;
}

/* ------------------------------------------------------------------- data -- */

/** Every row of a CSV log as an associative array, oldest first. */
function stats_read_log(string $file): array {
    if (!is_file($file)) return [];
    $fh = @fopen($file, 'r');
    if ($fh === false) return [];
    flock($fh, LOCK_SH);
    $rows = [];
    $head = fgetcsv($fh, null, ',', '"', '');
    while (($r = fgetcsv($fh, null, ',', '"', '')) !== false) {
        if ($head && count($r) === count($head)) $rows[] = array_combine($head, $r);
    }
    flock($fh, LOCK_UN);
    fclose($fh);
    return $rows;
}

function stats_geo_cache(): array {
    $raw  = is_file(GEO_CACHE) ? @file_get_contents(GEO_CACHE) : false;
    $data = $raw ? json_decode($raw, true) : null;
    return is_array($data) ? $data : [];
}

/** POST a JSON body and decode the JSON reply. cURL if present, else streams. */
function stats_http_post_json(string $url, array $body): ?array {
    $payload = json_encode($body);
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true, CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 8, CURLOPT_CONNECTTIMEOUT => 4,
        ]);
        $out = curl_exec($ch);
        curl_close($ch);
    } elseif (ini_get('allow_url_fopen')) {
        $ctx = stream_context_create(['http' => [
            'method' => 'POST', 'header' => "Content-Type: application/json\r\n",
            'content' => $payload, 'timeout' => 8,
        ]]);
        $out = @file_get_contents($url, false, $ctx);
    } else {
        return null;
    }
    $data = is_string($out) ? json_decode($out, true) : null;
    return is_array($data) ? $data : null;
}

/**
 * Look up any addresses not yet in the cache, 100 at a time, and save the
 * cache. Private and reserved addresses are labelled without a lookup.
 * Returns the cache and how many addresses are still unresolved.
 */
function stats_resolve(array $ips): array {
    $cache   = stats_geo_cache();
    $pending = [];
    foreach ($ips as $ip) {
        if ($ip === '' || isset($cache[$ip])) continue;
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            $cache[$ip] = ['cc' => '', 'country' => 'Private network', 'region' => '', 'city' => ''];
            continue;
        }
        $pending[] = $ip;
    }

    $batches = array_slice(array_chunk($pending, 100), 0, GEO_MAX_BATCHES_PER_LOAD);
    foreach ($batches as $batch) {
        $reply = stats_http_post_json(
            'http://ip-api.com/batch?fields=status,country,countryCode,regionName,city,query', $batch);
        if ($reply === null) break; /* service unreachable — try again next load */
        foreach ($reply as $r) {
            if (!isset($r['query'])) continue;
            $cache[$r['query']] = ($r['status'] ?? '') === 'success'
                ? ['cc' => $r['countryCode'] ?? '', 'country' => $r['country'] ?? '',
                   'region' => $r['regionName'] ?? '', 'city' => $r['city'] ?? '']
                : ['cc' => '', 'country' => 'Unknown', 'region' => '', 'city' => ''];
        }
    }

    if ($batches) {
        @file_put_contents(GEO_CACHE, json_encode($cache, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX);
    }
    $unresolved = 0;
    foreach (array_unique($ips) as $ip) if ($ip !== '' && !isset($cache[$ip])) $unresolved++;
    return [$cache, $unresolved];
}

$rows   = stats_read_log(DOWNLOAD_LOG);
/* Before the first edition went live, "v1" served the old file name, which was
   still the early release. File those rows under the early release so the
   tables agree with the tally (see v1_since in lib/counters.php). */
$v1Since = (string) (counters_read()['v1_since'] ?? '');
if ($v1Since !== '') {
    foreach ($rows as &$r) {
        if ($r['file'] === 'v1' && substr($r['time_utc'], 0, 10) < $v1Since) $r['file'] = 'early';
    }
    unset($r);
}
$ips    = array_values(array_unique(array_column($rows, 'ip')));
[$geo, $unresolved] = stats_resolve($ips);

function stats_geo(array $geo, string $ip): array {
    return $geo[$ip] ?? ['cc' => '', 'country' => 'Not yet looked up', 'region' => '', 'city' => ''];
}

/* ----------------------------------------------------------------- export -- */

if (isset($_GET['export'])) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="downloads-' . gmdate('Y-m-d') . '.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, array_merge(DOWNLOAD_LOG_COLUMNS, ['country', 'region', 'city']), ',', '"', '');
    foreach ($rows as $r) {
        $g = stats_geo($geo, $r['ip']);
        fputcsv($out, array_merge(array_values($r), [$g['country'], $g['region'], $g['city']]), ',', '"', '');
    }
    fclose($out);
    exit;
}

/* ------------------------------------------------------------- summaries -- */

$counts   = counters_read();
$versions = array_keys(BOOK_FILES);
$byCountry = [];  /* country => [v1 => n, early => n, total => n] */
$byRegion  = [];  /* "region, country" => same */
$counted   = array_filter($rows, fn($r) => $r['counted'] === '1');

function stats_tally(array &$table, string $key, string $version, array $versions): void {
    $table[$key] ??= array_fill_keys($versions, 0) + ['total' => 0];
    if (isset($table[$key][$version])) $table[$key][$version]++;
    $table[$key]['total']++;
}

foreach ($counted as $r) {
    $g = stats_geo($geo, $r['ip']);
    stats_tally($byCountry, $g['country'], $r['file'], $versions);
    stats_tally($byRegion, ($g['region'] !== '' ? $g['region'] . ', ' : '') . $g['country'], $r['file'], $versions);
}
$byTotal = fn($a, $b) => $b['total'] <=> $a['total'];
uasort($byCountry, $byTotal);
uasort($byRegion, $byTotal);

$recent = array_slice(array_reverse($rows), 0, 200);

/* ------------------------------------------------------------------- page -- */

stats_page_start('Download statistics');
?>
<p class="bar">
  <a href="?export=csv">Export CSV (with locations)</a>
  <a href="?logout">Sign out</a>
</p>

<section class="tiles">
  <div><b><?= counters_fmt((int) ($counts['visits'] ?? 0)) ?></b><span>Site visits</span></div>
  <?php foreach (BOOK_FILES as $k => $f): ?>
    <div><b><?= counters_fmt(counters_downloads($counts, $k)) ?></b><span><?= h($f['label']) ?> downloads</span></div>
  <?php endforeach; ?>
</section>
<p class="muted">Tallies since <?= h((string) ($counts['started'] ?? '')) ?>; the first edition since
  <?= h((string) ($counts['v1_since'] ?? '')) ?> &mdash; earlier downloads were the early release and are counted there. Repeat downloads of the same file
  from the same address within <?= REPEAT_WINDOW / 60 ?> minutes are logged but not counted.
  <?php if (!empty($counts['legacy'])): ?>
    Early-release downloads include <?= counters_fmt(array_sum(array_map('intval', $counts['legacy']))) ?>
    counted before the per-download log existed, so they have no time or location.
  <?php endif; ?>
</p>

<?php if (!$rows): ?>
  <p>No downloads logged yet.</p>
<?php else: ?>
  <?php if ($unresolved): ?>
    <p class="note"><?= $unresolved ?> address<?= $unresolved === 1 ? '' : 'es' ?> still to look up —
      reload in a minute.</p>
  <?php endif; ?>

  <?php foreach (['By country' => $byCountry, 'By region' => $byRegion] as $title => $table): ?>
    <h2><?= h($title) ?></h2>
    <div class="scroll">
    <table>
      <thead><tr><th><?= $title === 'By country' ? 'Country' : 'Region' ?></th>
        <?php foreach (BOOK_FILES as $f): ?><th class="n"><?= h($f['label']) ?></th><?php endforeach; ?>
        <th class="n">Total</th></tr></thead>
      <tbody>
      <?php foreach ($table as $name => $c): ?>
        <tr><td><?= h($name) ?></td>
          <?php foreach ($versions as $v): ?><td class="n"><?= $c[$v] ?></td><?php endforeach; ?>
          <td class="n"><b><?= $c['total'] ?></b></td></tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    </div>
  <?php endforeach; ?>
  <p class="muted small">These tables come from the download log, which started later than the
    tallies above, so they can be lower.</p>

  <h2>Recent downloads</h2>
  <p class="muted">Newest first, last <?= count($recent) ?>. Times are UTC. Faded rows are repeats.</p>
  <div class="scroll">
  <table>
    <thead><tr><th>Time (UTC)</th><th>File</th><th class="n">Page</th><th>IP</th><th>Location</th><th>Referrer</th></tr></thead>
    <tbody>
    <?php foreach ($recent as $r):
      $g = stats_geo($geo, $r['ip']);
      $loc = implode(', ', array_filter([$g['city'], $g['region'], $g['country']])); ?>
      <tr<?= $r['counted'] === '1' ? '' : ' class="repeat"' ?>>
        <td class="nowrap"><?= h(str_replace(['T', 'Z'], [' ', ''], $r['time_utc'])) ?></td>
        <td><?= h(BOOK_FILES[$r['file']]['label'] ?? $r['file']) ?></td>
        <td class="n"><?= h($r['page']) ?></td>
        <td class="nowrap"><?= h($r['ip']) ?></td>
        <td><?= h($loc) ?></td>
        <td class="ref"><?= h($r['referrer']) ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  </div>

  <p class="muted small">Location data: <a href="https://ip-api.com">ip-api.com</a>. City-level results are approximate.</p>
<?php endif;

stats_page_end();

/* ------------------------------------------------------------ page shell -- */

function stats_page_start(string $title): void { ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title><?= h($title) ?></title>
<style>
  :root { color-scheme: light dark; --fg: #1d1d1f; --muted: #6b6b70; --line: #dcdce0; --bg: #fff; --sunk: #f5f5f7; --accent: #9b2246; }
  @media (prefers-color-scheme: dark) { :root { --fg: #ececee; --muted: #a0a0a8; --line: #3a3a40; --bg: #18181b; --sunk: #222226; --accent: #f08aa6; } }
  * { box-sizing: border-box; }
  body { margin: 0; padding: 24px 16px 64px; font: 15px/1.5 system-ui, sans-serif; color: var(--fg); background: var(--bg); }
  main { max-width: 1100px; margin: 0 auto; }
  h1 { font-size: 1.5rem; margin: 0 0 16px; } h2 { font-size: 1.1rem; margin: 32px 0 8px; }
  a { color: var(--accent); }
  .bar { display: flex; gap: 16px; }
  .tiles { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 12px; margin: 16px 0 8px; }
  .tiles div { background: var(--sunk); border-radius: 10px; padding: 14px 16px; }
  .tiles b { display: block; font-size: 1.8rem; line-height: 1.1; }
  .tiles span { color: var(--muted); font-size: 0.85rem; }
  .muted { color: var(--muted); } .small { font-size: 0.85rem; }
  .note { background: var(--sunk); border-left: 3px solid var(--accent); padding: 8px 12px; }
  .scroll { overflow-x: auto; }
  table { border-collapse: collapse; width: 100%; font-size: 0.9rem; }
  th, td { text-align: left; padding: 6px 10px; border-bottom: 1px solid var(--line); vertical-align: top; }
  th { font-weight: 600; background: var(--sunk); }
  .n { text-align: right; font-variant-numeric: tabular-nums; }
  .nowrap { white-space: nowrap; }
  .ref { max-width: 280px; overflow-wrap: anywhere; color: var(--muted); }
  tr.repeat td { opacity: 0.5; }
  .login { display: grid; gap: 8px; max-width: 320px; }
  .login input, .login button { font: inherit; padding: 8px 10px; border-radius: 8px; border: 1px solid var(--line); }
  .login button { background: var(--accent); color: var(--bg); border: 0; cursor: pointer; }
  .error { color: var(--accent); }
  pre { background: var(--sunk); padding: 10px 12px; border-radius: 8px; overflow-x: auto; }
</style>
</head>
<body><main>
<h1><?= h($title) ?></h1>
<?php }

function stats_page_end(): void { ?>
</main></body>
</html>
<?php }
