<?php

namespace App\Config;

use App\Libraries\FirebaseClient;

/**
 * Builds a ready-to-use FirebaseClient from config/firebase.php, so
 * Models/Controllers never construct one by hand or hardcode the URL.
 */
class FirebaseClientFactory
{
    public static function make(): FirebaseClient
    {
        $config = require dirname(__DIR__, 2) . '/config/firebase.php';

        return new FirebaseClient($config['database_url'], $config['auth_token'] ?? null);
    }
}
