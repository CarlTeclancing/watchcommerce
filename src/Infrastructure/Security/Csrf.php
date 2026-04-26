<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

final class Csrf
{
    private const SESSION_KEY = '_csrf';

    public function token(): string
    {
        if (!isset($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::SESSION_KEY];
    }

    public function verify(string $providedToken): bool
    {
        $sessionToken = $_SESSION[self::SESSION_KEY] ?? '';
        return $sessionToken !== '' && hash_equals($sessionToken, $providedToken);
    }
}
