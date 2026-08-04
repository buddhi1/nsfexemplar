<?php
/* =============================================================================
   Shared footer — the ONLY place the footer is defined.
   Edit this file to change the footer on every page.

   Closes <body>/<html>, so nothing should follow the include on a page.
   ========================================================================== */

$CONTACT = CONTACT_EMAIL;   /* declared in lib/contact.php */
$REPO    = 'https://github.com/CDER-Center/CS1-CS2_Exemplar_Ebook/tree/main';
$DISCORD = 'https://discord.gg/xdh3uvD3b';

/* Link columns: heading => [ [href, label], … ] */
$FOOTER_COLS = [
    'Project' => [
        ['project.php#goals',    'Goals'],
        ['project.php#nsf',      'NSF support'],
        ['project.php#partners', 'Partners'],
        ['project.php#teams',    'Team structure'],
    ],
    'eBook' => [
        ['ebook.php#explorer', 'Search exemplars'],
        ['ebook.php#glance',   'CS1 at a glance'],
        ['ebook.php#chapters', 'Chapter downloads'],
        ['ebook.php#repos',    'Repositories'],
    ],
    'Research &amp; team' => [
        ['research.php#publications',  'Publications'],
        ['research.php#presentations', 'Presentations'],
        ['research.php#methodology',   'Methodology'],
        ['team.php#authors',           'Authors'],
        ['contact.php',                'Contact Us'],
    ],
    'CDER Center' => [
        ['https://cdercenter.org/',                'Center home'],
        ['https://cdercenter.org/pdc-curriculum/', 'PDC curriculum'],
        ['https://cdercenter.org/pdc-center/',     'About CDER'],
        [$REPO,                                    'GitHub repository'],
    ],
];

/* Community links rendered as icons beside the contact address. */
function icon_discord(): string {
    return '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M19.27 5.33A16.6 16.6 0 0 0 15.2 4.1a.06.06 0 0 0-.07.03c-.18.31-.38.72-.51 1.04a15.4 15.4 0 0 0-4.24 0c-.14-.33-.34-.73-.52-1.04a.06.06 0 0 0-.07-.03 16.55 16.55 0 0 0-4.07 1.23.06.06 0 0 0-.03.02C2.98 9.15 2.27 12.85 2.62 16.5a.07.07 0 0 0 .03.05 16.7 16.7 0 0 0 5 2.5.07.07 0 0 0 .07-.02c.39-.52.73-1.07 1.02-1.65a.06.06 0 0 0-.03-.09c-.54-.2-1.05-.45-1.55-.73a.06.06 0 0 1 0-.11l.3-.24a.06.06 0 0 1 .07 0 11.9 11.9 0 0 0 10.02 0 .06.06 0 0 1 .07 0l.31.24a.06.06 0 0 1 0 .11c-.5.29-1.01.53-1.55.73a.06.06 0 0 0-.04.09c.3.58.64 1.13 1.02 1.65a.07.07 0 0 0 .08.02 16.65 16.65 0 0 0 5-2.5.07.07 0 0 0 .03-.05c.42-4.22-.69-7.89-2.93-11.15a.05.05 0 0 0-.03-.02ZM8.85 14.28c-.98 0-1.79-.9-1.79-2.01 0-1.11.79-2.01 1.79-2.01 1.01 0 1.81.91 1.8 2.01 0 1.11-.8 2.01-1.8 2.01Zm6.31 0c-.98 0-1.79-.9-1.79-2.01 0-1.11.79-2.01 1.79-2.01 1.01 0 1.81.91 1.8 2.01 0 1.11-.79 2.01-1.8 2.01Z"/></svg>';
}
function icon_github(): string {
    return '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M12 2a10 10 0 0 0-3.16 19.49c.5.09.68-.22.68-.48v-1.7c-2.78.6-3.37-1.34-3.37-1.34-.45-1.16-1.11-1.47-1.11-1.47-.91-.62.07-.61.07-.61 1 .07 1.53 1.03 1.53 1.03.9 1.53 2.36 1.09 2.94.83.09-.65.35-1.09.63-1.34-2.22-.25-4.56-1.11-4.56-4.94 0-1.09.39-1.98 1.03-2.68-.1-.25-.45-1.27.1-2.64 0 0 .84-.27 2.75 1.02a9.5 9.5 0 0 1 5 0c1.91-1.29 2.75-1.02 2.75-1.02.55 1.37.2 2.39.1 2.64.64.7 1.03 1.59 1.03 2.68 0 3.84-2.34 4.69-4.57 4.94.36.31.68.92.68 1.85v2.74c0 .27.18.58.69.48A10 10 0 0 0 12 2Z"/></svg>';
}
?>

<footer class="site-footer">
  <div class="shell">
    <div class="footer-grid">
      <div>
        <a class="brandmark" href="index.php" style="margin-block-end:var(--sp-3)">
          <span class="glyph" aria-hidden="true"><?= brand_glyph() ?></span>
          <span class="name"><?= e($PROJECT_SHORT) ?></span>
        </a>
        <p class="small muted" style="max-inline-size:34ch"><em><?= e($PROJECT_NAME) ?></em> &mdash;
          a project of the CDER Center, supported by NSF Award <a href="https://www.nsf.gov/awardsearch/showAward?AWD_ID=2321015">#2321015</a>.</p>
        <p class="footer-contact mt-3"><a href="mailto:<?= e($CONTACT) ?>"><?= e($CONTACT) ?></a></p>
        <ul class="social mt-3">
          <li><a href="<?= e($DISCORD) ?>" title="Join the community on Discord">
            <?= icon_discord() ?><span>Discord</span></a></li>
          <li><a href="<?= e($REPO) ?>" title="Instructional material on GitHub">
            <?= icon_github() ?><span>GitHub</span></a></li>
        </ul>
      </div>
<?php foreach ($FOOTER_COLS as $heading => $links): ?>
      <div><h2><?= $heading ?></h2><ul>
<?php foreach ($links as [$href, $label]): ?>
        <li><a href="<?= e($href) ?>"><?= e($label) ?></a></li>
<?php endforeach; ?>
      </ul></div>
<?php endforeach; ?>
    </div>

    <div class="sponsors">
      <span class="sponsors-label">Supported by</span>
      <ul class="sponsor-logos">
        <li>
          <a class="sponsor" href="https://www.nsf.gov" title="U.S. National Science Foundation">
            <span class="plate plate--seal">
              <img src="assets/logos/nsf-seal.png" alt="U.S. National Science Foundation"
                   width="320" height="320" loading="lazy">
            </span>
          </a>
        </li>
        <li>
          <a class="sponsor" href="https://cdercenter.org/" title="CDER Center">
            <span class="plate">
              <img src="assets/logos/cder.png" alt="CDER Center"
                   width="290" height="70" loading="lazy">
            </span>
          </a>
        </li>
        <li>
          <a class="sponsor" href="https://tc.computer.org/tcpp/" title="IEEE TCPP">
            <span class="plate">
              <img src="assets/logos/tcpp.webp" alt="IEEE Technical Committee on Parallel Processing"
                   width="220" height="73" loading="lazy">
            </span>
          </a>
        </li>
      </ul>
    </div>

    <p class="colophon">
      <span>&copy; <?= date('Y') ?> CDER Center</span>
    </p>
  </div>
</footer>

<button class="to-top" type="button" data-to-top hidden aria-label="Back to top">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
       stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
    <path d="M12 19V6M6 12l6-6 6 6"/>
  </svg>
</button>

<script src="assets/data.js"></script>
<script src="assets/site.js"></script>
</body>
</html>
