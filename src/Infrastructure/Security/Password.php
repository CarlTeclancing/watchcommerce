<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

final class Password
{
    public static function hash(string $plainText): string
    {
        return password_hash($plainText, PASSWORD_ARGON2ID);
    }

    public static function verify(string $plainText, string $hash): bool
    {
        return password_verify($plainText, $hash);
    }
}
