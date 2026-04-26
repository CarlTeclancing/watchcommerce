<?php
declare(strict_types=1);

$log = [];

try {
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
    $log[] = "Connecting to {$cfg['host']}:{$cfg['port']} db={$cfg['database']} user={$cfg['username']}";

    // Connect without DB first to ensure it exists
    $pdo = new PDO(
        "mysql:host={$cfg['host']};port={$cfg['port']};charset={$cfg['charset']}",
        $cfg['username'], $cfg['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$cfg['database']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $log[] = "Database ensured.";

    // Reconnect with DB selected
    $pdo = new PDO(
        "mysql:host={$cfg['host']};port={$cfg['port']};dbname={$cfg['database']};charset={$cfg['charset']}",
        $cfg['username'], $cfg['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Run migrations
    $sql = file_get_contents(__DIR__ . '/../migrations/001_init.sql');
    $statements = array_filter(array_map('trim', explode(';', (string)$sql)), static fn(string $s): bool => $s !== '');
    $count = 0;
    foreach ($statements as $statement) {
        $pdo->exec($statement);
        $count++;
    }
    $log[] = "Migrations done: {$count} statements.";

    // Run seeds
    $sql = file_get_contents(__DIR__ . '/../seeds/seed.sql');
    $statements = array_filter(array_map('trim', explode(';', (string)$sql)), static fn(string $s): bool => $s !== '');
    $count = 0;
    foreach ($statements as $statement) {
        $pdo->exec($statement);
        $count++;
    }
    $log[] = "Seeds done: {$count} statements.";

    // Verify
    $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
    $log[] = count($tables) . " tables created: " . implode(', ', $tables);

    $watches = $pdo->query('SELECT COUNT(*) FROM watches')->fetchColumn();
    $users   = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
    $roles   = $pdo->query('SELECT COUNT(*) FROM roles')->fetchColumn();
    $log[] = "Data: watches={$watches}, users={$users}, roles={$roles}";

    $log[] = "ALL DONE - application is ready!";

} catch (Throwable $e) {
    $log[] = "ERROR: " . $e->getMessage();
    $log[] = "File: " . $e->getFile() . " line " . $e->getLine();
}

$output = implode("\n", $log) . "\n";
echo $output;
file_put_contents(__DIR__ . '/../storage/logs/setup.log', $output);
