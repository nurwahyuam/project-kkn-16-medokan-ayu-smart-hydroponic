<?php

/**
 * Dashboard page view.
 *
 * Composes all dashboard components using data arrays (instead of
 * copy-pasted markup) so new widgets can be added without duplicating
 * HTML. Rendered by DashboardController, whose output is then wrapped
 * inside layouts/app.php.
 */

$viewsPath = dirname(__DIR__);
$activeNav = 'home';

require_once $viewsPath . '/components/sensor-card.php';
require_once $viewsPath . '/components/quick-actions.php';

// Sensor widgets shown in the sensor grid. Adding a new sensor (e.g. the
// planned Water Level metric) only requires adding one array entry here.
$sensorMetrics = [
    [
        'icon'  => 'bi bi-thermometer-high',
        'title' => 'Nutrient Temp',
        'value' => '27°C',
        'color' => '#FF6B6B',
        'id'    => 'val-suhu',
    ],
    [
        'icon'  => 'bi bi-lightning-charge-fill',
        'title' => 'TDS',
        'value' => '850',
        'color' => '#FFD166',
        'id'    => 'val-tds',
    ],
];

// Quick-action widgets (Pump status, ESP32 connectivity, ...).
$quickActions = [
    [
        'icon'  => 'bi bi-fan',
        'type'  => 'pump',
        'title' => 'Pump',
        'value' => 'Running',
        'id'    => 'val-pump',
    ],
    [
        'icon'  => 'bi bi-wifi',
        'type'  => 'wifi',
        'title' => 'ESP32',
        'value' => 'Connected',
        'id'    => 'val-esp-status',
    ],
];

?>

<!-- HEADER -->
<?php require $viewsPath . '/layouts/header.php'; ?>

<!-- WELCOME -->
<div class="welcome">
    <h2>Hydroponic Dashboard</h2>
    <p>Monitor your hydroponic farm in real time.</p>
</div>

<!-- LAST UPDATE -->
<div class="last-update">
    <i class="bi bi-clock-history"></i>
    Last update: <span id="val-last-update">--</span>
</div>

<!-- WEATHER -->
<?php require $viewsPath . '/components/weather-card.php'; ?>

<!-- QUICK ACTION -->
<?php quickActionsGrid($quickActions); ?>

<!-- SENSOR GRID -->
<div class="sensor-grid">
    <?php foreach ($sensorMetrics as $metric): ?>
        <?php sensorCard($metric['icon'], $metric['title'], $metric['value'], $metric['color'], $metric['id']); ?>
    <?php endforeach; ?>
</div>

<!-- NUTRIENT (TDS) STATUS INDICATOR -->
<?php require $viewsPath . '/components/nutrient-status.php'; ?>

<!-- BOTTOM NAVIGATION -->
<?php require $viewsPath . '/layouts/bottom-nav.php'; ?>
