<?php
namespace App\Http\Controllers\controlMainPage;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Services\LineService;
use App\Models\Nosql;
use App\Models\Tasks;

use App\Http\Controllers\controlGetInfo\tasksInfo;
use App\Http\Controllers\controlMainPage\ApiController;

use Pusher\Pusher;

class sendMsg extends Controller
{
    private LineService $lineService;
    private Nosql $nosql;
    private Tasks $tasksModel;

    public function __construct(LineService $lineService, Nosql $nosql, Tasks $tasksModel)
    {
        $this->lineService = $lineService;
        $this->nosql = $nosql;
        $this->tasksModel = $tasksModel;
    }

    public function sendMessage(Request $request)
    {
        $replyId = $request->input('replyId');
        $message = $request->input('message');
        $file = $request->file('file') ?? $request->input('file');
        $quote = $request->input('quoteToken');
        $empCode = $request->input('empCode');
        $taskCode = $request->input('taskCode');
        $taskStatus = $request->input('taskStatus');

        if ($empCode !== tasksInfo::getLastEmp($taskCode)) {
            $this->tasksModel->updateStatus($taskCode, $taskStatus, $empCode);
        }

        if ($file) {
            if($request->file('file')) {
                $request->merge(['messageType' => "image"]);
            } else {
                $request->merge(['messageType' => "image-payment"]);
                $this->selecteQuota($request->input('quotaCode'), $request->input('version'));
            }

            $apiController = new ApiController($this->tasksModel);
            $response = $apiController->uploadImages($request);
            if ($response !== true) {
                return $this->handleErrorResponse($response);
            }


        } elseif ($quote) {
            return $this->handleQuoteMessage($request, $replyId, $message, $quote, $taskCode);
        } else {
            return $this->handleTextMessage($request, $replyId, $message, $taskCode);
        }
    }

    private function handleQuoteMessage(Request $request, $replyId, $message, $quote, $taskCode)
    {
        $response = $this->lineService->quoteMessage($replyId, $message, $quote);
        if ($response && $response->getStatusCode() === 200) {
            $this->prepareQuoteMessage($request);
            $this->saveMessage($request);
            $this->tasksModel->setUpdateTime($taskCode);
        }
        return $this->handleErrorResponse($response);
    }

    private function handleTextMessage(Request $request, $replyId, $message, $taskCode)
    {
       $response = $this->lineService->sendMessage($replyId, $message);
        if ($response && $response->getStatusCode() === 200) {
            $request->merge(['messageType' => 'text']);
            $this->saveMessage($request);
            $this->tasksModel->setUpdateTime($taskCode);
        }
        return $this->handleErrorResponse($response);
    }

    private function sendPusher($message, $taskCode)
    {
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true,
            ]
        );
        $pusher->trigger("chat.$taskCode", 'message-sent', [
            'message' => $message
        ]);
    }

    private function handleErrorResponse($response)
    {
        // \Log::error('Error sending message: ' . $response);
        session()->flash('showchat', true);
        return redirect()->back()->withInput()->with('error', 'ไม่สามารถส่งข้อความ หรือ รูปภาพได้ โปรดลอง refesh');
    }

    private function prepareQuoteMessage(Request $request)
    {
        $quoteType = $request->input('quoteTypeInput');
        $quoteContent = match ($quoteType) {
            'image' => 'รูปภาพ',
            'sticker' => 'สติกเกอร์',
            default => $request->input('quoteContentInput')
        };

        $request->merge([
            'quoteType' => 'text',
            'messageType' => 'text',
            'quoteContentInput' => $quoteContent
        ]);
    }

    private function saveMessage(Request $request)
    {
        try {
            $message = $this->msgPattern($request);
            $this->nosql->insertDocument(env('MONGO_COLLECTION'), $message);
            $this->sendPusher($message, $request->input('taskCode'));
        } catch (\Exception $e) {
            \Log::error('Nosql Error saving message (c.sendMsg): ' . $e->getMessage());
        }
    }

    private function selecteQuota($quotaCode, $version)
    {
        return $this->nosql->updateQuota(env('MONGO_COLLECTION_OCR'), $quotaCode, $version);
    }

    private function msgPattern(Request $request)
    {
        return [
            'messageDate' => Carbon::now()->toDateString(),
            'messagetime' => Carbon::now()->toTimeString(),
            'taskId' => $request->input('taskCode') ?? $request->input('TasksCode'),
            'messageContent' => $request->input('message'),
            'messageType' => $request->input('messageType'),
            'replyToId' => $request->input('replyId'),
            'replyToName' => $request->input('replyName') ?? $request->input('cusName'),
            'userId' => "TNG-" . $request->input('userId'),
            'userName' => $request->input('userName'),
            'quoteToken' => $request->input('quoteToken'),
            'quoteType' => $request->input('quoteType'),
            'quoteContent' => $request->input('quoteContentInput'),
        ];
    }
}