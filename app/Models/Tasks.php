<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\Models\Nosql;
use Carbon\Carbon;

class Tasks extends Model
{   
    public static function assign($taskCode, $branchCode)
    {
        $newTask = self::getNewTask($branchCode);
        $updated = DB::table('TASKS')
        ->where('TasksCode', $taskCode)
        ->update([
            'TasksCode' => $newTask, 
            'TasksBrchCode' => $branchCode, 
            'TasksStatusCode' => '1', 
            'TasksUpdate' => Carbon::now()
        ]);   
        self::setTaskHis($taskCode, NULL, '1');
        return $updated;
    }

    private static function getNewTask($branchCode) {
        $lastTask = DB::table('TASKS')
                    ->where('TasksBrchCode', $branchCode)
                    ->orderBy('TasksCode', 'desc')
                    ->first();
        $lastTask = $lastTask ? $lastTask->TasksCode : NULL;
        if (substr($lastTask, 2, 2) != substr((date('Y')), 2)) {
            $newTask = $branchCode . substr((date('Y')), 2) . (date('m')) . '001';
        } elseif (substr($lastTask, 2, 2) == substr((date('Y')), 2) && substr($lastTask, 4, 2) != (date('m'))) {
            $newTask = $branchCode . substr((date('Y')), 2) . (date('m')) . '001';
        } else {
            $num = substr($lastTask, 6) + 1;
            $newTask = $branchCode . substr((date('Y')), 2) . (date('m')) . str_pad($num, 3, '0', STR_PAD_LEFT);
        }
        return $newTask;
    }

    public static function updateStatus($taskCode, $statusCode, $empCode)
    {
        $updated = DB::table('TASKS')
                    ->where('TasksCode', $taskCode)
                    ->update([
                        'TasksStatusCode' => $statusCode,
                        'TasksUpdate' => Carbon::now()
                    ]);
        self::setTaskHis($taskCode, $empCode, $statusCode);
        return $updated;
    }

    private static function setTaskHis($taskCode, $empCode, $taskHisStatus)
    {
        // ดึงค่า TaskHisSeq สูงสุดจาก TASKSTATUS_HISTORICAL
        $maxSeq = DB::table('TASKSTATUS_HISTORICAL')
        ->where('TaskHisCode', $taskCode)
        ->max('TaskHisSeq');

        // เพิ่มค่า TaskHisSeq ขึ้น 1
        $taskHisSeq = $maxSeq ? $maxSeq + 1 : 1;

        // สร้างอาร์เรย์ข้อมูลที่จะแทรก
        $data = [
            'TaskHisCode' => $taskCode,
            'TaskHisSeq' => $taskHisSeq,
            'TaskHisStatusCode' => $taskHisStatus,
            'TaskHisUpdate' => Carbon::now()
        ];

        // เพิ่ม TaskHisEmpCusCode ถ้า $empCode ไม่เป็น NULL
        if ($empCode !== NULL) {
            $data['TaskHisEmpCusCode'] = $empCode;
        }

        // แทรกข้อมูลใหม่ลงใน TASKSTATUS_HISTORICAL
        DB::table('TASKSTATUS_HISTORICAL')->insert($data);
    }

    public static function setUpdateTime($taskCode)
    {
        $update = DB::table('TASKS')
                ->where('TasksCode', $taskCode)
                ->update(['TasksUpdate' => Carbon::now()]);
    }
}