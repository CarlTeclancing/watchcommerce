<?php

declare(strict_types=1);

namespace App\Core;

final class View
{
    public static function render(string $template, array $data = []): string
    {
        $viewPath = __DIR__ . '/../../views/' . $template . '.php';
        if (!file_exists($viewPath)) {
            return 'View not found: ' . htmlspecialchars($template, ENT_QUOTES, 'UTF-8');
        }

        if (!isset($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }
        $data['csrf_token'] = $_SESSION['_csrf'];

        // Add base path to data for use in views
        if (!isset($data['basePath'])) {
            $data['basePath'] = Response::getBasePath();
        }

        extract($data, EXTR_SKIP);
        ob_start();
        include $viewPath;
        return (string)ob_get_clean();
    }
}
