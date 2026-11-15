<?php
/* =============================================================================
   Shared page chrome — the ONLY place the nav bar is defined.
   Edit this file to change the header on every page.

   Each page sets these before including it:
     $PAGE        nav key: home | project | ebook | team | research | resources
     $PAGE_TITLE  page name; the project name is appended automatically
     $DESC        meta description
     $OG          optional ['title' => …, 'description' => …] for social cards
   ========================================================================== */

/* Counters: one visit per session, and the figures for any page that shows
   them. Never fatal — if data/ is not writable it simply stops counting. */
require_once __DIR__ . '/../lib/counters.php';
require_once __DIR__ . '/../lib/contact.php';   /* CONTACT_EMAIL, form handling */
counters_bump_visit();
$COUNTS = counters_read();

/* The project's full name, as awarded. Change it here and it changes
   everywhere: masthead, page titles, footer. */
$PROJECT_NAME  = 'Modern Course Exemplars infused with Parallel and Distributed Computing '
               . 'for the Introductory Computing Course Sequence';
$PROJECT_SHORT = 'Modern Course Exemplars';
$PROJECT_TAG   = 'Infused with Parallel &amp; Distributed Computing for the Introductory Computing Course Sequence';

/* -------------------------------------------------------------------- DOI --
   Each edition has its own registered identifier. The first edition is the
   volume of record; the early release (August 2026, CS1 only) keeps its DOI
   so anything that cited it still resolves. Change them here and every badge,
   citation and BibTeX entry follows. */
$DOI           = '10.14809/20.500.12588/7992.2';
$DOI_URL       = 'https://doi.org/' . $DOI;
$DOI_EARLY     = '10.14809/4mcf-5jmt';
$DOI_EARLY_URL = 'https://doi.org/' . $DOI_EARLY;

/* ---------------------------------------------------------------- citation --
   The citations as the volumes print them ("How to cite this e-book" on the
   title page), used verbatim wherever the site offers one to copy. */
$CITE_AUTHORS = 'Prasad, S. K., Weems, C., Thota, N., Sussman, A., Vaidyanathan, R., Gannod, G., '
              . 'Crockett, A., Bunde, D., Spacco, J., Srivastava, S., Bourke, C., Suo, X., Maher, P., '
              . 'Gruner, C., Smith, M., Zhu, M., and Wang, J.';
$CITE_TITLE   = 'Toward Modern Models of Introductory Computing Courses – CS1 and CS2 Course '
              . 'Exemplars infused with Parallel and Distributed Computing Concepts.';
$CITATION = $CITE_AUTHORS . ' Nov. 2026. ' . $CITE_TITLE . ' First Edition. CDER Center. '
          . 'NSF Award #2321015-20. ' . $DOI_URL . '.';
$CITATION_EARLY = $CITE_AUTHORS . ' Aug. 2026 (early release). ' . $CITE_TITLE . ' CDER Center. '
                . 'NSF Award #2321015. ' . $DOI_EARLY_URL . '.';

/**
 * A two-segment DOI badge in the Zenodo/shields idiom: a neutral label, then
 * the identifier itself. The identifier is a button that copies the resolver
 * URL; the arrow beside it opens the record, since a DOI you cannot follow is
 * not much use. They are siblings rather than nested because a link inside a
 * button is not valid content — the pill shape is drawn by the wrapper.
 */
function doi_badge(string $doi, string $url, string $class = '', string $edition = ''): string {
    $copy = '<svg class="doi-ico doi-ico--copy" viewBox="0 0 24 24" fill="none" stroke="currentColor" '
          . 'stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" '
          . 'focusable="false"><rect x="9" y="9" width="11" height="11" rx="2"/>'
          . '<path d="M5 15V5a2 2 0 0 1 2-2h10"/></svg>';
    $done = '<svg class="doi-ico doi-ico--done" viewBox="0 0 24 24" fill="none" stroke="currentColor" '
          . 'stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" '
          . 'focusable="false"><path d="M4 12.5 9 17.5 20 6.5"/></svg>';
    $out  = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" '
          . 'stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">'
          . '<path d="M7 17 17 7M9 7h8v8"/></svg>';

    /* $edition names which volume the identifier belongs to, now that there
       is more than one; it sits between the label and the identifier. */
    $for  = $edition !== '' ? ' for the ' . strtolower($edition) : '';
    /* a phone has room for "1st ed." beside the long identifier, not "First edition" */
    $short = ['First edition' => '1st ed.', 'Early release' => 'Early'][$edition] ?? $edition;
    return '<span class="doi ' . e($class) . '">'
         . '<button class="doi-badge" type="button" data-copy="' . e($url) . '"'
         . ' data-copy-noun="DOI link"'
         . ' aria-label="Copy the DOI link' . e($for) . ', ' . e($url) . '" title="Copy the DOI link">'
         . '<span class="doi-label">DOI</span>'
         . ($edition !== '' ? '<span class="doi-ed" data-short="' . e($short) . '"><span class="doi-ed-full">'
                              . e($edition) . '</span></span>' : '')
         . '<span class="doi-value">' . e($doi) . $copy . $done . '</span>'
         /* role=status so the copy is announced; the icon swap covers everyone else */
         . '<span class="visually-hidden" role="status" data-copy-label></span>'
         . '</button>'
         . '<a class="doi-open" href="' . e($url) . '" title="Open the DOI record">'
         . $out . '<span class="visually-hidden">Open the DOI record' . e($for) . ' at doi.org</span>'
         . '</a></span>';
}

/* BibTeX for the same volume. Author names are the initials used in the
   citation above rather than expanded forms, so the two never disagree. */
$BIBTEX = <<<BIB
@book{cder2026exemplars,
  title     = {Toward Modern Models of Introductory Computing Courses:
               CS1 and CS2 Course Exemplars infused with Parallel and
               Distributed Computing Concepts},
  editor    = {Prasad, S. K. and Weems, C. and Thota, N. and Sussman, A. and
               Vaidyanathan, R. and Gannod, G. and Crockett, A. and Bunde, D. and
               Spacco, J. and Srivastava, S. and Bourke, C. and Suo, X. and
               Maher, P. and Gruner, C. and Smith, M. and Zhu, M. and Wang, J.},
  edition   = {First},
  year      = {2026},
  month     = nov,
  publisher = {CDER Center},
  doi       = {{$DOI}},
  url       = {{$DOI_URL}},
  note      = {NSF Award #2321015-20}
}
BIB;

$BIBTEX_EARLY = <<<BIB
@book{cder2026exemplarsearly,
  title     = {Toward Modern Models of Introductory Computing Courses:
               CS1 and CS2 Course Exemplars infused with Parallel and
               Distributed Computing Concepts},
  editor    = {Prasad, S. K. and Weems, C. and Thota, N. and Sussman, A. and
               Vaidyanathan, R. and Gannod, G. and Crockett, A. and Bunde, D. and
               Spacco, J. and Srivastava, S. and Bourke, C. and Suo, X. and
               Maher, P. and Gruner, C. and Smith, M. and Zhu, M. and Wang, J.},
  year      = {2026},
  month     = aug,
  publisher = {CDER Center},
  doi       = {{$DOI_EARLY}},
  url       = {{$DOI_EARLY_URL}},
  note      = {Early release (CS1). NSF Award #2321015}
}
BIB;

$NAV = [
    'home'      => ['/',     'Home'],
    'project'   => ['project.php',   'Project'],
    'ebook'     => ['ebook.php',     'eBook'],
    'team'      => ['team.php',      'Team'],
    'research'  => ['research.php',  'Research'],
    'resources' => ['resources.php', 'Resources'],
];

/* Set apart from the page links: this one leaves the site. */
$CDER_HOME = 'https://cdercenter.org/';

$PAGE       = $PAGE       ?? 'home';
$PAGE_TITLE = $PAGE_TITLE ?? null;
$DESC       = $DESC       ?? '';
$OG         = $OG         ?? null;

/* Home leads with the project; interior pages lead with the section. */
$TITLE = $PAGE_TITLE === null
    ? $PROJECT_SHORT . ' — infused with Parallel and Distributed Computing'
    : $PAGE_TITLE . ' · ' . $PROJECT_SHORT;

/**
 * A site file's URL with ?v=<modification time>, so a browser fetches the new
 * copy as soon as one is uploaded instead of waiting out its cache.
 */
function asset_url(string $path): string {
    $t = @filemtime(__DIR__ . '/../' . $path);
    return $path . ($t ? '?v=' . $t : '');
}

/** Escape for HTML attribute/text context. */
function e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/* Download arrow, for buttons that fetch the e-book. */
function icon_download(): string {
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"'
         . ' stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M12 4v11M8 11l4 4 4-4M5 19h14"/></svg>';
}

/* Arrow leaving a box: the link opens in a new tab. Sits at the end of the
   link; the hidden text says the same thing to screen readers. */
function icon_new_tab(): string {
    return '<svg class="icon-newtab" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"'
         . ' stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">'
         . '<path d="M14 4h6v6M20 4l-9 9"/><path d="M18 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h5"/></svg>'
         . '<span class="visually-hidden"> (opens in a new tab)</span>';
}

/* The brand glyph is used in both the header and the footer. */
function brand_glyph(): string {
    return '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false">'
         . '<rect x="4" y="4.5" width="6.6" height="6.6" rx="1.4"/>'
         . '<rect x="13.4" y="4.5" width="6.6" height="6.6" rx="1.4"/>'
         . '<rect x="4" y="12.9" width="6.6" height="6.6" rx="1.4"/>'
         . '<rect x="13.4" y="12.9" width="6.6" height="6.6" rx="1.4" opacity=".55"/></svg>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($TITLE) ?></title>
<?php if ($DESC): ?>
<meta name="description" content="<?= e($DESC) ?>">
<?php endif; ?>
<link rel="stylesheet" href="<?= e(asset_url('assets/site.css')) ?>">
<script>
/* Apply a stored theme before first paint — site.js runs at the end of the
   body, which would otherwise flash the wrong theme on every navigation. */
document.documentElement.classList.add('js');
try{var t=sessionStorage.getItem('theme');if(t){var r=document.documentElement;
r.classList.toggle('dark',t==='dark');r.style.colorScheme=t;r.dataset.themeLocked='true';}}catch(e){}
</script>
<link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><rect width='32' height='32' rx='7' fill='%23b81d51'/><g fill='white'><rect x='6' y='7' width='7' height='7' rx='1.5'/><rect x='19' y='7' width='7' height='7' rx='1.5'/><rect x='6' y='18' width='7' height='7' rx='1.5'/><rect x='19' y='18' width='7' height='7' rx='1.5'/></g></svg>">
<?php if ($OG): ?>
<meta property="og:title" content="<?= e($OG['title'] ?? $TITLE) ?>">
<meta property="og:description" content="<?= e($OG['description'] ?? $DESC) ?>">
<meta property="og:type" content="website">
<?php endif; ?>
</head>
<body data-page="<?= e($PAGE) ?>">

<a class="skip-link" href="#content">Skip to main content</a>

<header class="site-header">
  <div class="shell masthead">
    <a class="brandmark" href="/" title="<?= e($PROJECT_NAME) ?>">
      <span class="glyph" aria-hidden="true"><?= brand_glyph() ?></span>
      <span class="name"><?= e($PROJECT_SHORT) ?><span class="tag"><?= $PROJECT_TAG ?></span></span>
    </a>

    <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="primary-nav">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" focusable="false"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
      Menu
    </button>

    <nav class="primary-nav" id="primary-nav" aria-label="Primary">
      <ul>
<?php foreach ($NAV as $key => [$href, $label]): ?>
        <li><a class="nav-link" href="<?= e($href) ?>"<?= $key === $PAGE ? ' aria-current="page"' : '' ?>><?= e($label) ?></a></li>
<?php endforeach; ?>
        <li class="nav-out">
          <a class="nav-link nav-link--cder" href="<?= e($CDER_HOME) ?>">
            <img src="assets/logos/cder.png" alt="" width="290" height="70" aria-hidden="true">
            <span>Back to CDER</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
              <path d="M7 17 17 7M9 7h8v8"/>
            </svg>
          </a>
        </li>
      </ul>
    </nav>

    <button class="icon-btn" type="button" data-theme-toggle aria-label="Switch to dark theme">
      <svg class="sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="4.2"/><path d="M12 2.5v2M12 19.5v2M2.5 12h2M19.5 12h2M5.2 5.2l1.4 1.4M17.4 17.4l1.4 1.4M18.8 5.2l-1.4 1.4M6.6 17.4l-1.4 1.4"/></svg>
      <svg class="moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M20 14.5A8.5 8.5 0 0 1 9.5 4a8.5 8.5 0 1 0 10.5 10.5Z"/></svg>
    </button>
  </div>
</header>
