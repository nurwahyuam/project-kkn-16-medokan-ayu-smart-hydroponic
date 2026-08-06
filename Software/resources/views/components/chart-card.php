<?php

/**
 * Renders a single chart card.
 *
 * @param string $canvasId DOM id for the <canvas>, read by public/assets/js/chart.js
 * @param string $title    Card title shown above the chart
 * @param string $i18nKey  Optional i18n.js dictionary key for the title
 */
function chartCard(string $canvasId, string $title, string $i18nKey = "")
{
?>

<div class="chart-card">

    <div class="chart-header">
        <h6 <?= $i18nKey ? 'data-i18n="' . $i18nKey . '"' : '' ?>><?= $title ?></h6>
    </div>

    <div class="chart-scroll">

        <div class="chart-wrapper">

            <canvas id="<?= $canvasId ?>"></canvas>

        </div>

    </div>

</div>

<?php
}
