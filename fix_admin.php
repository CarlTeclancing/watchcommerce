<?php
$pdo  = new PDO("mysql:host=localhost;dbname=watch_commerce", "root", "");
$hash = password_hash("admin123", PASSWORD_ARGON2ID);
$stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
$stmt->execute([$hash, "admin@watchcommerce.test"]);
echo "Done!\n";