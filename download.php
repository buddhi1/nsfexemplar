<?php
/* =============================================================================
   Counted download endpoint.

     download.php?f=book                 → cder_exemplar_cs1_cs2.pdf
     download.php?f=03-cs1-tntech        → chapters/03-cs1-tntech.pdf

   Records the download, then redirects to the real file so the browser handles
   range requests and resuming itself. The PDFs stay directly reachable; this
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

header('Cache-Control: no-store');
header('Location: ' . $target, true, 302);
exit;
