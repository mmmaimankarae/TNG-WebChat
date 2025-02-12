<?php

namespace App\Http\Controllers\controlDupView;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\controlGetInfo\tableInfo;

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
        if ($this->currentRoute === 'employee') {
            return $this->sampleEmp();
        } else if ($this->currentRoute === 'branch') {
            return $this->sampleBranch();
        } else if ($this->currentRoute === 'product') {
            return $this->sampleProd();
        }
    }

    private function sampleEmp() {
        $title = 'ข้อมูลพนักงาน';
        $table = [
            '1' => 'รหัสพนักงาน',
            '2' => 'ชื่อ',
            '3' => 'นามสกุล',
            '4' => 'สาขา',
            '5' => 'รหัสตำแหน่ง',
        ];
        $data = $this->tableInfo->roleInfo();
        return view('support-data', compact('title', 'table', 'data'));
    }

    private function sampleBranch() {
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
            '11' => 'รหัสภูมิภาค',
        ];
        $data = $this->tableInfo->regionInfo();
        return view('support-data', compact('title', 'table', 'data'));
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
