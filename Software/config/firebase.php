<?php

/**
 * Firebase configuration.
 *
 * Same Realtime Database instance already used by the frontend (see
 * public/assets/js/config.js -> FIREBASE_CONFIG.databaseURL).
 * Centralizing it here means both the JS SDK and this PHP backend
 * always point at the same database, with one source of truth.
 */
return [
    'database_url' => 'https://smart-urban-farming-kkn16-default-rtdb.asia-southeast1.firebasedatabase.app',

    /*
     * Firebase Auth ID token or database secret, appended as ?auth=...
     * on every REST request. Leave null while your Realtime Database
     * security rules allow public read/write — which they must
     * currently do, since the frontend's JS SDK connects with only a
     * databaseURL and no credentials at all.
     *
     * If you tighten your security rules later, generate a service
     * account / database secret in the Firebase console and set it
     * here (ideally via an environment variable, not committed to git).
     */
    'auth_token' => null,
];
