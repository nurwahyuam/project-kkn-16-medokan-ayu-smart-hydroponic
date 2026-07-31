<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Smart Hydroponic Dashboard</title>

    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendor/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<div class="mobile-app">
    

    <!-- HEADER -->
    <?php include __DIR__ . "/../components/header.php"; ?>

    <!-- WELCOME -->
    <div class="welcome">
        <h2>Hydroponic Dashboard</h2>
        <p>Monitor your hydroponic farm in real time.</p>
    </div>

    <!-- WEATHER -->
    <?php include __DIR__ . "/../components/weather-card.php"; ?>

    <!-- QUICK ACTION -->
    <?php include __DIR__ . "/../components/quick-actions.php"; ?>

    <!-- CHART -->
    <?php include __DIR__ . "/../components/chart-card.php"; ?>

    <!-- SENSOR CARD FUNCTION -->
    <?php include __DIR__ . "/../components/sensor-card.php"; ?>

    <!-- SENSOR GRID -->
    <div class="sensor-grid">

        <?php

        sensorCard(
            "bi bi-thermometer-half",
            "Temperature",
            "27°C",
            "#FF6B6B"
        );

        sensorCard(
            "bi bi-droplet-fill",
            "Humidity",
            "80%",
            "#4D96FF"
        );

        sensorCard(
            "bi bi-beaker-fill",
            "pH",
            "6.5",
            "#9B59B6"
        );

        sensorCard(
            "bi bi-lightning-charge-fill",
            "TDS",
            "850",
            "#FFD166"
        );

        ?>

    </div>

    <!-- RECENT ACTIVITY -->
    <?php include __DIR__ . "/../components/activity.php"; ?>

</div>

<!-- BOTTOM NAVIGATION -->
<?php include __DIR__ . "/../components/bottom-nav.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/app.js"></script>
