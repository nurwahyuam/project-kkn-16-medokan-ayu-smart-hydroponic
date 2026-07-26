<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

Smart Hydroponic Dashboard

</title>

<link
rel="stylesheet"
href="assets/vendor/bootstrap/css/bootstrap.min.css">

<link
rel="stylesheet"
href="assets/vendor/bootstrap-icons/bootstrap-icons.css">

<link
rel="stylesheet"
href="assets/css/style.css">

</head>

<body>

<div class="mobile-app">

<?php

include "components/header.php";

?>

<div class="welcome">

<h2>

Good Morning 👋

</h2>

<p>

Let's monitor today's hydroponic condition.

</p>

</div>

<?php

include "components/weather-card.php";

?>

<?php

include "components/sensor-card.php";

?>

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

</div>

<?php

include "components/bottom-nav.php";

?>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="assets/js/app.js"></script>

</body>

</html>