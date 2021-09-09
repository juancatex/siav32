<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
        ],

        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'db_sia'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => false,//modificado para aceptar consultas sql
            'engine' => null,
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST_P'),
            'port' => env('DB_PORT_P'),
            'database' => env('DB_DATABASE_P'),
            'username' => env('DB_USERNAME_P'),
            'password' => env('DB_PASSWORD_P'),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],
        'pgsql2020' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST_2'),
            'port' => env('DB_PORT_2'),
            'database' => env('DB_DATABASE_2'),
            'username' => env('DB_USERNAME_2'),
            'password' => env('DB_PASSWORD_2'),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],
        'pgsql2020back' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST_3'),
            'port' => env('DB_PORT_3'),
            'database' => env('DB_DATABASE_3'),
            'username' => env('DB_USERNAME_3'),
            'password' => env('DB_PASSWORD_3'),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],
        'pgsql_desarrollo' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST_PD'),
            'port' => env('DB_PORT_PD'),
            'database' => env('DB_DATABASE_PD'),
            'username' => env('DB_USERNAME_PD'),
            'password' => env('DB_PASSWORD_PD'),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'pgsql_localback' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST_back2020'),
            'port' => env('DB_PORT_back2020'),
            'database' => env('DB_DATABASE_back2020'),
            'username' => env('DB_USERNAME_back2020'),
            'password' => env('DB_PASSWORD_back2020'),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],
        'pgsql_local2020' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST_2020'),
            'port' => env('DB_PORT_2020'),
            'database' => env('DB_DATABASE_2020'),
            'username' => env('DB_USERNAME_2020'),
            'password' => env('DB_PASSWORD_2020'),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
        ],

        'telescope' => [
            'driver'      => 'mysql',
            'host'        => env('TELESCOPE_DB_HOST', '127.0.0.1'),
            'port'        => env('TELESCOPE_DB_PORT', '3306'),
            'database'    => env('TELESCOPE_DB_DATABASE', 'forge'),
            'username'    => env('TELESCOPE_DB_USERNAME', 'forge'),
            'password'    => env('TELESCOPE_DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => false,//modificado para aceptar consultas sql
            'engine' => null,
        ],    
    ],


    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => 'predis',

        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => 0,
        ],

    ],

];
