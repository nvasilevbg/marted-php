<?php
$driver = 'mysql';
return [
    'db' => [
        'driver' => $driver,
        'sqlite_path' => __DIR__ . '/../data/site.db',
        'mysql' => [
            'host' => 'localhost',
            'name' => 'dobr4dgm_NSN',
            'user' => 'dobr4dgm_NSN',
            'pass' => 'Marted2026!Db',
            'charset' => 'utf8mb4',
        ],
    ],
    'admin_user' => 'admin',
    'admin_pass' => 'Marted2026!Admin',
    'base_url' => 'https://dobrichmontaj.bg',
];