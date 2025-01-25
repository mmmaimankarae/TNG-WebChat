<?php

namespace App\Http\Controllers;

use MongoDB\Client as MongoClient;
use App\Services\LineService;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    protected $lineService;
    protected $mongoClient;

    public function __construct(LineService $lineService, MongoClient $mongoClient)
    {
        $this->lineService = $lineService;
        $this->mongoClient = $mongoClient;
    }

    public function index()
    {
        $collection = $this->mongoClient->selectDatabase(env('MONGO_DATABASE'))->selectCollection(env('MONGO_COLLECTION'));
        $messages = $collection->find()->toArray();

        return view('Tasks.detailMessage', compact('messages'));
    }

    public function downloadImage($messageId)
    {
        $imageContent = $this->lineService->getImage($messageId);

        return response($imageContent)
            ->header('Content-Type', 'image/jpeg')
            ->header('Content-Disposition', 'attachment; filename="image.jpg"');
    }

    public function showImage($messageId)
    {
        $imageContent = $this->lineService->getImage($messageId);

        return response($imageContent)
            ->header('Content-Type', 'image/jpeg');
    }
}