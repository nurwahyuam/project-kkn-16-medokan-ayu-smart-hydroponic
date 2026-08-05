<?php

/**
 * Response helpers.
 *
 * Plain functions (not a class) since these are simple, stateless
 * utilities used across many Controllers — a common PHP-native
 * "Helper" pattern. Loaded once via public/index.php.
 */

if (!function_exists('jsonResponse')) {
    /**
     * Sends a JSON response and terminates the script.
     * Reserved for use by the REST API Controllers introduced in Step 8
     * (GET /api/sensor, POST /api/relay, etc.) — no API route uses it yet.
     *
     * @param mixed $data       Any JSON-serializable value.
     * @param int   $statusCode HTTP status code (default 200).
     */
    function jsonResponse($data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        exit;
    }
}
