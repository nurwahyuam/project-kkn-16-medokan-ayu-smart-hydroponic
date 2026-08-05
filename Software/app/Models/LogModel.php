<?php

namespace App\Models;

use App\Libraries\FirebaseClient;

/**
 * Reads/writes general activity log entries (e.g. "relay toggled",
 * "device disconnected").
 *
 * ⚠️ NEW FUNCTIONALITY. The "Recent Activity" card currently shown on
 * the dashboard (resources/views/components/activity.php, populated
 * from a static array in resources/views/dashboard/dashboard.php)
 * still uses hardcoded sample data — it is NOT wired to this Model.
 * Making the activity feed live is a separate, deliberate change we
 * can make in a later step if you'd like it.
 */
class LogModel extends FirebaseModel
{
    private string $node;

    public function __construct(FirebaseClient $firebase, string $node = 'logs')
    {
        parent::__construct($firebase);
        $this->node = $node;
    }

    public function record(string $type, string $title): void
    {
        $this->firebase->push($this->node, [
            'type'  => $type,
            'title' => $title,
            'time'  => date('c'),
        ]);
    }

    /**
     * Returns up to $limit most recent entries, newest first.
     *
     * @return array<int, array{id: string, type: string, title: string, time: string}>
     */
    public function recent(int $limit = 10): array
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

        return array_reverse($entries);
    }
}
