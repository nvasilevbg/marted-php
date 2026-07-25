<?php
// Config — reads from env vars (Docker/Coolify) or falls back to SQLite (local).
// For shared hosting: copy config.sample.php to config.php and fill in real values.
$driver = getenv('DB_DRIVER') ?: 'sqlite';
return [
    'db' => [
        'driver' => $driver,
        'sqlite_path' => __DIR__ . '/../data/site.db',
        'mysql' => [
            'host' => getenv('DB_HOST') ?: 'localhost',
            'name' => getenv('DB_NAME') ?: 'MARTED_DB',
            'user' => getenv('DB_USER') ?: 'MARTED_USER',
            'pass' => getenv('DB_PASS') ?: 'MARTED_PASS',
            'charset' => 'utf8mb4',
        ],
    ],
    'admin_pass' => getenv('ADMIN_PASS') ?: 'CHANGE_ME_IN_CONFIG',
    'base_url' => getenv('BASE_URL') ?: '',
];