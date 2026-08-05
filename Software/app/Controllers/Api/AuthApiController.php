<?php

namespace App\Controllers\Api;

use App\Config\FirebaseClientFactory;
use App\Models\SessionModel;

/**
 * ⚠️ NEW FUNCTIONALITY: no login/authentication existed anywhere in
 * the original project. See config/auth.php for the credential store
 * and important production notes.
 */
class AuthApiController
{
    /** POST /api/login — body: {"username": "admin", "password": "..."} */
    public function login(): void
    {
        $body = requestJson();
        $username = $body['username'] ?? '';
        $password = $body['password'] ?? '';

        $auth = require dirname(__DIR__, 3) . '/config/auth.php';

        if ($username !== $auth['username'] || !password_verify($password, $auth['password_hash'])) {
            jsonResponse(['error' => 'Invalid username or password'], 401);
            return;
        }

        $sessions = new SessionModel(FirebaseClientFactory::make());
        $token = $sessions->create($username);

        jsonResponse([
            'token'      => $token,
            'expires_in' => 3600,
        ]);
    }
}
