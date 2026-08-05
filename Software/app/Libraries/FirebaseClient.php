<?php

namespace App\Libraries;

/**
 * Thin HTTP client for the Firebase Realtime Database REST API.
 *
 * Firebase exposes every database path as a REST resource: append
 * ".json" to the path and issue a standard HTTP verb. No SDK or
 * Composer package is required, which keeps this PHP-native project
 * dependency-free.
 *
 * Docs: https://firebase.google.com/docs/database/rest/start
 */
class FirebaseClient
{
    private string $databaseUrl;
    private ?string $authToken;

    public function __construct(string $databaseUrl, ?string $authToken = null)
    {
        $this->databaseUrl = rtrim($databaseUrl, '/');
        $this->authToken = $authToken;
    }

    /**
     * Reads data at $path.
     *
     * @param array<string, mixed> $query Extra query params, e.g.
     *                                    ['orderBy' => '"$key"', 'limitToLast' => 10]
     * @return mixed Decoded JSON value, or null if the path is empty.
     */
    public function get(string $path, array $query = []): mixed
    {
        $response = $this->request('GET', $path, null, $query);

        if ($response === '' || $response === 'null') {
            return null;
        }

        return json_decode($response, true);
    }

    /** Overwrites data at $path entirely (HTTP PUT). */
    public function set(string $path, mixed $data): mixed
    {
        return json_decode($this->request('PUT', $path, $data), true);
    }

    /** Appends $data as a new child with a Firebase-generated key (HTTP POST) — same as the JS SDK's push(). */
    public function push(string $path, mixed $data): mixed
    {
        return json_decode($this->request('POST', $path, $data), true);
    }

    /** Merges $data into existing data at $path without touching siblings (HTTP PATCH). */
    public function update(string $path, mixed $data): mixed
    {
        return json_decode($this->request('PATCH', $path, $data), true);
    }

    /** Deletes data at $path (HTTP DELETE). */
    public function delete(string $path): void
    {
        $this->request('DELETE', $path);
    }

    private function request(string $method, string $path, mixed $data, array $query = []): string
    {
        $url = $this->databaseUrl . '/' . ltrim($path, '/') . '.json';

        if ($this->authToken !== null) {
            $query['auth'] = $this->authToken;
        }

        if (!empty($query)) {
            $url .= '?' . http_build_query($query);
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        if ($data !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        }

        $response = curl_exec($ch);

        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new \RuntimeException("Firebase request to [{$method} {$path}] failed: {$error}");
        }

        curl_close($ch);

        return $response;
    }
}
