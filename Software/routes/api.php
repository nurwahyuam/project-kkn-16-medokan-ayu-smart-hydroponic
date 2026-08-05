<?php

use App\Controllers\Api\SensorApiController;
use App\Controllers\Api\DashboardApiController;
use App\Controllers\Api\RelayApiController;
use App\Controllers\Api\AuthApiController;

/**
 * REST API routes. Maps "METHOD /path" to [ControllerClass, methodName].
 * All responses are JSON (see app/Helpers/response.php's jsonResponse()).
 */
return [
    'GET /api/sensor'    => [SensorApiController::class, 'show'],
    'POST /api/sensor'   => [SensorApiController::class, 'store'],
    'GET /api/history'   => [SensorApiController::class, 'history'],
    'GET /api/dashboard' => [DashboardApiController::class, 'index'],
    'POST /api/relay'    => [RelayApiController::class, 'update'],
    'POST /api/login'    => [AuthApiController::class, 'login'],
    'GET /api/statistic' => [SensorApiController::class, 'statistic'],
];
