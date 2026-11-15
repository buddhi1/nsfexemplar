<?php
/* =============================================================================
   Download and visit counters, plus a per-download log.

   Two files under data/, both written under an exclusive lock. No database and
   no PHP extension beyond the defaults, so it works on any cPanel host as-is.
   The only requirement is that PHP can write to data/ — the code creates the
   directory and files on first use.

   data/counters.json — the running tally the site displays:
     {
       "schema":    3,
       "started":   "2026-07-31",
       "v1_since":  "2026-11-15",
       "visits":    1234,
       "downloads": { "v1": 40, "early": 212 },
       "legacy":    { "book": 157, "03-cs1-tntech": 2, ... },
       "recent":    { "<hash of ip+file>": <unix time>, ... }
     }
   "legacy" is the per-link tally from before the two versions were split. All
   of it was the early release, so it is folded into downloads.early when an
   old file is first read, and kept as-is for the record.
   "v1_since" is the day the first edition went live. Until then the "v1" key
   served cder_exemplar_cs1_cs2.pdf, which was still the early release, so
   when an older file is first read its "v1" tally is moved into "early", "v1"
   restarts at zero and v1_since is set to that day (schema 3). stats.php
   uses the same date to file earlier "v1" log rows under the early release.
   "recent" is a short-lived map used only to stop one person's repeat clicks
   from inflating the count. Entries expire after REPEAT_WINDOW seconds.

   data/downloads.csv — one row per download request:
     time_utc, file, link, page, counted, ip, referrer, user_agent
   "counted" is 0 for a repeat within REPEAT_WINDOW (still logged, so you can
   see which chapters people open, but not added to the tally).

   Visits are only counted, never logged: no address is kept for them.

   Locations are not looked up here — stats.php resolves them later, so a
   download never waits on a third-party service.
   ========================================================================== */

/* The published files, by version key. Rename here if a file is renamed.
   The key stays 'v1' for the current edition so the tally and the log keep one
   name for it across editions; only the label and file change.

   page_offset: the site quotes the book's printed page numbers (what its
   contents pages and a PDF viewer's page box show), but #page=N in a link
   opens the Nth page of the file. The first edition has a cover and a title
   page before printed page 1, so PDF page = printed page + 2. */
const BOOK_FILES = [
    'v1'    => ['file' => 'cder_exemplar_cs1_cs2_first_edition.pdf', 'label' => 'First edition', 'page_offset' => 2],
    'early' => ['file' => 'cder_exemplar_cs1_cs2_early_release.pdf', 'label' => 'Early release', 'page_offset' => 0],
];

const COUNTER_DIR   = __DIR__ . '/../data';
const COUNTER_FILE  = COUNTER_DIR . '/counters.json';
const DOWNLOAD_LOG  = COUNTER_DIR . '/downloads.csv';
const DOWNLOAD_LOG_COLUMNS = ['time_utc', 'file', 'link', 'page', 'counted', 'ip', 'referrer', 'user_agent'];

/** The same visitor fetching the same file again within this many seconds is not a new download. */
const REPEAT_WINDOW = 1800;

/** Obvious crawlers, so the numbers mean something. Not exhaustive by design. */
function counters_is_bot(): bool {
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    if ($ua === '') return true;
    return (bool) preg_match(
        '~bot|crawl|spider|slurp|bingpreview|facebookexternalhit|embedly|quora|pinterest'
        . '|vkshare|whatsapp|telegram|discordbot|preview|monitor|uptime|curl|wget|python-requests'
        . '|headlesschrome|lighthouse|pagespeed|gtmetrix~i',
        $ua
    );
}

/**
 * The visitor's address. REMOTE_ADDR is the only value the client cannot
 * forge; forwarding headers are ignored on purpose. If the site is ever put
 * behind Cloudflare or another proxy, every visitor will show the proxy's
 * address and this is the one place to change.
 */
function counters_client_ip(): string {
    return (string) ($_SERVER['REMOTE_ADDR'] ?? '');
}

function counters_defaults(): array {
    return [
        'schema'    => 3,
        'started'   => date('Y-m-d'),
        'v1_since'  => gmdate('Y-m-d'),   // UTC, like the log
        'visits'    => 0,
        'downloads' => ['v1' => 0, 'early' => 0],
        'legacy'    => [],
        'recent'    => [],
    ];
}

/**
 * Bring any stored shape up to schema 2. Before the split, "downloads" held one
 * tally per link key and every one of those downloads was the early release.
 */
function counters_normalize(array $data): array {
    if (($data['schema'] ?? 1) < 2) {
        $legacy = is_array($data['downloads'] ?? null) ? $data['downloads'] : [];
        $data['legacy']    = $legacy;
        $data['downloads'] = ['v1' => 0, 'early' => array_sum(array_map('intval', $legacy))];
        $data['schema']    = 2;
    }
    /* Schema 3: the first edition starts its own count. Everything counted
       under "v1" so far was the early release (see the header comment). */
    if (($data['schema'] ?? 1) < 3) {
        $dl = is_array($data['downloads'] ?? null) ? $data['downloads'] : [];
        $data['downloads'] = [
            'v1'    => 0,
            'early' => (int) ($dl['early'] ?? 0) + (int) ($dl['v1'] ?? 0),
        ];
        $data['v1_since'] = gmdate('Y-m-d');   // UTC, like the log
        $data['schema']   = 3;
    }
    $data += counters_defaults();
    $data['downloads'] += ['v1' => 0, 'early' => 0];
    return $data;
}

/** Read without locking — for display. Never throws; returns defaults if absent. */
function counters_read(): array {
    if (!is_file(COUNTER_FILE)) return counters_defaults();
    $raw = @file_get_contents(COUNTER_FILE);
    if ($raw === false) return counters_defaults();
    $data = json_decode($raw, true);
    if (!is_array($data)) return counters_defaults();
    return counters_normalize($data);
}

/**
 * Apply $mutate to the counter array under an exclusive lock.
 * Silently does nothing if the store is not writable, so a permissions
 * problem can never take the site down — it only stops counting.
 */
function counters_update(callable $mutate): void {
    if (!is_dir(COUNTER_DIR)) @mkdir(COUNTER_DIR, 0775, true);
    if (!is_dir(COUNTER_DIR)) return;

    $fh = @fopen(COUNTER_FILE, 'c+');
    if ($fh === false) return;

    if (flock($fh, LOCK_EX)) {
        clearstatcache(true, COUNTER_FILE);
        $size = filesize(COUNTER_FILE);
        $raw  = $size > 0 ? fread($fh, $size) : '';
        $data = json_decode($raw ?: '', true);
        $data = counters_normalize(is_array($data) ? $data : counters_defaults());

        $data = $mutate($data);

        rewind($fh);
        ftruncate($fh, 0);
        fwrite($fh, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        fflush($fh);
        flock($fh, LOCK_UN);
    }
    fclose($fh);
}

/** One visit per browser session, not per page view. */
function counters_bump_visit(): void {
    if (counters_is_bot()) return;
    if (PHP_SAPI === 'cli') return;
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params(['lifetime' => 0, 'httponly' => true, 'samesite' => 'Lax']);
        @session_start();
    }
    if (!empty($_SESSION['counted'])) return;
    $_SESSION['counted'] = true;
    counters_update(function (array $d): array {
        $d['visits'] = ($d['visits'] ?? 0) + 1;
        return $d;
    });
}

/**
 * Record one download of $version ('v1' or 'early'), reached through link key
 * $link, optionally opened at $page. Counts it unless the same visitor fetched
 * the same file within REPEAT_WINDOW, and logs it either way.
 */
function counters_bump_download(string $version, string $link, ?int $page): void {
    if (counters_is_bot()) return;
    if (!isset(BOOK_FILES[$version])) return;

    $ip      = counters_client_ip();
    $now     = time();
    $hash    = substr(hash('sha256', $ip . '|' . $version), 0, 16);
    $counted = false;

    counters_update(function (array $d) use ($version, $hash, $now, &$counted): array {
        /* Drop expired entries so the map stays small. */
        $recent = [];
        foreach (($d['recent'] ?? []) as $h => $t) {
            if ($now - (int) $t < REPEAT_WINDOW) $recent[$h] = (int) $t;
        }
        if (!isset($recent[$hash])) {
            $d['downloads'][$version] = ((int) ($d['downloads'][$version] ?? 0)) + 1;
            $counted = true;
        }
        $recent[$hash] = $now;
        $d['recent'] = $recent;
        return $d;
    });

    counters_append_csv(DOWNLOAD_LOG, DOWNLOAD_LOG_COLUMNS, [
        gmdate('Y-m-d\TH:i:s\Z', $now),
        $version,
        $link,
        $page ?? '',
        $counted ? 1 : 0,
        $ip,
        substr((string) ($_SERVER['HTTP_REFERER'] ?? ''), 0, 300),
        substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 300),
    ]);
}

/** Append one row to a CSV log, writing the header first if the file is new. Fails silently, like the tally. */
function counters_append_csv(string $file, array $columns, array $row): void {
    if (!is_dir(COUNTER_DIR)) @mkdir(COUNTER_DIR, 0775, true);
    $fh = @fopen($file, 'a');
    if ($fh === false) return;
    if (flock($fh, LOCK_EX)) {
        clearstatcache(true, $file);
        if (filesize($file) === 0) fputcsv($fh, $columns, ',', '"', '');
        /* A leading = + - @ would make a spreadsheet treat a referrer or user
           agent as a formula when the log is opened in Excel. */
        $row = array_map(function ($v) {
            $v = (string) $v;
            return ($v !== '' && strpbrk($v[0], '=+-@') !== false) ? "'" . $v : $v;
        }, $row);
        fputcsv($fh, $row, ',', '"', '');
        fflush($fh);
        flock($fh, LOCK_UN);
    }
    fclose($fh);
}

/* ---------------------------------------------------------------- targets --
   The whitelist is fixed in BOOK_FILES, so a crafted ?f= can never escape the
   site root.                                                               */

/**
 * Which version a link key serves, or null if the key is unknown or the file
 * is missing. "book" and the old per-chapter keys ("03-cs1-tntech", …) are the
 * current volume, so older links and bookmarks keep working.
 */
function counters_download_version(string $key): ?string {
    if ($key === 'early')                                                     $version = 'early';
    elseif ($key === 'book' || $key === 'v1' || preg_match('/^\d{2}-[a-z0-9-]+$/', $key)) $version = 'v1';
    else return null;
    return is_file(__DIR__ . '/../' . BOOK_FILES[$version]['file']) ? $version : null;
}

/**
 * Page to open the volume at. PDF viewers honour #page=N, which is how the
 * site offers chapter-level access without publishing chapter extracts.
 * Bounded so a crafted value cannot produce a silly fragment.
 */
function counters_download_page(string $raw): ?int {
    if ($raw === '' || !ctype_digit($raw)) return null;
    $n = (int) $raw;
    return ($n >= 1 && $n <= 9999) ? $n : null;
}

/** Downloads of one version: 'v1' or 'early'. */
function counters_downloads(array $data, string $version): int {
    return (int) ($data['downloads'][$version] ?? 0);
}

/** Every download of every version. */
function counters_downloads_total(array $data): int {
    $sum = 0;
    foreach (($data['downloads'] ?? []) as $v) $sum += (int) $v;
    return $sum;
}

/** Human-friendly figure: 1234 -> "1,234". */
function counters_fmt(int $n): string {
    return number_format($n);
}
