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

    public function sendMessage(Request $request)
    {
        $replyId = $request->input('replyId');
        $message = $request->input('message');
        $file = $request->file('file');
        $quote = $request->input('quoteToken');
        $empCode = $request->input('empCode');
        $taskCode = $request->input('taskCode');
        $taskStatus = $request->input('taskStatus');

        if ($empCode !== tasksInfo::getLastEmp($taskCode)) {
            $this->tasksModel->updateStatus($taskCode, $taskStatus, $empCode);
        }

        if ($file) {
            // Handle file upload
            $path = $file->store('uploads', 'public');
            $url = url(Storage::url($path));
            \Log::info('Image URL: ' . $url);
            
            $response = $this->lineService->sendImageMessage($replyId, $url, $url);
            return $this->handleResponse($response);
        } elseif ($quote) {
            $response = $this->lineService->quoteMessage($replyId, $message, $quote);
            return $this->handleQuoteResponse($response, $request, $taskCode, $replyId);
        } else {
            $response = $this->lineService->sendMessage($replyId, $message);
            return $this->handleTextResponse($response, $request, $taskCode, $replyId);
        }
    }

    private function handleResponse($response)
    {
        if ($response && $response->getStatusCode() === 200) {
            return redirect()->back()->withInput()->with('select', true);
        }
        return response()->json(['status' => 'error', 'message' => $response ? $response->getBody()->getContents() : 'Error occurred'], 500);
    }

    private function handleQuoteResponse($response, Request $request, $taskCode, $replyId)
    {
        if ($response && $response->getStatusCode() === 200) {
            $this->prepareQuoteMessage($request);
            $this->saveMessage($request);
            $this->tasksModel->setUpdateTime($taskCode);
            return redirect()->back()->withInput()->with('select', true);
        }
        return response()->json(['status' => 'error', 'message' => $response ? $response->getBody()->getContents() : 'Error occurred'], 500);
    }

    private function handleTextResponse($response, Request $request, $taskCode, $replyId)
    {
        if ($response && $response->getStatusCode() === 200) {
            $request->merge(['messageType' => 'text']);
            $this->saveMessage($request);
            $this->tasksModel->setUpdateTime($taskCode);
            if ($request->hasSession()) {
                $request->session()->flash('TasksLineID', $replyId);
            }
            return redirect()->back()->withInput()->with('select', true);
        }
        return response()->json(['status' => 'error', 'message' => $response ? $response->getBody()->getContents() : 'Error occurred'], 500);
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
            $this->nosql->insertDocument(env('MONGO_COLLECTION'), $this->msgPattern($request));
        } catch (\Exception $e) {
            \Log::error('Nosql Error saving message (c.sendMsg): ' . $e->getMessage());
        }
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
