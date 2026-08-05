<?php

/**
 * Device page view.
 *
 * The Temperature and Nutrient (TDS) charts that used to live on the
 * Home dashboard. Populated live by sensor.js via chart.js.
 */

$viewsPath = dirname(__DIR__);
$activeNav = 'device';

require_once $viewsPath . '/components/chart-card.php';
?>

<!-- HEADER -->
<?php require $viewsPath . '/layouts/header.php'; ?>

<!-- WELCOME -->
<div class="welcome">
    <h2>Grafik Perangkat</h2>
    <p>Tren suhu & nutrisi (TDS) dari sensor secara real-time.</p>
</div>

<!-- CHART: TEMPERATURE -->
<?php chartCard('tempChart', 'Grafik Suhu'); ?>

<!-- CHART: NUTRIENT (TDS) -->
<?php chartCard('nutrientChart', 'Grafik Nutrisi (TDS)'); ?>

<!-- BOTTOM NAVIGATION -->
<?php require $viewsPath . '/layouts/bottom-nav.php'; ?>
