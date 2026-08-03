<?php
$cfg = require __DIR__ . '/inc/config.php';
$m = $cfg['db']['mysql'];
$pdo = new PDO("mysql:host={$m['host']};dbname={$m['name']};charset=utf8mb4", $m['user'], $m['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
try {
    $v = $pdo->query("SELECT v FROM settings WHERE k='home_hero_images'")->fetchColumn();
    echo $v !== false ? "EXISTS: $v" : "MISSING — setting does not exist in DB";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
