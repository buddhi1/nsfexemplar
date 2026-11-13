<?php
/* =============================================================================
   Counted download endpoint.

     download.php?f=book                 → Version 1 (cder_exemplar_cs1_cs2.pdf)
     download.php?f=book&p=63            → Version 1, opened at page 63
     download.php?f=early                → the early release
                                           (cder_exemplar_cs1_cs2_early_release.pdf)
     download.php?f=03-cs1-tntech        → Version 1. Chapters are no longer
                                           published separately; the old keys
                                           still resolve so existing links and
                                           bookmarks do not 404.

   Records the download (lib/counters.php), then redirects to the real file so
   the browser handles range requests and resuming itself. The PDFs stay
   directly reachable; this only counts the ones the site sends people to.
   ========================================================================== */

require __DIR__ . '/lib/counters.php';

$key     = isset($_GET['f']) ? (string) $_GET['f'] : '';
$version = counters_download_version($key);

if ($version === null) {
    http_response_code(404);
    header('Content-Type: text/html; charset=utf-8');
    echo '<!doctype html><meta charset="utf-8"><title>Not found</title>'
       . '<p>That file does not exist. <a href="ebook.php#chapters">Browse the chapters</a>.</p>';
    exit;
}

$page = counters_download_page(isset($_GET['p']) ? (string) $_GET['p'] : '');
counters_bump_download($version, $key, $page);

/* #page=N asks the viewer to open at that page. Appended here rather than
   relying on the fragment surviving the redirect — a client is only obliged to
   carry one across by convention, and not all of them do. */
$target = BOOK_FILES[$version]['file'];
if ($page !== null) $target .= '#page=' . $page;

header('Cache-Control: no-store');
header('Location: ' . $target, true, 302);
exit;
