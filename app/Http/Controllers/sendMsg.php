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
    private $empInfo;

    public function __construct(LineService $lineService, Nosql $nosql)
    {
        $this->lineService = $lineService;
        $this->nosql = $nosql;
    }
    
    public static function sendMessage(Request $request)
    {
        $replyId = $request->input('replyId');
        $message = $request->input('message');
        $file = $request->file('file');
        $quote = $request->input('quoteToken');

        $empCode = $request->input('empCode');
        $taskCode = $request->input('taskCode');

        if($empCode != tasksInfo::getLastEmp($taskCode)) {
            Tasks::updateStatus($taskCode, $request->input('taskStatus'), $empCode);
        }

        if ($file) {
            // $path = $file->store('uploads', 'public');
            // $url = url(Storage::url($path));
            // \Log::info('Image URL: ' . $url);
            
            // // dd($url);

            // $response = $this->lineService->sendImageMessage($replyId, $url, $url);

            // if ($response && $response->getStatusCode() === 200) {
            //     //$this->saveMessage($request, $url);
            //     return redirect()->back()->withInput()->with('select', true);
            // } else {
            //     return response()->json(['status' => 'error', 'message' => $response ? $response->getBody()->getContents() : 'Error occurred'], 500);
            // } 
        } else if ($quote) {
            $response = $this->lineService->quoteMessage($replyId, $message, $quote);

            if ($response && $response->getStatusCode() === 200) {
                $request->merge(['quoteType' => 'text']);
                $this->saveMessage($request);
                Tasks::setUpdateTime($taskCode);
                return redirect()->back()->withInput()->with('select', true);
            } else {
                return response()->json(['status' => 'error', 'message' => $response ? $response->getBody()->getContents() : 'Error occurred'], 500);
            }
        } else {
            $response = $this->lineService->sendMessage($replyId, $message);

            if ($response && $response->getStatusCode() === 200) {
                $request->merge(['messageType' => 'text']);
                $this->saveMessage($request);
                Tasks::setUpdateTime($taskCode);
                $request->session()->flash('TasksLineID', $replyId);
                return redirect()->back()->withInput()->with('select', true);

            } else {
                return response()->json(['status' => 'error', 'message' => $response ? $response->getBody()->getContents() : 'Error occurred'], 500);
            }
        }
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
            'taskId' => $request->input('taskCode'),
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