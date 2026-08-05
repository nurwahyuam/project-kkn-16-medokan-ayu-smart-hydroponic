<?php

namespace App\Controllers;

/**
 * Handles the Device page — the Temperature and Nutrient (TDS) charts
 * that used to live on the Home dashboard now have their own page here.
 */
class DeviceController extends Controller
{
    public function index(): void
    {
        $this->render('device/device', [], 'Grafik Perangkat');
    }
}
