<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tasks;
use App\Http\Controllers\controlGetInfo\{empInfo, sidebarInfo, msgInfo};

class controlPage extends Controller
{
    private msgInfo $msgInfo;
    private array $statusThai = [
        '2' => 'รับเรื่องแล้ว', 
        '3' => 'แนบใบเสนอราคา', 
        '4' => 'ใบกำกับภาษี', 
        '5' => 'เสร็จสิ้น'
    ];

    public function __construct(msgInfo $msgInfo)
    {
        $this->msgInfo = $msgInfo;
    }

    public function default(Request $req)
    {
        $empInfo = new empInfo();
        $sidebarInfo = new sidebarInfo();
        
        $branchCode = $req->input('branchCode', $empInfo->getBranchCode());
        $accCode = $req->input('accCode', $empInfo->getAccCode());
        $empCode = $empInfo->getEmpCode();
        $current = request()->segment(2) === 'current-tasks' ? true : false;
        if ($current) {
            $sidebarChat = $sidebarInfo->getEmpTasks($branchCode, $empCode);
        } else {
            $sidebarChat = $sidebarInfo->getEmpTasks($branchCode);
        }
        
        $select = $req->boolean('select');
        $update = $req->boolean('update');
        $taskStatus = $req->input('taskStatus');

        if ($select || $update) {
            $taskLineID = $req->input('TasksLineID');
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
                'taskStatus' => $taskStatus
            ];

            if ($update) {
                Tasks::updateStatus($req->input('TasksCode'), $taskStatus, $empCode);
            }

            return view('main', $viewData);
        }
        return view('main', compact('sidebarChat', 'select'));
    }
}
