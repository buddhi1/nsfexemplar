<?php
/* =============================================================================
   Usage figures. Included by any page that wants to show them; $COUNTS comes
   from partials/header.php.

   Two numbers now, not four. When chapters were published as separate files
   there was a point in splitting downloads into volume / chapters / total —
   there is one file now, so those three collapse into one honest figure.
   ========================================================================== */

/* Every key counts towards this: the old per-chapter links still resolve, to
   the volume, so those downloads are real — they just predate the move to a
   single file. counters_chapter_total() can still separate them if the split
   is ever wanted again. */
$USAGE_TOTAL = counters_downloads_total($COUNTS);
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

  </div>
</div>
