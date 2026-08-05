<?php
/* =============================================================================
   Counted download endpoint.

     download.php?f=book                 → cder_exemplar_cs1_cs2.pdf
     download.php?f=book&p=63            → cder_exemplar_cs1_cs2.pdf#page=63
     download.php?f=03-cs1-tntech        → the volume. Chapters are no longer
                                           published separately; the old keys
                                           still resolve so existing links and
                                           bookmarks do not 404.

   Records the download, then redirects to the real file so the browser handles
   range requests and resuming itself. The PDF stays directly reachable; this
   only counts the ones the site sends people to.
   ========================================================================== */

require __DIR__ . '/lib/counters.php';

$key    = isset($_GET['f']) ? (string) $_GET['f'] : '';
$target = counters_download_target($key);

if ($target === null) {
    http_response_code(404);
    header('Content-Type: text/html; charset=utf-8');
    echo '<!doctype html><meta charset="utf-8"><title>Not found</title>'
       . '<p>That file does not exist. <a href="ebook.php#chapters">Browse the chapters</a>.</p>';
    exit;
}

counters_bump_download($key);

/* #page=N asks the viewer to open at that page. Appended here rather than
   relying on the fragment surviving the redirect — a client is only obliged to
   carry one across by convention, and not all of them do. */
$page = counters_download_page(isset($_GET['p']) ? (string) $_GET['p'] : '');
if ($page !== null) $target .= '#page=' . $page;

header('Cache-Control: no-store');
header('Location: ' . $target, true, 302);
exit;
