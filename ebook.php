<?php
$PAGE  = 'ebook';
$PAGE_TITLE = 'The eBook';
$DESC  = 'Search eight CS1 exemplars and seventeen classroom activities by language, institution type, activity style, duration and preparation level. Chapter summaries, teaching materials and the full e-book PDF.';
include 'partials/header.php';
?>

<main id="content" tabindex="-1">

<section class="hero hero--page">
  <div class="shell">
    <p class="eyebrow">The eBook</p>
    <h1 class="mt-3">Find the Exemplar or the Activity That Matches Your Course</h1>
    <p class="lede mt-4">Search by institution or by activity. Filter activities by type, time needed,
      preparation, language, PDC topic, CS1 anchor and institution &mdash; then open the chapter summary, the
      teaching materials, or the full e-book.</p>
    <p class="release-note mt-3">
      <span class="pill pill--brand" tabindex="0" role="note"
            aria-label="Early release. The CS1 chapters are published and complete. CS2 chapters are expected soon."
            data-tip="The CS1 chapters are published and complete. CS2 chapters are expected soon.">Early release</span>
    </p>
    <!-- Row one identifies the volume, row two acts on it. -->
    <div class="cluster hero-meta">
      <button class="btn btn--quiet btn--sm" type="button" data-copy="<?= e($CITATION) ?>"
              aria-label="Copy citation">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="9" y="9" width="11" height="11" rx="2"/><path d="M5 15V5a2 2 0 0 1 2-2h10"/></svg>
        <span data-copy-label>Copy citation</span>
      </button>
      <?= doi_badge($DOI, $DOI_URL) ?>
    </div>

    <div class="cluster hero-actions">
      <a class="btn btn--primary" href="download.php?f=book">Download the full e-book (PDF)</a>
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
      <p>This is a practical adoption guide. Rather than only arguing that introductory courses should be
        modernized, it provides classroom-tested, modular course materials that can be adopted at different
        scales &mdash; while ensuring that the basic CS1 learning outcomes are preserved.</p>
      <p class="muted">Begin with an adoption goal, use Chapter 1 to understand the common activity families,
        compare a small number of institutional chapters that resemble your local context, then move to the
        appendices and repositories for implementation resources. A small, feasible first adoption is often
        the best starting point for broader CS1 modernization.</p>
    </div>
    <div class="panel panel--sunken">
      <h3>Chapter anatomy</h3>
      <p class="tiny faint mt-2">The institutional chapters share a common structure, so you can compare
        exemplars without learning a new organization each time.</p>
      <ol class="mt-3 muted small" style="padding-inline-start:1.2rem; display:grid; gap:.5rem">
        <li><strong>Abstract</strong> &mdash; what was done and why.</li>
        <li><strong>Descriptor elements</strong> &mdash; programming language, relevant course, prerequisites,
          textbook, PDC topics with Bloom levels, learning outcomes, context for use.</li>
        <li><strong>Chapter body</strong> &mdash; course changes, unplugged and plugged-in activities, surveys
          and assessment, challenges, teaching tips, lessons learned.</li>
        <li><strong>Appendix &amp; repository</strong> &mdash; repository link, detailed pre/post syllabus,
          handouts, slides, assignments, starter code, surveys, anonymized evaluation data.</li>
      </ol>
      <p class="tiny faint mt-3">Before using a resource, verify that software versions, API links, datasets
        and institutional assessment requirements remain current.</p>
    </div>
  </div>
</div>

<!-- ─────────────────────────────────────────────────── which path? ────── -->
<div class="shell section--tight" id="paths">
  <p class="eyebrow">Where to start</p>
  <h2 class="mt-2">Which Path Should I Take?</h2>
  <p class="lede mt-2">Five ways in, depending on what you came for. Each goes straight to the part of the
    volume that answers it.</p>

  <ol class="pathlist mt-4">
    <li>
      <span class="pathlist-want">I want one class activity</span>
      <a class="pathlist-go" href="#activities">Start with the activity finder
        <span class="tiny faint">17 activities, filterable</span></a>
    </li>
    <li>
      <span class="pathlist-want">I want a lab or module</span>
      <a class="pathlist-go" href="download.php?f=book&amp;p=26">Read Chapter 1, then compare two institutions
        <span class="tiny faint">shared activity families, p.&nbsp;26</span></a>
    </li>
    <li>
      <span class="pathlist-want">I want a course model</span>
      <a class="pathlist-go" href="#chapters">Browse the institutional chapter summaries
        <span class="tiny faint">eight CS1 exemplars</span></a>
    </li>
    <li>
      <span class="pathlist-want">I want the teaching files</span>
      <a class="pathlist-go" href="#materials">Go to the repositories
        <span class="tiny faint">syllabi, handouts, starter code, data</span></a>
    </li>
    <li>
      <span class="pathlist-want">I want the evidence</span>
      <a class="pathlist-go" href="download.php?f=book&amp;p=13">Read the methodology and the chapter data sections
        <span class="tiny faint">Chapter 0, p.&nbsp;13</span></a>
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
                 placeholder="Search — e.g. &ldquo;penny&rdquo;, &ldquo;OpenMP&rdquo;, &ldquo;community college&rdquo;, &ldquo;Greenfoot&rdquo;">
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
        a <a href="download.php?f=book">single PDF</a>.</div>
    </noscript>
  </div>
</div>

<!-- ───────────────────────────────────────────────────────────── CS1 ──── -->
<div class="shell section" id="cs1">
  <p class="eyebrow">Part I &mdash; CS1</p>
  <h2 class="mt-2">CS1 Exemplars at a Glance</h2>
  <p class="lede mt-2">Treat this as a starting filter. The most important adoption question is not only
    &ldquo;which language matches mine?&rdquo; but &ldquo;which activity scale, preparation level, evidence
    type and implementation style match my course?&rdquo;</p>

  <div class="callout callout--info mt-4" id="glance">
    <strong>Comparing the eight exemplars side by side?</strong> The quick-comparison table
    &mdash; institutional context, language, infusion style and what each is a good starting point
    for &mdash; lives on the project page, so it is in one place rather than two.
    <a href="project.php#partners">See the comparison &rarr;</a>
  </div>

  <div class="callout mt-4">
    <strong>Most readers should start with Chapter 1.</strong> It distills the recurring activity families,
    shared PDC concepts, common implementation challenges and cross-institution lessons &mdash; then points
    into the institutional chapters for local implementation details.
    <a href="download.php?f=book&amp;p=26">Read Chapter 1 &rarr;</a>
  </div>
</div>

<!-- ───────────────────────────────────────────────────────────── CS2 ──── -->
<!-- <div class="band band--tint">
  <div class="shell section" id="cs2">
    <div class="grid grid--halves">
      <div class="prose">
        <p class="eyebrow">Part II &mdash; CS2</p>
        <h2 class="mt-2">CS2 Chapters Are in Preparation</h2>
        <p>This first release focuses on the CS1 portion and is being made available early to support
          Fall-term adoption. CS2 course packages are drafted and being finalized for subsequent-term
          adoption, and will be published here as they are released.</p>
        <p class="muted">In general, CS1 ideas were developed first, and experience with CS1 informed the
          approach to CS2. Every institutional exemplar in the search above already lists the CS2 activities
          planned for its package &mdash; open any exemplar and look under
          <em>Planned for the CS2 release</em>.</p>
        <div class="cluster mt-4">
          <a class="btn btn--primary" href="#explorer">See planned CS2 activities</a>
          <a class="btn btn--ghost" href="https://github.com/CDER-Center/CS1-CS2_Exemplar_Ebook/tree/main">Watch the repository</a>
        </div>
      </div>
      <div class="panel">
        <h3>What CS2 will cover</h3>
        <p class="tiny faint mt-2">Drawn from the institutional chapters&rsquo; stated plans.</p>
        <ul class="mt-3 muted small" style="padding-inline-start:1.1rem; display:grid; gap:.55rem">
          <li><strong>Fork&ndash;join parallelism</strong> analyzed in terms of work and span, sitting beside
            the asymptotic analysis already taught &mdash; with dependency graphs, race conditions and
            unplugged decomposition exercises.</li>
          <li><strong>Parallel sorting and reductions</strong> &mdash; parallel merge sort and quicksort
            benchmarked against sequential algorithms.</li>
          <li><strong>Parallel image processing</strong> &mdash; edge detection, blurring and noise removal
            with threads, so speedup is directly observable.</li>
          <li><strong>Distributed systems in practice</strong> &mdash; connection pooling in a multithreaded
            JDBC application, demonstrating both speedup and real distributed complexity.</li>
          <li><strong>Event-driven design</strong> &mdash; Greenfoot game projects built around mouse and
            keyboard interaction.</li>
          <li><strong>A Python CS2 package</strong> &mdash; threading and multiprocessing, OpenCV image
            processing, and performance engineering including Amdahl&rsquo;s law, strong vs weak scaling and
            NUMA effects.</li>
        </ul>
      </div>
    </div>
  </div>
</div> -->

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

    <!-- One panel for the shared repository, one for what individual teams
         publish elsewhere. These were previously two panels that both
         described the same repository layout in slightly different words. -->
    <div class="grid grid--halves mt-4">
      <article class="panel">
        <div class="cluster mb-3">
          <span class="card-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M12 2a10 10 0 0 0-3.16 19.49c.5.09.68-.22.68-.48v-1.7c-2.78.6-3.37-1.34-3.37-1.34-.45-1.16-1.11-1.47-1.11-1.47-.91-.62.07-.61.07-.61 1 .07 1.53 1.03 1.53 1.03.9 1.53 2.36 1.09 2.94.83.09-.65.35-1.09.63-1.34-2.22-.25-4.56-1.11-4.56-4.94 0-1.09.39-1.98 1.03-2.68-.1-.25-.45-1.27.1-2.64 0 0 .84-.27 2.75 1.02a9.5 9.5 0 0 1 5 0c1.91-1.29 2.75-1.02 2.75-1.02.55 1.37.2 2.39.1 2.64.64.7 1.03 1.59 1.03 2.68 0 3.84-2.34 4.69-4.57 4.94.36.31.68.92.68 1.85v2.74c0 .27.18.58.69.48A10 10 0 0 0 12 2Z"/></svg></span>
          <div><h3 style="font-size:1.2rem">CDER-Center / CS1-CS2_Exemplar_Ebook</h3>
            <p class="tiny faint">The shared project repository</p></div>
        </div>
        <p class="muted">Everything lives in this one repository, under <code>CS1/</code>, in a directory per
          chapter named <code>chapterN-INSTITUTION</code> &mdash; matching the chapter numbers in the volume.
          Every chapter directory follows the same five-folder layout, so material is where you expect it
          regardless of which exemplar you open.</p>
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
          <a class="btn btn--primary btn--sm" href="https://github.com/CDER-Center/CS1-CS2_Exemplar_Ebook/tree/main/CS1">Browse the CS1  in Git</a>
          <a class="btn btn--ghost btn--sm" href="https://github.com/CDER-Center/CS1-CS2_Exemplar_Ebook/tree/main">Repository root</a>
        </div>
      </article>

      <!-- <article class="panel">
        <h3 style="font-size:1.2rem">Material Hosted by the Teams</h3>
        <p class="muted mt-3">Some institutions publish additional material of their own, outside the shared
          repository. These are worth reading alongside that chapter&rsquo;s appendix.</p>
        <dl class="deflist mt-4">
          <div>
            <dt>TNTECH</dt>
            <dd>The team&rsquo;s own copy of the CS1 material, including the Flag Maker grids and slides:
              <a href="https://github.com/cscaprilcrockett/CDER_TNTECH_CS1">cscaprilcrockett/CDER_TNTECH_CS1</a>.</dd>
          </div>
          <div>
            <dt>UNL</dt>
            <dd>Codeless modules, with a live demo at
              <a href="https://go.unl.edu/PDCatUNL">go.unl.edu/PDCatUNL</a> and source at
              <a href="https://github.com/cbourke/PDCatUNL">cbourke/PDCatUNL</a>. The course itself is at
              <a href="https://github.com/cbourke/ComputerScienceI">cbourke/ComputerScienceI</a>.</dd>
          </div>
          <div>
            <dt>Webster</dt>
            <dd>Browser-based parallel visualizations used in the flag-colouring activity:
              <a href="https://github.com/xiaoyuansuo51-webster/Flag_Coloring_Activity">xiaoyuansuo51-webster/Flag_Coloring_Activity</a>.</dd>
          </div>
          <div>
            <dt>Casper</dt>
            <dd>A custom zyBooks section at
              <a href="https://github.com/charlottegruner/CDER_CC_CS1">charlottegruner/CDER_CC_CS1</a>.</dd>
          </div>
        </dl>
      </article> -->
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
    cross-references between chapters, and a shared bibliography (pp.&nbsp;296&ndash;301).
    Chapters are not published as standalone files, because a chapter cut out of the volume loses both. The
    summaries below are here so you can find the right chapter quickly, then open the volume at that page.
  </div>

  <div class="table-wrap mt-4">
    <table class="data data--chapters">
      <caption>Page numbers refer to the pages of the current release.</caption>
      <thead><tr><th scope="col">Chapter</th><th scope="col">Part</th><th scope="col">Pages</th><th scope="col">Open</th></tr></thead>
      <tbody id="chapter-rows">
        <tr><td colspan="4" class="small faint">The chapter list requires JavaScript &mdash;
          <a href="download.php?f=book">open the full volume instead</a>, which carries the same contents.</td></tr>
      </tbody>
    </table>
  </div>
  <div class="cluster mt-4">
    <a class="btn btn--primary" href="download.php?f=book">Download the full e-book (PDF)</a>
    <a class="btn btn--ghost" href="https://github.com/CDER-Center/CS1-CS2_Exemplar_Ebook/tree/main/CS1">Open teaching materials</a>
  </div>
</div>


<!-- ──────────────────────────────────────────────────────── citation ──── -->
<div class="shell section--tight" id="cite">
  <div class="cite-card">
    <div>
      <p class="eyebrow">Cite this volume</p>
      <p class="tiny faint mt-2">Copy either form, or select the text if you would rather edit it into your
        own style. The plain-text wording is what the project uses, so please keep it as it stands.</p>
      <p class="tiny faint mt-3">The DOI identifies the e-book as a whole and resolves to its landing page,
        not to any single chapter. Cite chapters by their number within the volume.</p>
      <div class="mt-3"><?= doi_badge($DOI, $DOI_URL) ?></div>
    </div>
    <div>
      <p class="cite-label">Plain text</p>
      <p class="cite-text" data-cite-text><?= e($CITATION) ?></p>
      <div class="cluster mt-3">
        <button class="btn btn--primary btn--sm" type="button" data-copy="<?= e($CITATION) ?>"
                aria-label="Copy citation">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="9" y="9" width="11" height="11" rx="2"/><path d="M5 15V5a2 2 0 0 1 2-2h10"/></svg>
          <span data-copy-label>Copy citation</span>
        </button>
        <a class="btn btn--ghost btn--sm" href="download.php?f=book">Download the volume</a>
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
    </div>
  </div>
</div>

<!-- ────────────────────────────────────────────────────── assessments ─── -->



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
