<?php

namespace App\Controllers\Api;

use App\Config\FirebaseClientFactory;
use App\Models\SensorModel;
use App\Models\RelayModel;

/**
 * Combined dashboard payload — useful for a future mobile app or SPA
 * that wants sensor + relay state in a single request instead of two.
 */
class DashboardApiController
{
    /** GET /api/dashboard */
    public function index(): void
    {
        $firebase = FirebaseClientFactory::make();
        $sensor = new SensorModel($firebase);
        $relay = new RelayModel($firebase);

        jsonResponse([
            'sensor' => $sensor->latest(),
            'relay'  => $relay->all(),
        ]);
    }
}
