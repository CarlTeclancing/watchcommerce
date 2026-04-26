<?php
declare(strict_types=1);

if (file_exists(__DIR__ . '/../.env')) {
    foreach (file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (!str_starts_with(trim($line), '#') && str_contains($line, '=')) {
            [$key, $val] = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($val);
        }
    }
}

$cfg = require __DIR__ . '/../config/database.php';
$pdo = new PDO(
    "mysql:host={$cfg['host']};port={$cfg['port']};dbname={$cfg['database']};charset={$cfg['charset']}",
    $cfg['username'], $cfg['password'],
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

$tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
echo count($tables) . " tables found: " . implode(', ', $tables) . "\n";

if (in_array('watches', $tables, true)) {
    $watches = $pdo->query('SELECT COUNT(*) FROM watches')->fetchColumn();
    $users   = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
    $roles   = $pdo->query('SELECT COUNT(*) FROM roles')->fetchColumn();
    echo "watches={$watches}, users={$users}, roles={$roles}\n";
    echo "\nSample watches:\n";
    foreach ($pdo->query('SELECT title, list_price, status FROM watches LIMIT 5')->fetchAll(PDO::FETCH_ASSOC) as $w) {
        echo "  - {$w['title']} \${$w['list_price']} [{$w['status']}]\n";
    }
} else {
    echo "No tables found - migration may have failed.\n";
}
