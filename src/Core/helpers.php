<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Global helpers for views
 */

/**
 * Get the application base path (for use in URLs)
 */
function basePath(string $path = ''): string
{
    $basePath = Response::getBasePath();
    if ($path === '') {
        return $basePath;
    }
    // Ensure path starts with /
    if ($path[0] !== '/') {
        $path = '/' . $path;
    }
    return $basePath . $path;
}

/**
 * Generate a URL with the base path
 */
function url(string $path): string
{
    return basePath($path);
}
