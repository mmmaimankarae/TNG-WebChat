<?php
namespace App\Http\Controllers\controlDupView;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\controlGetInfo\empInfo;

class dupFormAuthen extends Controller
{
    public function signin() {
        $accName = '';
        $title = 'เข้าสู่ระบบ';
        $isReset = false;
        return view('signin-reset', compact('accName', 'title', 'isReset'));
    }

    public function reset() {
        $empInfo = new empInfo();
        $accName = $empInfo->getAccName();
        $title = 'เปลี่ยนรหัสผ่าน';
        $isReset = true;
        return view('signin-reset', compact('accName', 'title', 'isReset'));
    }
}
