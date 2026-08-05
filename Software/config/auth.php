<?php

/**
 * ⚠️ NEW FUNCTIONALITY: no login/authentication existed anywhere in
 * the original project. This is the minimal credential store backing
 * POST /api/login, added only because Step 8's endpoint list requires
 * it and something has to gate the write-capable endpoint
 * (POST /api/relay).
 *
 * CHANGE THE DEFAULT PASSWORD BEFORE DEPLOYING. Ideally, generate the
 * hash once via CLI:
 *   php -r "echo password_hash('your-new-password', PASSWORD_DEFAULT);"
 * and paste the resulting string as a literal below, instead of
 * calling password_hash() on every request.
 */
return [
    'username' => 'admin',
    'password_hash' => password_hash('changeme123', PASSWORD_DEFAULT),
];
