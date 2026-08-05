<?php

namespace App\Controllers\Api;

use App\Config\FirebaseClientFactory;
use App\Models\SensorModel;

/**
 * Sensor-related REST endpoints.
 */
class SensorApiController
{
    private function model(): SensorModel
    {
        return new SensorModel(FirebaseClientFactory::make());
    }

    /** GET /api/sensor — most recent sensor reading. */
    public function show(): void
    {
        $latest = $this->model()->latest();

        if ($latest === null) {
            jsonResponse(['error' => 'No sensor data available'], 404);
            return;
        }

        jsonResponse($latest);
    }

    /**
     * POST /api/sensor — records a new sensor reading.
     * Body: {"suhu": 27.4, "tds": 850, "status_pompa": "Running"}
     *
     * This lets a device (e.g. the ESP32) push a reading through this
     * backend API instead of writing to Firebase directly. Your ESP32
     * firmware currently writes to Firebase directly, so switching it
     * to call this endpoint instead is optional — both end up in the
     * same "monitoring" node.
     */
    public function store(): void
    {
        $body = requestJson();

        $suhu = $body['suhu'] ?? null;
        $tds = $body['tds'] ?? null;
        $statusPompa = $body['status_pompa'] ?? null;

        if ($suhu === null || $tds === null) {
            jsonResponse(['error' => '"suhu" and "tds" are required'], 422);
            return;
        }

        $firebase = FirebaseClientFactory::make();
        $result = $firebase->push('monitoring', [
            'suhu'         => $suhu,
            'tds'          => $tds,
            'status_pompa' => $statusPompa ?? 'Unknown',
        ]);

        jsonResponse(['id' => $result['name'] ?? null], 201);
    }

    /** GET /api/history?limit=10 — recent readings, oldest first. */
    public function history(): void
    {
        $limit = isset($_GET['limit']) ? max(1, (int) $_GET['limit']) : 10;

        jsonResponse($this->model()->history($limit));
    }

    /** GET /api/statistic?limit=20 — min/max/avg over recent history. */
    public function statistic(): void
    {
        $limit = isset($_GET['limit']) ? max(1, (int) $_GET['limit']) : 20;
        $history = $this->model()->history($limit);

        if (empty($history)) {
            jsonResponse(['error' => 'No sensor data available'], 404);
            return;
        }

        $temps = array_map(fn ($entry) => (float) ($entry['suhu'] ?? 0), $history);
        $tdsValues = array_map(fn ($entry) => (float) ($entry['tds'] ?? 0), $history);

        jsonResponse([
            'count' => count($history),
            'suhu' => [
                'min' => min($temps),
                'max' => max($temps),
                'avg' => round(array_sum($temps) / count($temps), 2),
            ],
            'tds' => [
                'min' => min($tdsValues),
                'max' => max($tdsValues),
                'avg' => round(array_sum($tdsValues) / count($tdsValues), 2),
            ],
        ]);
    }
}
