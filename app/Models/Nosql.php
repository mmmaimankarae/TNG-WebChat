<?php
namespace App\Models;

use MongoDB\Client;

class Nosql
{
    protected $client;
    protected $database;

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
        $this->database = $this->client->$database;
    }

    public function getCollection($collectionName)
    {
        return $this->database->$collectionName;
    }

    public function insertDocument($collectionName, $document)
    {
        $this->getCollection($collectionName)->insertOne($document);
    }

    public function updateTaskId($collectionName, $oldTaskId, $newTaskId)
    {
        try {
            $this->getCollection($collectionName)->updateMany(
                ['taskId' => $oldTaskId],
                ['$set' => ['taskId' => $newTaskId]]
            );
        } catch (\Exception $e) {
            \Log::error('Nosql Error update message (m.Nosql): ' . $e->getMessage());
        }
    }

    public function findMessages($collectionName, $filter)
    {
        return $this->getCollection($collectionName)->find($filter)->toArray();
    }
}
