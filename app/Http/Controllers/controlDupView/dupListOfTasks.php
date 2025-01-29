<?php

namespace App\Http\Controllers\controlDupView;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\controlGetInfo\empInfo;
use App\Http\Controllers\controlGetInfo\tasksInfo;

class dupListOfTasks extends Controller
{
    private $empInfo;
    private $tasksInfo;

    public function newTasks() {
        $empInfo = new empInfo();
        $branch = $empInfo->getBranchCode();
        $tasksInfo = new tasksInfo();
        $results = $tasksInfo->getTasksInfo($branch);
        $title = 'รายการงานใหม่';
        return view('listOfTasks', compact('results', 'title'));
    }

    public function currentTasks() {
        $empInfo = new empInfo();
        $branch = $empInfo->getBranchCode();
        $accCode = $empInfo->getAccCode();
        $tasksInfo = new tasksInfo();
        $results = $tasksInfo->getTasksInfo($branch, $accCode);

        /* ดึงข้อมูลผู้รับผิดชอบงาน และ & อัปเดตค่าโดยตรง */
        foreach ($results as &$task) {
            $task->participant = $this->getPartiName($task->TasksCode);
        }

        $title = 'งานที่ดำเนินอยู่';
        return view('listOfTasks', compact('results', 'title'));
    }
}