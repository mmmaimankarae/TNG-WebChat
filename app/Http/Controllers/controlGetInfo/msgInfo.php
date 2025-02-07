<?php

namespace App\Http\Controllers\controlGetInfo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use MongoDB\Client as MongoClient;
use App\Services\LineService;

class msgInfo extends Controller
{
    protected $lineService;
    protected $mongoClient;

    public function __construct(LineService $lineService, MongoClient $mongoClient)
    {
        $this->lineService = $lineService;
        $this->mongoClient = $mongoClient;
    }

    private function getCollections()
    {
        return  $this->mongoClient->selectDatabase(env('MONGO_DATABASE'))->selectCollection(env('MONGO_COLLECTION'));
    }

    public function getMsg($taskCode)
    {
        $collection = $this->getCollections();
        $messages = $collection->find(['taskId' => $taskCode])->toArray();
    
        /* หา Msg จาก รหัส Task เก่า */
        $allMessages = $this->getOldMsg($messages);
    
        /* เรียงข้อมูลตาม messageDate และ messagetime */
        usort($allMessages, function($a, $b) {
            $dateA = strtotime($a['messageDate'] . ' ' . $a['messagetime']);
            $dateB = strtotime($b['messageDate'] . ' ' . $b['messagetime']);
            return $dateA - $dateB;
        });
        return $allMessages;
    }
    
    private function getOldMsg($messages)
    {
        $collection = $this->getCollections();
        $allMessages = $messages;
        $oldTasksFound = [];

        foreach ($messages as $message) {
            if (isset($message['oldTasks']) && $message['oldTasks'] != '') {
                $oldTasks = strpos($message['oldTasks'], ',') !== false ? explode(',', $message['oldTasks']) : [$message['oldTasks']];
                $oldTasksFound = array_merge($oldTasksFound, $oldTasks);
                /* กรอง รหัส Tasks ที่ซ้ำออก */
                $oldTasksFound = array_unique($oldTasksFound);
                foreach ($oldTasksFound as $oldTaskCode) {
                    $oldMessages = $collection->find(['taskId' => $oldTaskCode])->toArray();
                    $allMessages = array_merge($allMessages, $oldMessages);
                }
            }
        }
        return $allMessages;
    }
    
    public function previewImage($messageId)
    {
        $imageContent = $this->lineService->getImage($messageId);
        return response($imageContent)
            ->header('Content-Type', 'image/jpeg');
    }

    public function viewImage(Request $request)
    {
        $messageId = $request->input('messageId');
        $imageContent = $this->lineService->getImage($messageId);
    
        if (!$imageContent) {
            return view('viewImage', ['imageUrl' => null, 'errorMessage' => 'รูปภาพหมดอายุแล้ว']);
        }
    
        $imageUrl = route('preview.image', ['messageId' => $messageId]);
    
        return view('viewImage', ['imageUrl' => $imageUrl, 'errorMessage' => null]);
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

    
    public function getMsgByUser($userID)
    {
        $collection = $this->getCollections();

        /* ค้นหาข้อความทั้งหมดของ userID ที่ระบุ */
        $userMessages = $collection->find(['userId' => $userID])->toArray();
        /* ยกเว้นมั Group ID */
        $userMessages = array_filter($userMessages, function($userMessages) {
            return empty($userMessages['groupId']);
        });
        /* หาจาก gtoupId*/
        if (empty($userMessages)) {
            $userMessages = $collection->find(['groupId' => $userID])->toArray();
        }

        /* ดึง taskId ทั้งหมด และลบค่าซ้ำ */
        $taskIds = array_unique(array_column($userMessages, 'taskId'));

        if (empty($taskIds)) {
            return [];
        }
        $taskIds = array_values(array_filter($taskIds));

        /* ค้นหาข้อความทั้งหมดที่มี taskId ตรงกับที่หาได้ */
        $messages = $collection->find(['taskId' => ['$in' => $taskIds]])->toArray();

        $uniqueMessages = [];
        foreach ($messages as $message) {
            $uniqueMessages[(string) $message['_id']] = $message;
        }

        $messages = array_values($uniqueMessages);

        /* เรียงข้อมูลตาม messageDate และ messagetime */
        usort($messages, function ($a, $b) {
            $dateA = strtotime($a['messageDate'] . ' ' . $a['messagetime']);
            $dateB = strtotime($b['messageDate'] . ' ' . $b['messagetime']);
            return $dateA - $dateB;
        });

        return $messages;
    }
}