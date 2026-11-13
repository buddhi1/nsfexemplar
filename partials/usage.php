<?php
/* =============================================================================
   Usage figures. Included by any page that wants to show them; $COUNTS comes
   from partials/header.php.

   Visits, then downloads per version. Downloads from before the two versions
   were split were all the early release, and are counted there.
   ========================================================================== */
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
        <span class="metric-value"><?= counters_fmt(counters_downloads($COUNTS, 'v1')) ?></span>
        <span class="metric-label">Version 1 downloads</span>
      </div>
      <div class="metric">
        <span class="metric-value"><?= counters_fmt(counters_downloads($COUNTS, 'early')) ?></span>
        <span class="metric-label">Early release downloads</span>
      </div>
    </div>

  </div>
</div>
