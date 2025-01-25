<?php

namespace App\Http\Controllers\controlForm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class controlFormAuthen extends Controller
{
    public function signin() {
        $accName = '';
        $title = 'เข้าสู่ระบบ';
        $isReset = false;
        return view('signin-reset', compact('accName', 'title', 'isReset'));
    }

    public function reset(Request $req) {
        $accName = $req->input('accName');
        $title = 'เปลี่ยนรหัสผ่าน';
        $isReset = true;
        return view('signin-reset', compact('accName', 'title', 'isReset'));
    }
}
