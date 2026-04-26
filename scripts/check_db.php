<?php
declare(strict_types=1);

// Load .env
if (file_exists(__DIR__ . '/../.env')) {
    foreach (file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (!str_starts_with(trim($line), '#') && str_contains($line, '=')) {
            [$key, $val] = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($val);
        }
    }
}

$cfg = require __DIR__ . '/../config/database.php';
echo "Host: {$cfg['host']}\n";
echo "Port: {$cfg['port']}\n";
echo "Database: {$cfg['database']}\n";
echo "User: {$cfg['username']}\n";

try {
    // Connect without specifying a database first
    $dsn = "mysql:host={$cfg['host']};port={$cfg['port']};charset={$cfg['charset']}";
    $pdo = new PDO($dsn, $cfg['username'], $cfg['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "MySQL connection: OK\n";

    $databases = $pdo->query('SHOW DATABASES')->fetchAll(PDO::FETCH_COLUMN);
    echo "Existing databases: " . implode(', ', $databases) . "\n";

    if (!in_array($cfg['database'], $databases, true)) {
        echo "Database '{$cfg['database']}' does NOT exist - creating it...\n";
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$cfg['database']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "Database created.\n";
    } else {
        echo "Database '{$cfg['database']}' already exists.\n";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
