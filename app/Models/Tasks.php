<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\Models\Nosql;

class Tasks extends Model
{   
    public static function assign($taskCode, $branchCode)
    {
        $newTask = self::getNewTask($branchCode);
        //$nosql = self::upDateTaskNosql($taskCode, $newTask);
        $updated = DB::table('TASKS')
        ->where('TasksCode', $taskCode)
        ->update(['TasksCode' => $newTask, 'TasksBrchCode' => $branchCode]);
        return $updated;
    }

    private static function getNewTask($branchCode) {
        $lastTask = DB::table('TASKS')
                    ->where('TasksBrchCode', $branchCode)
                    ->orderBy('TasksCode', 'desc')
                    ->first();
        if (substr($lastTask, 2, 2) != substr((date('Y')), 2)) {
            $newTask = $branchCode . substr((date('Y')), 2) . '001';
        } else {
            $num = substr($lastTask, 4) + 1;
            $newTask = $branchCode . substr((date('Y')), 2) . str_pad($num, 3, '0', STR_PAD_LEFT);
        }
        return $newTask;
    }

    private static function upDateTaskNosql($taskCode, $newTask)
    {
        $nosql = new Nosql();
        $docunment = $nosql->collection->findoOne(['TasksCode' => $taskCode]);
    }
}