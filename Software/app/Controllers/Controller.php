<?php

namespace App\Controllers;

/**
 * Base Controller.
 *
 * Provides a single, reusable way to render a view inside the master
 * layout. Concrete Controllers should extend this instead of manually
 * repeating the ob_start()/require dance.
 */
abstract class Controller
{
    /**
     * Renders a view file and wraps its output in resources/views/layouts/app.php.
     *
     * @param string $view      View path relative to resources/views, without ".php"
     *                          (e.g. "dashboard/dashboard").
     * @param array  $data      Associative array made available to the view as local variables.
     * @param string $pageTitle Value used for the <title> tag.
     */
    protected function render(string $view, array $data = [], string $pageTitle = 'Smart Hydroponic Dashboard'): void
    {
        $viewsPath = dirname(__DIR__, 2) . '/resources/views';
        $viewFile = $viewsPath . '/' . $view . '.php';

        if (!is_file($viewFile)) {
            throw new \RuntimeException("View not found: {$view}");
        }

        // Make $data keys available as variables inside the view.
        extract($data);

        ob_start();
        require $viewFile;
        $viewContent = ob_get_clean();

        require $viewsPath . '/layouts/app.php';
    }
}
