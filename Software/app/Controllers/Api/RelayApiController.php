<?php

namespace App\Controllers\Api;

use App\Config\FirebaseClientFactory;
use App\Models\RelayModel;

/**
 * ⚠️ NEW FUNCTIONALITY (see app/Models/RelayModel.php) — writes to
 * Firebase's "control/relay" node. Requires matching ESP32 firmware to
 * actually switch a physical relay; that firmware work is outside
 * this project's scope.
 */
class RelayApiController
{
    /** POST /api/relay — body: {"relay": "pump", "state": true} */
    public function update(): void
    {
        $body = requestJson();
        $relay = $body['relay'] ?? null;
        $state = $body['state'] ?? null;

        if (!is_string($relay) || $relay === '' || !is_bool($state)) {
            jsonResponse(['error' => '"relay" (string) and "state" (boolean) are required'], 422);
            return;
        }

        $model = new RelayModel(FirebaseClientFactory::make());
        $model->setState($relay, $state);

        jsonResponse(['relay' => $relay, 'state' => $state]);
    }
}
