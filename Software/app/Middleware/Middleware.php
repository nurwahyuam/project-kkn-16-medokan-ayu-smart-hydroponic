<?php

namespace App\Middleware;

/**
 * Contract for request middleware (e.g. authentication, CORS).
 *
 * No concrete middleware is registered yet — the current site has no
 * login/authentication anywhere in the original project. This
 * interface documents the pattern so an AuthMiddleware, CorsMiddleware,
 * etc. can be added in Step 8 (REST API) without changing how the
 * Router calls them.
 */
interface Middleware
{
    /**
     * @return bool true to let the request continue, false to halt it.
     *              A middleware that returns false is responsible for
     *              sending its own response (e.g. a 401) before doing so.
     */
    public function handle(): bool;
}
