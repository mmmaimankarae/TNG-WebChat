<?php

namespace App\Http\Controllers\controlGetInfo;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class tasksInfo extends Controller
{
    public function getTasksInfo($brch, $accCode = null) {
        /* ดึงข้อมูล Tasks ที่ลูกค้าส่งข้อความมา */
        $query = DB::table('TASKS as t')
            ->select([
            't.TasksCode',
            'c.CusCode',
            'c.CusName',
            'c.CusGroupCode',
            't.TasksStatusCode',
            't.TasksUpdate',
            'th.TaskHisArriveTime'
        ])
        ->join('CUSTOMER_LINE as l', 't.TasksCusCode', '=', 'l.CustomerCode')
        ->join('CUSTOMER as c', 'c.CusCode', '=', 'l.CustomerCode')
        ->leftJoin('TASKSTATUS_HISTORICAL as th', 'th.TaskHisCode', '=', 't.TasksCode')
        ->leftJoin('ACCOUNT as a', 'th.TaskHisEmpCusCode', '=', 'a.AccEmpCode')
        ->whereNotIn('t.TasksStatusCode', [2, 3, 6, 7, 8, 11, 12, 13, 14, 15])
        ->where('t.TasksBrchCode', '=', $brch)
        ->where(function ($q) {
            $q->whereNull('th.TaskHisArriveTime')
              ->orWhere('th.TaskHisArriveTime', '<', now());
        });

        /* สำหรับ current-pages */
        if ($accCode) {
            $query->where('a.AccCode', '=', $accCode);
        }

        $results = $query->groupBy(
                    't.TasksCode',
                    'c.CusCode',
                    'c.CusName',
                    'c.CusGroupCode',
                    't.TasksStatusCode',
                    't.TasksUpdate',
                    'th.TaskHisArriveTime'
                )
                ->orderBy('t.TasksUpdate')
                ->get();

        return $results;
    }

    private function getPartiName($taskCode) {
        /* ดึงข้อมูลผู้รับผิดชอบงาน */
        $result = DB::table('TASKSTATUS_HISTORICAL as th')
                    ->join('ACCOUNT as a', 'th.TaskHisEmpCusCode', '=', 'a.AccEmpCode')
                    ->where('th.TaskHisCode', $taskCode)
                    ->groupBy('a.AccName', 'th.TaskHisUpdate')
                    ->orderByDesc('th.TaskHisUpdate')
                    ->value('a.AccName');
        return $result;
    }
    
    public static function getLastEmp($taskCode)
    {
        $result = DB::table('TASKSTATUS_HISTORICAL')
                    ->where('TaskHisCode', $taskCode)
                    ->orderBy('TaskHisUpdate', 'DESC')
                    ->value('TaskHisEmpCusCode');
        return $result;
    }
}