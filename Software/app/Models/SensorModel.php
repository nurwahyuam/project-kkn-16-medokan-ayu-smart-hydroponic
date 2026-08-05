<?php

namespace App\Models;

use App\Libraries\FirebaseClient;

/**
 * Reads sensor readings from Firebase's "monitoring" node — the exact
 * same node public/assets/js/api.js subscribes to in real time on the
 * client side. This Model gives server-side PHP code (e.g. a future
 * REST API, or a Telegram alert job) access to the same data.
 */
class SensorModel extends FirebaseModel
{
    private string $node;

    public function __construct(FirebaseClient $firebase, string $node = 'monitoring')
    {
        parent::__construct($firebase);
        $this->node = $node;
    }

    /**
     * Returns the most recent sensor reading, or null if none exist.
     *
     * @return array{id: string, suhu: float, tds: float, status_pompa: string}|null
     */
    public function latest(): ?array
    {
        $entries = $this->history(1);
        return $entries[0] ?? null;
    }

    /**
     * Returns up to $limit most recent readings, oldest first — the
     * same ordering the frontend's temperature chart expects.
     *
     * @return array<int, array{id: string, suhu: float, tds: float, status_pompa: string}>
     */
    public function history(int $limit = 10): array
    {
        $raw = $this->firebase->get($this->node, [
            'orderBy' => '"$key"',
            'limitToLast' => $limit,
        ]);

        if (!is_array($raw)) {
            return [];
        }

        $entries = [];
        foreach ($raw as $id => $entry) {
            $entries[] = array_merge(['id' => $id], (array) $entry);
        }

        return $entries;
    }
}
