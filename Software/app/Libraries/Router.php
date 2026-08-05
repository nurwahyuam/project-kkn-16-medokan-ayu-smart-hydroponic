<?php

namespace App\Libraries;

/**
 * Simple method+path Router.
 *
 * Routes are keyed as "METHOD /path" (e.g. "GET /api/sensor") so the
 * same path can support different HTTP methods — needed for the REST
 * API's GET/POST pairs (e.g. GET /api/sensor vs POST /api/sensor).
 */
class Router
{
    /** @var array<string, array{0: class-string, 1: string}> */
    private array $routes;

    private string $basePath;

    public function __construct(array $routes, string $basePath)
    {
        $this->routes = $routes;
        $this->basePath = $basePath;
    }

    public function dispatch(string $method, string $requestUri): void
    {
        $path = parse_url($requestUri, PHP_URL_PATH) ?? '/';
        $path = rtrim($path, '/');

        if ($path === '') {
            $path = '/';
        }

        $key = strtoupper($method) . ' ' . $path;

        if (!isset($this->routes[$key])) {
            $this->renderNotFound($path);
            return;
        }

        [$controllerClass, $action] = $this->routes[$key];
        $controller = new $controllerClass();
        $controller->{$action}();
    }

    private function renderNotFound(string $path): void
    {
        http_response_code(404);

        if (str_starts_with($path, '/api/')) {
            jsonResponse(['error' => 'Not Found'], 404);
            return;
        }

        $viewPath = $this->basePath . '/resources/views/errors/404.php';

        if (is_file($viewPath)) {
            require $viewPath;
            return;
        }

        echo '404 Not Found';
    }
}
