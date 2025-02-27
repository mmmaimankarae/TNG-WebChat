<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;

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
            'newPass' => 'required'
            ],
            [ /* ข้อความแจ้งเตือน */
            'oldPass.required' => '* กรุณากรอกรหัสผ่านเดิม',
            ]
        );
        return $this->authenCheck($req);
    }

    /* ตรวจสอบความถูกต้องของการเข้าสู่ระบบ */
    public function authenCheck(Request $req){
        $accName = $req->input('accName') ?? $req->input('forgotAcc');
        $password = $req->input('password') ?? null;
        $newPass = $req->input('newPass') ?? null;
        $oldPass = $req->input('oldPass') ?? null;
        $action = $req->input('actionFor');

        $account = $this->haveAccount($accName);

        /* ถ้ามี username ที่ระบุ */
        if($account) {
            /* ตั้ง Password ใหม่ "กรณีลืม Password" */
            if ($password == null && $newPass == null) {
                $reset = Auth::resetPassword($accName);
                if ($reset) {
                    return redirect('/');
                }
                else {
                    return redirect()->back()->withErrors(['forgotAcc' => 'เกิดข้อผิดพลาดในการตั้งรหัสผ่านใหม่']);
                }
            }
            /* เช็ค Password เพื่อเข้าสู่ระบบ */
            else if (Hash::check($password, $account->AccPass)) {
                $payload = [
                    'accCode' => trim($account->AccCode),
                    'roleCode' => trim($account->AccRoleCode),
                    'branchCode' => trim($account->EmpBrchCode),
                    'iat' => time(),
                    'exp' => time() + (8 * 3600)
                ];
                /* encode Info ด้วย JWT */
                $jwt = JWT::encode($payload, env('JWT_SECRET'), 'HS256');

                Cookie::queue(Cookie::make('access-jwt', $jwt, (8 * 60), '/', null, false, true));
                return redirect()->route('sale-admin.new-tasks');
            }
            /* เช็ค Password เก่า เพื่อเปลี่ยน Password ใหม่ */
            else if ($oldPass != null) {
                if (Hash::check($oldPass, $account->AccPass)) {
                    $reset = Auth::resetPassword($accName, $newPass);
                    return $this->authenSignout();
                }
                else {
                    return redirect()->back()->withErrors(['errorInput' => 'รหัสผ่านเก่าไม่ถูกต้อง']);
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
        else if ($password != null) {
            return redirect()->back()->withErrors(['errorInput' => 'ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง']);
        }
        /* ถ้าไม่มี username ที่ระบุ */
        else {
            return redirect()->back()->withErrors(['accName' => 'ชื่อผู้ใช้งานไม่ถูกต้อง']);
        }
    }

    /* ตรวจสอบว่ามี Account หรือไม่ */
    private function haveAccount($accName)
    {
        try{
            $account = DB::table('ACCOUNT as A')
                ->leftJoin('EMPLOYEE as E', 'A.AccName', '=', 'E.EmpCode')
                ->where('A.AccName', $accName)
                ->where(function ($query) {
                    $query->where('E.EmpResign', '!=', 'Y')
                        ->orWhereNull('E.EmpResign');
                })
                ->select('A.*', 'E.*')
                ->first();
            return $account;
        } catch (\Exception $e) {
            \Log::error('Database error: ' . $e->getMessage());
            return false;
        }
    }

    /* ออกจากระบบ */
    public function authenSignout(){
        Cookie::queue(Cookie::forget('access-jwt'));
        return redirect('/');
    }
}