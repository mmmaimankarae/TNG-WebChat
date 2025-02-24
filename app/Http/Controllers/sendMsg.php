<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LineService;
use App\Models\Nosql;
use Carbon\Carbon;
use App\Models\Tasks;
use App\Http\Controllers\controlGetInfo\tasksInfo;
use Illuminate\Support\Facades\Storage;

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

    public function sendMessage(Request $req)
    {
        $replyId = $req->input('replyId');
        $message = $req->input('message');
        $file = $req->file('file');
        $quote = $req->input('quoteToken');
        $empCode = $req->input('empCode');
        $taskCode = $req->input('taskCode');
        $taskStatus = $req->input('taskStatus');

        if ($empCode !== tasksInfo::getLastEmp($taskCode)) {
            $this->tasksModel->updateStatus($taskCode, $taskStatus, $empCode);
        }

        if ($file) {
            return $this->handleFileUpload($file, $replyId);
        } elseif ($quote) {
            return $this->handleQuoteMessage($req, $replyId, $message, $quote, $taskCode);
        } else {
            return $this->handleTextMessage($req, $replyId, $message, $taskCode);
        }
    }

    private function handleFileUpload($file, $replyId)
    {
        $path = $file->store('uploads', 'public');
        $url = url(Storage::url($path));
        \Log::info('Image URL: ' . $url);

        $response = $this->lineService->sendImageMessage($replyId, $url, $url);
        return $this->handleResponse($response);
    }

    private function handleQuoteMessage(Request $req, $replyId, $message, $quote, $taskCode)
    {
        $response = $this->lineService->quoteMessage($replyId, $message, $quote);
        if ($response && $response->getStatusCode() === 200) {
            $this->prepareQuoteMessage($req);
            $this->saveMessage($req);
            $this->tasksModel->setUpdateTime($taskCode);
            return redirect()->back()->withInput()->with('showchat', true);
        }
        return $this->handleErrorResponse($response);
    }

    private function handleTextMessage(Request $req, $replyId, $message, $taskCode)
    {
       $response = $this->lineService->sendMessage($replyId, $message);
        if ($response && $response->getStatusCode() === 200) {
            $req->merge(['messageType' => 'text']);
            $this->saveMessage($req);
            $this->tasksModel->setUpdateTime($taskCode);
            if ($req->hasSession()) {
                $req->session()->flash('taskLineID', $replyId);
                $req->session()->flash('showchat', true);
            }
            return redirect()->back()->withInput();
        }
        return $this->handleErrorResponse($response);
    }

    private function handleResponse($response)
    {
        if ($response && $response->getStatusCode() === 200) {
            return redirect()->back()->withInput()->with('showchat', true);
        }
        return $this->handleErrorResponse($response);
    }

    private function handleErrorResponse($response)
    {
        $errorMessage = $response ? $response->getBody()->getContents() : 'Error occurred';
        return redirect()->back()->withInput()->withErrors(['message' => 'ไม่สามารถส่งข้อความได้: ' . $errorMessage]);
    }

    private function prepareQuoteMessage(Request $req)
    {
        $quoteType = $req->input('quoteTypeInput');
        $quoteContent = match ($quoteType) {
            'image' => 'รูปภาพ',
            'sticker' => 'สติกเกอร์',
            default => $req->input('quoteContentInput')
        };

        $req->merge([
            'quoteType' => 'text',
            'messageType' => 'text',
            'quoteContentInput' => $quoteContent
        ]);
    }

    private function saveMessage(Request $req)
    {
        try {
            $this->nosql->insertDocument(env('MONGO_COLLECTION'), $this->msgPattern($req));
        } catch (\Exception $e) {
            \Log::error('Nosql Error saving message (c.sendMsg): ' . $e->getMessage());
        }
    }

    private function msgPattern(Request $req)
    {
        return [
            'messageDate' => Carbon::now()->toDateString(),
            'messagetime' => Carbon::now()->toTimeString(),
            'taskId' => $req->input('taskCode') ?? $req->input('TasksCode'),
            'messageContent' => $req->input('message'),
            'messageType' => $req->input('messageType'),
            'replyToId' => $req->input('replyId'),
            'replyToName' => $req->input('replyName') ?? $req->input('cusName'),
            'userId' => "TNG-" . $req->input('userId'),
            'userName' => $req->input('userName'),
            'quoteToken' => $req->input('quoteToken'),
            'quoteType' => $req->input('quoteType'),
            'quoteContent' => $req->input('quoteContentInput'),
        ];
    }
}