<?php

declare(strict_types=1);

namespace App\Infrastructure\Http;

final class Validator
{
    public static function validate(array $input, array $rules): array
    {
        $errors = [];
        $validated = [];

        foreach ($rules as $field => $ruleLine) {
            $rulesForField = explode('|', $ruleLine);
            $value = $input[$field] ?? null;

            foreach ($rulesForField as $rule) {
                if ($rule === 'required' && ($value === null || $value === '')) {
                    $errors[$field][] = 'This field is required.';
                    continue;
                }

                if ($rule === 'email' && $value !== null && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = 'Invalid email.';
                }

                if (str_starts_with($rule, 'min:') && is_string($value)) {
                    $min = (int)substr($rule, 4);
                    if (mb_strlen($value) < $min) {
                        $errors[$field][] = "Minimum length is {$min}.";
                    }
                }

                if ($rule === 'numeric' && $value !== null && !is_numeric($value)) {
                    $errors[$field][] = 'Must be numeric.';
                }
            }

            $validated[$field] = is_string($value) ? trim($value) : $value;
        }

        return [$validated, $errors];
    }
}
