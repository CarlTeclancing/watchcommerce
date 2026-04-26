<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

final class Container
{
    private array $bindings = [];
    private array $instances = [];

    public function singleton(string $id, callable $resolver): void
    {
        $this->bindings[$id] = $resolver;
    }

    public function get(string $id): mixed
    {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        if (!isset($this->bindings[$id])) {
            throw new RuntimeException("Service not bound: {$id}");
        }

        $this->instances[$id] = $this->bindings[$id]($this);
        return $this->instances[$id];
    }
}
