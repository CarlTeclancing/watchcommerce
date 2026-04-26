<?php
declare(strict_types=1);

echo "Starting seed test...\n";

try {
    // Load .env
    echo "1. Loading .env...\n";
    if (file_exists(__DIR__ . '/../.env')) {
        foreach (file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            if (!str_starts_with(trim($line), '#') && str_contains($line, '=')) {
                [$key, $val] = explode('=', $line, 2);
                $_ENV[trim($key)] = trim($val);
            }
        }
    }
    echo "   .env loaded\n";

    // Load config
    echo "2. Loading database config...\n";
    $dbConfig = require __DIR__ . '/../config/database.php';

    // Connect
    echo "3. Connecting to database...\n";
    $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $dbConfig['host'], $dbConfig['port'], $dbConfig['database'], $dbConfig['charset']);
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "   Connected!\n";

    // Load seed SQL
    echo "4. Loading seed SQL...\n";
    $sql = file_get_contents(__DIR__ . '/../seeds/seed.sql');
    echo "   Loaded " . strlen($sql) . " bytes\n";

    // Split and execute
    echo "5. Executing seed statements...\n";
    $statements = array_filter(
        array_map('trim', explode(';', (string)$sql)),
        static fn(string $s): bool => $s !== ''
    );
    echo "   Found " . count($statements) . " statements\n";

    $count = 0;
    foreach ($statements as $i => $statement) {
        $pdo->exec($statement);
        $count++;
    }

    echo "   Executed all statements\n";

    // Verify seed data
    echo "6. Verifying seed data...\n";
    $watches = $pdo->query('SELECT COUNT(*) as cnt FROM watches')->fetch(PDO::FETCH_ASSOC);
    $users   = $pdo->query('SELECT COUNT(*) as cnt FROM users')->fetch(PDO::FETCH_ASSOC);
    $roles   = $pdo->query('SELECT COUNT(*) as cnt FROM roles')->fetch(PDO::FETCH_ASSOC);

    echo "   Watches: " . $watches['cnt'] . "\n";
    echo "   Users: " . $users['cnt'] . "\n";
    echo "   Roles: " . $roles['cnt'] . "\n";

    echo "\n✓ Seed completed successfully!\n";

} catch (Throwable $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
    exit(1);
}
