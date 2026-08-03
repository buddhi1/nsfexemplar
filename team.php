<?php
$PAGE  = 'team';
$PAGE_TITLE = 'About the Team';
$DESC  = 'Authors and contributors of the CS1 course exemplars infused with parallel and distributed computing — backbone team, chapter authors at eight institutions, and the wider CDER community.';
include 'partials/header.php';
?>

<main id="content" tabindex="-1">

<section class="hero hero--page">
  <div class="shell">
    <p class="eyebrow">About the team</p>
    <h1 class="mt-3">Authors and Contributors</h1>
    <p class="lede mt-4">Every development and testing team member was a past CDER trainee or early adopter.
      The training pipeline is how this project staffed itself.</p>
  </div>
</section>

<!-- ─────────────────────────────────────────────────────── backbone ───── -->
<div class="shell section" id="backbone">
  <p class="eyebrow">Backbone team</p>
  <h2 class="mt-2">Coordination and PDC Expertise</h2>
  <p class="lede mt-2">The backbone team ran the training workshops and biweekly meetings, facilitated
    discussion across institutions, provided PDC domain expertise, and authored Chapter 0.</p>
  <div class="grid grid--auto mt-4">
    <div class="card person"><span class="avatar" aria-hidden="true">SP</span><span><span class="who">Sushil K. Prasad</span><span class="where">University of Texas at San Antonio · Lead institution</span></span></div>
    <div class="card person"><span class="avatar" aria-hidden="true">AS</span><span><span class="who">Alan Sussman</span><span class="where">University of Maryland, College Park</span></span></div>
    <div class="card person"><span class="avatar" aria-hidden="true">NT</span><span><span class="who">Neena Thota</span><span class="where">University of Massachusetts, Amherst</span></span></div>
    <div class="card person"><span class="avatar" aria-hidden="true">RV</span><span><span class="who">Ramachandran Vaidyanathan</span><span class="where">Louisiana State University, Baton Rouge</span></span></div>
    <div class="card person"><span class="avatar" aria-hidden="true">CW</span><span><span class="who">Charles Weems</span><span class="where">University of Massachusetts, Amherst</span></span></div>
  </div>
</div>

<!-- ────────────────────────────────────────────────── chapter 1 authors ── -->
<div class="band band--tint">
  <div class="shell section" id="common">
    <div class="grid grid--halves">
      <div class="prose">
        <p class="eyebrow">Chapter 1</p>
        <h2 class="mt-2">Common CS1 Activities and Lessons</h2>
        <p>Chapter 1 is the shared resource chapter that sits between the project overview and the
          institution-specific chapters. Rather than presenting another local implementation, it identifies
          the recurring activity families and reusable guidance that cut across all eight exemplars &mdash;
          and it is the chapter most readers should start from.</p>
        <p class="muted">It was written jointly by authors from a development team and a testing team, which
          is why it reflects both building the material and adopting someone else&rsquo;s.</p>
        <p><a href="download.php?f=02-common-cs1">Chapter 1 (PDF, 38 pp) &rarr;</a></p>
      </div>
      <div class="grid" style="align-content:start">
        <div class="card person"><span class="avatar" aria-hidden="true">AC</span><span><span class="who">April Crockett</span><span class="where">Tennessee Technological University · Development team</span></span></div>
        <div class="card person"><span class="avatar" aria-hidden="true">SS</span><span><span class="who">Srishti Srivastava</span><span class="where">University of Southern Indiana · Testing team</span></span></div>
      </div>
    </div>
  </div>
</div>

<!-- ──────────────────────────────────────────────────────── authors ───── -->
<div class="shell section" id="authors">
  <p class="eyebrow">Chapter authors</p>
  <h2 class="mt-2">Development and Testing Teams</h2>
  <p class="lede mt-2">Two development teams built the original exemplars in two different languages and
    institutional contexts. Six testing teams transferred them into their own courses, adapted them, and fed
    the experience back.</p>
  <div class="grid grid--wide mt-4" id="team-cards">
    <noscript><p class="muted">The team list requires JavaScript. All chapter authors are named on the
      <a href="project.php#partners">Partners table</a> and in each chapter PDF.</p></noscript>
  </div>
</div>

<!-- ────────────────────────────────────────────────────── contributors ── -->
<div class="band band--sunken">
  <div class="shell section" id="contributors">
    <p class="eyebrow">Contributors</p>
    <h2 class="mt-2">Beyond the Chapter Bylines</h2>

    <div class="grid grid--halves mt-4">
      <article class="panel">
        <h3>Co-authors on project publications</h3>
        <p class="muted mt-3">The project&rsquo;s conference papers carry contributors who do not appear as
          chapter authors, including <strong>Michael Gerten</strong>, <strong>Justin Firestone</strong>,
          <strong>Amrita Ghimire</strong>, <strong>Shaun Gao</strong> and <strong>Timila Dangol</strong>.</p>
        <p class="muted mt-3">Laboratory instructors and teaching assistants at each institution also carried
          a real share of the work: coordination with lab staff was repeatedly identified as one of the
          harder parts of adoption, because everyone supporting the course needed enough familiarity with the
          new concepts and tooling to guide students effectively.</p>
        <p class="mt-3"><a href="research.php#publications">See the publications &rarr;</a></p>
      </article>

      <article class="panel">
        <h3>The wider CDER community</h3>
        <p class="muted mt-3">The volume draws on the NSF/IEEE-TCPP curriculum working group whose guideline
          defines the topic scope; more than 140 funded early adopters whose experience reports shaped what
          was worth building, within a community that has grown to roughly 300 participants and institutions
          nationally and internationally; and the Edu* and SIGCSE communities who reviewed and stress-tested
          the materials.</p>
        <p class="muted mt-3">Faculty learning community formation &mdash; community building among CS1 and
          CS2 practitioners, sustainability through professional curriculum bodies and institutional
          adoption, and cross-team coordination &mdash; was led within the backbone team.</p>
      </article>
    </div>

    <article class="panel panel--sunken mt-4">
      <div class="split">
        <div style="max-inline-size:54ch">
          <p class="eyebrow">Join the community</p>
          <h3 class="mt-2">Adopt, adapt, report back</h3>
          <p class="muted mt-3">Some consultation from project personnel is available to help adopters select
            and adapt materials. If you adopt an exemplar, the community&rsquo;s strong preference is that you
            report the experience &mdash; including what didn&rsquo;t work &mdash; at EduPar, EduHPC, EduHiPC
            or SIGCSE. The project Discord is the quickest way to reach other adopters.</p>
        </div>
        <div class="cluster push">
          <a class="btn btn--primary" href="https://discord.gg/xdh3uvD3b">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M19.27 5.33A16.6 16.6 0 0 0 15.2 4.1a.06.06 0 0 0-.07.03c-.18.31-.38.72-.51 1.04a15.4 15.4 0 0 0-4.24 0c-.14-.33-.34-.73-.52-1.04a.06.06 0 0 0-.07-.03 16.55 16.55 0 0 0-4.07 1.23.06.06 0 0 0-.03.02C2.98 9.15 2.27 12.85 2.62 16.5a.07.07 0 0 0 .03.05 16.7 16.7 0 0 0 5 2.5.07.07 0 0 0 .07-.02c.39-.52.73-1.07 1.02-1.65a.06.06 0 0 0-.03-.09c-.54-.2-1.05-.45-1.55-.73a.06.06 0 0 1 0-.11l.3-.24a.06.06 0 0 1 .07 0 11.9 11.9 0 0 0 10.02 0 .06.06 0 0 1 .07 0l.31.24a.06.06 0 0 1 0 .11c-.5.29-1.01.53-1.55.73a.06.06 0 0 0-.04.09c.3.58.64 1.13 1.02 1.65a.07.07 0 0 0 .08.02 16.65 16.65 0 0 0 5-2.5.07.07 0 0 0 .03-.05c.42-4.22-.69-7.89-2.93-11.15a.05.05 0 0 0-.03-.02ZM8.85 14.28c-.98 0-1.79-.9-1.79-2.01 0-1.11.79-2.01 1.79-2.01 1.01 0 1.81.91 1.8 2.01 0 1.11-.8 2.01-1.8 2.01Zm6.31 0c-.98 0-1.79-.9-1.79-2.01 0-1.11.79-2.01 1.79-2.01 1.01 0 1.81.91 1.8 2.01 0 1.11-.79 2.01-1.8 2.01Z"/></svg>
            Join the Discord
          </a>
          <a class="btn btn--ghost" href="mailto:contact@nsfexemplar.cdercenter.org">Contact the project</a>
        </div>
      </div>
      <p class="small faint mt-4">All correspondence about the exemplars, adoption support and this site
        goes to <a href="mailto:contact@nsfexemplar.cdercenter.org">contact@nsfexemplar.cdercenter.org</a>.</p>
    </article>
  </div>
</div>

</main>

<?php include 'partials/footer.php'; ?>
