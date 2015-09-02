<?php

$env = [
    'publicUrl' => 'http://localhost:8039',
    'assetsUrl' => 'http://localhost:8039/assets',
    
    'devVersion' => true,
    
    'databases' => [
        'default' => [
            'driver' => 'pdo_pgsql',
            'dbname' => 'orm1',
            'server' => 'localhost',
            'user' => 'postgres',
            'password' => 'postgres',
        ]
    ],
];