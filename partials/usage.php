<?php
/* =============================================================================
   Usage figures. Included by any page that wants to show them; $COUNTS comes
   from partials/header.php.

   Two numbers now, not four. When chapters were published as separate files
   there was a point in splitting downloads into volume / chapters / total —
   there is one file now, so those three collapse into one honest figure.
   ========================================================================== */

$USAGE_TOTAL  = counters_downloads_total($COUNTS);
/* Downloads counted under the old chapter keys. Those links still resolve, to
   the volume, so the figure is real — it just predates the single-file move. */
$USAGE_LEGACY = counters_chapter_total($COUNTS);
?>
<div class="shell section--tight">
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
        <span class="metric-value"><?= counters_fmt($USAGE_TOTAL) ?></span>
        <span class="metric-label">E-book downloads</span>
      </div>
    </div>
<?php if ($USAGE_LEGACY > 0): ?>
    <p class="usage-foot tiny faint">Includes <?= counters_fmt($USAGE_LEGACY) ?> counted while chapters
      were published as separate files. Those links now open the full volume.</p>
<?php endif; ?>
  </div>
</div>
