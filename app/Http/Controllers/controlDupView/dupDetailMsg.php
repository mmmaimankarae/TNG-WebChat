<?php

namespace App\Http\Controllers\controlDupView;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\controlGetInfo\empInfo;
use App\Http\Controllers\controlGetInfo\msgInfo;

class dupDetailMsg extends Controller
{
    private $empInfo;
    private $msgInfo;

    public function __construct(msgInfo $msgInfo)
    {
        $this->msgInfo = $msgInfo;
    }

    public function detail(Request $request) {
        $taskCode = $request->input('taskCode');
        $cusCode = $request->input('cusCode');
        $cusName = $request->input('cusName');
        $status = $request->input('status');

        $this->empInfo = new empInfo();
        $roleCode = $this->empInfo->getRole();

        $messages = $this->msgInfo->getMsg($taskCode);
        return view('detailMsg', compact('taskCode', 'cusCode', 'cusName', 'status', 'roleCode', 'messages'));
    }
    
}