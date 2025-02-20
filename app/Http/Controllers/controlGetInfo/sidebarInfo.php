<?php

namespace App\Http\Controllers\controlGetInfo;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class sidebarInfo extends Controller
{
    /* Query เพื่อดึงข้อมูลช่องแชททั้งหมดที่เคยเกิดขึ้นกับ Branch นี้ */
    /* ดึงข้อมูลเฉพาะที่พนักงานคนนี้รับเรื่องไว้ */
    public function getEmpTasks($branchCode, $empCode = null) {
        try {
            $rankedTask = DB::table('TASK as T')
            /* JOIN */
            ->join('CUSTOMER_LINE as CL', 'T.TaskLineID', '=', 'CL.CusLineID')
            ->join('CUSTOMER as C', 'CL.CusLineCode', '=', 'C.CusCode')
            ->join('BRANCH as B', 'T.TaskBrchCode', '=', 'B.BrchCode')
            ->leftJoin('TASK_STATUS_HISTORY as TH', 'T.TaskCode', '=', 'TH.TaskHisCode')
            /* SELECT */
            ->select(
                'T.TaskLineID',
                'T.TaskCode',
                'C.CusName',
                'C.CusCode',
                'T.TaskUpdate',
                'T.TaskStatusCode',
                'CL.CusLineType',
                /* ดึงข้อมูลพนักงานที่รับเรื่องก่อนหน้า */
                DB::raw('LAST_VALUE(TH.TaskHisEmpCode) OVER (PARTITION BY T.TaskLineID ORDER BY T.TaskUpdate DESC) AS PrevEmpCode'),
                /* เรียงลำดับจาก status 11, 6 ก่อน แล้วค่อยเรียงตามวันที่ */
                DB::raw('ROW_NUMBER() OVER (
                    PARTITION BY T.TaskLineID 
                    ORDER BY 
                        CASE 
                            WHEN T.TaskStatusCode = 11 THEN 3
                            WHEN T.TaskStatusCode = 6 THEN 2
                            WHEN TH.TaskHisEmpCode IS NULL THEN 0
                            ELSE 1 
                        END, 
                        T.TaskUpdate DESC
                ) AS rn')
            )
            ->where('B.BrchCode', $branchCode); /* ระบุเฉพาะ Branch ที่ต้องการ */

            $results = DB::table(DB::raw("({$rankedTask->toSql()}) as RT"))
            ->mergeBindings($rankedTask)
            ->leftJoin('ACCOUNT as A', 'RT.PrevEmpCode', '=', 'A.AccName')
            ->select(
                'RT.TaskLineID',
                'RT.TaskCode',
                'RT.TaskStatusCode',
                'RT.CusName',
                'RT.CusCode',
                'RT.TaskUpdate',
                'RT.CusLineType',
                'A.AccName as PrevEmpAccName'
            )
            ->where('RT.rn', 1)
            ->when($empCode, function ($query, $empCode) { /* ระบุเฉพาะพนักงาน */
                return $query->where('RT.PrevEmpCode', $empCode);
            })
            ->orderByRaw('CASE WHEN RT.TaskStatusCode = 6 THEN 1 ELSE 0 END')
            ->orderBy('RT.TaskUpdate', 'DESC')
            ->get();
            
            return $results;
        } catch (\Exception $e) {
            \Log::error('sidebarInfo, Database error: ' . $e->getMessage());
            return false;
        }
    }
}