<?php

namespace App\Controllers;

/**
 * Handles the History page — the Recent Activity feed that used to
 * live on the Home dashboard now has its own page here.
 */
class HistoryController extends Controller
{
    public function index(): void
    {
        $this->render('monitoring/history', [], 'Riwayat Aktivitas');
    }
}
