<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\controlGetInfo\branchInfo;

use App\Models\Tasks;

class controlAssignTask extends Controller
{
    public function wilAssign(Request $req) {
        $branchs = new branchInfo();
        $branchs = $branchs->branchInfo($req);
        $regionThai = ['1' => 'กรุงเทพ', '2' => 'ภาคเหนือ', '3' => 'ภาคกลาง', '4' => 'ภาคอีสาน', '5' => 'ภาคใต้', '6' => 'ภาคตะวันออก'];
        $taskCode = $req->input('taskCode');

        return view('assignTask', compact('taskCode', 'regionThai', 'branchs'));
    }

    public function assignTask(Request $req) {
        Tasks::assign($req->input('taskCode'), $req->input('branchCode'));
        return redirect()->route('sale-admin.new-tasks');
    }
}