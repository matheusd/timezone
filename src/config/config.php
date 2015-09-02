<?php
/************************************
 *  THIS IS A SAMPLE CONFIG FILE
 ************************************/
 
$env = [
    //public url for this project
    'publicUrl' => 'http://localhost:8039',
    
    //wether this is a development/testing/staging version
    'devVersion' => true,
    
    'databases' => [
        //main database config 
        'default' => [
            'driver' => 'pdo_pgsql',
            'dbname' => 'orm1',
            'server' => 'localhost',
            'user' => 'postgres',
            'password' => 'postgres',
        ]
    ],
];