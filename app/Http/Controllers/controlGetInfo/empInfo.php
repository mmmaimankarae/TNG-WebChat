<?php

namespace App\Http\Controllers\controlGetInfo;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class empInfo extends Controller
{
    private $decoded;

    public function __construct() {
        $this->decoded = request()->attributes->get('decoded');
    }

    public function getAccCode() {
        $accCode = $this->decoded->accCode;
        return $accCode;
    }

    public function getAccName() {
        $accCode = $this->getAccCode();
        $accName = $this->getNameFromDB($accCode);
        return $accName;
    }

    private function getNameFromDB($accCode) {
        $accName = DB::table('ACCOUNT')
            ->where('accCode', $accCode)
            ->select('AccName')
            ->first();
        return $accName;
    }

    public function getBranchCode() {
        $branchCode = $this->decoded->branchCode;
        return $branchCode;
    }

    public function getRole() {
        $roleCode = $this->decoded->roleCode;
        return $roleCode;
    }

    public function checkEmpBranch($accName) {
        try {
            $results = DB::table('ACCOUNT as A')
            ->leftJoin('EMPLOYEE as E', 'A.AccEmpCode', '=', 'E.EmpCode')
            ->leftJoin('BRANCH as B', 'E.EmpBrchCode', '=', 'B.BrchCode')
            ->select('A.AccName' ,'B.BrchCode')
            ->where('A.AccName', $accName)
            ->get();
            return $results->pluck('BrchCode');
        } catch (\Exception $e) {
            \Log::error('Find Error (c.controlGetInfo.empInfo): ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function getRegionCode() {
        $branchCode = $this->getBranchCode();
        try {
            $results = DB::table('BRANCH')
            ->select('BrchRegionCode')
            ->where('BrchCode', $branchCode)
            ->get();
            return $results->pluck('BrchRegionCode');
        } catch (\Exception $e) {
            \Log::error('Find Error (c.controlGetInfo.empInfo): ' . $e->getMessage());
            return $e->getMessage();
        }
    }
}