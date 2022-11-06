<?php 
 /**
 * SmyPhp - A simple PHP framework
 * @author SegunCodes
 * 
 *  @package SmyPhp
 * This config file should contain the connection parameters to your database
 * Your models use this configuration.
*/ 
return [
    'userClass' => \App\Models\User::class,
    'db' => [ 
        'connection' => $_ENV['DB_CONNECTION'],
        'host' => $_ENV['DB_HOST'],
        'port' => $_ENV['DB_PORT'],
        'database' => $_ENV['DB_DATABASE'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD']
    ]
];