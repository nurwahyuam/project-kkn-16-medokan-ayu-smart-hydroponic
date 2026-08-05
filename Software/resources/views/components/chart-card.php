<?php

/**
 * Renders a single chart card.
 *
 * @param string $canvasId DOM id for the <canvas>, read by public/assets/js/chart.js
 * @param string $title    Card title shown above the chart
 */
function chartCard(string $canvasId, string $title)
{
?>

<div class="chart-card">

    <div class="chart-header">
        <h6><?= $title ?></h6>
    </div>

    <div class="chart-scroll">

        <div class="chart-wrapper">

            <canvas id="<?= $canvasId ?>"></canvas>

        </div>

    </div>

</div>

<?php
}
