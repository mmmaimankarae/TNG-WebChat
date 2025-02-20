<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\Models\Nosql;
use Carbon\Carbon;

class Tasks extends Model
{   

    public function assign($taskCode, $branchCode)
    {
        try {
            $newTask = self::getNewTask($branchCode);
            $updated = DB::table('TASK')
            ->where('TaskCode', $taskCode)
            ->update([
                'TaskCode' => $newTask, 
                'TaskBrchCode' => $branchCode, 
                'TaskStatusCode' => '1', 
                'TaskUpdate' => Carbon::now()
            ]);
            self::setTaskHis($newTask, NULL, '1');
            $this->updateNosql($taskCode, $newTask);
            return $updated;
        } catch (\Exception $e) {
            \Log::error('Update Error (m.Tasks): ' . $e->getMessage());
            return false;
        }
    }

    private function updateNosql($oldTaskId, $newTaskId)
    {
        $noql = new Nosql();
        $noql->updateTaskId($oldTaskId, $newTaskId);
    }

    private function getNewTask($branchCode) {
        try {
            $lastTask = DB::table('TASK')
            ->where('TaskBrchCode', $branchCode)
            ->orderBy('TaskCode', 'desc')
            ->first();
            $lastTask = $lastTask ? $lastTask->TaskCode : NULL;
            if (substr($lastTask, 2, 2) != substr((date('Y')), 2)) {
                $newTask = $branchCode . substr((date('Y')), 2) . (date('m')) . '000';
            } elseif (substr($lastTask, 2, 2) == substr((date('Y')), 2) && substr($lastTask, 4, 2) != (date('m'))) {
                $newTask = $branchCode . substr((date('Y')), 2) . (date('m')) . '000';
            } else {
                $num = substr($lastTask, 6) + 1;
                $newTask = $branchCode . substr((date('Y')), 2) . (date('m')) . str_pad($num, 3, '0', STR_PAD_LEFT);
            }
            return $newTask;
        } catch (\Exception $e) {
            \Log::error('Get New Task Error (m.Tasks): ' . $e->getMessage());
            return false;
        }
    }

    public function updateStatus($taskCode, $statusCode, $empCode)
    {
        try {
            $updated = DB::table('TASK')
            ->where('TaskCode', $taskCode)
            ->update([
                'TaskStatusCode' => $statusCode,
                'TaskUpdate' => Carbon::now()
            ]);
            self::setTaskHis($taskCode, $empCode, $statusCode);
            return $updated;
        } catch (\Exception $e) {
            \Log::error('Update Error (m.Tasks): ' . $e->getMessage());
            return false;
        }
    }

    private function setTaskHis($taskCode, $empCode, $taskHisStatus)
    {
        try {
            /* ดึงค่า TaskHisSeq สูงสุดจาก TASK_STATUS_HISTORY */
            $maxSeq = DB::table('TASK_STATUS_HISTORY')
            ->where('TaskHisCode', $taskCode)
            ->max('TaskHisSeq');

            /* เพิ่มค่า TaskHisSeq ขึ้น 1 */
            $taskHisSeq = $maxSeq ? $maxSeq + 1 : 1;
            /* สร้างอาร์เรย์ข้อมูลที่จะแทรก */
            $data = [
                'TaskHisCode' => $taskCode,
                'TaskHisSeq' => $taskHisSeq,
                'TaskHisStatusCode' => $taskHisStatus,
                'TaskHisUpdate' => Carbon::now()
            ];

            /* เพิ่ม TaskHisEmpCode ถ้า $empCode ไม่เป็น NULL */
            if ($empCode !== NULL) {
                $data['TaskHisEmpCode'] = $empCode;
            } 
            DB::table('TASK_STATUS_HISTORY')->insert($data);
            return true;
        } catch (\Exception $e) {
            \Log::error('Insert Error (m.Tasks): ' . $e->getMessage());
            return false;
        }
    }

    public function setUpdateTime($taskCode)
    {
        try {
            $update = DB::table('TASK')
            ->where('TaskCode', $taskCode)
            ->update(['TaskUpdate' => Carbon::now()]);
            return $update;
        } catch (\Exception $e) {
            \Log::error('Update Error (m.Tasks): ' . $e->getMessage());
            return false;
        }
    }
}