<?php
$PAGE  = 'resources';
$PAGE_TITLE = 'Resources';
$DESC  = 'Reusable assessment instruments, survey design, Bloom-level mapping and timing tables, plus answers to the questions instructors ask before adopting PDC material in CS1.';
include 'partials/header.php';
?>

<main id="content" tabindex="-1">

<section class="hero hero--page">
  <div class="shell">
    <p class="eyebrow">Resources</p>
    <h1 class="mt-3">Assessments, Repositories and Answers</h1>
    <p class="lede mt-4">The instruments, the material repositories, and the questions adopters ask before
      they commit.</p>
  </div>
</section>


<!-- ────────────────────────────────────────────────────── assessments ─── -->
<div class="shell section" id="assessments">
  <p class="eyebrow">Assessments</p>
  <h2 class="mt-2">Instruments You Can Reuse</h2>
  <p class="lede mt-2">Common instruments were used across all eight institutions, so results can be compared
    between sites &mdash; and so you can compare yours against theirs.</p>

  <div class="grid grid--auto mt-5">
    <article class="card"><h3>Pre- and post-course surveys</h3>
      <p>The shared course-wide instrument: student background and demographics, self-reported experience
        with general computing and with PDC topics, interest, perceived importance and understanding.</p>
      <p class="card-foot">Used at all eight institutions &middot; <a href="#survey-design">what it asks</a> &middot; <a href="download.php?f=book&amp;p=26">Chapter 1 &sect; 1.5</a></p></article>
    <article class="card"><h3>Activity pre/post quizzes</h3>
      <p>Short knowledge checks bracketing an individual activity, so its effect can be isolated from the
        rest of the semester.</p>
      <p class="card-foot"><a href="download.php?f=book&amp;p=276">MSU</a> &middot; <a href="download.php?f=book&amp;p=122">USI</a> &middot; <a href="download.php?f=book&amp;p=236">HPU</a></p></article>
    <article class="card"><h3>Adapted ASPECT engagement survey</h3>
      <p>An engagement instrument adapted for unplugged activities, with results grouped by aspect category.
        Used with Penny Sorting and Flag Maker.</p>
      <p class="card-foot"><a href="download.php?f=book&amp;p=26">Chapter 1</a> &middot; <a href="download.php?f=book&amp;p=236">HPU</a> &middot; <a href="download.php?f=book&amp;p=63">TNTECH</a></p></article>
    <article class="card"><h3>Bloom-level mapping tables</h3>
      <p>Per institution, a table mapping each PDC concept to the Bloom level reached by each activity &mdash;
        the fastest way to check an activity against your own learning outcomes.</p>
      <p class="card-foot">Tables 2.1, 4.1, 5.1, 6.1, 7.1, 8.1, 9.1 &middot; <a href="ebook.php#chapters">find the chapter &rarr;</a></p></article>
    <article class="card"><h3>Activity timing tables</h3>
      <p>Approximate instructional time for each activity phase, so you can fit an activity into a real class
        period rather than discovering the overrun live.</p>
      <p class="card-foot"><a href="download.php?f=book&amp;p=122">USI, Tables 4.9&ndash;4.13</a> &middot; <a href="download.php?f=book&amp;p=236">HPU, Tables 8.3&ndash;8.12</a></p></article>
    <article class="card"><h3>Anonymized datasets &amp; analysis</h3>
      <p>Every chapter repository carries an <code>Evaluation_Data_and_Analysis</code> directory with survey
        instruments, de-identified responses, evaluation metrics and statistical analyses.</p>
      <p class="card-foot"><a href="ebook.php#repos">Browse the repositories &rarr;</a></p></article>
    <article class="card"><h3>Faculty surveys</h3>
      <p>Pre-selection, post-selection, annual and post-adoption instruments for the instructors themselves
        &mdash; prior PDC background, perceived effectiveness, students taught, and whether interventions
        will be retained, reduced or expanded.</p>
      <p class="card-foot"><a href="download.php?f=book&amp;p=5">Chapter 0, evaluation methods</a></p></article>
    <article class="card"><h3>Consent and IRB material</h3>
      <p>Informed-consent forms alongside the surveys, quizzes, lab reports and programming-assignment
        reports collected between Spring 2024 and Spring 2026.</p>
      <p class="card-foot"><a href="ebook.php#chapters">Chapter appendices &rarr;</a> &middot; <a href="ebook.php#repos">repositories</a></p></article>
  </div>
</div>

<!-- ─────────────────────────────────────────────────── survey structure ── -->
<div class="band band--sunken">
  <div class="shell section" id="survey-design">
    <div class="grid grid--halves">
      <div class="prose">
        <p class="eyebrow">Survey design</p>
        <h2 class="mt-2">What the Shared Instrument Actually Asks</h2>
        <p>Students rate their experience with basic programming skills, then their familiarity with PDC
          concepts, then a short set of attitude statements. All items use a seven-point Likert scale:
          programming and PDC concept questions run from <em>1 = no experience at all</em> to
          <em>7 = extremely experienced</em>; attitude questions from <em>1 = strongly disagree</em> to
          <em>7 = strongly agree</em>.</p>
        <p class="muted">Because every site ran the same items, an adopter can administer them locally and
          compare directly against the published per-institution tables.</p>
        <p><a href="download.php?f=book&amp;p=26">Chapter 1 &sect; 1.5 &rarr;</a></p>
      </div>

      <div class="panel">
        <h3>Instrument structure</h3>
        <dl class="deflist mt-4">
          <div>
            <dt>Programming</dt>
            <dd>Data and variable types · arithmetic expressions and operators · branching · loops · calling
              functions and methods · arrays · writing static functions · writing functions and methods ·
              writing classes · creating objects</dd>
          </div>
          <div>
            <dt>PDC concepts</dt>
            <dd><strong>Parallel processing</strong> — operations within a program executing simultaneously.
              <strong>Distributed computing</strong> — a program running using data from a different computer.
              <strong>Event handling</strong> — a function that responds to an independent action.</dd>
          </div>
          <div>
            <dt>Attitudes</dt>
            <dd>A1 interest in computing · A2 interest in learning PDC · A3 belief that learning PDC is
              important · A4 understanding of computing · A5 understanding of PDC</dd>
          </div>
          <div>
            <dt>Analysis</dt>
            <dd>Matched pre/post pairs, Wilcoxon signed-rank tests, Cliff&rsquo;s delta effect sizes, and
              thematic coding of open responses</dd>
          </div>
        </dl>
      </div>
    </div>
  </div>
</div>

<!-- ─────────────────────────────────────────────────────────── FAQs ───── -->
<div class="shell section" id="faqs">
  <p class="eyebrow">FAQs</p>
  <h2 class="mt-2">Before You Adopt</h2>
  <div class="faq-wrap mt-4">
    <div class="faq-list">
      <div class="faq">
        <button class="disclose-btn" type="button" aria-expanded="false" data-disclose>
          <span>I have one class period. What should I run?</span>
          <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m9 6 6 6-6 6"/></svg>
        </button>
        <div class="disclose-panel"><div><div class="inner"><p>Flag Maker or Penny Search, unplugged. Both need only paper, markers or coins,
        run in a single 40&ndash;75&nbsp;minute session, require no programming background, and surface
        speedup, load balance and race conditions concretely. Start with Chapter 1, then look at whichever
        institutional chapter most resembles your class size.</p>
        <p><a href="ebook.php#activities">Browse activities by duration &rarr;</a></p></div></div></div>
      </div>
      <div class="faq">
        <button class="disclose-btn" type="button" aria-expanded="false" data-disclose>
          <span>Will adding PDC hurt my core learning outcomes?</span>
          <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m9 6 6 6-6 6"/></svg>
        </button>
        <div class="disclose-panel"><div><div class="inner"><p>That was the explicit design constraint &mdash; the basic CS1 learning outcomes
        are preserved. Two checks are available. Casper&rsquo;s data shows perception of learning gains in
        non-PDC topics stayed broadly flat between baseline and intervention semesters while PDC gains rose.
        MSU compared a no-intervention semester against the Flag Maker intervention semester and found gains
        across all basic programming categories in both, with comparable post-survey means.</p></div></div></div>
      </div>
      <div class="faq">
        <button class="disclose-btn" type="button" aria-expanded="false" data-disclose>
          <span>There is no room in my syllabus. How did others make space?</span>
          <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m9 6 6 6-6 6"/></svg>
        </button>
        <div class="disclose-panel"><div><div class="inner"><p>Three broad strategies, often combined: selectively reducing or deferring
        existing content, modifying current assignments and activities, and adding short PDC-focused modules.
        Topics commonly reduced included binary and random-access files, C-style strings and arrays, detailed
        output formatting, two-dimensional arrays and advanced class relationships &mdash; usually judged
        coverable more briefly or revisitable in CS2.</p>
        <p>Some institutions removed nothing at all. One placed three asynchronous PDC modules outside class
          time; another used existing lab and recitation periods. Scheduled PDC instructional time ranged from
          none to about seven hours.</p></div></div></div>
      </div>
      <div class="faq">
        <button class="disclose-btn" type="button" aria-expanded="false" data-disclose>
          <span>My course is in Python. Is anything usable?</span>
          <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m9 6 6 6-6 6"/></svg>
        </button>
        <div class="disclose-panel"><div><div class="inner"><p>Yes. All unplugged activities &mdash; Flag Maker, Penny Search and Sorting,
        Coin Addition, the More Processors writing activity &mdash; are language-neutral, as are the
        animations and the codeless modules. Casper College&rsquo;s physical computing projects use
        Python&rsquo;s multiprocessing package. A Python CS2 package covering threading, multiprocessing,
        OpenCV image processing and performance engineering is planned for the CS2 release.</p></div></div></div>
      </div>
      <div class="faq">
        <button class="disclose-btn" type="button" aria-expanded="false" data-disclose>
          <span>How much faculty effort should I budget?</span>
          <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m9 6 6 6-6 6"/></svg>
        </button>
        <div class="disclose-panel"><div><div class="inner"><p>The development teams reported more than 100 hours across the initial
        development year &mdash; creating examples, finding curriculum insertion points, revising
        assignments, testing software and datasets, and refining materials. Adopting already-developed
        modular materials substantially reduces that, but still requires local adaptation, instructor
        preparation and refinement based on student responses.</p>
        <p>Budget separately for coordinating with lab instructors and teaching assistants: they need enough
          familiarity with the concepts and tooling to guide students, and this was repeatedly named as one
          of the harder parts.</p></div></div></div>
      </div>
      <div class="faq-more" id="faq-more" data-faq-more>
      <div class="faq">
        <button class="disclose-btn" type="button" aria-expanded="false" data-disclose>
          <span>Do I need IRB approval to use these materials?</span>
          <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m9 6 6 6-6 6"/></svg>
        </button>
        <div class="disclose-panel"><div><div class="inner"><p>Not to teach them. You need IRB approval if you intend to collect and publish
        student data. Each testing team&rsquo;s IRB experience and instrument design is documented in the
        chapters if you want to run your own study.</p></div></div></div>
      </div>
      <div class="faq">
        <button class="disclose-btn" type="button" aria-expanded="false" data-disclose>
          <span>What if my institution is nothing like any of these?</span>
          <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m9 6 6 6-6 6"/></svg>
        </button>
        <div class="disclose-panel"><div><div class="inner"><p>The set was chosen to span community college, liberal arts, private
        undergraduate, public master&rsquo;s, mid-sized public and R1, on both semester and quarter calendars,
        with class sizes from about 24 to about 100 and student populations including dual-credit high-school
        students and part-time adult learners. Filter by the constraint that binds hardest for you &mdash;
        usually preparation time or class size, not institution type.</p></div></div></div>
      </div>
      <div class="faq">
        <button class="disclose-btn" type="button" aria-expanded="false" data-disclose>
          <span>Is the technical setup going to bite me?</span>
          <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m9 6 6 6-6 6"/></svg>
        </button>
        <div class="disclose-panel"><div><div class="inner"><p>Anticipate differences among student computers, compiler configurations,
        OpenMP support and execution timing &mdash; all of which the teams hit. Before using any resource,
        verify that software versions, API links, datasets and institutional assessment requirements are
        still current. The unplugged and codeless pathways exist precisely to avoid this class of
        problem.</p></div></div></div>
      </div>

      <div class="faq">
        <button class="disclose-btn" type="button" aria-expanded="false" data-disclose>
          <span>Can I get help adopting?</span>
          <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m9 6 6 6-6 6"/></svg>
        </button>
        <div class="disclose-panel"><div><div class="inner"><p>Yes &mdash; some consultation from project personnel is available to help
        adopters select and adapt materials.
        <a href="mailto:contact@nsfexemplar.cdercenter.org">contact@nsfexemplar.cdercenter.org</a>.</p></div></div></div>
      </div>
      </div>
    </div>
    <button class="btn btn--ghost btn--sm mt-3" type="button"
            data-faq-toggle aria-expanded="true" aria-controls="faq-more" hidden>
      Show 4 more questions
    </button>
  </div>
</div>

<!-- ───────────────────────────────────────────────── finding material ─── -->
<div class="band band--tint">
  <div class="shell section" id="finding">
    <p class="eyebrow">Finding material quickly</p>
    <h2 class="mt-2">Search Terms That Work</h2>
    <p class="lede mt-2">The fastest way to use the volume is to combine chapter-level navigation with search.
      The index of terms provides another entry point for locating recurring PDC concepts, activity names,
      tools and assessment terms across the institutional chapters.</p>

    <div class="grid grid--halves mt-4">
      <div class="panel">
        <h3>For activities and concepts</h3>
        <p class="muted mt-3">Flag Maker · Penny Search · Penny Sorting · OpenMP · Earthquake Tracker ·
          Greenfoot · animation · visualization · data parallelism · speedup · load balance · event handling ·
          remote data · API</p>
      </div>
      <div class="panel">
        <h3>For assessment material</h3>
        <p class="muted mt-3">pre/post survey · student feedback · engagement · assessment · reflection ·
          data analysis</p>
      </div>
    </div>

    <div class="cluster mt-4">
      <a class="btn btn--primary" href="ebook.php#explorer">Search the exemplars</a>
      <a class="btn btn--ghost" href="ebook.php#activities">Browse by activity</a>
    </div>
  </div>
</div>

<!-- ──────────────────────────────────────────────────── wider CDER ────── -->
<div class="shell section" id="more">
  <p class="eyebrow">Beyond this project</p>
  <h2 class="mt-2">Wider CDER Resources</h2>
  <div class="grid grid--auto mt-4">
    <article class="card"><h3>PDC curriculum guideline</h3>
      <p>The NSF/IEEE-TCPP topic list with Bloom levels that defines the scope this project draws from.
        Version I (2012) and Version II&beta; (2020).</p>
      <p class="card-foot"><a href="https://cdercenter.org/pdc-curriculum/">Read the guideline &rarr;</a></p></article>
    <article class="card"><h3>Courseware</h3>
      <p>The wider CDER collection of community-contributed teaching material &mdash; syllabi, slides,
        assignments and labs across the undergraduate curriculum, beyond this project's exemplars.</p>
      <p class="card-foot"><a href="https://cdercenter.org/courseware/">Browse the courseware &rarr;</a></p></article>
    <article class="card"><h3>Peachy Assignments</h3>
      <p>Peer-reviewed parallel programming assignments, selected and presented each year at EduPar and
        EduHPC &mdash; ready to drop into a course once students are past the introductory sequence.</p>
      <p class="card-foot"><a href="https://cdercenter.org/peachy-assignments/">See the assignments &rarr;</a></p></article>
    <article class="card"><h3>CDER book series</h3>
      <p>Volumes 1 and 2 (Morgan Kaufmann, Springer) available as free preprint chapters; Volume 3, focused
        on adopter experience and resources, expected from Springer in late 2026.</p>
      <p class="card-foot"><a href="https://cdercenter.org/">Centre home &rarr;</a></p></article>
    <article class="card"><h3>Project repository</h3>
      <p>Syllabi, handouts, slides, assignments, starter code, surveys and anonymized evaluation data for
        every chapter &mdash; curated periodically as adopters revise and extend them.</p>
      <p class="card-foot"><a href="https://github.com/CDER-Center/CS1-CS2_Exemplar_Ebook/tree/main">GitHub &rarr;</a></p></article>
  </div>
</div>

</main>

<?php include 'partials/footer.php'; ?>
