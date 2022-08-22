<?php
return [
    'dqe_mysql' => [
        'read' => [
            'host' => env('DQE_MYSQL_HOST', 'localhost')
        ],
        'write' => [
            'host' => env('DQE_MYSQL_HOST', 'localhost')
        ],
        'driver'    => 'mysql',
        'database'  => env('DQE_MYSQL_DATABASE', 'forge'),
        'username'  => env('DQE_MYSQL_USERNAME', 'forge'),
        'password'  => env('DQE_MYSQL_PASSWORD', ''),
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
        'strict'    => false,
        'unix_socket'   => env('DB_UNIX_SOCKET'),
    ],
    'dqe_mongodb' => [
        'host'     => env('DQE_MONGO_DB_HOST', 'localhost'),
        'driver'   => 'mongodb',
        'port'     => env('DQE_MONGO_DB_PORT', 27017),
        'database' => env('DQE_MONGO_DB_DATABASE', 'speedypaper'),
        'username' => env('DQE_MONGO_DB_USERNAME', ''),
        'password' => env('DQE_MONGO_DB_PASSWORD', ''),
        'options' => [
            'database' => env('DQE_MONGO_DB_DATABASE', 'speedypaper') // sets the authentication database required by mongo 3
        ]
    ],
    'clickhouse' => [
        'driver' => 'clickhouse',
        'host' => env('CLICKHOUSE_HOST'),
        'port' => env('CLICKHOUSE_PORT','8123'),
        'database' => env('CLICKHOUSE_DATABASE','CLICKHOUSE_USERNAME'),
        'username' => env('CLICKHOUSE_USERNAME','default'),
        'password' => env('CLICKHOUSE_PASSWORD',''),
        'timeout_connect' => env('CLICKHOUSE_TIMEOUT_CONNECT',2),
        'timeout_query' => env('CLICKHOUSE_TIMEOUT_QUERY',2),
        'https' => (bool)env('CLICKHOUSE_HTTPS', null),
        'retries' => env('CLICKHOUSE_RETRIES', 0),
    ],
];
