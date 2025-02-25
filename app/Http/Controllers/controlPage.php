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
        
        /* setting ค่า default ที่จำเป็น */
        $branchCode = $empInfo->getBranchCode();
        $accCode = $empInfo->getAccCode();
        $empCode = $empInfo->getAccName()->AccName;
        $current = request()->segment(2) === 'current-tasks';
        
        $sidebarChat = $current 
            ? $sidebarInfo->getEmpTasks($branchCode, $empCode) 
            : $sidebarInfo->getEmpTasks($branchCode);
        $showchat = session('showchat', $req->boolean('showchat'));
        $updateStatus = $req->boolean('updateStatus');
        $taskStatus = $req->input('taskStatus');

        /* เริ่มต้นหน้า แสดงแค่ sidebar */
        if (!$showchat && !$updateStatus) {
            return view('main', compact('sidebarChat', 'showchat'));
        }
        $taskCode = $req->input('taskCode');
        $taskLineID = session('taskLineID', $req->input('taskLineID'));

        $messages = $this->msgInfo->getMsgByUser($taskLineID);

        if ($showchat) { /* !!!!! */
            /* ส่วนของข้อมูลใน status */
            $checkQuota = $this->tasksInfo->checkQuota($taskCode) ?? 'not found';
            $amountInfo = $this->amountInfo->amountInfo($taskCode);
        }

        if ($updateStatus) {
            $this->tasksModel->updateStatus($taskCode, $taskStatus, $empCode);
            if ($taskStatus === '6') {
                $req->merge(['file' => null]);
                $req->merge(['message' => 'ขอบคุญสำหรับการสั่งซื้อ ทางเรากำลังจัดส่งสินค้าให้ คุณลูกค้ารอรับได้เลย🙏']);
                $req->merge(['replyId' => $taskLineID]);

                $this->sendMsg->sendMessage($req);
                $showchat = false;
                return view('main', compact('sidebarChat', 'showchat'));
            }

            if ($taskStatus === '4') {
                /* ส่งข้อความแจ้งยอดชำระ */
                $req->merge(['message' => 
                'ยอดชำระ ' . $req->input('totalPrice') . ' บาท ขอบคุณครับ']);
                $req->merge(['replyId' => $req->input('replyId')]);
                $this->sendMsg->sendMessage($req);

                /* รูปช่องทางการชำระ */
                $region = $empInfo->getRegionCode($branchCode);
                if ($region === '1') {
                    $file_path = 'public/payments/HO.JPEG';
                } else {
                    $file_path = 'public/payments/' . $branchCode . '.jpeg';
                }

                $req->merge(['file' => "path"]);
                $req->merge(['file_path' => $file_path]);
                $this->sendMsg->sendMessage($req);
                $showchat = false;
                return view('main', compact('sidebarChat', 'showchat'));
            }
        }

        return view('main', [
            'sidebarChat' => $sidebarChat,
            'showchat' => $showchat,
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
