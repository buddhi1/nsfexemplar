<?php
$PAGE  = 'home';
$DESC  = 'Eight classroom-tested CS1 exemplars infusing parallel and distributed computing into introductory computing. A free 288-page e-book with activities, assessments and repositories. NSF Award #2321015.';
$OG    = ['description' => 'Eight classroom-tested CS1 exemplars infusing parallel and distributed computing into introductory computing. Free, evaluated, ready to adopt.'];
/* News lives in assets/news.json so it can be edited without touching markup.
   Read here rather than fetched, so it renders without JavaScript. */
$NEWS = [];
$newsRaw = @file_get_contents(__DIR__ . '/content/news.json');
if ($newsRaw !== false) {
    $decoded = json_decode($newsRaw, true);
    if (is_array($decoded) && !empty($decoded['items'])) {
        $NEWS = $decoded['items'];
    }
}

/* Partner institutions, same pattern as the news. */
$INSTITUTIONS = [];
$instRaw = @file_get_contents(__DIR__ . '/content/institutions.json');
if ($instRaw !== false) {
    $decoded = json_decode($instRaw, true);
    if (is_array($decoded) && !empty($decoded['items'])) {
        $INSTITUTIONS = $decoded['items'];
    }
}

include 'partials/header.php';
?>

<main id="content" tabindex="-1">

<!-- ═══════════════════════════════════════════════════════════ WELCOME ══ -->
<section class="hero hero--split">
  <div class="shell hero-grid">
    <div>
      <p class="eyebrow">CDER Center · <a href="https://www.nsf.gov/awardsearch/showAward?AWD_ID=2321015">NSF Award #2321015</a></p>
      <h1 class="mt-3">A First Course Where Computation Isn&rsquo;t Only Sequential</h1>
      <p class="lede mt-4">Modern software is parallel, distributed, event-driven and API-based. Introductory
        computing mostly still isn&rsquo;t. Eight institutions changed that in their own CS1, then measured
        whether it worked, and published everything.</p>
      <div class="cluster hero-actions">
        <a class="btn btn--primary" href="download.php?f=book">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M12 4v11M8 11l4 4 4-4M5 19h14"/></svg>
          Download eBook
        </a>
        <a class="btn btn--ghost" href="ebook.php">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H19v15H6.5A2.5 2.5 0 0 0 4 20.5Z"/><path d="M4 20.5A2.5 2.5 0 0 1 6.5 18H19v3H6.5A2.5 2.5 0 0 1 4 20.5Z"/></svg>
          Go to eBook
        </a>
        <a class="btn btn--ghost" href="ebook.php#activities">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" focusable="false"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.6-3.6"/></svg>
          Find an activity
        </a>

         <button class="btn btn--primary btn--sm" type="button" data-copy="<?= e($CITATION) ?>"
                aria-label="Copy citation">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="9" y="9" width="11" height="11" rx="2"/><path d="M5 15V5a2 2 0 0 1 2-2h10"/></svg>
          <span>Copy citation</span>
        </button>

        <!-- <button class="btn btn--ghost btn--icon" type="button"
                data-copy="<?= e($CITATION) ?>" aria-label="Copy citation"
                title="Copy the citation for this volume">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="9" y="9" width="11" height="11" rx="2"/><path d="M5 15V5a2 2 0 0 1 2-2h10"/></svg>
          <span class="visually-hidden">Copy citation</span>
        </button> -->
      </div>
    </div>

    <!-- Flag Maker: the project's most widely adopted activity, animated.
         Renders as a static flag if the script never runs. -->
    <!-- Two exemplars from the volume, animated and crossfading.
         Each renders a static fallback if the script never runs. -->
    <div class="showcase" data-showcase>
      <!-- <div class="showcase-track">

        <figure class="flagmaker showcase-slide" data-active style="margin:0"
                data-title="Flag Maker" data-caption="Flag Maker — Chapter 1 § 1.3.1 · run at five institutions">
          <figcaption class="flagmaker-head">
            <span class="fm-title">Flag Maker</span>
            <span class="fm-sub">Students act as processors coloring a flag grid</span>
          </figcaption>
          <div class="fm-stage" data-flagmaker>
            <svg class="fm-fallback" viewBox="0 0 12 8" preserveAspectRatio="none" role="img" aria-label="A flag divided into four horizontal color bands, as used in the Flag Maker activity." focusable="false">
              <rect x="0" y="0" width="12" height="2" fill="var(--flag-1)"/>
              <rect x="0" y="2" width="12" height="2" fill="var(--flag-2)"/>
              <rect x="0" y="4" width="12" height="2" fill="var(--flag-3)"/>
              <rect x="0" y="6" width="12" height="2" fill="var(--flag-4)"/>
            </svg>
          </div>
          <div class="fm-legend">
            <div class="fm-modes" data-fm-modes></div>
            <div class="fm-readout" data-fm-readout aria-live="off">96 cells &nbsp;·&nbsp; <b>1.0&times;</b></div>
          </div>
        </figure>

        <figure class="flagmaker showcase-slide" style="margin:0"
                data-title="Penny Sorting" data-caption="Penny Sorting — Chapter 1 § 1.3.2 · run at four institutions">
          <figcaption class="flagmaker-head">
            <span class="fm-title">Penny Sorting</span>
            <span class="fm-sub">Workers sort one pile; only the split changes</span>
          </figcaption>
          <div class="penny-stage" data-penny>
            <p class="penny-note">Four workers sorting a pile of 24 coins.</p>
          </div>
          <div class="fm-legend">
            <div class="fm-modes" data-penny-modes></div>
            <div class="fm-readout" data-penny-readout aria-live="off">24 coins &nbsp;·&nbsp; <b>1.0&times;</b></div>
          </div>
        </figure>

      </div> -->
      <div class="showcase-dots" role="tablist" aria-label="Featured activity" data-showcase-dots></div>
      <p class="showcase-label" data-showcase-label></p>
    </div>
  </div>
</section>

<div class="shell metrics-wrap">
  <div class="metrics reveal">
    <div class="metric"><span class="metric-value">8</span><span class="metric-label">CS1 exemplars</span></div>
    <div class="metric"><span class="metric-value">14</span><span class="metric-label">Activities</span></div>
    <div class="metric"><span class="metric-value">4</span><span class="metric-label">Languages</span></div>
    <div class="metric"><span class="metric-value">288</span><span class="metric-label">Pages, free</span></div>
    <div class="metric"><span class="metric-value">10</span><span class="metric-label">Chapters</span></div>
  </div>
</div>

<!-- ──────────────────────────────────────────── start from your goal ──── -->
<div class="shell section--tight">
  <p class="eyebrow">Start from your adoption goal</p>
  <h2 class="mt-2">Three Ways in, Depending on How Much You Want to Change</h2>
  <p class="lede mt-2">This volume is designed for selective reading. You do not need to read every chapter
    before finding something usable.</p>

  <div class="path-strip mt-4">
    <div class="path reveal">
      <span class="path-effort">Lowest effort</span>
      <h3>A single class activity</h3>
      <p>Start with Chapter 1 and look for an unplugged, visual or short discussion-based activity.
        Often one 40&ndash;60&nbsp;minute session with printed handouts.</p>
      <a href="ebook.php#activities">Browse activities &rarr;</a>
    </div>
    <div class="path reveal">
      <span class="path-effort">Medium</span>
      <h3>A short module or lab</h3>
      <p>Read the common description, then compare two or three institutional chapters with constraints
        similar to yours &mdash; class size, calendar, language, prep time.</p>
      <a href="ebook.php#glance">Compare exemplars &rarr;</a>
    </div>
    <div class="path reveal">
      <span class="path-effort">Full</span>
      <h3>Course-level infusion</h3>
      <p>Scan the descriptor elements and course-change sections, study the chapters closest to your
        local course, then work from the appendices and repositories.</p>
      <a href="download.php?f=01-overview-roadmap" target="_blank">Chapter 0 &mdash; roadmap (PDF) &rarr;</a>
    </div>
  </div>

  <div class="callout mt-4">
    <strong>A useful first-adoption strategy:</strong> choose the simplest version of an activity that fits
    your constraints. After one offering, expand it, add a plugged-in component, collect more systematic
    evidence, or connect it to a broader module.
  </div>
</div>

<!-- ──────────────────────────────── featured e-book + download history ─── -->
<div class="band band--feature">
  <div class="shell section" id="featured">
    <p class="eyebrow">Featured e-book</p>
    <h2 class="mt-2 mb-4">Toward Modern Models of Introductory Computing Courses</h2>

    <div class="feature-book">
      <!-- The cover art already carries the title, subtitle and author list, and
           the heading above repeats them, so the image itself is decorative and
           the link takes its name from the hidden span. -->
      <figure class="book-cover reveal">
        <a class="book-cover-link" href="download.php?f=book">
          <picture>
            <source srcset="assets/book_cover/book_cover.webp" type="image/webp">
            <img src="assets/book_cover/book_cover.png" alt="" width="576" height="864"
                 decoding="async" loading="lazy">
          </picture>
          <span class="visually-hidden">Download the e-book (PDF, 288 pages)</span>
        </a>
        <figcaption>288 pp &middot; <a href="https://www.nsf.gov/awardsearch/showAward?AWD_ID=2321015">NSF #2321015</a></figcaption>
      </figure>

      <div>
        <div class="prose">
          <p>Students regularly use applications that depend on multicore processors, remote services,
            asynchronous events, large data streams and libraries that hide complex computation. Yet many do
            not learn these ideas formally until advanced electives, if at all.</p>
          <p>This volume responds with a practical adoption guide for instructors, course coordinators,
            departments and academic leaders. Rather than only arguing that introductory courses should be
            modernized, it provides classroom-tested, modular course materials that can be adopted at
            different scales.</p>
          <p class="muted">The goal is not to turn introductory computing students into parallel programmers,
            but to help them develop an early conceptual model in which computation may involve multiple
            workers, remote data, performance trade-offs and coordination among interacting components &mdash;
            all while ensuring that the basic CS1 learning outcomes are preserved.</p>
        </div>

        <div class="cluster mt-4">
          <a class="btn btn--primary" href="download.php?f=book">Download the full PDF · 288 pp</a>
          <a class="btn btn--ghost" href="ebook.php#chapters">Download by chapter</a>
          <a class="btn btn--ghost" href="https://github.com/CDER-Center/CS1-CS2_Exemplar_Ebook/tree/main">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M12 2a10 10 0 0 0-3.16 19.49c.5.09.68-.22.68-.48v-1.7c-2.78.6-3.37-1.34-3.37-1.34-.45-1.16-1.11-1.47-1.11-1.47-.91-.62.07-.61.07-.61 1 .07 1.53 1.03 1.53 1.03.9 1.53 2.36 1.09 2.94.83.09-.65.35-1.09.63-1.34-2.22-.25-4.56-1.11-4.56-4.94 0-1.09.39-1.98 1.03-2.68-.1-.25-.45-1.27.1-2.64 0 0 .84-.27 2.75 1.02a9.5 9.5 0 0 1 5 0c1.91-1.29 2.75-1.02 2.75-1.02.55 1.37.2 2.39.1 2.64.64.7 1.03 1.59 1.03 2.68 0 3.84-2.34 4.69-4.57 4.94.36.31.68.92.68 1.85v2.74c0 .27.18.58.69.48A10 10 0 0 0 12 2Z"/></svg>
            Repository
          </a>
        </div>

        <div class="callout callout--info mt-4">
          <strong>This first release covers CS1.</strong> It is published early to support Fall-term
          adoption, and includes eight institutional CS1 exemplars that have been classroom tested and
          evaluated, together with shared activities and instructional resources. CS2 chapters are expected
          soon for subsequent-term adoption.
        </div>
      </div>
    </div>
  </div>

  <!-- download history, inside the same highlighted band -->
  <!-- <div class="shell section--tight">
    <div class="usage">
    <div class="usage-head">
      <p class="eyebrow">Usage</p>
      <p class="tiny faint">Counted since <?= e(date('j F Y', strtotime($COUNTS['started'] ?? 'today'))) ?>.</p>
    </div>
    <div class="usage-figures">
      <div class="metric">
        <span class="metric-value"><?= counters_fmt((int) ($COUNTS['visits'] ?? 0)) ?></span>
        <span class="metric-label">Site visits</span>
      </div>
      <div class="metric">
        <span class="metric-value"><?= counters_fmt(counters_downloads($COUNTS, 'book')) ?></span>
        <span class="metric-label">Full volume</span>
      </div>
      <div class="metric">
        <span class="metric-value"><?= counters_fmt(counters_chapter_total($COUNTS)) ?></span>
        <span class="metric-label">Chapters</span>
      </div>
      <div class="metric">
        <span class="metric-value"><?= counters_fmt(counters_downloads($COUNTS, 'book') + counters_chapter_total($COUNTS)) ?></span>
        <span class="metric-label">Downloads in total</span>
      </div>
    </div>
      <p class="usage-foot"><a href="ebook.php#chapters">Downloads per chapter &rarr;</a></p>
    </div>
  </div> -->
  
</div>

<?php /* ---------------------------------------------------------------------
   News section — commented out for now; the project timeline on the project
   page carries this for the moment. content/news.json is still maintained and
   still read above, so removing this wrapper brings the section straight back.

<!-- ───────────────────────────────────────────────────────────── news ─── -->
<div class="shell section">
  <div class="grid grid--aside">
    <div>
      <p class="eyebrow">News</p>
      <h2 class="mt-2">Project Updates</h2>
      <p class="muted mt-3">Where the work stands, and what is coming next.</p>
      <!-- <p class="news-hint"><?= count($NEWS) ?> updates, newest first. Hover to hold a page still.</p> -->
    </div>
    <div>
      <div class="news-window" data-news-window>
        <div class="news-reel">
          <ul class="news-list">
<?php foreach ($NEWS as $n): ?>
            <li>
              <time datetime="<?= e($n['date']) ?>"><?= e($n['label']) ?></time>
              <span class="headline"><?= e($n['title']) ?></span>
              <p><?= e($n['body']) ?></p>
<?php if (!empty($n['link']['href'])): ?>
              <span class="news-more"><a href="<?= e($n['link']['href']) ?>"><?= e($n['link']['text'] ?? 'Read more') ?> &rarr;</a></span>
<?php endif; ?>
            </li>
<?php endforeach; ?>
          </ul>
        </div>
      </div>

      <div class="news-pager" data-news-pager hidden>
        <div class="dots" role="tablist" aria-label="News pages" data-news-dots></div>
        <span class="news-count" data-news-count aria-live="polite"></span>
      </div>
    </div>
  </div>
</div>

--------------------------------------------------------------------------- */ ?>

<!-- ─────────────────────────────────────────────── what gets taught ───── -->
<div class="band band--tint">
  <div class="shell section">
    <p class="eyebrow">Recurring activity families</p>
    <h2 class="mt-2">What Actually Gets Taught</h2>
    <p class="lede mt-2">Rather than another local course implementation, Chapter 1 identifies the activity
      families that recur across all eight institutions, adapted to different languages, calendars and
      class sizes.</p>

    <div class="grid grid--three mt-5">
      <article class="card reveal"><span class="pill pill--brand">Unplugged</span><h3 class="mt-2"><a class="stretch" href="ebook.php?activity=flag-maker#explorer">Flag Maker</a></h3>
        <p>Students act as processors coloring flag-grid cells in sequential and parallel scenarios. Surfaces
          speedup, task decomposition, contention, pipelining, critical path and race conditions &mdash; with
          paper and markers.</p>
        <p class="card-foot">40&ndash;60 min · 5 institutions</p></article>

      <article class="card reveal"><span class="pill pill--brand">Unplugged</span><h3 class="mt-2"><a class="stretch" href="ebook.php?activity=penny#explorer">Penny Search / Penny Sorting</a></h3>
        <p>Students search or sort pennies under sequential, balanced-parallel and load-imbalanced scenarios,
          then compare measured times against ideal speedup.</p>
        <p class="card-foot">Fits a 50- or 75-min class · 4 institutions</p></article>

      <article class="card reveal"><span class="pill">Visualization</span><h3 class="mt-2"><a class="stretch" href="ebook.php?activity=animations#explorer">Animations &amp; simulations</a></h3>
        <p>Parallel search, parallel linked lists, flag coloring and Zombie Attack &mdash; codeless visuals
          that need no setup and no programming background, with prediction before code.</p>
        <p class="card-foot">10&ndash;15 min each</p></article>

      <article class="card reveal"><span class="pill pill--accent">Plugged-in</span><h3 class="mt-2"><a class="stretch" href="ebook.php?activity=earthquake#explorer">Earthquake Tracker &amp; remote data</a></h3>
        <p>Students retrieve live USGS data, parse JSON, filter events and display results &mdash; distributed
          data access against a real service instead of parsing console text.</p>
        <p class="card-foot">~1.25 hours · 3 institutions</p></article>

      <article class="card reveal"><span class="pill pill--accent">Plugged-in</span><h3 class="mt-2"><a class="stretch" href="ebook.php?activity=openmp#explorer">OpenMP data parallelism</a></h3>
        <p>A few pragmas on array creation, summing or sorting, plus benchmarking &mdash; high conceptual
          return for very little added cognitive load.</p>
        <p class="card-foot">One lab plus homework</p></article>

      <article class="card reveal"><span class="pill pill--accent">Plugged-in</span><h3 class="mt-2"><a class="stretch" href="ebook.php?activity=greenfoot#explorer">Greenfoot &amp; event-driven work</a></h3>
        <p>Code attached to sprites and invoked by system events rather than called from <code>main</code>,
          ending in open-ended student games.</p>
        <p class="card-foot">75-min demo to a full project · 4 institutions</p></article>
    </div>

    <div class="cluster mt-5">
      <a class="btn btn--primary" href="ebook.php#activities">Browse all 14 activities</a>
      <a class="btn btn--ghost" href="download.php?f=02-common-cs1">Chapter 1 &mdash; Common CS1 activities (PDF)</a>
    </div>
  </div>
</div>

<!-- ────────────────────────────────────────────────── institutions ────── -->
<div class="shell section">
  <p class="eyebrow">Partners</p>
  <h2 class="mt-2">Eight Institutions</h2>
  <p class="lede mt-2">No single model fits all languages, calendars, class sizes, student populations or
    instructor backgrounds. The diversity is the point.</p>

  <div class="inst-grid mt-4">
<?php foreach ($INSTITUTIONS as $i): ?>
    <a class="inst-card" data-role="<?= e($i['role']) ?>"
       href="ebook.php?institution=<?= e($i['id']) ?>#explorer">
      <span class="inst-logo">
<?php if (!empty($i['logo']) && is_file(__DIR__ . '/assets/logos/institutions/' . $i['logo'])): ?>
        <img src="assets/logos/institutions/<?= e($i['logo']) ?>" alt="<?= e($i['name']) ?>" loading="lazy">
<?php else: ?>
        <span class="mono" aria-hidden="true"><?= e($i['mono']) ?></span>
<?php endif; ?>
      </span>
      <span class="inst-body">
        <span class="inst-name"><?= e($i['short']) ?></span>
        <span class="inst-meta"><?= e($i['setting']) ?> &middot; <?= e($i['langs']) ?></span>
        <span class="inst-meta"><?= e($i['place']) ?></span>
        <span class="inst-role"><?= e($i['role']) ?></span>
      </span>
    </a>
<?php endforeach; ?>
  </div>

  <div class="panel panel--sunken mt-5">
    <div class="split">
      <div style="max-inline-size:52ch">
        <h3>Adopt, adapt, report back</h3>
        <p class="muted mt-3">Some consultation from project personnel is available to help adopters select
          and adapt materials. If you adopt an exemplar, the community&rsquo;s strong preference is that you
          report the experience &mdash; including what didn&rsquo;t work.</p>
      </div>
      <div class="cluster push">
        <a class="btn btn--primary" href="https://discord.gg/xdh3uvD3b">Join the Discord</a>
        <a class="btn btn--ghost" href="mailto:contact@nsfexemplar.cdercenter.org">Contact the project</a>
      </div>
    </div>
  </div>
</div>

</main>

<?php include 'partials/footer.php'; ?>
