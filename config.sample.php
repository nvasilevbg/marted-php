<?php
// Kopiray tozi fail kato config.php na servera i popalni stojnostite.
return [
    'db' => [
        'driver' => 'mysql',
        'sqlite_path' => __DIR__ . '/data/site.db',
        'mysql' => [
            'host' => 'localhost',
            'name' => 'vasata_baza',
            'user' => 'vasia_user',
            'pass' => 'vasata_parola',
            'charset' => 'utf8mb4',
        ],
    ],
    'admin_pass' => 'smeni_tazi_parola',
    'base_url' => 'https://vashiqt-domajn.bg',
];