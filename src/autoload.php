<?php
declare(strict_types=1);

/**
 * Simple PSR-4 Autoloader
 * No Composer required - just add namespaced classes to src/ and they'll load automatically
 */
spl_autoload_register(function (string $class): bool {
    $prefix = 'App\\';
    $baseDir = __DIR__;

    if (strpos($class, $prefix) !== 0) {
        return false;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = $baseDir . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
        return true;
    }

    return false;
}, true, true);

