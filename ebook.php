<?php
$PAGE  = 'ebook';
$PAGE_TITLE = 'The eBook';
$DESC  = 'The first edition covers CS1 and CS2: sixteen course exemplars from eight institutions, two common activity chapters, and a searchable finder for classroom activities. Chapter summaries, teaching materials and the full e-book PDF.';
include 'partials/header.php';
?>

<main id="content" tabindex="-1">

<section class="hero hero--page">
  <div class="shell">
    <p class="eyebrow">The eBook &middot; First Edition</p>
    <h1 class="mt-3">Find the Exemplar or the Activity That Matches Your Course</h1>
    <p class="lede mt-4">Sixteen course exemplars &mdash; eight for CS1, eight for CS2 &mdash; from eight
      institutions. Search by institution or by activity, then open the chapter summary, the teaching
      materials, or the full e-book.</p>
    <!-- Row one identifies the volume, row two acts on it. -->
    <div class="cluster hero-meta">
      <button class="btn btn--quiet btn--sm" type="button" data-copy="<?= e($CITATION) ?>"
              aria-label="Copy citation">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="9" y="9" width="11" height="11" rx="2"/><path d="M5 15V5a2 2 0 0 1 2-2h10"/></svg>
        <span data-copy-label>Copy citation</span>
      </button>
      <?= doi_badge($DOI, $DOI_URL, '', 'First edition') ?>
      <?= doi_badge($DOI_EARLY, $DOI_EARLY_URL, '', 'Early release') ?>
    </div>

    <div class="cluster hero-actions">
      <a class="btn btn--primary" href="download.php?f=book" target="_blank" rel="noopener">
        <?= icon_download() ?>
        Download the full e-book <?= icon_new_tab() ?>
      </a>
      <a class="btn btn--soft" href="download.php?f=early" target="_blank" rel="noopener"
         aria-label="Download the early release: August 2026, CS1 chapters only, superseded by the First Edition. Opens in a new tab."
         data-tip="August 2026 · CS1 chapters only · superseded by the First Edition.">
        <?= icon_download() ?>
        Early release <?= icon_new_tab() ?>
      </a>
      <a class="btn btn--ghost" href="#chapters">Chapter summaries</a>
      <a class="btn btn--ghost" href="#repos">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M3 7.5A1.5 1.5 0 0 1 4.5 6h4l2 2.5h7A1.5 1.5 0 0 1 19 10v7.5A1.5 1.5 0 0 1 17.5 19h-13A1.5 1.5 0 0 1 3 17.5Z"/></svg>
        Appendices &amp; material
      </a>
    </div>
  </div>
</section>

<!-- ───────────────────────────────────────────────────────── about it ─── -->
<div class="shell section--tight" id="about">
  <div class="grid grid--halves">
    <div class="prose">
      <p class="eyebrow">About the volume</p>
      <h2 class="mt-2">A Guided Collection, Not a Linear Book</h2>
      <p>This is a practical adoption guide for the introductory computing sequence. Rather than only
        arguing that introductory courses should be modernized, it provides classroom-tested, modular course
        materials that can be adopted at different scales &mdash; while ensuring that the basic CS1 and CS2
        learning outcomes are preserved.</p>
      <p>CS1 and CS2 are treated as one connected sequence. CS1 establishes an early conceptual awareness that
        computation need not be strictly sequential; CS2 deepens it through implementation, correctness
        reasoning, synchronization and measured performance &mdash; and the CS2 exemplars also show short
        conceptual on-ramps for students who arrive without any PDC exposure.</p>
      <p class="muted">Begin with an adoption goal, use Chapter 1 for CS1 or Chapter 10 for CS2 to find the
        relevant activity families, compare a small number of institutional chapters that resemble your local
        context, then move to the appendices and repositories for implementation resources. A small, feasible
        first adoption is often the best starting point.</p>
    </div>
    <div class="panel panel--sunken">
      <h3>Chapter anatomy</h3>
      <p class="tiny faint mt-2">Every institutional chapter, CS1 or CS2, shares one structure, so you can
        compare exemplars without learning a new organization each time (Chapter&nbsp;0, Figure&nbsp;4).</p>
      <ol class="mt-3 muted small" style="padding-inline-start:1.2rem; display:grid; gap:.5rem">
        <li><strong>Abstract</strong> &mdash; what was done and why.</li>
        <li><strong>Descriptor elements</strong> &mdash; programming language, relevant course, prerequisites,
          PDC topics with Bloom levels, learning outcomes, context for use.</li>
        <li><strong>Chapter body</strong> &mdash; course changes, unplugged and plugged-in activities, surveys
          and assessment, challenges, teaching tips, lessons learned.</li>
        <li><strong>Appendix &amp; repository</strong> &mdash; repository link, detailed pre/post syllabus or
          course calendar, handouts, slides, assignments, starter code, surveys, anonymized evaluation data,
          analysis summaries.</li>
      </ol>
      <p class="tiny faint mt-3">Before using a resource, verify that software versions, API links, datasets
        and institutional assessment requirements remain current.</p>
    </div>
  </div>
</div>

<!-- ─────────────────────────────────────────────────── which path? ────── -->
<!-- Chapter 0, Figure 2: choose the course first, then the scale of adoption. -->
<div class="shell section--tight" id="paths">
  <p class="eyebrow">Where to start</p>
  <h2 class="mt-2">Which Path Should I Take?</h2>
  <p class="lede mt-2">First choose the course &mdash; CS1, CS2 or the sequence &mdash; then the scale of
    the change. Each line below goes straight to the part of the volume that answers it.</p>

  <ol class="pathlist mt-4">
    <li>
      <span class="pathlist-want">I teach CS1</span>
      <a class="pathlist-go" href="download.php?f=book&amp;p=28">Start with Chapter 1, the common CS1 activities
        <span class="tiny faint">Flag Maker, Penny Sorting, Greenfoot, Earthquake Tracker &middot; p.&nbsp;28</span></a>
    </li>
    <li>
      <span class="pathlist-want">I teach CS2</span>
      <a class="pathlist-go" href="download.php?f=book&amp;p=288">Start with Chapter 10, the common CS2 activities
        <span class="tiny faint">Fork&ndash;Join, parallel sorting, image processing &middot; p.&nbsp;288</span></a>
    </li>
    <li>
      <span class="pathlist-want">I want one class activity</span>
      <a class="pathlist-go" href="#activities">Use the activity finder
        <span class="tiny faint">filter by time, preparation, language and topic</span></a>
    </li>
    <li>
      <span class="pathlist-want">I want a lab or a short module</span>
      <a class="pathlist-go" href="#parts">Read the common chapter, then compare two or three institutions
        <span class="tiny faint">pick the ones whose constraints are closest to yours</span></a>
    </li>
    <li>
      <span class="pathlist-want">I want a course or sequence model</span>
      <a class="pathlist-go" href="#institutions">Compare the institutional exemplars
        <span class="tiny faint">seven institutions have a paired CS1 and CS2 package</span></a>
    </li>
    <li>
      <span class="pathlist-want">I want the teaching files</span>
      <a class="pathlist-go" href="#materials">Go to the repositories
        <span class="tiny faint">syllabi, handouts, starter code, data</span></a>
    </li>
    <li>
      <span class="pathlist-want">I want the evidence</span>
      <a class="pathlist-go" href="download.php?f=book&amp;p=13">Read the methodology, then each chapter&rsquo;s surveys
        <span class="tiny faint">Chapter 0 &sect;&thinsp;0.3 &middot; p.&nbsp;13</span></a>
    </li>
  </ol>
</div>

<!-- ──────────────────────────────────────────────────────── explorer ──── -->
<div class="band band--sunken">
  <!-- deep-link targets: both scroll here, and site.js picks the browse mode -->
  <span id="institutions"></span><span id="activities"></span>
  <div class="shell section" id="explorer">
    <div class="split mb-4">
      <div>
        <p class="eyebrow">Search</p>
        <h2 class="mt-2">Browse the Material</h2>
      </div>
      <div class="modeswitch push" role="tablist" aria-label="Browse mode">
        <button type="button" data-mode="inst" role="tab" aria-selected="true">By institution</button>
        <button type="button" data-mode="act" role="tab" aria-selected="false">By activity</button>
      </div>
    </div>

    <form class="panel" id="ex-form" role="search" aria-label="Exemplar and activity explorer">
      <div class="split">
        <label class="searchbar">
          <span class="visually-hidden">Search by institution, activity, PDC topic, language or author</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" focusable="false"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.6-3.6"/></svg>
          <input type="search" id="ex-q" data-search-primary autocomplete="off"
                 placeholder="Search — e.g. &ldquo;penny&rdquo;, &ldquo;OpenMP&rdquo;, &ldquo;fork-join&rdquo;, &ldquo;Greenfoot&rdquo;">
        </label>
        <button class="btn btn--ghost" type="button" id="ex-clear">Clear all</button>
      </div>
      <div class="grid mt-4" id="ex-facets"
           style="grid-template-columns:repeat(auto-fit,minmax(min(16rem,100%),1fr)); gap:var(--sp-4)"></div>
    </form>

    <div class="result-meta" role="status" aria-live="polite" id="ex-status"></div>
    <div class="grid grid--wide" id="ex-results"></div>

    <noscript>
      <div class="callout mt-4"><strong>Search needs JavaScript.</strong> Every chapter is still listed and
        downloadable in the <a href="#chapters">chapter table</a> below, and the full volume is available as
        a <a href="download.php?f=book" target="_blank" rel="noopener">single PDF <?= icon_new_tab() ?></a>.</div>
    </noscript>
  </div>
</div>

<!-- ──────────────────────────────────────────────────────── the parts ──── -->
<!-- Chapter 0, Figure 3: the two common chapters share one outline, and each
     opens its part of the volume. The lists are filled from CHAPTERS. -->
<div class="shell section" id="parts">
  <p class="eyebrow">Two parts, one sequence</p>
  <h2 class="mt-2">CS1 and CS2 Exemplars at a Glance</h2>
  <p class="lede mt-2">Treat this as a starting filter. The most important adoption question is not only
    &ldquo;which language matches mine?&rdquo; but &ldquo;which activity scale, preparation level, evidence
    type and implementation style match my course?&rdquo;</p>

  <div class="grid grid--halves mt-4">
    <section class="panel part-panel" id="cs1" aria-labelledby="cs1-h">
      <div>
        <p class="eyebrow">Part I &mdash; p.&nbsp;27</p>
        <h3 class="mt-2" id="cs1-h">Computer Science 1 (CS1)</h3>
      </div>
      <p class="part-start muted"><strong>Start with Chapter 1</strong>, the common CS1 chapter: Flag Maker,
        Penny Search and Penny Sorting, animations and simulations (&sect;&thinsp;1.3); Programming Flag Maker,
        Greenfoot and the Earthquake Tracker API work (&sect;&thinsp;1.4); then the course-wide surveys
        (&sect;&thinsp;1.5).</p>
      <p><a class="btn btn--primary btn--sm" href="download.php?f=book&amp;p=28">Chapter 1 &middot; p.&nbsp;28</a></p>
      <p class="tiny faint">Eight institutional CS1 course packages</p>
      <ol class="partlist" data-part-list="Part I · CS1"></ol>
    </section>

    <section class="panel part-panel" id="cs2" aria-labelledby="cs2-h">
      <div>
        <p class="eyebrow">Part II &mdash; p.&nbsp;287</p>
        <h3 class="mt-2" id="cs2-h">Computer Science 2 (CS2)</h3>
      </div>
      <p class="part-start muted"><strong>Start with Chapter 10</strong>, the common CS2 chapter: the unplugged
        Overly Complicated Machine (&sect;&thinsp;10.3.1); Greenfoot event handling, Fork&ndash;Join, a sorting
        algorithm comparison and parallel image processing (&sect;&thinsp;10.4); then the course-wide surveys
        (&sect;&thinsp;10.5).</p>
      <p><a class="btn btn--primary btn--sm" href="download.php?f=book&amp;p=288">Chapter 10 &middot; p.&nbsp;288</a></p>
      <p class="tiny faint">Eight institutional CS2 course packages &mdash; Webster has two, in C++ and Python</p>
      <ol class="partlist" data-part-list="Part II · CS2"></ol>
    </section>
  </div>

  <div class="callout callout--info mt-4" id="glance">
    <strong>Adopting across the sequence?</strong> Seven institutions published both a CS1 and a CS2 package
    &mdash; every one except HPU &mdash; so a paired reading shows how early conceptual exposure grows into
    implementation, synchronization and performance analysis. The side-by-side comparison of institutional
    context, language and infusion style is on the project page.
    <a href="project.php#partners">See the comparison &rarr;</a>
  </div>
</div>

<!-- ─────────────────────────────────────────────────────── repositories ── -->
<div class="band band--sunken">
  <!-- #repos is linked from other pages; #materials is the name used here -->
  <span id="materials"></span>
  <div class="shell section" id="repos">
    <p class="eyebrow">Appendices &amp; GitHub</p>
    <h2 class="mt-2">Appendices and Instructional Material</h2>
    <p class="lede mt-2">Each chapter closes with an appendix that points here. The chapters describe the
      activities; these repositories hold the material you actually teach from &mdash; and are curated
      periodically as adopters revise and extend them.</p>

    <div class="grid grid--halves mt-4">
      <article class="panel">
        <div class="cluster mb-3">
          <span class="card-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M12 2a10 10 0 0 0-3.16 19.49c.5.09.68-.22.68-.48v-1.7c-2.78.6-3.37-1.34-3.37-1.34-.45-1.16-1.11-1.47-1.11-1.47-.91-.62.07-.61.07-.61 1 .07 1.53 1.03 1.53 1.03.9 1.53 2.36 1.09 2.94.83.09-.65.35-1.09.63-1.34-2.22-.25-4.56-1.11-4.56-4.94 0-1.09.39-1.98 1.03-2.68-.1-.25-.45-1.27.1-2.64 0 0 .84-.27 2.75 1.02a9.5 9.5 0 0 1 5 0c1.91-1.29 2.75-1.02 2.75-1.02.55 1.37.2 2.39.1 2.64.64.7 1.03 1.59 1.03 2.68 0 3.84-2.34 4.69-4.57 4.94.36.31.68.92.68 1.85v2.74c0 .27.18.58.69.48A10 10 0 0 0 12 2Z"/></svg></span>
          <div><h3 style="font-size:1.2rem">CDER-Center / CS1-CS2_Exemplar_Ebook</h3>
            <p class="tiny faint">The shared project repository</p></div>
        </div>
        <p class="muted">Everything lives in this one repository: <code>CS1/</code> for Part&nbsp;I and
          <code>CS2/</code> for Part&nbsp;II, with a directory per chapter named
          <code>chapterN-INSTITUTION</code> &mdash; matching the chapter numbers in the volume, so CS1 runs from
          <code>chapter2-TTU</code> to <code>chapter9-MSU</code> and CS2 from <code>chapter10-CDER</code> to
          <code>chapter18-MSU</code>. Chapter directories follow the same layout, so material is where you
          expect it regardless of which exemplar you open.</p>
        <ul class="mt-3 muted small" style="padding-inline-start:1.1rem; display:grid; gap:.4rem">
          <li><code>Detailed_Pre_and_Post_Syllabus</code> &mdash; the course before and after, plus a
            description of the changes made</li>
          <li><code>Unplugged_Activities</code> &mdash; activity guides, worksheets, handouts, facilitator
            resources</li>
          <li><code>Plugged_Activities</code> &mdash; programming exercises, source and starter code, lab
            activities</li>
          <li><code>Evaluation_Data_and_Analysis</code> &mdash; survey instruments, anonymized responses,
            evaluation metrics, statistical analyses</li>
          <li><code>Optional_Archive</code> &mdash; earlier syllabi and master schedules, so baseline and
            intervention semesters can be compared</li>
        </ul>
        <div class="cluster mt-4">
          <a class="btn btn--primary btn--sm" href="https://github.com/CDER-Center/CS1-CS2_Exemplar_Ebook/tree/main/CS1">Browse CS1 material</a>
          <a class="btn btn--primary btn--sm" href="https://github.com/CDER-Center/CS1-CS2_Exemplar_Ebook/tree/main/CS2">Browse CS2 material</a>
          <a class="btn btn--ghost btn--sm" href="https://github.com/CDER-Center/CS1-CS2_Exemplar_Ebook/tree/main">Repository root</a>
        </div>
      </article>

      <article class="panel">
        <h3 style="font-size:1.2rem">Finding Things Quickly</h3>
        <p class="muted mt-3">The fastest route is chapter-level navigation plus search. The abstract and
          descriptor elements give a quick overview of each institutional chapter; Chapters&nbsp;1 and&nbsp;10
          point from each activity family to the institutions that ran it. In the PDF, search for:</p>
        <p class="small mt-3"><strong>Activities and tools</strong> &mdash; Flag Maker, Penny Search, Penny
          Sorting, OpenMP, Earthquake Tracker, Greenfoot, animation, visualization, Fork-Join, parallel sorting,
          parallel image processing.</p>
        <p class="small mt-2"><strong>Concepts</strong> &mdash; data parallelism, speedup, load balance, event
          handling, remote data, API, work and span, race condition, synchronization, benchmarking,
          scalability.</p>
        <p class="small mt-2"><strong>Evidence</strong> &mdash; pre/post survey, student feedback, engagement,
          assessment, reflection, data analysis.</p>
        <p class="tiny faint mt-3">From Chapter&nbsp;0 &sect;&thinsp;0.5.4, &ldquo;Finding Materials Quickly&rdquo;.</p>
      </article>
    </div>
  </div>
</div>

<!-- ────────────────────────────────────────────────── chapter summaries ── -->
<div class="shell section" id="chapters">
  <p class="eyebrow">Chapter summaries</p>
  <h2 class="mt-2">Chapter Summaries</h2>
  <p class="lede mt-2">Every chapter, with its abstract, contents and authors, so you can see what is in the
    volume before opening it. Each one links to its own first page in the full PDF and to the teaching
    material that goes with it.</p>

  <div class="callout callout--info mt-4">
    <strong>The full PDF is the official version of record.</strong> It is a single volume with complete
    cross-references between chapters, and a shared bibliography (pp.&nbsp;509&ndash;515).
    Chapters are not published as standalone files, because a chapter cut out of the volume loses both. The
    summaries below are here so you can find the right chapter quickly, then open the volume at that page.
  </div>

  <div class="table-wrap mt-4">
    <table class="data data--chapters">
      <caption>Page numbers are the first edition&rsquo;s printed page numbers, as in its own contents.</caption>
      <thead><tr><th scope="col">Chapter</th><th scope="col">Part</th><th scope="col">Pages</th><th scope="col">Open</th></tr></thead>
      <tbody id="chapter-rows">
        <tr><td colspan="4" class="small faint">The chapter list requires JavaScript &mdash;
          <a href="download.php?f=book" target="_blank" rel="noopener">open the full volume instead <?= icon_new_tab() ?></a>, which carries the same contents.</td></tr>
      </tbody>
    </table>
  </div>
  <div class="cluster mt-4">
    <a class="btn btn--primary" href="download.php?f=book" target="_blank" rel="noopener"><?= icon_download() ?> Download the full e-book <?= icon_new_tab() ?></a>
    <a class="btn btn--ghost" href="https://github.com/CDER-Center/CS1-CS2_Exemplar_Ebook/tree/main">Open teaching materials</a>
  </div>
</div>


<!-- ──────────────────────────────────────────────────────── citation ──── -->
<div class="shell section--tight" id="cite">
  <div class="cite-card">
    <div>
      <p class="eyebrow">Cite this volume</p>
      <p class="tiny faint mt-2">Copy either form, or select the text if you would rather edit it into your
        own style. The plain-text wording is what the volume itself prints, so please keep it as it stands.</p>
      <p class="tiny faint mt-3">Each DOI identifies one edition as a whole and resolves to its landing page,
        not to any single chapter. Cite chapters by their number within the volume.</p>
    </div>
    <div>
      <p class="cite-label">DOI</p>
      <div class="cluster mt-2 mb-4">
        <?= doi_badge($DOI, $DOI_URL, '', 'First edition') ?>
        <?= doi_badge($DOI_EARLY, $DOI_EARLY_URL, '', 'Early release') ?>
      </div>
      <p class="cite-label">Plain text &middot; First Edition</p>
      <p class="cite-text" data-cite-text><?= e($CITATION) ?></p>
      <div class="cluster mt-3">
        <button class="btn btn--primary btn--sm" type="button" data-copy="<?= e($CITATION) ?>"
                aria-label="Copy citation">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="9" y="9" width="11" height="11" rx="2"/><path d="M5 15V5a2 2 0 0 1 2-2h10"/></svg>
          <span data-copy-label>Copy citation</span>
        </button>
        <a class="btn btn--ghost btn--sm" href="download.php?f=book" target="_blank" rel="noopener"><?= icon_download() ?> Download the volume <?= icon_new_tab() ?></a>
      </div>

      <p class="cite-label mt-5">BibTeX</p>
      <pre class="cite-code"><code><?= e($BIBTEX) ?></code></pre>
      <div class="cluster mt-3">
        <button class="btn btn--primary btn--sm" type="button" data-copy="<?= e($BIBTEX) ?>"
                data-copy-noun="BibTeX" aria-label="Copy the BibTeX entry">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="9" y="9" width="11" height="11" rx="2"/><path d="M5 15V5a2 2 0 0 1 2-2h10"/></svg>
          <span data-copy-label>Copy BibTeX</span>
        </button>
      </div>

      <details class="cite-early mt-5">
        <summary>Citing the early release instead</summary>
        <p class="tiny faint mt-2">The August 2026 early release (CS1 chapters only) keeps its own DOI. Cite it
          only if that is the version you used.</p>
        <p class="cite-text mt-3"><?= e($CITATION_EARLY) ?></p>
        <div class="cluster mt-3">
          <button class="btn btn--ghost btn--sm" type="button" data-copy="<?= e($CITATION_EARLY) ?>"
                  aria-label="Copy the early release citation">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="9" y="9" width="11" height="11" rx="2"/><path d="M5 15V5a2 2 0 0 1 2-2h10"/></svg>
            <span data-copy-label>Copy citation</span>
          </button>
          <button class="btn btn--ghost btn--sm" type="button" data-copy="<?= e($BIBTEX_EARLY) ?>"
                  data-copy-noun="BibTeX" aria-label="Copy the early release BibTeX entry">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="9" y="9" width="11" height="11" rx="2"/><path d="M5 15V5a2 2 0 0 1 2-2h10"/></svg>
            <span data-copy-label>Copy BibTeX</span>
          </button>
          <a class="btn btn--ghost btn--sm" href="download.php?f=early" target="_blank" rel="noopener"><?= icon_download() ?> Early release <?= icon_new_tab() ?></a>
        </div>
      </details>
    </div>
  </div>
</div>

<?php include 'partials/usage.php'; ?>

</main>

<!-- exemplar / activity detail dialog -->
<dialog class="sheet" id="ex-dialog" aria-labelledby="ex-dialog-title">
  <form method="dialog" class="sheet-head">
    <strong id="ex-dialog-title"></strong>
    <button class="icon-btn" style="margin-inline-start:auto" value="close" aria-label="Close">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" focusable="false"><path d="M6 6l12 12M18 6L6 18"/></svg>
    </button>
  </form>
  <div class="sheet-body" id="ex-dialog-body"></div>
</dialog>

<?php include 'partials/footer.php'; ?>
