<?php

/**
 * History page view.
 *
 * The Recent Activity feed that used to live on the Home dashboard.
 * Populated live by sensor.js via the same #activity-list container.
 */

$viewsPath = dirname(__DIR__);
$activeNav = 'history';

require_once $viewsPath . '/components/activity.php';

// Fallback shown only before Firebase's first response arrives.
$activityItems = [
    [
        'type'  => 'primary',
        'icon'  => 'bi bi-arrow-repeat',
        'title' => 'Loading recent activity...',
        'time'  => '',
    ],
];
?>

<!-- HEADER -->
<?php require $viewsPath . '/layouts/header.php'; ?>

<!-- WELCOME -->
<div class="welcome">
    <h2 data-i18n="history_title">Riwayat Aktivitas</h2>
    <p data-i18n="history_subtitle">Log pembacaan sensor & status pompa terbaru.</p>
</div>

<!-- RECENT ACTIVITY -->
<?php activityCard($activityItems); ?>

<!-- BOTTOM NAVIGATION -->
<?php require $viewsPath . '/layouts/bottom-nav.php'; ?>
