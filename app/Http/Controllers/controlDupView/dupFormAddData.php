<?php

namespace App\Http\Controllers\controlDupView;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\controlGetInfo\tableInfo;
use App\Http\Controllers\controlGetInfo\empInfo;

class dupFormAddData extends Controller
{
    private $tableInfo;
    private $currentRoute;

    public function __construct(tableInfo $tableInfo, Request $request)
    {
        $this->tableInfo = $tableInfo;
        $this->currentRoute = $request->segment(2);
    }

    public function default() {
        $empInfo = new empInfo();
        $accCode = $empInfo->getAccCode();

        if ($this->currentRoute === 'employee') {
            return $this->sampleEmp($accCode);
        } else if ($this->currentRoute === 'branch') {
            return $this->sampleBranch($accCode);
        } else if ($this->currentRoute === 'product') {
            return $this->sampleProd($accCode);
        }
    }

    private function sampleEmp($accCode) {
        $title = 'ข้อมูลพนักงาน';
        $table = [
            '1' => 'รหัสพนักงาน',
            '2' => 'ชื่อ',
            '3' => 'นามสกุล',
            '4' => 'ชื่อย่อสาขา',
            '5' => 'สถานะการทำงาน (Y = ลาออก)',
            '6' => 'รหัสตำแหน่ง',
        ];
        $data = $this->tableInfo->roleInfo();
        $branch = $this->tableInfo->branchInfo();
        return view('support-data', compact('title', 'table', 'data', 'branch', 'accCode'));
    }

    private function sampleBranch($accCode) {
        $title = 'ข้อมูลสาขา';
        $table = [
            '1' => 'ชื่อย่อสาขา',
            '2' => 'ชื่อสาขา',
            '3' => 'ละติจูด',
            '4' => 'ลองจิจูด',
            '5' => 'ที่อยู่',
            '6' => 'ตำบล/แขวง',
            '7' => 'อำเภอ/เขต',
            '8' => 'จังหวัด',
            '9' => 'รหัสไปรษณีย์',
            '10' => 'เบอร์โทร',
            '11' => 'สถานะสาขา (Y = ปิด)',
            '12' => 'รหัสภูมิภาค',
        ];
        $data = $this->tableInfo->regionInfo();
        return view('support-data', compact('title', 'table', 'data', 'accCode'));
    }

    private function sampleProd() {
        $title = 'ข้อมูลสินค้า';
        $table = [
            '1' => 'รหัสสินค้า',
            '2' => 'ชื่อสินค้า',
            '3' => 'หน่วยนับ',
            '4' => 'รหัสประเภทสินค้า',
        ];
        $data = $this->tableInfo->prodTypeInfo();
        return view('support-data', compact('title', 'table', 'data'));
    }
}
