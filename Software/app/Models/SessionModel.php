<?php

namespace App\Models;

use App\Libraries\FirebaseClient;

/**
 * Manages login session tokens, stored in Firebase under "sessions/{token}".
 *
 * ⚠️ NEW FUNCTIONALITY — see config/auth.php for context. This is a
 * deliberately simple token store (no JWT library, no Composer
 * dependency) suited to a small IoT dashboard, not a full auth system.
 */
class SessionModel extends FirebaseModel
{
    private string $node;

    public function __construct(FirebaseClient $firebase, string $node = 'sessions')
    {
        parent::__construct($firebase);
        $this->node = $node;
    }

    /** Creates a new session token for $username, valid for $ttlSeconds. */
    public function create(string $username, int $ttlSeconds = 3600): string
    {
        $token = bin2hex(random_bytes(32));

        $this->firebase->set($this->node . '/' . $token, [
            'username'   => $username,
            'created_at' => time(),
            'expires_at' => time() + $ttlSeconds,
        ]);

        return $token;
    }

    /** Returns the session data for $token, or null if missing/expired. */
    public function find(string $token): ?array
    {
        $session = $this->firebase->get($this->node . '/' . $token);

        if (!is_array($session)) {
            return null;
        }

        if (($session['expires_at'] ?? 0) < time()) {
            $this->firebase->delete($this->node . '/' . $token);
            return null;
        }

        return $session;
    }
}
