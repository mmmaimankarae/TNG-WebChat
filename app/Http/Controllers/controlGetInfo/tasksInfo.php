<?php

namespace App\Http\Controllers\controlGetInfo;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class tasksInfo extends Controller
{    
    public static function getLastEmp($taskCode)
    {
        try {
            $result = DB::table('TASK_STATUS_HISTORY')
                        ->where('TaskHisCode', $taskCode)
                        ->orderBy('TaskHisUpdate', 'DESC')
                        ->value('TaskHisEmpCode');
            return $result;
        } catch (\Exception $e) {
            \Log::error('Get Last Emp Error (c.controlGetInfo.tasksInfo): ' . $e->getMessage());
            return false;
        }
    }

    public function checkQuota($taskCode)
    {
        try {
            $result = DB::table('TASK_STATUS_HISTORY')
                        ->where('TaskHisCode', $taskCode)
                        ->where('TaskHisStatusCode', '2')
                        ->first();
            return $result;
        } catch (\Exception $e) {
            \Log::error('Check Status for AI Error (c.controlGetInfo.tasksInfo): ' . $e->getMessage());
            return false;
        }
    }
}