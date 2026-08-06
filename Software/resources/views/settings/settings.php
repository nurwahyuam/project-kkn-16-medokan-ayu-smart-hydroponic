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
    <h2 data-i18n="settings_title">Pengaturan</h2>
    <p data-i18n="settings_subtitle">Sesuaikan tampilan aplikasi.</p>
</div>

<!-- THEME TOGGLE -->
<div class="theme-toggle-row">
    <div>
        <h6 data-i18n="settings_dark_mode">Mode Gelap</h6>
        <span data-i18n="settings_dark_mode_default">Default: Terang</span>
    </div>

    <label class="theme-toggle-switch">
        <input type="checkbox" id="theme-toggle-input">
        <span class="theme-toggle-track"></span>
    </label>
</div>

<!-- LANGUAGE SELECT -->
<div class="theme-toggle-row">
    <div>
        <h6 data-i18n="settings_language">Bahasa</h6>
        <span data-i18n="settings_language_default">Default: Indonesia</span>
    </div>

    <select id="language-select" class="settings-select">
        <option value="id">Indonesia</option>
        <option value="en">English</option>
    </select>
</div>

<!-- BOTTOM NAVIGATION -->
<?php require $viewsPath . '/layouts/bottom-nav.php'; ?>
