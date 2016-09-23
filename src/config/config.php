<?php
/************************************
 *  THIS IS A SAMPLE CONFIG FILE
 ************************************/

//This $env variable will be imported to the dependency injection container
//at the config/* root. So, for example, $env['publicUrl'] will become (inside
//the DI container) $di['config/publicUrl'].
$env = [
    //public url for this project
    'publicUrl' => 'http://localhost:8039',

    //custom assets url (for those not available at CDNs)
    'assetsUrl' => 'http://localhost:8039/assets',
    
    //wether this is a development/testing/staging version
    //set to false on production
    'devVersion' => true,

    //databases configuration
    'databases' => [
        //main database config 
        'default' => [
            'driver' => 'pdo_pgsql',
            'dbname' => 'orm1',
            'host' => 'localhost',
            'user' => 'postgres',
            'password' => 'postgres',
        ],

        //database config for unit/integration testing
        //Since this is a simple project, a sqlite database should
        //suffice for testing
        'test' => [
            'driver' => 'pdo_sqlite',
            'path' => __DIR__ . '/db-testing.sqlite',
        ]
    ],
];