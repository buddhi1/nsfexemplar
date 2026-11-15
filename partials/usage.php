<?php
/* =============================================================================
   Usage figures. Included by any page that wants to show them; $COUNTS comes
   from partials/header.php.

   Visits, then downloads per version. Everything downloaded before the first
   edition went live (v1_since) was the early release, and is counted there.
   ========================================================================== */
?>
<div class="shell section--tight">
  <div class="usage">
    <div class="usage-head">
      <p class="eyebrow">Usage</p>
    </div>
    <div class="usage-figures">
      <div class="metric">
        <span class="metric-value"><?= counters_fmt((int) ($COUNTS['visits'] ?? 0)) ?></span>
        <span class="metric-label">Site visits</span>
      <p class="tiny faint">Counted since <?= e(date('j F Y', strtotime($COUNTS['started'] ?? 'today'))) ?>.</p>
      </div>
      <div class="metric">
        <span class="metric-value"><?= counters_fmt(counters_downloads($COUNTS, 'v1')) ?></span>
        <span class="metric-label">First edition downloads</span>
        <span class="metric-note">since <?= e(date('j M Y', strtotime($COUNTS['v1_since'] ?? 'today'))) ?></span>
      </div>
      <div class="metric">
        <span class="metric-value"><?= counters_fmt(counters_downloads($COUNTS, 'early')) ?></span>
        <span class="metric-label">Early release downloads</span>
        <span class="metric-note">since <?= e(date('j M Y', strtotime($COUNTS['started'] ?? 'today'))) ?></span>
      </div>
    </div>

  </div>
</div>
