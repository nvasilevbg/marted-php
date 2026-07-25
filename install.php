<?php
// install.php — run once after uploading to set up the database.
// Delete this file after successful installation.
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/html; charset=utf-8');

$configFile = __DIR__ . '/inc/config.php';
if (!file_exists($configFile)) {
    die('<h2>config.php not found</h2><p>Copy <code>config.sample.php</code> to <code>inc/config.php</code> and fill in your MySQL credentials first.</p>');
}
$cfg = require $configFile;
if ($cfg['db']['driver'] !== 'mysql') {
    die('<h2>Set DB_DRIVER=mysql in config.php</h2>');
}
$m = $cfg['db']['mysql'];
echo "<h2>Installing MarTed...</h2>";
echo "<p>Connecting to MySQL ({$m['host']}/{$m['name']})...</p>";
try {
    $pdo = new PDO("mysql:host={$m['host']};dbname={$m['name']};charset={$m['charset']}", $m['user'], $m['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("<p style='color:red'>Connection failed: " . htmlspecialchars($e->getMessage()) . "</p>");
}
echo "<p style='color:green'>Connected.</p>";

$sql = file_get_contents(__DIR__ . '/schema.sql');
if (!$sql) die("<p style='color:red'>schema.sql not found.</p>");

// Strip SQL comments before splitting
$sql = preg_replace('/--[^\r\n]*/', '', $sql);

$stmts = preg_split('/;[\r\n]+/', $sql);
$ok = 0; $errors = [];
foreach ($stmts as $stmt) {
    $stmt = trim($stmt);
    if ($stmt === '') continue;
    try {
        $pdo->exec($stmt);
        $ok++;
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate') !== false || $e->getCode() == 23000) {
            // Index already exists — skip
        } else {
            $errors[] = $e->getMessage();
        }
    }
}
echo "<p>Executed $ok statements.</p>";
if ($errors) {
    echo "<p style='color:orange'>Warnings: " . count($errors) . "</p>";
    foreach (array_slice($errors, 0, 5) as $e) echo "<pre>" . htmlspecialchars($e) . "</pre>";
}

// Verify
$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
echo "<p>Tables: " . implode(', ', $tables) . "</p>";
$count = $pdo->query("SELECT COUNT(*) FROM settings")->fetchColumn();
echo "<p>Settings rows: $count</p>";

if ($count > 0) {
    echo "<p style='color:green;font-size:18px'><strong>OK! Installation complete.</strong></p>";
    echo "<p>Visit your site: <a href='/'>Home</a> | <a href='/admin/'>Admin</a></p>";
    echo "<p style='color:red'><strong>Delete install.php now!</strong></p>";
} else {
    echo "<p style='color:red'>No settings found — check schema.sql.</p>";
}