<?php

return [
    'default' => [
        'host' => env('MONGO_HOST'),
        'port' => env('MONGO_PORT'),
        'database' => env('MONGO_DATABASE'),
        'username' => env('MONGO_USERNAME'),
        'password' => env('MONGO_PASSWORD'),
        'options' => [
            'database' => env('DB_AUTHENTICATION_DATABASE', 'admin'), // required with Mongo 3+
        ],
    ],
];