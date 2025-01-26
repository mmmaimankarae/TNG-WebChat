<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;

use Illuminate\Http\Request;

use App\Models\authenticate as Auth;

class controlAuthenticate extends Controller
{
    /* ตรวจสอบการกรอกข้อมูล เพื่อเข้าสู่ระบบ */
    public function authValidate(Request $req){
        $req->validate(
            [ /* ตรวจสอบ req */
            'accName' => 'required',
            'password' => 'required'
            ],
            [ /* ข้อความแจ้งเตือน */
            'accName.required' => '* กรุณากรอกชื่อผู้ใช้',
            'password.required' => '* กรุณากรอกรหัสผ่าน'
            ]
        );
        return $this->authenCheck($req);
    }

    /* ตรวจสอบการกรอกข้อมูล username สำหรับการลืมรหัสผ่าน */
    public function forgotPopupValidate(Request $req){
        $req->validate(
            [ /* ตรวจสอบ req */
            'forgotAcc' => 'required'
            ],
            [ /* ข้อความแจ้งเตือน */
            'forgotAcc.required' => '* กรุณากรอกชื่อผู้ใช้'
            ]
        );
        return $this->authenCheck($req);
    }

    /* ตรวจสอบการกรอกข้อมูลเปลี่ยนรหัสผ่าน */
    public function resetPopupValidate(Request $req){
        $req->validate(
            [ /* ตรวจสอบ req */
            'oldPass' => 'required',
            'password' => 'required'
            ],
            [ /* ข้อความแจ้งเตือน */
            'oldPass.required' => '* กรุณากรอกรหัสผ่านเดิม',
            'password.required' => '* กรุณากรอกรหัสผ่านใหม่'
            ]
        );
        return $this->authenCheck($req);
    }

    public function authenCheck(Request $req){
        $accName = $req->input('accName') ?? $req->input('forgotAcc');
        $password = $req->input('password') ?? null;
        $oldPass = $req->input('oldPass') ?? null;
        $action = $req->input('actionFor');

        $account = DB::table('ACCOUNT')
                    ->where('AccName', $accName)
                    ->first();

        /* ถ้ามี username ที่ระบุ */
        if($account) {
            /* เช็ค username เพื่อจะ reset รหัส "กรณีลืม Password" */
            if ($password == null) {
                $reset = Auth::resetPassword($accName);
                if ($reset) {
                    return redirect()->back()->withErrors(['successReset' => 'ระบบทำการตั้งรหัสผ่านใหม่ให้คุณแล้ว กดปุ่ม "ปิด" หรือ "ยกเลิก" และใส่รหัสที่ตกลงกันไว้']);
                }
                else {
                    return redirect()->back()->withErrors(['forgotAcc' => 'เกิดข้อผิดพลาดในการตั้งรหัสผ่านใหม่']);
                }
            }
            /* เช็ครหัสผ่าน */
            else if (Hash::check($password, $account->AccPass)) {
                $branchCode = $this->getBranchCode($accName);
                $payload = [
                    'accCode' => $account->AccCode,
                    'empCode' => $account->AccEmpCode,
                    'roleCode' => $account->AccRoleCode,
                    'branchCode' => $branchCode,
                    'iat' => time(),
                    'exp' => time() + (8 * 3600)
                ];
                $jwt = JWT::encode($payload, env('JWT_SECRET'), 'HS256');
                setcookie('access-jwt', $jwt, time() + (8 * 3600), '/', '', false, true);
                return redirect('/welcome');
            }
            /* เช็ครหัสผ่านเก่า เพื่อเปลี่ยน Password ไหม */
            else if ($oldPass != null) {
                if (Hash::check($oldPass, $account->AccPass)) {
                    $reset = Auth::resetPassword($accName, $password);
                    if ($reset) {
                        $this->authenSignout();
                    }
                }
                else {
                    return redirect()->back()->withErrors(['oldPass' => 'เกิดข้อผิดพลาดในการตั้งรหัสผ่านใหม่']);
                }
            }
            else {
                return redirect()->back()->withErrors(['errorInput' => 'ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง']);
            }
        }
        /* ถ้าไม่มี username ที่ระบุ และระบุว่าลืม Password */
        else if ($action == 'forgotPass') {
            return redirect()->back()->withErrors(['forgotAcc' => 'ชื่อผู้ใช้งานไม่ถูกต้อง']);
        }
        /* ถ้าไม่มี username ที่ระบุ */
        return redirect()->back()->withErrors(['errorInput' => 'ชื่อผู้ใช้งานไม่ถูกต้อง']);
    }
    
    public function authenSignout(){
        setcookie('access-jwt', '', time() - 3600, '/', '', false, true);
        return redirect('/');
    }

    private function getBranchCode($accName)
    {
        $branchCode = DB::table('EMPLOYEE')
                    ->join('ACCOUNT', 'EMPLOYEE.EmpCode', '=', 'ACCOUNT.AccEmpCode')
                    ->where('ACCOUNT.AccName', $accName)
                    ->select('EMPLOYEE.EmpBrchCode')
                    ->first();
        return $branchCode;
    }
}