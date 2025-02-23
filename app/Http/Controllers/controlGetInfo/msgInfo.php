<?php

namespace App\Http\Controllers\controlGetInfo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Nosql;
use App\Services\LineService;

class msgInfo extends Controller
{
    protected $lineService;
    protected $nosql;

    public function __construct(LineService $lineService, Nosql $nosql)
    {
        $this->lineService = $lineService;
        $this->nosql = $nosql;
    }

    private function getCollections($collectionName)
    {
        return $this->nosql->getCollection($collectionName);
    }

    public function getMsgByUser($userID)
    {
        $collectionName = env('MONGO_COLLECTION');
        $userMessages = $this->nosql->findMessages($collectionName, ['userId' => $userID]);

        $userMessages = array_filter($userMessages, function ($msg) {
            return empty($msg['groupId']);
        });

        if (empty($userMessages)) {
            $userMessages = $this->nosql->findMessages($collectionName, ['groupId' => $userID]);
        }

        $taskIds = array_values(array_unique(array_column($userMessages, 'taskId')));
        if (empty($taskIds)) {
            return [];
        }

        $messages = $this->nosql->findMessages($collectionName, ['taskId' => ['$in' => $taskIds]]);
        $replyMessages = $this->nosql->findMessages($collectionName, ['replyToId' => $userID]);
        $messages = array_merge($messages, $replyMessages);

        $uniqueMessages = [];
        foreach ($messages as $message) {
            $uniqueMessages[(string) $message['_id']] = $message;
        }

        $messages = array_values($uniqueMessages);

        usort($messages, function ($a, $b) {
            return strtotime($a['messageDate'] . ' ' . $a['messagetime']) - strtotime($b['messageDate'] . ' ' . $b['messagetime']);
        });

        return $messages;
    }

        public function previewImage($messageId)
    {
        $imageContent = $this->lineService->getImage($messageId);
        return response($imageContent)
            ->header('Content-Type', 'image/jpeg');
    }

    public function viewImage(Request $request)
    {
        $messageIds = explode(',', $request->input('messageId'));
        $imageUrls = [];
        $downloadable = false;
    
        foreach ($messageIds as $messageId) {
            $messageId = trim($messageId);
            
            if (strpos($messageId, 'c:\xampp\htdocs\TNG-WebChat\storage\app\public\\') !== false) {
                $messageId = str_replace('c:\xampp\htdocs\TNG-WebChat\storage\app\public\\', '', $messageId);
                $messageId = asset('storage/' . $messageId);
            }

            if (strpos($messageId, 'storage') !== false) {
                $imageUrls[] = [
                    'url' => $messageId,
                    'downloadable' => false
                ];
                continue;
            }
    
            $imageContent = $this->lineService->getImage($messageId);
            if (!$imageContent) {
                continue;
            }
    
            $imageUrls[] = [
                'url' => route('preview.image', ['messageId' => $messageId]),
                'downloadable' => true
            ];
    
            $downloadable = true;
        }
    
        if (empty($imageUrls)) {
            return view('viewImage', ['imageUrls' => [], 'errorMessage' => 'ไม่มีรูปภาพที่ใช้งานได้']);
        }
    
        return view('viewImage', ['imageUrls' => $imageUrls, 'downloadable' => $downloadable]);
    }    

    public function downloadImage($messageId)
    {
        $imageContent = $this->lineService->getImage($messageId);

        if (!$imageContent) {
            return response('Image not found', 404);
        }

        return response($imageContent)
            ->header('Content-Type', 'image/jpeg')
            ->header('Content-Disposition', 'attachment; filename="image.jpeg"');
    }
}
