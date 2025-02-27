<?php
namespace App\Http\Controllers\controlDupView;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\controlGetInfo\{empInfo, tableInfo, sidebarInfo};

class dupFormAddData extends Controller
{
    private $tableInfo;
    private $currentRoute;
    private $sidebarInfo;

    public function __construct(tableInfo $tableInfo, Request $req)
    {
        $this->tableInfo = $tableInfo;
        $this->currentRoute = $req->segment(2);
    }

    public function default(Request $req) {
        $empInfo = new empInfo();
        $accCode = $empInfo->getAccCode();
        $branchCode = $empInfo->getBranchCode();

        if ($this->currentRoute === 'employee') {
            return $this->sampleEmp($accCode);
        } else if ($this->currentRoute === 'branch') {
            return $this->sampleBranch($accCode);
        } else if ($this->currentRoute === 'product') {
            return $this->sampleProd($accCode);
        } elseif ($this->currentRoute === 'payment') {
            return $this->samplePayment($accCode, $branchCode, $req);
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
        return view('it-support.support-data', compact('title', 'table', 'data', 'branch', 'accCode'));
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
        return view('it-support.support-data', compact('title', 'table', 'data', 'accCode'));
    }

    private function sampleProd($accCode) {
        $title = 'ข้อมูลสินค้า';
        $table = [
            '1' => 'รหัสสินค้า',
            '2' => 'ชื่อสินค้า',
            '3' => 'หน่วยนับ',
            '4' => 'สถานะการขารย (Y = ไม่ขาย)',
            '5' => 'รหัสประเภทสินค้า',
        ];

        $tableType = [
            '1' => 'รหัสประเภทสินค้า',
            '2' => 'ชื่อประเภทสินค้า',
            '3' => 'สถานะประเภท (Y = ยกเลิกประเภท)',
        ];
        $data = $this->tableInfo->prodTypeInfo();
        return view('it-support.support-data', compact('title', 'table', 'data', 'accCode', 'tableType'));
    }

    private function samplePayment($accCode, $branchCode, $req) {
        $title = 'ช่องทางการชำระเงิน';
        $table = [
            '1' => 'สาขา',
            '2' => 'รูปภาพ',
            '3' => 'แก้ไขรูปภาพ',
            '4' => '',
        ];

        $region = $req->input('region') ?? '1';
        $data = $this->tableInfo->paymentInfo($region);
        $dataDesc = $this->tableInfo->paymentDescInfo();
        $sidebarInfo = new sidebarInfo();
        $sidebarChat = $sidebarInfo->getEmpTasks($branchCode);
        $regionThai = ['1' => 'กรุงเทพ', '2' => 'ภาคเหนือ', '3' => 'ภาคกลาง', '4' => 'ภาคใต้', '5' => 'ภาคอีสาน', '6' => 'ภาคตะวันออก'];
        return view('sale-admin.payment-data', compact('title', 'table', 'data', 'accCode', 'sidebarChat', 'regionThai', 'dataDesc'));
    }
}
