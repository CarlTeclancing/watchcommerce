<?php

declare(strict_types=1);

return [
    'env' => $_ENV['APP_ENV'] ?? 'local',
    'debug' => filter_var($_ENV['APP_DEBUG'] ?? true, FILTER_VALIDATE_BOOL),
    'url' => $_ENV['APP_URL'] ?? 'http://localhost:8080',
    'name' => 'Watch Commerce',
    'session_key' => 'watch_commerce_session',
];
