<?php

declare(strict_types=1);

namespace App\Core;

use App\Infrastructure\Security\Auth;
use RuntimeException;

final class Router
{
    private array $routes = [];

    public function __construct(private Container $container)
    {
    }

    public function get(string $path, array $handler, array $middleware = []): void
    {
        $this->register('GET', $path, $handler, $middleware);
    }

    public function post(string $path, array $handler, array $middleware = []): void
    {
        $this->register('POST', $path, $handler, $middleware);
    }

    public function dispatch(Request $request): Response
    {
        foreach ($this->routes[$request->method] ?? [] as $route) {
            $params = $this->match($route['path'], $request->path);
            if ($params === null) {
                continue;
            }

            if (!$this->passesMiddleware($route['middleware'])) {
                return Response::redirect('/login');
            }

            [$class, $method] = $route['handler'];
            $controller = new $class($this->container);

            return $controller->{$method}($request, $params);
        }

        return new Response('Not Found', 404);
    }

    private function register(string $method, string $path, array $handler, array $middleware): void
    {
        $this->routes[$method][] = ['path' => rtrim($path, '/') ?: '/', 'handler' => $handler, 'middleware' => $middleware];
    }

    private function match(string $routePath, string $requestPath): ?array
    {
        $pattern = $this->buildRegexPattern($routePath);

        if (!preg_match($pattern, $requestPath, $matches)) {
            return null;
        }

        return array_filter($matches, static fn ($key): bool => !is_int($key), ARRAY_FILTER_USE_KEY);
    }

    private function buildRegexPattern(string $routePath): string
    {
        $parts = [];
        $lastPos = 0;

        // Find all {param} placeholders
        while (preg_match('/\{([^}]+)\}/', $routePath, $matches, PREG_OFFSET_CAPTURE, $lastPos)) {
            // Add the literal part before the parameter
            if ($matches[0][1] > $lastPos) {
                $literal = substr($routePath, $lastPos, $matches[0][1] - $lastPos);
                $parts[] = preg_quote($literal, '#');
            }

            // Add the parameter as a named capture group
            $paramName = $matches[1][0];
            $parts[] = '(?P<' . $paramName . '>[^/]+)';

            $lastPos = $matches[0][1] + strlen($matches[0][0]);
        }

        // Add any remaining literal part
        if ($lastPos < strlen($routePath)) {
            $literal = substr($routePath, $lastPos);
            $parts[] = preg_quote($literal, '#');
        }

        return '#^' . implode('', $parts) . '$#';
    }

    private function passesMiddleware(array $middleware): bool
    {
        if ($middleware === []) {
            return true;
        }

        /** @var Auth $auth */
        $auth = $this->container->get(Auth::class);

        foreach ($middleware as $item) {
            if ($item === 'auth' && !$auth->check()) {
                return false;
            }
            if ($item === 'admin' && !$auth->can('admin.dashboard')) {
                return false;
            }
        }

        return true;
    }
}
