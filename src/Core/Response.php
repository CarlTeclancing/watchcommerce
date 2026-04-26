<?php

declare(strict_types=1);

namespace App\Core;

final class Response
{
    private static ?string $basePath = null;

    public function __construct(private string $body = '', private int $status = 200, private array $headers = [])
    {
    }

    public static function setBasePath(string $path): void
    {
        self::$basePath = rtrim($path, '/');
    }

    public static function getBasePath(): string
    {
        if (self::$basePath === null) {
            $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
            self::$basePath = rtrim(dirname($scriptName), '/');
        }
        return self::$basePath;
    }

    public static function json(array $data, int $status = 200): self
    {
        return new self(json_encode($data, JSON_THROW_ON_ERROR), $status, ['Content-Type' => 'application/json']);
    }

    public static function redirect(string $url, int $status = 302): self
    {
        // If URL is relative, prepend base path
        if ($url[0] === '/' && !str_starts_with($url, self::getBasePath())) {
            $url = self::getBasePath() . $url;
        }
        return new self('', $status, ['Location' => $url]);
    }

    public function send(): void
    {
        http_response_code($this->status);
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }
        echo $this->body;
    }
}
