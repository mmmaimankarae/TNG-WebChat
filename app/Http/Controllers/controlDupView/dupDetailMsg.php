<?php

namespace App\Http\Controllers\controlDupView;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class dupDetailMsg extends Controller
{
    public function detail(Request $request) {
        $taskCode = $request->input('taskCode');
        $cusCode = $request->input('cusCode');
        $cusName = $request->input('cusName');
    
        return view('detailMsg', compact('taskCode', 'cusCode', 'cusName'));
    }
    
}