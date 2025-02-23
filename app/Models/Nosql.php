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
    
    public function updateQuota($quotaCode, $version)
    {
        try {
            $collection = env('MONGO_COLLECTION_OCR');
            // อัปเดต `invoice` ของเอกสารที่ตรงกันให้เป็น "true"
            $collection->updateOne(
                ['document_id' => $quotaCode, 'document_version' => $version],
                ['$set' => ['invoice' => "true"]]
            );

            // อัปเดตเอกสารอื่น ๆ ที่มี document_id เดียวกัน แต่ version ไม่ตรง ให้เป็น "false"
            $collection->updateMany(
                ['document_id' => $quotaCode, 'document_version' => ['$ne' => $version]],
                ['$set' => ['invoice' => "false"]]
            );

            return response()->json(['status' => 'success', 'message' => 'อัปเดตข้อมูลเรียบร้อยแล้ว']);
        } catch (\Exception $e) {
            Log::error('MongoDB Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด'], 500);
        }
    }

    public function findMessages($collectionName, $filter)
    {
        return $this->getCollection($collectionName)->find($filter)->toArray();
    }
}
