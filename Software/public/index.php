<?php

/**
 * Front controller.
 *
 * Every web request is routed through this single file. Bootstrapping
 * (autoload + helpers) and dispatching (routing) are delegated to
 * dedicated classes/files so this file stays a thin entry point.
 */

define('BASE_PATH', dirname(__DIR__));

// The Autoloader class itself must be required manually once — it's
// what makes every *other* App\ class loadable automatically.
require BASE_PATH . '/app/Libraries/Autoloader.php';

App\Libraries\Autoloader::register(BASE_PATH);

require BASE_PATH . '/app/Helpers/response.php';
require BASE_PATH . '/app/Helpers/request.php';

$webRoutes = require BASE_PATH . '/routes/web.php';
$apiRoutes = require BASE_PATH . '/routes/api.php';
$routes = array_merge($webRoutes, $apiRoutes);

$router = new App\Libraries\Router($routes, BASE_PATH);
$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
