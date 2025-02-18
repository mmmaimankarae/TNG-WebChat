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
    private $lineService;
    private $nosql;
    private $tasksModel;

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

        if ($empCode != tasksInfo::getLastEmp($taskCode)) {
            $this->tasksModel->updateStatus($taskCode, $request->input('taskStatus'), $empCode);
        }

        if ($file) {
            // // Handle file upload and sending image message
            // $path = $file->store('uploads', 'public');
            // $url = url(Storage::url($path));
            // \Log::info('Image URL: ' . $url);
            // $response = $this->lineService->sendImageMessage($replyId, $url, $url);
            // if ($response && $response->getStatusCode() === 200) {
            //     dd(ok);
            //     // $this->saveMessage($request, $url);
            //     return redirect()->back()->withInput()->with('select', true);
            // } else {
            //     return response()->json(['status' => 'error', 'message' => $response ? $response->getBody()->getContents() : 'Error occurred'], 500);
            // }
        } elseif ($quote) {
            $response = $this->lineService->quoteMessage($replyId, $message, $quote);

            if ($response && $response->getStatusCode() === 200) {
                $this->handleQuoteMessage($request, $taskCode, $replyId);
                return redirect()->back()->withInput()->with('select', true);
            } else {
                return response()->json(['status' => 'error', 'message' => $response ? $response->getBody()->getContents() : 'Error occurred'], 500);
            }
        } else {
            $response = $this->lineService->sendMessage($replyId, $message);

            if ($response && $response->getStatusCode() === 200) {
                $request->merge(['messageType' => 'text']);
                $this->saveMessage($request);
                $this->tasksModel->setUpdateTime($taskCode);
                $request->session()->flash('TasksLineID', $replyId);
                return redirect()->back()->withInput()->with('select', true);
            } else {
                return response()->json(['status' => 'error', 'message' => $response ? $response->getBody()->getContents() : 'Error occurred'], 500);
            }
        }
    }

    private function handleQuoteMessage(Request $request, $taskCode, $replyId)
    {
        if ($request->input('quoteTypeInput') === 'image') {
            $request->merge(['quoteContentInput' => 'รูปภาพ']);
        } elseif ($request->input('quoteTypeInput') === 'sticker') {
            $request->merge(['quoteContentInput' => 'สติกเกอร์']);
        }
        $request->merge(['quoteType' => 'text']);
        $request->merge(['messageType' => 'text']);
        $this->saveMessage($request);
        $this->tasksModel->setUpdateTime($taskCode);
        $request->session()->flash('TasksLineID', $replyId);
    }

    private function saveMessage(Request $request)
    {
        $document = $this->msgPattern($request);
        $this->nosql->insertDocument($document);
    }

    private function msgPattern(Request $request)
    {
        return [
            'messageDate' => Carbon::now()->toDateString(),
            'messagetime' => Carbon::now()->toTimeString(),
            'taskId' => $request->input('taskCode') ?? $request->input('TaskCode'),
            'messageContent' => $request->input('message'),
            'messageType' => $request->input('messageType'),
            'replyToId' => $request->input('replyId'),
            'replyToName' => $request->input('replyName'),
            'userId' => "TNG-" . $request->input('userId'),
            'userName' => $request->input('userName'),
            'quoteToken' => $request->input('quoteToken'),
            'quoteType' => $request->input('quoteType'),
            'quoteContent' => $request->input('quoteContentInput'),
        ];
    }
}