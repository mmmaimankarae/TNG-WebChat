<?php

namespace App\Http\Controllers\controlDupView;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\controlGetInfo\empInfo;

class dupDetailMsg extends Controller
{
    private $empInfo;
    public function detail(Request $request) {
        $taskCode = $request->input('taskCode');
        $cusCode = $request->input('cusCode');
        $cusName = $request->input('cusName');
        $status = $request->input('status');

        $this->empInfo = new empInfo();
        $roleCode = $this->empInfo->getRole();
        return view('detailMsg', compact('taskCode', 'cusCode', 'cusName', 'status', 'roleCode'));
    }
    
}