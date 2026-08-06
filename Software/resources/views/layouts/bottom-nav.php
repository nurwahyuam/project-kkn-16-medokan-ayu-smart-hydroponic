<?php
/**
 * Bottom navigation.
 *
 * Each page sets $activeNav ('home'|'history'|'device'|'settings')
 * before requiring this file, so the correct tab gets the "active"
 * class. Defaults to 'home' if not set.
 */
$activeNav = $activeNav ?? 'home';
?>
<nav class="bottom-nav">

    <a href="/" class="bottom-item <?= $activeNav === 'home' ? 'active' : '' ?>">

        <i class="bi bi-house-fill"></i>

        <span data-i18n="nav_home">Beranda</span>

    </a>

    <a href="/history" class="bottom-item <?= $activeNav === 'history' ? 'active' : '' ?>">

        <i class="bi bi-clock-history"></i>

        <span data-i18n="nav_history">Aktivitas</span>

    </a>

    <a href="/device" class="bottom-item <?= $activeNav === 'device' ? 'active' : '' ?>">

        <i class="bi bi-graph-up"></i>

        <span data-i18n="nav_device">Grafik</span>

    </a>

    <a href="/settings" class="bottom-item <?= $activeNav === 'settings' ? 'active' : '' ?>">

        <i class="bi bi-gear-fill"></i>

        <span data-i18n="nav_settings">Pengaturan</span>

    </a>

</nav>
