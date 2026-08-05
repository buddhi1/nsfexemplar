<?php
$PAGE  = 'project';
$PAGE_TITLE = 'About the Project';
$DESC  = 'Goals, NSF support and partners of the Modern Course Exemplars project — infusing parallel and distributed computing into the introductory computing sequence. NSF Award #2321015.';
include 'partials/header.php';
?>

<main id="content" tabindex="-1">

<section class="hero hero--page">
  <div class="shell">
    <p class="eyebrow">About the project</p>
    <h1 class="mt-3">Somebody Has to Go First</h1>
    <p class="lede mt-4">Stakeholders agreed that introductory computing needed modernizing &mdash; but nobody
      had a modernized course to copy. This project builds one, in eight different institutional contexts,
      and measures whether it works.</p>
  </div>
</section>

<!-- ───────────────────────────────────────────────────────────── goals ── -->
<div class="shell section" id="goals">
  <div class="grid grid--halves">
    <div class="prose">
      <p class="eyebrow">Goals</p>
      <h2 class="mt-2">The Problem, Stated Plainly</h2>
      <p>The CDER Center ran a series of stakeholder workshops from 2020 to 2023, supported by NSF
        CyberTraining Institute Conceptualization Award
        <a href="https://www.nsf.gov/awardsearch/showAward?AWD_ID=2002649">#2002649</a>, examining the
        systemic barriers to broader PDC adoption &mdash; among them the continued dominance of a sequential
        model of computation in early courses. Employer stakeholders &mdash; including national labs and
        agencies &mdash; reported that computer science and engineering students are not graduating with a
        sufficient grasp of the software development process for the modern computing ecosystem, which
        relies on parallelism, distribution, asynchrony, scaling, integration across disparate libraries and
        data sources, test-driven design and pervasive concerns for security.</p>
      <p>The lack of preparation is rooted in an obsolete algorithmic model of computing &mdash; sequential,
        synchronous, with text-based I/O &mdash; that instruction relies on from introductory programming
        through data structures, algorithms and software engineering. Once that model is entrenched, students
        may only encounter PDC ad hoc via electives.</p>
      <p>Education stakeholders said changing the model is nearly impossible because of a lack of exemplars,
        few teaching materials, little instructor training in modern computing, and little evidence that
        changing the model benefits students. Perhaps the greatest impediment is that academic stakeholders
        find it difficult to visualize what a different approach would look like &mdash; and most feel such a
        change is simply not possible. What the workshops heard repeatedly was:
        <em>&ldquo;someone else needs to go first.&rdquo;</em></p>
      <p class="small faint"><a href="download.php?f=book&amp;p=9">Read this in full &mdash; Chapter 0
        &sect;&thinsp;0.1, p.&nbsp;9 &rarr;</a></p>
    </div>
    <ol class="goal-list">
      <li><span class="goal-n">1</span><span><b>Build</b> a modern first-year sequence around a computing
        model that includes parallelism and distribution as found in current systems.</span></li>
      <li><span class="goal-n">2</span><span><b>Generalize</b> it across two colleges and two languages, to
        show the model is not language- or context-specific.</span></li>
      <li><span class="goal-n">3</span><span><b>Evaluate</b> with pre/post instruments grounded in education
        science, measuring unmodified courses first as a control.</span></li>
      <li><span class="goal-n">4</span><span><b>Transfer</b> to six further institutions chosen for diversity
        of size, schedule and student population, and find what makes it widely usable.</span></li>
      <li><span class="goal-n">5</span><span><b>Disseminate</b> everything &mdash; course sequences,
        materials, chapters and results &mdash; through active outreach.</span></li>
    </ol>
  </div>
</div>

<!-- ───────────────────────────────────────────────────────────── scope ── -->
<div class="band band--sunken">
  <div class="shell section" id="topics">
    <p class="eyebrow">Scope</p>
    <h2 class="mt-2">Three PDC Topics, Chosen Deliberately</h2>
    <p class="lede mt-2">Not everything in the curriculum guideline belongs in a first-year course. The
      project narrowed to three ideas that attach naturally to existing CS1 and CS2 content.</p>

    <div class="grid grid--auto mt-5">
      <article class="card">
        <span class="card-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" aria-hidden="true" focusable="false"><rect x="3.5" y="5" width="7" height="6" rx="1"/><rect x="13.5" y="5" width="7" height="6" rx="1"/><rect x="3.5" y="13" width="7" height="6" rx="1"/><rect x="13.5" y="13" width="7" height="6" rx="1"/></svg></span>
        <h3>Data parallelism</h3>
        <p>Attaches to loops, arrays, searching and sorting. A range-based <code>for</code> loop already
          models dependence-free processing of a collection &mdash; a candidate for parallelism sitting in
          plain sight.</p>
      </article>
      <article class="card">
        <span class="card-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><circle cx="6" cy="12" r="2.6"/><circle cx="18" cy="6.5" r="2.6"/><circle cx="18" cy="17.5" r="2.6"/><path d="M8.4 10.8 15.6 7.6M8.4 13.2l7.2 3.2"/></svg></span>
        <h3>Distributed data access</h3>
        <p>Modern file and user I/O APIs read and write serialized objects, even from remote repositories.
          Students pull live data from a real remote service instead of parsing console text.</p>
      </article>
      <article class="card">
        <span class="card-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M13 3 5 13h6l-1 8 8-10h-6Z"/></svg></span>
        <h3>Event-driven processing</h3>
        <p>Graphical interfaces exemplify event-driven processing, which illustrates many of the basic ideas
          behind control parallelism &mdash; and is the paradigm internet-native students already inhabit.</p>
      </article>
    </div>

<?php /* ---------------------------------------------------------------------
   Commented out: both objections are answered at more length in the Resources
   FAQ ("There is no room in my syllabus…" and "Will adding PDC hurt my core
   learning outcomes?"), so repeating them here only cost page height.
   Remove this <?php /* wrapper and the closing marker to bring them back.

<div class="grid grid--halves mt-5">
      <div class="panel">
        <h3>&ldquo;There&rsquo;s no room in the syllabus&rdquo;</h3>
        <p class="muted mt-3">Institutions created space in three broad, sometimes overlapping ways:
          selectively reducing existing content, modifying current assignments and activities, and adding
          short PDC-focused modules. Topics reduced or deferred included binary and random-access files,
          C-style strings and arrays, detailed output formatting, two-dimensional arrays, advanced class
          relationships and static members &mdash; generally not because they are unimportant, but because
          they could be covered more briefly or revisited later.</p>
        <p class="muted mt-3">Others retained the full syllabus. One placed three asynchronous PDC modules
          outside class time; another used existing lab and recitation periods. Across institutions,
          scheduled instructional time devoted to PDC ranged from none at all to about seven hours &mdash;
          roughly one to two weeks of student engagement.</p>
      </div>
      <div class="panel">
        <h3>&ldquo;Isn&rsquo;t this too advanced?&rdquo;</h3>
        <p class="muted mt-3">Students in CS1 are simultaneously learning syntax, control structures,
          debugging and problem-solving. Introducing threads, race conditions or synchronization at
          implementation level too early creates unnecessary cognitive load. So the exemplars lead with
          conceptual understanding, visual demonstrations, bounded code examples and unplugged activities
          before students interact with parallel code at all.</p>
        <p class="muted mt-3">The intent is not for students to become proficient parallel programmers in
          CS1, but for them to recognize that computational work can be divided, that modern computers
          contain multiple processing units, and to begin reasoning about the benefits and limits of
          parallel execution.</p>
      </div>
    </div>

--------------------------------------------------------------------------- */ ?>
  </div>
</div>

<!-- ──────────────────────────────────────────────────── teams & timeline ── -->
<div class="shell section" id="teams">
  <p class="eyebrow">How it was run</p>
  <h2 class="mt-2">Three Sets of Teams, Working Closely Together</h2>
  <div class="grid grid--auto mt-5">
    <article class="card"><span class="pill pill--brand">Development</span>
      <h3 class="mt-2">Two institutions, two languages</h3>
      <p>Tennessee Tech (C++, semester system, large lecture and lab CS1) and Knox College (Java, quarter
        system, classes of about 24) each created exemplars for their first-year sequence, coordinating so
        the same PDC concepts were covered in context-specific ways.</p></article>
    <article class="card"><span class="pill pill--brand">Testing</span>
      <h3 class="mt-2">Six adopting institutions</h3>
      <p>Recruited through the CDER community and the Edu* and SIGCSE networks, then selected to balance
        language and institution type. Each transferred the exemplars into their own courses, identified
        opportunities and challenges, and fed the experience back to improve both sets of teams.</p></article>
    <article class="card"><span class="pill pill--brand">Backbone</span>
      <h3 class="mt-2">Coordination &amp; expertise</h3>
      <p>Prasad, Sussman, Thota, Vaidyanathan and Weems ran the training workshops and biweekly meetings,
        facilitated discussion, and provided PDC domain expertise.</p></article>
  </div>

  <p class="eyebrow mt-6">Project timeline</p>
  <h3 class="mt-2 mb-4">Four years, from recruitment to publication</h3>
  <ol class="timeline timeline--rail">
    <li>
      <span class="year">2023</span>
      <h4>Recruitment and kickoff</h4>
      <p>Six testing teams recruited and selected on course fit, institutional willingness and language,
        then brought together at an in-person summer workshop.</p>
    </li>
    <li>
      <span class="year">2024</span>
      <h4>CS1 transfer and baselines</h4>
      <p>CS1 exemplars transferred to the testing teams. IRB approval obtained, research questions agreed,
        and baseline offerings taught and measured with no intervention.</p>
    </li>
    <li>
      <span class="year">2025</span>
      <h4>CS2 work and intervention runs</h4>
      <p>Baseline data and the first experimental CS1 runs presented; focus turned to CS2 while the new
        courses ran and evidence accumulated.</p>
    </li>
    <li>
      <span class="year">2026&ndash;27</span>
      <h4>Analysis and publication</h4>
      <p>Cross-institution analysis, this e-book and conference reports &mdash; plus a follow-on NSF award
        extending training to AI and Big Data.</p>
    </li>
  </ol>
</div>

<!-- ─────────────────────────────────────────────────────── NSF support ── -->
<div class="band band--tint">
  <div class="shell section" id="nsf">
    <article class="panel">
      <div class="split">
        <div><p class="eyebrow">NSF support</p><h2 class="mt-2"><a href="https://www.nsf.gov/awardsearch/showAward?AWD_ID=2321015">Award #2321015</a></h2></div>
        <span class="pill pill--accent push">CyberTraining: Implementation: Medium</span>
      </div>
      <p class="muted mt-4" style="max-inline-size:var(--measure)"><em>Modern Course Exemplars infused with
        Parallel and Distributed Computing for the Introductory Computing Course Sequence (2023&ndash;2027).</em>
        The project adopts a &ldquo;go first&rdquo; effort to create exemplars of first-year course sequence
        changes and accompanying materials, while demonstrating their effectiveness and transferability.</p>

      <div class="grid grid--auto mt-5">
        <div class="card"><h3>Where the need was identified</h3>
          <p>NSF CyberTraining Institute Conceptualization Award <a href="https://www.nsf.gov/awardsearch/showAward?AWD_ID=2002649"><strong>#2002649</strong></a> supported the
            2020&ndash;23 stakeholder workshops that examined the systemic barriers to PDC adoption and
            produced the &ldquo;someone needs to go first&rdquo; conclusion this project answers.</p></div>
        <div class="card"><h3>Prior support that made this possible</h3>
          <p>The earlier CDER center grant (curriculum guidelines, dissemination, early adoption) and an NSF
            CyberTraining implementation grant for broader instructor training in incorporating PDC into
            undergraduate CS and CE curricula.</p></div>
        <div class="card"><h3>Foundational grants</h3>
          <p>IIS 1143533 · CCF 1135124 · CCF 1048711 · CNS 0950432 &mdash; supporting the NSF/IEEE-TCPP
            curriculum initiative, with additional support from IEEE TCPP, Intel, NVIDIA and IBM.</p></div>
        <div class="card"><h3>What comes next</h3>
          <p>A new NSF award will organize additional instructor training workshops to further infuse AI and
            Big Data, together with PDC, into CS1, CS2 and Computer Systems courses through the CDER
            center.</p></div>
      </div>
    </article>
  </div>
</div>

<!-- ────────────────────────────────────────────────────────── lineage ─── -->
<div class="shell section" id="lineage">
  <p class="eyebrow">Prior work</p>
  <h2 class="mt-2">This Did Not Arise in Isolation</h2>
  <p class="lede mt-2">The CDER Center has worked for more than a decade to integrate PDC concepts into
    undergraduate computing curricula. Hover or tab through any row for the detail.</p>

  <ol class="timeline timeline--compact mt-5">
    <li>
      <button class="era" type="button" aria-expanded="false" data-disclose>
        <span class="year">2010&ndash;2020</span>
        <span class="era-title">The IEEE TCPP Curriculum Initiative</span>
        <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m9 6 6 6-6 6"/></svg>
      </button>
      <div class="detail"><div><p>Began with a single question: what should every computing undergraduate know about parallel and distributed computing? The resulting guidelines identified PDC topics across programming, architecture, algorithms and cross-cutting areas, and mapped them to levels of coverage. Version I was released in 2012; Version II&beta; in 2020, with expanded attention to Big Data, energy and distributed computing. ACM/IEEE CS2013 linked to it explicitly, and CS2023 includes a dedicated PDC knowledge area citing the Version II&beta; effort.</p></div></div>
    </li>
    <li>
      <button class="era" type="button" aria-expanded="false" data-disclose>
        <span class="year">Ongoing</span>
        <span class="era-title">Early adopters</span>
        <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m9 6 6 6-6 6"/></svg>
      </button>
      <div class="detail"><div><p>Curriculum guidelines alone do not change courses. The CDER early adopter competitions incentivized instructors to incorporate PDC into existing courses and report their experiences. More than 140 early adopters have been supported, and the broader community of early adopters and trained instructors has grown to roughly 300 participants or institutions nationally and internationally. They demonstrated PDC could be infused widely &mdash; and revealed a persistent need for complete, coherent, classroom-tested course exemplars, especially for early courses.</p></div></div>
    </li>
    <li>
      <button class="era" type="button" aria-expanded="false" data-disclose>
        <span class="year">Vols 1&ndash;3</span>
        <span class="era-title">The CDER book series</span>
        <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m9 6 6 6-6 6"/></svg>
      </button>
      <div class="detail"><div><p>The first two volumes, published by Morgan Kaufmann and Springer, gathered chapters on introductory parallelism, threads, fork-join parallelism, performance, scalability, energy efficiency, MapReduce, GPU computing, mobile concurrency and integrative GUI applications, available as free preprints and collectively reaching tens of thousands of downloads. A third volume, focused on adopter experience and resources, is expected from Springer in late 2026.</p></div></div>
    </li>
    <li>
      <button class="era" type="button" aria-expanded="false" data-disclose>
        <span class="year">EduPar · EduHPC · EduHiPC</span>
        <span class="era-title">The Edu* workshop series</span>
        <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m9 6 6 6-6 6"/></svg>
      </button>
      <div class="detail"><div><p>EduPar (with IPDPS) began as a venue for early adopters to report experiences and discuss curricular and pedagogical issues. EduHPC (with SC) created a complementary venue connected to the HPC community; EduHiPC (with HiPC, in India) extended the model internationally. Together they transformed isolated adoption efforts into an ongoing professional community.</p></div></div>
    </li>
    <li>
      <button class="era" type="button" aria-expanded="false" data-disclose>
        <span class="year">Training</span>
        <span class="era-title">Instructor workshops</span>
        <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m9 6 6 6-6 6"/></svg>
      </button>
      <div class="detail"><div><p>Distinct from the Edu* research workshops, CDER has organized instructor-facing training: NSF CyberTraining week-long summer workshops, tutorials and special sessions at SIGCSE, training associated with EduHiPC, and hybrid or online events. <strong>All of this project&rsquo;s development and testing team members were past trainees or early adopters</strong> &mdash; the training pipeline is how the project staffed itself.</p></div></div>
    </li>
  </ol>
</div>

<!-- ──────────────────────────────────────────────────────── partners ──── -->
<div class="band band--sunken">
  <div class="shell section" id="partners">
    <p class="eyebrow">Partners</p>
    <h2 class="mt-2">Eight Institutions, Deliberately Unalike</h2>
    <p class="lede mt-2">No single model fits all languages, calendars, class sizes, student populations or
      instructor backgrounds. This project emphasizes multiple pathways for PDC infusion.</p>
    <div class="table-wrap mt-4" id="glance">
      <table class="data data--glance">
        <caption>Quick comparison of CS1 institutional exemplars for potential adopters (Table 1, Chapter 0).</caption>
        <colgroup>
          <col class="col-ch"><col class="col-ctx"><col class="col-lang">
          <col class="col-style"><col class="col-start">
        </colgroup>
        <thead><tr>
          <th scope="col">Chapter</th><th scope="col">Institutional context</th><th scope="col">Lang.</th>
          <th scope="col">Primary PDC infusion style</th><th scope="col">Good starting point for</th>
        </tr></thead>
        <tbody>
          <tr><th scope="row">2 · TNTECH</th><td>Mid-sized public university; large CS1 lecture/lab setting</td><td>C++</td><td>Unplugged Penny and Flag Maker activities; OpenMP/data-parallel programming; remote-data/API assignment</td><td>Large-section CS1 adoption; programming-linked PDC infusion; multi-semester evidence</td></tr>
          <tr><th scope="row">3 · Knox</th><td>Small residential liberal arts college; Java-based introductory sequence</td><td>Java</td><td>Conceptual and programming-oriented CS1 activities adapted to a small-college context</td><td>Liberal-arts setting; smaller classes; Java-based adaptation</td></tr>
          <tr><th scope="row">4 · USI</th><td>Public master&rsquo;s-level institution; introductory object-oriented CS1 course</td><td>Java</td><td>Unplugged Flag Maker and Penny activities, plugged-in Flag Maker and Parallel Sort, Greenfoot, and activity-level assessment</td><td>Structured activity adoption; evidence-rich unplugged and plugged-in examples</td></tr>
          <tr><th scope="row">5 · UNL</th><td>Large R1 institution; multiple CS1 sections and computing majors</td><td>C, Java</td><td>Codeless modules, visual and conceptual activities, remote-data ideas, scalable conceptual infusion</td><td>Low-code pathway; large-institution adaptation; conceptual modules; online classes</td></tr>
          <tr><th scope="row">6 · Webster</th><td>Private primarily undergraduate institution; small-to-moderate CS1 sections</td><td>C++</td><td>Animations, visualizations, performance examples, and game/simulation-oriented activities</td><td>Visualization-first adoption; low-barrier conceptual entry points</td></tr>
          <tr><th scope="row">7 · Casper</th><td>Two-year public community college; small cohorts and mixed student pathways</td><td>C++, Python</td><td>Custom digital-textbook material, unplugged activities, OpenMP, remote data, and physical computing</td><td>Community-college setting; small cohorts; varied delivery formats</td></tr>
          <tr><th scope="row">8 · HPU</th><td>Private undergraduate-serving institution with small class sizes</td><td>Java</td><td>Flag Maker, Penny Sorting, lightweight plugged/code-review extension, and low-resource activities</td><td>Small-scale first adoption; low-preparation unplugged activities</td></tr>
          <tr><th scope="row">9 · MSU</th><td>Public university; focused CS1 intervention</td><td>Java</td><td>Flag Maker-centered data-parallelism activity with supporting animations and assessment</td><td>Minimal-infusion model; short, focused CS1 intervention</td></tr>
        </tbody>
      </table>
    </div>
    <p class="small faint mt-3">Chapter authors for each institution are listed on the
      <a href="team.php#authors">team page</a>.</p>
    <div class="cluster mt-4">
      <a class="btn btn--primary" href="ebook.php#explorer">Search the exemplars</a>
      <a class="btn btn--ghost" href="team.php">Meet the team</a>
    </div>
  </div>
</div>

<!-- ────────────────────────────────────────────────────── get in touch ─── -->
<div class="band band--feature">
  <div class="shell section--tight" id="contact">
    <div class="grid grid--halves">
      <div class="prose">
        <p class="eyebrow">Get in touch</p>
        <h2 class="mt-2">Thinking of Adopting?</h2>
        <p>The material is free and needs no permission to use. Tell us what you are considering and we can
          point you at the right chapter, help you scope a first adoption, or arrange a short consultation.</p>
        <p class="muted">Knowing where the exemplars travel also lets the project report to NSF.</p>
      </div>
      <div class="panel">
        <div class="cluster">
          <a class="btn btn--primary" href="contact.php">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M4 6.5h16v11H4z"/><path d="m4 7 8 6 8-6"/></svg>
            Request adoption help
          </a>
          <a class="btn btn--ghost" href="https://discord.gg/xdh3uvD3b">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M19.27 5.33A16.6 16.6 0 0 0 15.2 4.1a.06.06 0 0 0-.07.03c-.18.31-.38.72-.51 1.04a15.4 15.4 0 0 0-4.24 0c-.14-.33-.34-.73-.52-1.04a.06.06 0 0 0-.07-.03 16.55 16.55 0 0 0-4.07 1.23.06.06 0 0 0-.03.02C2.98 9.15 2.27 12.85 2.62 16.5a.07.07 0 0 0 .03.05 16.7 16.7 0 0 0 5 2.5.07.07 0 0 0 .07-.02c.39-.52.73-1.07 1.02-1.65a.06.06 0 0 0-.03-.09c-.54-.2-1.05-.45-1.55-.73a.06.06 0 0 1 0-.11l.3-.24a.06.06 0 0 1 .07 0 11.9 11.9 0 0 0 10.02 0 .06.06 0 0 1 .07 0l.31.24a.06.06 0 0 1 0 .11c-.5.29-1.01.53-1.55.73a.06.06 0 0 0-.04.09c.3.58.64 1.13 1.02 1.65a.07.07 0 0 0 .08.02 16.65 16.65 0 0 0 5-2.5.07.07 0 0 0 .03-.05c.42-4.22-.69-7.89-2.93-11.15a.05.05 0 0 0-.03-.02ZM8.85 14.28c-.98 0-1.79-.9-1.79-2.01 0-1.11.79-2.01 1.79-2.01 1.01 0 1.81.91 1.8 2.01 0 1.11-.8 2.01-1.8 2.01Zm6.31 0c-.98 0-1.79-.9-1.79-2.01 0-1.11.79-2.01 1.79-2.01 1.01 0 1.81.91 1.8 2.01 0 1.11-.79 2.01-1.8 2.01Z"/></svg>
            Join the Discord
          </a>
        </div>
        <p class="small muted mt-4">Prefer plain email? Write to
          <a href="mailto:<?= e(CONTACT_EMAIL) ?>"><?= e(CONTACT_EMAIL) ?></a>. Every route reaches the
          same people.</p>
      </div>
    </div>
  </div>
</div>

</main>

<?php include 'partials/footer.php'; ?>
