<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use App\Infrastructure\Database\Connection;
use App\Shared\Logger;
use PDO;

final class Auth
{
    private const SESSION_USER_ID = 'auth_user_id';
    private const SESSION_2FA_PASS = 'auth_2fa_passed';

    public function __construct(private Connection $connection, private Logger $logger)
    {
    }

    public function attempt(string $email, string $password): bool
    {
        $statement = $this->connection->pdo()->prepare('SELECT id, password_hash, two_factor_enabled FROM users WHERE email = :email LIMIT 1');
        $statement->execute(['email' => $email]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$user || !Password::verify($password, $user['password_hash'])) {
            return false;
        }

        $_SESSION[self::SESSION_USER_ID] = (int)$user['id'];
        $_SESSION[self::SESSION_2FA_PASS] = !(bool)$user['two_factor_enabled'];
        $this->logger->info('User logged in', ['user_id' => $user['id']]);

        return true;
    }

    public function verifyTwoFactorCode(string $code): bool
    {
        if (!$this->check()) {
            return false;
        }

        $valid = preg_match('/^\d{6}$/', $code) === 1;
        if ($valid) {
            $_SESSION[self::SESSION_2FA_PASS] = true;
        }

        return $valid;
    }

    public function logout(): void
    {
        unset($_SESSION[self::SESSION_USER_ID], $_SESSION[self::SESSION_2FA_PASS]);
    }

    public function check(): bool
    {
        return isset($_SESSION[self::SESSION_USER_ID]);
    }

    public function requiresTwoFactor(): bool
    {
        return $this->check() && !($_SESSION[self::SESSION_2FA_PASS] ?? false);
    }

    public function userId(): ?int
    {
        return $_SESSION[self::SESSION_USER_ID] ?? null;
    }

    public function can(string $permission): bool
    {
        if (!$this->check() || $this->requiresTwoFactor()) {
            return false;
        }

        $sql = 'SELECT COUNT(*) FROM user_roles ur
                INNER JOIN role_permissions rp ON rp.role_id = ur.role_id
                INNER JOIN permissions p ON p.id = rp.permission_id
                WHERE ur.user_id = :user_id AND p.key_name = :permission';
        $statement = $this->connection->pdo()->prepare($sql);
        $statement->execute(['user_id' => $this->userId(), 'permission' => $permission]);

        return (int)$statement->fetchColumn() > 0;
    }
}
