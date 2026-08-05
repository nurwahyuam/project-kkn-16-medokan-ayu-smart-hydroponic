<?php

/**
 * Settings page view.
 *
 * Currently just the dark/light theme toggle. The switch itself is a
 * plain checkbox (id="theme-toggle-input") wired up by
 * public/assets/js/theme.js's initThemeToggle().
 */

$viewsPath = dirname(__DIR__);
$activeNav = 'settings';
?>

<!-- HEADER -->
<?php require $viewsPath . '/layouts/header.php'; ?>

<!-- WELCOME -->
<div class="welcome">
    <h2>Pengaturan</h2>
    <p>Sesuaikan tampilan aplikasi.</p>
</div>

<!-- THEME TOGGLE -->
<div class="theme-toggle-row">
    <div>
        <h6>Mode Gelap</h6>
        <span>Default: Terang</span>
    </div>

    <label class="theme-toggle-switch">
        <input type="checkbox" id="theme-toggle-input">
        <span class="theme-toggle-track"></span>
    </label>
</div>

<!-- BOTTOM NAVIGATION -->
<?php require $viewsPath . '/layouts/bottom-nav.php'; ?>
