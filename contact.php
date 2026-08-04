<?php
/* The form is handled before any output, so a successful post can finish
   without the page having started rendering. */
require_once __DIR__ . '/lib/contact.php';
$FORM = contact_handle();

$PAGE       = 'contact';
$PAGE_TITLE = 'Adoption &amp; contact';
$DESC       = 'Tell us what you plan to adopt, ask for a short consultation, or just get in touch with '
            . 'the CDER exemplar project team.';
include 'partials/header.php';

$COURSES = contact_courses();
$v = $FORM['values'];
$err = $FORM['errors'];
/** Was this field rejected? */
$bad = static fn(string $k): bool => isset($err[$k]);
?>

<main id="content" tabindex="-1">

<section class="hero hero--page">
  <div class="shell">
    <p class="eyebrow">Adoption &amp; contact</p>
    <h1 class="mt-3">Tell Us What You Plan to Adopt</h1>
    <p class="lede mt-4">Adopting one activity, a lab, or a whole course model &mdash; we would like to hear
      about it, and we can help you get started. Nothing here is a commitment.</p>
  </div>
</section>

<div class="shell section--tight">
  <div class="grid grid--halves">
    <div class="prose">
      <h2>Why Ask</h2>
      <p>The material is free and needs no permission to use. This form exists because knowing who is
        adopting what helps us support you properly, and lets the project report honestly on where the
        exemplars have travelled.</p>
      <p class="muted">If you would rather not use a form, write to
        <a href="mailto:<?= e(CONTACT_EMAIL) ?>"><?= e(CONTACT_EMAIL) ?></a> or come and ask in the
        community &mdash; both reach the same people.</p>

      <div class="cluster mt-4">
        <a class="btn btn--ghost" href="mailto:<?= e(CONTACT_EMAIL) ?>">Email us instead</a>
        <a class="btn btn--ghost" href="https://discord.gg/xdh3uvD3b">
          <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M19.27 5.33A16.6 16.6 0 0 0 15.2 4.1a.06.06 0 0 0-.07.03c-.18.31-.38.72-.51 1.04a15.4 15.4 0 0 0-4.24 0c-.14-.33-.34-.73-.52-1.04a.06.06 0 0 0-.07-.03 16.55 16.55 0 0 0-4.07 1.23.06.06 0 0 0-.03.02C2.98 9.15 2.27 12.85 2.62 16.5a.07.07 0 0 0 .03.05 16.7 16.7 0 0 0 5 2.5.07.07 0 0 0 .07-.02c.39-.52.73-1.07 1.02-1.65a.06.06 0 0 0-.03-.09c-.54-.2-1.05-.45-1.55-.73a.06.06 0 0 1 0-.11l.3-.24a.06.06 0 0 1 .07 0 11.9 11.9 0 0 0 10.02 0 .06.06 0 0 1 .07 0l.31.24a.06.06 0 0 1 0 .11c-.5.29-1.01.53-1.55.73a.06.06 0 0 0-.04.09c.3.58.64 1.13 1.02 1.65a.07.07 0 0 0 .08.02 16.65 16.65 0 0 0 5-2.5.07.07 0 0 0 .03-.05c.42-4.22-.69-7.89-2.93-11.15a.05.05 0 0 0-.03-.02ZM8.85 14.28c-.98 0-1.79-.9-1.79-2.01 0-1.11.79-2.01 1.79-2.01 1.01 0 1.81.91 1.8 2.01 0 1.11-.8 2.01-1.8 2.01Zm6.31 0c-.98 0-1.79-.9-1.79-2.01 0-1.11.79-2.01 1.79-2.01 1.01 0 1.81.91 1.8 2.01 0 1.11-.79 2.01-1.8 2.01Z"/></svg>
          Join the Discord
        </a>
      </div>

      <p class="tiny faint mt-5">We use what you send to answer you and to report adoption numbers to NSF.
        Names and institutions are never published without asking first.</p>
    </div>

    <div class="panel">
<?php if ($FORM['status'] === 'ok'): ?>
      <div class="callout callout--info" role="status">
        <strong>Thank you &mdash; that reached us.</strong> Someone from the project will reply to
        <?= e($v['email']) ?>. If you asked for a consultation we will suggest a couple of times.
      </div>
      <p class="mt-4"><a class="btn btn--ghost" href="ebook.php">Back to the eBook</a></p>

<?php elseif ($FORM['status'] === 'undelivered'): ?>
      <div class="callout" role="alert">
        <strong>The message could not be sent from the server.</strong> Nothing you did &mdash; mail is not
        going out from this host. Please send the same details to
        <a href="mailto:<?= e(CONTACT_EMAIL) ?>"><?= e(CONTACT_EMAIL) ?></a> and we will pick it up there.
      </div>

<?php else: ?>
<?php if ($FORM['status'] === 'error' && !$err): ?>
      <div class="callout" role="alert">
        <strong>That submission did not go through.</strong> Please try once more, or write to
        <a href="mailto:<?= e(CONTACT_EMAIL) ?>"><?= e(CONTACT_EMAIL) ?></a>.
      </div>
<?php elseif ($err): ?>
      <div class="callout" role="alert">
        <strong>A few things need checking</strong> &mdash; they are marked below.
      </div>
<?php endif; ?>

      <form method="post" action="contact.php#form" id="form" class="form mt-2">
        <input type="hidden" name="token" value="<?= e(contact_token()) ?>">
        <!-- Honeypot: off-screen, skipped by keyboard, hidden from assistive tech.
             Anything that fills it in is not a person. -->
        <div class="hp" aria-hidden="true">
          <label for="website">Website</label>
          <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
        </div>

        <div class="field">
          <label for="name">Your name <span class="req" aria-hidden="true">*</span></label>
          <input type="text" id="name" name="name" required autocomplete="name"
                 value="<?= e($v['name']) ?>"<?= $bad('name') ? ' aria-invalid="true" aria-describedby="e-name"' : '' ?>>
          <?php if ($bad('name')): ?><p class="field-err" id="e-name"><?= e($err['name']) ?></p><?php endif; ?>
        </div>

        <div class="field">
          <label for="email">Email <span class="req" aria-hidden="true">*</span></label>
          <input type="email" id="email" name="email" required autocomplete="email"
                 value="<?= e($v['email']) ?>"<?= $bad('email') ? ' aria-invalid="true" aria-describedby="e-email"' : '' ?>>
          <?php if ($bad('email')): ?><p class="field-err" id="e-email"><?= e($err['email']) ?></p><?php endif; ?>
        </div>

        <div class="field">
          <label for="institution">Institution <span class="req" aria-hidden="true">*</span></label>
          <input type="text" id="institution" name="institution" required autocomplete="organization"
                 value="<?= e($v['institution']) ?>"<?= $bad('institution') ? ' aria-invalid="true" aria-describedby="e-inst"' : '' ?>>
          <?php if ($bad('institution')): ?><p class="field-err" id="e-inst"><?= e($err['institution']) ?></p><?php endif; ?>
        </div>

        <fieldset class="field">
          <legend>Which course?</legend>
          <div class="checks">
<?php foreach ($COURSES as $val => $label): ?>
            <label class="chip"><input type="checkbox" name="courses[]" value="<?= e($val) ?>"
              <?= in_array($val, $v['courses'], true) ? ' checked' : '' ?>> <span><?= e($label) ?></span></label>
<?php endforeach; ?>
          </div>
        </fieldset>

        <div class="field-row">
          <div class="field">
            <label for="language">Programming language</label>
            <input type="text" id="language" name="language" value="<?= e($v['language']) ?>"
                   placeholder="Java, C++, Python&hellip;">
          </div>
          <div class="field">
            <label for="term">Possible term</label>
            <input type="text" id="term" name="term" value="<?= e($v['term']) ?>"
                   placeholder="Fall 2026">
          </div>
        </div>

        <div class="field">
          <label for="interest">Activity or chapter of interest</label>
          <textarea id="interest" name="interest" rows="4"
                    placeholder="e.g. Flag Maker in a large CS1 lab, or the Casper chapter"
                    <?= $bad('interest') ? ' aria-invalid="true" aria-describedby="e-int"' : '' ?>><?= e($v['interest']) ?></textarea>
          <?php if ($bad('interest')): ?><p class="field-err" id="e-int"><?= e($err['interest']) ?></p><?php endif; ?>
        </div>

        <div class="field">
          <label class="check"><input type="checkbox" name="consultation"<?= $v['consultation'] ? ' checked' : '' ?>>
            <span>I would like a short consultation</span></label>
          <label class="check"><input type="checkbox" name="followup"<?= $v['followup'] ? ' checked' : '' ?>>
            <span>CDER may follow up later about how it went</span></label>
        </div>

        <div class="cluster mt-4">
          <button class="btn btn--primary" type="submit">Send</button>
          <span class="tiny faint">Fields marked * are required.</span>
        </div>
      </form>
<?php endif; ?>
    </div>
  </div>
</div>

</main>

<?php include 'partials/footer.php'; ?>
