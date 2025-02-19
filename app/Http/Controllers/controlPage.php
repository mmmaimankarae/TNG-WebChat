<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tasks;
use App\Http\Controllers\sendMsg;
use App\Http\Controllers\controlGetInfo\{empInfo, sidebarInfo, msgInfo};

class controlPage extends Controller
{
    private msgInfo $msgInfo;
    private sendMsg $sendMsg;
    private Tasks $tasksModel;
    private array $statusThai = [
        '2' => 'รับเรื่องแล้ว', 
        '3' => 'แนบใบเสนอราคา',
        '4' => 'ชำระเงิน', 
        '5' => 'ใบกำกับภาษี', 
        '6' => 'เสร็จสิ้น'
    ];

    public function __construct(msgInfo $msgInfo, sendMsg $sendMsg, Tasks $tasksModel)
    {
        $this->msgInfo = $msgInfo;
        $this->sendMsg = $sendMsg;
        $this->tasksModel = $tasksModel;
    }

    public function default(Request $req)
    {
        $empInfo = new empInfo();
        $sidebarInfo = new sidebarInfo();
        $tasksModel = new Tasks();
        $select = false;
        
        $branchCode = $req->input('branchCode', $empInfo->getBranchCode());
        $accCode = $req->input('accCode', $empInfo->getAccCode());
        $empCode = $empInfo->getAccName();
        $current = request()->segment(2) === 'current-tasks' ? true : false;
        if ($current) {
            $sidebarChat = $sidebarInfo->getEmpTasks($branchCode, $empCode);
        } else {
            $sidebarChat = $sidebarInfo->getEmpTasks($branchCode);
        }
        $select = $req->boolean('select') || session('select');
        $update = $req->boolean('update');
        $taskStatus = $req->input('taskStatus');

        if ($select || $update) {
            $taskLineID = $req->input('TasksLineID') ?? session('TasksLineID');
            $messages = $this->msgInfo->getMsgByUser($taskLineID);
            
            $viewData = [
                'sidebarChat' => $sidebarChat,
                'select' => $select,
                'messages' => $messages,
                'roleCode' => $empInfo->getRole(),
                'taskCode' => $req->input('TasksCode'),
                'cusCode' => $req->input('cusCode'),
                'cusName' => $req->input('cusName'),
                'taskLineID' => $taskLineID,
                'statusThai' => $this->statusThai,
                'taskStatus' => $taskStatus,
                'accName' => $empInfo->getAccName(),
                'accCode' => $accCode,
                'empCode' => $empCode,
            ];

            if ($update) {
                $taskCode = $req->input('TasksCode');
                if ($taskStatus === '6') {
                    $req->merge(['file' => null]);
                    $req->merge(['message' => 'ขอบคุญสำหรับการสั่งซื้อ ทางเรากำลังจัดส่งสินค้าให้ คุณลูกค้ารอรับได้เลย🙏']);
                    $req->merge(['replyId' => $taskLineID]);
                    
                    $this->sendMsg->sendMessage($req);
                    $this->tasksModel->updateStatus($taskCode, $taskStatus, $empCode);
                    $select = false;
                    return view('main', compact('sidebarChat', 'select'));
                }
                $this->tasksModel->updateStatus($taskCode, $taskStatus, $empCode);
            }

            return view('main', $viewData);
        }
        return view('main', compact('sidebarChat', 'select'));
    }
}
