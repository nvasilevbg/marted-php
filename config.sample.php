<?php
// Copy this file to inc/config.php and fill in your MySQL credentials.
// This is for shared hosting (superhosting.bg) — no Docker, no env vars.
return [
    'db' => [
        'driver' => 'mysql',
        'sqlite_path' => __DIR__ . '/../data/site.db',
        'mysql' => [
            'host' => 'localhost',
            'name' => 'YOUR_DB_NAME',
            'user' => 'YOUR_DB_USER',
            'pass' => 'YOUR_DB_PASS',
            'charset' => 'utf8mb4',
        ],
    ],
    'admin_pass' => 'CHANGE_THIS_TO_A_STRONG_PASSWORD',
    'base_url' => 'https://your-domain.bg',
];