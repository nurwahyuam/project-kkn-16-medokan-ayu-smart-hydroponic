<?php

namespace App\Controllers;

/**
 * Handles requests for the main dashboard page.
 */
class DashboardController extends Controller
{
    /**
     * Render the dashboard page.
     */
    public function index(): void
    {
        $this->render('dashboard/dashboard', [], 'Smart Hydroponic Dashboard');
    }
}
