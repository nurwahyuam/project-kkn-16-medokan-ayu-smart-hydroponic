<?php
/**
 * Master HTML layout.
 *
 * Expects the following variables to be set by the caller (a Controller):
 *   string $pageTitle    Page <title>
 *   string $viewContent  Pre-rendered HTML for the page body
 */
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($pageTitle ?? 'Smart Hydroponic Dashboard') ?></title>

    <!--
        Applies the saved theme (light/dark) BEFORE any CSS loads, so
        dark-mode users never see a flash of the light theme. Kept as a
        tiny inline classic script (not type="module") so it runs
        synchronously, unlike dashboard.js which is deferred by the
        module system.
    -->
    <script>
        (function () {
            if (localStorage.getItem('hydroponic-theme') === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
        })();
    </script>

    <!--
        Bootstrap + Bootstrap Icons are now loaded ONLY from local vendor files.
        Original project loaded Bootstrap Icons twice (CDN + local) and pointed
        Bootstrap's local CSS/JS at a path that did not exist
        (assets/vendor/bootstrap/css/... instead of .../dist/css/...), so the
        local Bootstrap bundle silently 404'd. Both issues are fixed here.
    -->
    <link rel="stylesheet" href="/assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/vendor/bootstrap-icons/font/bootstrap-icons.min.css">

    <!--
        Modular CSS (Step 4). Order matters: tokens/reset/layout first,
        then components, then utilities/responsive last so they can
        override component defaults when needed.
    -->
    <link rel="stylesheet" href="/assets/css/variables.css">
    <link rel="stylesheet" href="/assets/css/reset.css">
    <link rel="stylesheet" href="/assets/css/layout.css">
    <link rel="stylesheet" href="/assets/css/navbar.css">
    <link rel="stylesheet" href="/assets/css/sidebar.css">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <link rel="stylesheet" href="/assets/css/weather.css">
    <link rel="stylesheet" href="/assets/css/card.css">
    <link rel="stylesheet" href="/assets/css/table.css">
    <link rel="stylesheet" href="/assets/css/button.css">
    <link rel="stylesheet" href="/assets/css/utilities.css">
    <link rel="stylesheet" href="/assets/css/responsive.css">
</head>

<body>

    <div class="mobile-app">
        <?= $viewContent ?>
    </div>

    <!-- Chart.js now served from local vendor copy instead of external CDN -->
    <script src="/assets/vendor/chart.js/dist/chart.umd.min.js"></script>
    <script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="/assets/js/dashboard.js"></script>
</body>

</html>
