<?php

Dotenv\Dotenv::createImmutable(__DIR__)->load();

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/database/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/database/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'production' => [
            'adapter' => env('DB_CONNECTION', 'mysql'),
            'host' => env('DB_HOST', 'localhost'),
            'name' => env('DB_DATABASE', 'production_db'),
            'user' => env('DB_USERNAME', 'root'),
            'pass' => env('DB_PASSWORD', ''),
            'port' => env('DB_PORT', '3306'),
            'charset' => 'utf8',
        ],
        'development' => [
            'adapter' => env('DB_CONNECTION', 'mysql'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'name' => env('DB_DATABASE', 'development_db'),
            'user' => env('DB_USERNAME', 'root'),
            'pass' => env('DB_PASSWORD', ''),
            'port' => env('DB_PORT', '3306'),
            'charset' => env('DB_CHARSET', 'utf8'),
        ],
        'testing' => [
            'adapter' => env('TEST_DB_CONNECTION', 'mysql'),
            'host' => env('TEST_DB_HOST', '127.0.0.1'),
            'name' => env('TEST_DB_DATABASE', 'development_db'),
            'user' => env('TEST_DB_USERNAME', 'root'),
            'pass' => env('TEST_DB_PASSWORD', ''),
            'port' => env('TEST_DB_PORT', '3306'),
            'charset' => env('TEST_DB_CHARSET', 'utf8'),
        ]
    ],
    'version_order' => 'creation'
];