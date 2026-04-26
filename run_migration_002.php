<?php
$env = [];
foreach (file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    if (!str_starts_with(trim($line), '#') && str_contains($line, '=')) {
        [$key, $val] = explode('=', $line, 2);
        $env[trim($key)] = trim($val);
    }
}

try {
    $pdo = new PDO(
        'mysql:host=' . $env['DB_HOST'] . ';port=' . $env['DB_PORT'],
        $env['DB_USER'],
        $env['DB_PASSWORD']
    );
    
    $pdo->exec('USE ' . $env['DB_NAME']);
    
    $sql = file_get_contents(__DIR__ . '/migrations/002_add_categories.sql');
    $pdo->exec($sql);
    
    echo 'Migration 002 applied successfully!';
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
