<?php

namespace App\Libraries;

/**
 * Minimal PSR-4-style autoloader for the App\ namespace.
 *
 * Maps App\Sub\Name to {basePath}/app/Sub/Name.php.
 */
class Autoloader
{
    public static function register(string $basePath): void
    {
        spl_autoload_register(function (string $class) use ($basePath): void {
            $prefix = 'App\\';

            if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
                return;
            }

            $relativeClass = substr($class, strlen($prefix));
            $file = $basePath . '/app/' . str_replace('\\', '/', $relativeClass) . '.php';

            if (is_file($file)) {
                require $file;
            }
        });
    }
}
