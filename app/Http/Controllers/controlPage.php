<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tasks;
use App\Http\Controllers\sendMsg;
use App\Http\Controllers\controlGetInfo\{empInfo, sidebarInfo, msgInfo, tasksInfo, amountInfo};

class controlPage extends Controller
{
    private msgInfo $msgInfo;
    private sendMsg $sendMsg;
    private Tasks $tasksModel;
    private tasksInfo $tasksInfo;
    private amountInfo $amountInfo;
    private array $statusThai = [
        '2' => 'รับเรื่องแล้ว', 
        '3' => 'แนบใบเสนอราคา',
        '4' => 'ชำระเงิน', 
        '5' => 'ใบกำกับภาษี', 
        '6' => 'เสร็จสิ้น'
    ];

    public function __construct(msgInfo $msgInfo, sendMsg $sendMsg, Tasks $tasksModel, tasksInfo $tasksInfo, amountInfo $amountInfo)
    {
        $this->msgInfo = $msgInfo;
        $this->sendMsg = $sendMsg;
        $this->tasksModel = $tasksModel;
        $this->tasksInfo = $tasksInfo;
        $this->amountInfo = $amountInfo;
    }

    public function default(Request $req)
    {
        $empInfo = new empInfo();
        $sidebarInfo = new sidebarInfo();
        
        $branchCode = $req->input('branchCode', $empInfo->getBranchCode());
        $accCode = $req->input('accCode', $empInfo->getAccCode());
        $empCode = $empInfo->getAccName()->AccName;
        $current = request()->segment(2) === 'current-tasks';
        
        $sidebarChat = $current 
            ? $sidebarInfo->getEmpTasks($branchCode, $empCode) 
            : $sidebarInfo->getEmpTasks($branchCode);
        
        $select = $req->boolean('select') || session('select');
        $update = $req->boolean('update');
        $taskStatus = $req->input('taskStatus');

        if (!$select && !$update) {
            return view('main', compact('sidebarChat', 'select'));
        }
        
        $taskCode = $req->input('TasksCode');
        $taskLineID = $req->input('TasksLineID') ?? session('TasksLineID');
        $messages = $this->msgInfo->getMsgByUser($taskLineID);
        $checkQuota = $this->tasksInfo->checkQuota($taskCode) ?? 'not found';
        $amountInfo = $this->amountInfo->amountInfo($taskCode);

        if ($update) {
            $this->tasksModel->updateStatus($taskCode, $taskStatus, $empCode);
            if ($taskStatus === '6') {
                $this->sendMsg->sendMessage(new Request([
                    'file' => null,
                    'message' => 'ขอบคุณสำหรับการสั่งซื้อ ทางเรากำลังจัดส่งสินค้าให้ คุณลูกค้ารอรับได้เลย🙏',
                    'replyId' => $taskLineID
                ]));
                return view('main', compact('sidebarChat', 'select'));
            }
        }

        return view('main', [
            'sidebarChat' => $sidebarChat,
            'select' => $select,
            'messages' => $messages,
            'roleCode' => $empInfo->getRole(),
            'taskCode' => $taskCode,
            'cusCode' => $req->input('cusCode'),
            'cusName' => $req->input('cusName'),
            'taskLineID' => $taskLineID,
            'statusThai' => $this->statusThai,
            'taskStatus' => $taskStatus,
            'accName' => $empInfo->getAccName(),
            'accCode' => $accCode,
            'empCode' => $empCode,
            'branchCode' => $branchCode,
            'checkQuota' => $checkQuota,
            'amountInfo' => $amountInfo
        ]);
    }
}
