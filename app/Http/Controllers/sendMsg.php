<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LineService;
use App\Models\Nosql;
use Carbon\Carbon;
use App\Models\Tasks;
use App\Http\Controllers\controlGetInfo\tasksInfo;

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
    
    public function sendMessage(Request $request)
    {
        $replyId = $request->input('replyId');
        $message = $request->input('message');
        $empCode = $request->input('empCode');
        $taskCode = $request->input('taskCode');

        if($empCode != tasksInfo::getLastEmp($taskCode)) {
            Tasks::updateStatus($taskCode, $request->input('taskStatus'), $empCode);
        }

        $response = $this->lineService->sendMessage($replyId, $message);

        if ($response && $response->getStatusCode() === 200) {
            $this->saveMessage($request);
            return redirect()->back()->with('select', true);
        } else {
            return response()->json(['status' => 'error', 'message' => $response ? $response->getBody()->getContents() : 'Error occurred'], 500);
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
        ];
    }
}