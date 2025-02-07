<?php

namespace App\Http\Controllers\controlGetInfo;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class sidebarInfo extends Controller
{
    /* Query เพื่อดึงข้อมูลช่องแชททั้งหมดที่เคยเกิดขึ้นกับ Branch นี้ */
    /* ดึงข้อมูลเฉพาะที่พนักงานคนนี้รับเรื่องไว้ */
    public function getEmpTasks($branchCode, $empCode = null) {
        $rankedTasks = DB::table('TASKS as T')
        ->join('CUSTOMER_LINE as CL', 'T.TasksLineID', '=', 'CL.CustomerLineID')
        ->join('CUSTOMER as C', 'CL.CustomerCode', '=', 'C.CusCode')
        ->join('BRANCH as B', 'T.TasksBrchCode', '=', 'B.BrchCode')
        ->leftJoin('TASKSTATUS_HISTORICAL as TH', 'T.TasksCode', '=', 'TH.TaskHisCode')
        ->select(
            'T.TasksLineID',
            'T.TasksCode',
            'C.CusName',
            'C.CusCode',
            'T.TasksUpdate',
            'T.TasksStatusCode',
            'CL.cusLineType',
            DB::raw('MAX(TH.TaskHisEmpCusCode) OVER (PARTITION BY T.TasksLineID ORDER BY T.TasksUpdate) AS PrevEmpCode'),
            DB::raw('ROW_NUMBER() OVER (
                PARTITION BY T.TasksLineID 
                ORDER BY 
                    CASE 
                        WHEN T.TasksStatusCode = 5 THEN 2
                        WHEN TH.TaskHisEmpCusCode IS NULL THEN 0
                        ELSE 1 
                    END, 
                    T.TasksUpdate DESC
            ) AS rn')
        )
        ->where('B.BrchCode', $branchCode);

        $results = DB::table(DB::raw("({$rankedTasks->toSql()}) as RT"))
        ->mergeBindings($rankedTasks)
        ->leftJoin('ACCOUNT as A', 'RT.PrevEmpCode', '=', 'A.AccEmpCode')
        ->select(
            'RT.TasksLineID',
            'RT.TasksCode',
            'RT.TasksStatusCode',
            'RT.CusName',
            'RT.CusCode',
            'RT.TasksUpdate',
            'RT.cusLineType',
            'A.AccName as PrevEmpAccName'
        )
        ->where('RT.rn', 1)
        ->when($empCode, function ($query, $empCode) {
            return $query->where('RT.PrevEmpCode', $empCode);
        })
        ->orderByRaw('CASE WHEN RT.TasksStatusCode = 5 THEN 1 ELSE 0 END')
        ->orderBy('RT.TasksUpdate', 'DESC')
        ->get();
        
        return $results;
    }
}