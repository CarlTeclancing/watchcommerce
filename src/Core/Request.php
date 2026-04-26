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

        // Strip base path if application is in a subdirectory
        $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/');
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
