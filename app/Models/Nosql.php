<?php
namespace App\Models;

use MongoDB\Client;

class Nosql
{
    protected $client;
    protected $database;

    public function __construct()
    {
        try {
            $host = env('MONGO_HOST');
            $port = env('MONGO_PORT');
            $database = env('MONGO_DATABASE');
            $username = env('MONGO_USERNAME');
            $password = env('MONGO_PASSWORD');
            $authDatabase = env('DB_AUTHENTICATION_DATABASE', 'admin');

            $uri = "mongodb://{$username}:{$password}@{$host}:{$port}/{$authDatabase}";
            $this->client = new Client($uri);
            $this->database = $this->client->$database;
        } catch (\Exception $e) {
            \Log::error('Nosql Error (m.Nosql): ' . $e->getMessage());
        }
    }

    public function getCollection($collectionName)
    {
        return $this->database->$collectionName;
    }

    public function insertDocument($collectionName, $document)
    {
        try {
            $this->getCollection($collectionName)->insertOne($document);
        } catch (\Exception $e) {
            \Log::error('Nosql Error insert message (m.Nosql): ' . $e->getMessage());
        }
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
    
    public function updateQuota($collectionName, $quotaCode, $version)
    {
        try {
            $collection = $this->getCollection($collectionName);

            $collection->updateOne(
                ['document_id' => $quotaCode, 'document_version' => (int) $version],
                ['$set' => ['invoice' => "true"]]
            );

            $collection->updateMany(
                ['document_id' => $quotaCode, 'document_version' => ['$ne' => (int) $version]],
                ['$set' => ['invoice' => "false"]]
            );

            return true;
        } catch (\Exception $e) {
            Log::error('MongoDB Error: ' . $e->getMessage());
            return false;
        }
    }

    public function findMessages($collectionName, $filter)
    {
        try {
            return $this->getCollection($collectionName)->find($filter)->toArray();
        } catch (\Exception $e) {
            \Log::error('Nosql Error find message (m.Nosql): ' . $e->getMessage());
        }
    }
}
