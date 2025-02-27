<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Tasks;
use App\Http\Controllers\controlGetInfo\{branchInfo, sidebarInfo, empInfo};

class controlAssignTask extends Controller
{
    public function wilAssign(Request $request) {
        $sidebarInfo = new sidebarInfo();
        $empInfo = new empInfo();
        $branchs = new branchInfo();

        $empCode = $empInfo->getAccName()->AccName;
        $branchCode = $empInfo->getBranchCode();
        $select = false;

        $sidebarChat = $sidebarInfo->getEmpTasks($branchCode, $empCode);
        $branchs = $branchs->branchInfo($request);
        $regionThai = ['1' => 'กรุงเทพ', '2' => 'ภาคเหนือ', '3' => 'ภาคกลาง', '4' => 'ภาคใต้', '5' => 'ภาคอีสาน', '6' => 'ภาคตะวันออก'];
        $taskCode = $request->input('taskCode');
        $cusName = $request->input('cusName');

        return view('sale-admin.assignTask', compact('cusName', 'taskCode', 'regionThai', 'branchs', 'sidebarChat', 'select'));
    }

    public function assignTask(Request $request) {
        $tasksModel = new Tasks();
        $tasksModel->assign($request->input('taskCode'), $request->input('branchCode'));
        return redirect()->route('sale-admin.new-tasks');
    }
}