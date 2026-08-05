<?php

namespace App\Controllers;

/**
 * Handles the Settings page — currently just the dark/light theme toggle.
 */
class SettingsController extends Controller
{
    public function index(): void
    {
        $this->render('settings/settings', [], 'Pengaturan');
    }
}
