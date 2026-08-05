<?php

namespace App\Models;

use App\Libraries\FirebaseClient;

/**
 * Reads/writes relay (pump, lamp, ...) control state.
 *
 * ⚠️ NEW FUNCTIONALITY: the original project only ever DISPLAYED
 * `status_pompa` (read-only, from the "monitoring" node) — there was
 * no control/actuation capability anywhere in the codebase. This Model
 * is added now to prepare for the `POST /api/relay` endpoint planned
 * in Step 8.
 *
 * The Firebase path below ("control/relay") does not exist yet in your
 * database and currently has NOTHING listening to it — your ESP32
 * firmware would need matching code to read this node and actually
 * switch a relay. That firmware work is outside this project's scope;
 * this Model only prepares the PHP/Firebase side of the pipeline.
 */
class RelayModel extends FirebaseModel
{
    private string $node;

    public function __construct(FirebaseClient $firebase, string $node = 'control/relay')
    {
        parent::__construct($firebase);
        $this->node = $node;
    }

    /** @return array<string, bool> Current relay states keyed by relay name (e.g. ['pump' => true]). */
    public function all(): array
    {
        $data = $this->firebase->get($this->node);
        return is_array($data) ? $data : [];
    }

    /** Sets a single relay's state, e.g. setState('pump', true). */
    public function setState(string $relay, bool $state): void
    {
        $this->firebase->update($this->node, [$relay => $state]);
    }
}
