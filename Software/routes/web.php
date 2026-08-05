<?php

use App\Controllers\DashboardController;
use App\Controllers\HistoryController;
use App\Controllers\DeviceController;
use App\Controllers\SettingsController;

/**
 * Simple method+path web router.
 *
 * Maps "METHOD /path" to [ControllerClass, methodName]. Add a new
 * entry here whenever a new page is introduced.
 */
return [
    'GET /'          => [DashboardController::class, 'index'],
    'GET /dashboard' => [DashboardController::class, 'index'],
    'GET /history'   => [HistoryController::class, 'index'],
    'GET /device'    => [DeviceController::class, 'index'],
    'GET /settings'  => [SettingsController::class, 'index'],
];
