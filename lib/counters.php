<?php
/* =============================================================================
   Download and visit counters.

   Storage is a single JSON file under data/, updated under an exclusive lock.
   No database and no PHP extension beyond the defaults, so it works on any
   cPanel host as-is. The only requirement is that PHP can write to data/ —
   the code creates the directory and file on first use.

   Shape:
     {
       "started":   "2026-07-31",
       "visits":    1234,
       "downloads": { "book": 210, "03-cs1-tntech": 44, ... }
     }
   ========================================================================== */

/* The published volume. Rename here if the file is ever renamed. */
const BOOK_FILE = 'cder_exemplar_cs1_cs2.pdf';

const COUNTER_DIR  = __DIR__ . '/../data';
const COUNTER_FILE = COUNTER_DIR . '/counters.json';

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

function counters_defaults(): array {
    return ['started' => date('Y-m-d'), 'visits' => 0, 'downloads' => []];
}

/** Read without locking — for display. Never throws; returns defaults if absent. */
function counters_read(): array {
    if (!is_file(COUNTER_FILE)) return counters_defaults();
    $raw = @file_get_contents(COUNTER_FILE);
    if ($raw === false) return counters_defaults();
    $data = json_decode($raw, true);
    if (!is_array($data)) return counters_defaults();
    return $data + counters_defaults();
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
        $size = filesize(COUNTER_FILE);
        $raw  = $size > 0 ? fread($fh, $size) : '';
        $data = json_decode($raw ?: '', true);
        if (!is_array($data)) $data = counters_defaults();
        $data += counters_defaults();

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

function counters_bump_download(string $key): void {
    if (counters_is_bot()) return;
    counters_update(function (array $d) use ($key): array {
        $d['downloads'][$key] = ($d['downloads'][$key] ?? 0) + 1;
        return $d;
    });
}

/* ---------------------------------------------------------------- targets --
   The whitelist is derived from what is actually on disk, so a crafted ?f=
   can never escape the site root.                                          */

function counters_download_target(string $key): ?string {
    $root = __DIR__ . '/..';
    if (!is_file("$root/" . BOOK_FILE)) return null;
    /* One published file now. Chapters used to be served separately; those keys
       still resolve to the volume so older links and bookmarks keep working
       rather than 404ing, and they keep counting under their own name. */
    if ($key === 'book' || preg_match('/^\d{2}-[a-z0-9-]+$/', $key)) return BOOK_FILE;
    return null;
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

/** Convenience for templates: downloads for one key. */
function counters_downloads(array $data, string $key): int {
    return (int) ($data['downloads'][$key] ?? 0);
}

/** Every chapter download added together (excludes the full volume). */
/**
 * Every download, whatever key it was counted under. All keys now serve the
 * same file — chapters stopped being published separately — so the total is
 * the only figure that means anything.
 */
function counters_downloads_total(array $data): int {
    $sum = 0;
    foreach (($data['downloads'] ?? []) as $v) $sum += (int) $v;
    return $sum;
}

/** Downloads counted under the old per-chapter keys, for the historical note. */
function counters_chapter_total(array $data): int {
    $sum = 0;
    foreach (($data['downloads'] ?? []) as $k => $v) {
        if ($k !== 'book') $sum += (int) $v;
    }
    return $sum;
}

/** Human-friendly figure: 1234 -> "1,234". */
function counters_fmt(int $n): string {
    return number_format($n);
}
