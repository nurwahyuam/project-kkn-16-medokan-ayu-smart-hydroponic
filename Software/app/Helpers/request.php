<?php

/**
 * Request helpers.
 */

if (!function_exists('requestJson')) {
    /**
     * Decodes the raw JSON request body into an associative array.
     * Returns [] if the body is empty or not valid JSON.
     */
    function requestJson(): array
    {
        $raw = file_get_contents('php://input');
        $decoded = json_decode($raw, true);

        return is_array($decoded) ? $decoded : [];
    }
}
