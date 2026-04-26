<?php
declare(strict_types=1);

echo "Starting migration test...\n";

try {
    // Load .env
    echo "1. Loading .env...\n";
    if (file_exists(__DIR__ . '/../.env')) {
        $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        echo "   Found " . count($lines) . " lines in .env\n";
        foreach ($lines as $line) {
            if (!str_starts_with(trim($line), '#') && str_contains($line, '=')) {
                [$key, $val] = explode('=', $line, 2);
                $_ENV[trim($key)] = trim($val);
            }
        }
    }

    // Load config
    echo "2. Loading database config...\n";
    $dbConfig = require __DIR__ . '/../config/database.php';
    echo "   Host: {$dbConfig['host']}, Port: {$dbConfig['port']}, DB: {$dbConfig['database']}\n";

    // Connect
    echo "3. Connecting to MySQL...\n";
    $dsn = sprintf('mysql:host=%s;port=%d;charset=%s', $dbConfig['host'], $dbConfig['port'], $dbConfig['charset']);
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "   Connected!\n";

    // Create database
    echo "4. Creating database...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbConfig['database']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "   Database ready!\n";

    // Reconnect with DB selected
    echo "5. Reconnecting to database...\n";
    $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $dbConfig['host'], $dbConfig['port'], $dbConfig['database'], $dbConfig['charset']);
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "   Connected to {$dbConfig['database']}!\n";

    // Load migration SQL
    echo "6. Loading migration SQL...\n";
    $sql = file_get_contents(__DIR__ . '/../migrations/001_init.sql');
    echo "   Loaded " . strlen($sql) . " bytes\n";

    // Split and execute
    echo "7. Executing statements...\n";
    $statements = array_filter(
        array_map('trim', explode(';', (string)$sql)),
        static fn(string $s): bool => $s !== ''
    );
    echo "   Found " . count($statements) . " statements\n";

    $count = 0;
    foreach ($statements as $i => $statement) {
        echo "   Executing statement " . ($i + 1) . "...\n";
        $pdo->exec($statement);
        $count++;
    }

    echo "8. Verifying tables...\n";
    $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
    echo "   " . count($tables) . " tables created\n";
    if (count($tables) > 0) {
        echo "   Tables: " . implode(', ', $tables) . "\n";
    }

    echo "\n✓ Migration completed successfully!\n";

} catch (Throwable $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}
