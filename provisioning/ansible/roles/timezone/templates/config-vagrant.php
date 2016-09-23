<?php

$env = [
    'publicUrl' => 'http://192.168.100.196:8083',
    'assetsUrl' => 'http://192.168.100.196:8083/assets',
    
    'devVersion' => true,
    
    'databases' => [
        'default' => [
            'driver' => 'pdo_pgsql',
            'dbname' => 'mdtimezone',
            'host' => 'localhost',
            'user' => 'mdtimezone',
            'password' => 'mdtimezone',
        ],
        'test' => [
            'driver' => 'pdo_sqlite',
            'path' => __DIR__ . '/db-testing.sqlite',

            //'driver' => 'pdo_pgsql',
            //'dbname' => 'orm2',
            //'server' => 'localhost',
            //'user' => 'postgres',
            //'password' => 'postgres',
        ]
    ],
];