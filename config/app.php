<?php
// Central application configuration — single source of truth.
// Edit the values below and the entire app will use them.

return [
    'app' => [
        'name' => 'TipSupport',
        'env'  => 'production',
        // If null, the app will auto-detect from the request
        'url'  => null,
        'timezone' => 'UTC',
    ],

    'database' => [
        'driver'   => 'mysql',
        'host'     => '127.0.0.1',
        'port'     => 3306,
        'database' => 'piggytip',
        'username' => 'piggytip',
        'password' => 'PASSWORD',
        'charset'  => 'utf8mb4',
    ],
];

