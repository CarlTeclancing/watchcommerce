<?php

declare(strict_types=1);

namespace App\Core;

final class Request
{
    public function __construct(
        public readonly string $method,
        public readonly string $path,
        public readonly array $query,
        public readonly array $post,
        public readonly array $server,
        public readonly array $session
    ) {
    }

    public static function capture(): self
    {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

        // Strip base path if application is in a subdirectory.
        // Use APP_URL when available so /public never leaks into the path.
        $appUrl = $_ENV['APP_URL'] ?? '';
        if ($appUrl) {
            $basePath = rtrim(parse_url($appUrl, PHP_URL_PATH) ?? '', '/');
        } else {
            $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/');
        }
        if ($basePath && strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath));
        }

        return new self(
            strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET'),
            rtrim($path, '/') ?: '/',
            $_GET,
            $_POST,
            $_SERVER,
            $_SESSION ?? []
        );
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->post[$key] ?? $this->query[$key] ?? $default;
    }
}
