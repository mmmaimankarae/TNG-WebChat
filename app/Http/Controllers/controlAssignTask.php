<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\controlGetInfo\{branchInfo, sidebarInfo, empInfo};

use App\Models\Tasks;

class controlAssignTask extends Controller
{
    public function wilAssign(Request $req) {
        $sidebarInfo = new sidebarInfo();
        $empInfo = new empInfo();
        $branchs = new branchInfo();

        $empCode = $empInfo->getEmpCode();
        $branchCode = $empInfo->getBranchCode();
        $select = false;

        $sidebarChat = $sidebarInfo->getEmpTasks($branchCode, $empCode);
        $branchs = $branchs->branchInfo($req);
        $regionThai = ['1' => 'กรุงเทพ', '2' => 'ภาคเหนือ', '3' => 'ภาคกลาง', '4' => 'ภาคอีสาน', '5' => 'ภาคใต้', '6' => 'ภาคตะวันออก'];
        $taskCode = $req->input('taskCode');
        $cusName = $req->input('cusName');

        return view('assignTask', compact('cusName', 'taskCode', 'regionThai', 'branchs', 'sidebarChat', 'select'));
    }

    public function assignTask(Request $req) {
        Tasks::assign($req->input('taskCode'), $req->input('branchCode'));
        return redirect()->route('sale-admin.new-tasks');
    }
}