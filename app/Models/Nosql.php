<?php

namespace App\Models;

use MongoDB\Client;

class Nosql
{
    protected $client;
    protected $collection;

    public function __construct()
    {
        $host = env('MONGO_HOST');
        $port = env('MONGO_PORT');
        $database = env('MONGO_DATABASE');
        $username = env('MONGO_USERNAME');
        $password = env('MONGO_PASSWORD');
        $authDatabase = env('DB_AUTHENTICATION_DATABASE', 'admin');

        $uri = "mongodb://{$username}:{$password}@{$host}:{$port}/{$authDatabase}";
        $this->client = new Client($uri);
        $this->collection = $this->client->$database->{env('MONGO_COLLECTION')};
    }
}