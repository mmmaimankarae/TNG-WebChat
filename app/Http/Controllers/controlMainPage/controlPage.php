<?php
namespace App\Http\Controllers\controlMainPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tasks;

use App\Http\Controllers\controlMainPage\sendMsg;
use App\Http\Controllers\controlGetInfo\{empInfo, sidebarInfo, msgInfo, tasksInfo, quotationInfo, tableInfo};

class controlPage extends Controller
{
    private msgInfo $msgInfo;
    private sendMsg $sendMsg;
    private Tasks $tasksModel;
    private tasksInfo $tasksInfo;
    private quotationInfo $quotationInfo;
    private tableInfo $tableInfo;
    private array $statusThai = [
        '2' => 'รับเรื่องแล้ว', 
        '3' => 'แนบใบเสนอราคา',
        '4' => 'ชำระเงิน', 
        '5' => 'ใบกำกับภาษี', 
        '6' => 'เสร็จสิ้น'
    ];

    public function __construct(msgInfo $msgInfo, sendMsg $sendMsg, Tasks $tasksModel, tasksInfo $tasksInfo, quotationInfo $quotationInfo)
    {
        $this->msgInfo = $msgInfo;
        $this->sendMsg = $sendMsg;
        $this->tasksModel = $tasksModel;
        $this->tasksInfo = $tasksInfo;
        $this->quotationInfo = $quotationInfo;
    }

    public function default(Request $request)
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
        $showchat = session('showchat', $request->boolean('showchat'));
        $updateStatus = $request->boolean('updateStatus');
        $taskStatus = $request->input('taskStatus');

        /* เริ่มต้นหน้า แสดงแค่ sidebar */
        if (!$showchat && !$updateStatus) {
            return view('main', compact('sidebarChat', 'showchat'));
        }
        $taskCode = $request->input('taskCode');
        $taskLineID = session('taskLineID', $request->input('taskLineID'));

        $messages = $this->msgInfo->getMsgByUser($taskLineID);

        if ($showchat) { /* !!!!! */
            /* ส่วนของข้อมูลใน status */
            $checkQuota = $this->tasksInfo->checkQuota($taskCode) ?? 'not found';
            $quotationInfo = $this->quotationInfo->quotationInfo($taskCode);
        }

        if ($updateStatus) {
            $this->tasksModel->updateStatus($taskCode, $taskStatus, $empCode);
            if ($taskStatus === '6') {
                $request->merge(['file' => null]);
                $request->merge(['message' => 'ขอบคุญสำหรับการสั่งซื้อ ทางเรากำลังจัดส่งสินค้าให้ คุณลูกค้ารอรับได้เลย🙏']);
                $request->merge(['replyId' => $taskLineID]);

                $this->sendMsg->sendMessage($request);
                session()->flash('showchat', false);
                return view('main', compact('sidebarChat', 'showchat'));
            }

            if ($taskStatus === '4') {
                $tableInfo = new tableInfo();
                $payment_desc = $tableInfo->paymentDescInfo();

                $payment = str_replace('** จำนวนเงิน **',  $request->input('totalPrice') , $payment_desc);

                /* ส่งข้อความแจ้งยอดชำระ */
                $request->merge(['message' => $payment]);
                $request->merge(['replyId' => $request->input('replyId')]);
                $this->sendMsg->sendMessage($request);

                /* รูปช่องทางการชำระ */
                $region = $empInfo->getRegionCode($branchCode);
                if ($region === '1') {
                    $file_path = 'public/payments/HO.JPEG';
                } else {
                    $file_path = 'public/payments/' . $branchCode . '.jpeg';
                }

                $request->merge(['file' => "path"]);
                $request->merge(['file_path' => $file_path]);
                $this->sendMsg->sendMessage($request);
                session()->flash('showchat', false);
                return view('main', compact('sidebarChat', 'showchat'));
            }
        }

        return view('main', [
            'sidebarChat' => $sidebarChat,
            'showchat' => $showchat,
            'messages' => $messages,
            'roleCode' => $empInfo->getRole(),
            'taskCode' => $taskCode,
            'cusCode' => $request->input('cusCode'),
            'cusName' => $request->input('cusName'),
            'taskLineID' => $taskLineID,
            'statusThai' => $this->statusThai,
            'taskStatus' => $taskStatus,
            'accName' => $empInfo->getAccName(),
            'accCode' => $accCode,
            'empCode' => $empCode,
            'branchCode' => $branchCode,
            'checkQuota' => $checkQuota,
            'amountInfo' => $quotationInfo
        ]);
    }
}
