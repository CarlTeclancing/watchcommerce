<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Infrastructure\Http\Validator;
use PHPUnit\Framework\TestCase;

final class ValidatorTest extends TestCase
{
    public function testValidatorReturnsErrorsForInvalidInput(): void
    {
        [$validated, $errors] = Validator::validate([
            'email' => 'invalid',
            'password' => '123',
        ], [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $this->assertArrayHasKey('email', $errors);
        $this->assertArrayHasKey('password', $errors);
        $this->assertSame('invalid', $validated['email']);
    }

    public function testValidatorPassesValidInput(): void
    {
        [, $errors] = Validator::validate([
            'name' => 'John Admin',
            'email' => 'john@example.com',
        ], [
            'name' => 'required|min:2',
            'email' => 'required|email',
        ]);

        $this->assertSame([], $errors);
    }
}
