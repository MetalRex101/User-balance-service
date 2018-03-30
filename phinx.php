<?php

(new \Dotenv\Dotenv(__DIR__))->load();

// MIGRATION CONFIG
return [
    'paths' => [
        'migrations' => __DIR__.'/db/migrations',
    ],
    'environments' => [
        'default_migration_table' => 'migrations',
        'default_database' => getenv('APP_ENV'),
        'develop' => [
            'adapter' => 'mysql',
            'host' => getenv('DB_HOST'),
            'name' => getenv('DB_NAME'),
            'user' => getenv('DB_USER'),
            'pass' => getenv('DB_PASS'),
            'port' => getenv('DB_PORT'),
        ],
    ],
];
