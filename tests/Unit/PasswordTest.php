<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Infrastructure\Security\Password;
use PHPUnit\Framework\TestCase;

final class PasswordTest extends TestCase
{
    public function testPasswordHashAndVerify(): void
    {
        $hash = Password::hash('StrongPassword123!');

        $this->assertTrue(Password::verify('StrongPassword123!', $hash));
        $this->assertFalse(Password::verify('wrong', $hash));
    }
}
