<?php
/**
 * Nutrient (TDS) status indicator.
 *
 * Status text/color is computed client-side in sensor.js based on the
 * live TDS reading, per this table:
 *
 *   TDS (ppm)   Status
 *   < 400       Baik      (blue)
 *   400–800     Bagus     (green)
 *   801–1200    Cukup     (yellow)
 *   1201–1600   Berlebih  (orange)
 *   > 1600      Buruk     (red)
 */
?>
<div class="nutrient-status">
    <span class="nutrient-status-dot" id="nutrient-status-dot"></span>
    <span class="nutrient-status-label" id="nutrient-status-label">Menunggu data...</span>
</div>
