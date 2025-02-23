<?php

namespace App\Http\Controllers\controlDupView;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InsertBasedData;

class dupInsertCSV extends Controller
{
    public function uploadCSV(Request $request)
    {
        $file = $request->file('data_csv');
        $accCode = $request->input('accCode');
        $table = $request->input('table');
        $filePath = $file->getRealPath();
        $file = fopen($filePath, 'r');
        $header = fgetcsv($file);
        $data = [];

        while ($row = fgetcsv($file)) {
            $data[] = $row;
        }

        fclose($file);

        $result = $this->processData($table, $data, $accCode);

        if (isset($result['messageType'])) {
            session()->flash('messageInsertPd', $result['messageType']);
            return redirect()->back();
        }
        session()->flash('messageInsert', $result['message']);
        return redirect()->back();
    }

    private function processData($table, $data, $accCode)
    {
        $insertBasedData = new InsertBasedData();

        switch ($table) {
            case "employee":
                $inserted = $insertBasedData->insertEmp($data, $accCode);
                break;
            case "branch":
                $inserted = $insertBasedData->insertBranch($data, $accCode);
                break;
            case "prod":
                $inserted = $insertBasedData->insertProd($data, $accCode);
                break;
            case "prodType":
                $inserted = $insertBasedData->insertProdType($data, $accCode);
                if ($inserted) {
                    return ['messageType' => 'ข้อมูลถูกเพิ่มเรียบร้อยแล้ว'];
                } else {
                    return ['messageType' => 'ข้อมูลไม่ถูกเพิ่ม กรุณาสอบข้อมูลอีกครั้ง'];
                }
                $inserted = null;
                break;
            case "payment":
                $inserted = $insertBasedData->insertPayment($data, $accCode);
                if ($inserted) {
                    return ['messageType' => 'ข้อมูลถูกเพิ่มเรียบร้อยแล้ว'];
                } else {
                    return ['messageType' => 'ข้อมูลไม่ถูกเพิ่ม กรุณาสอบข้อมูลอีกครั้ง'];
                }
                $inserted = null;
                break;
            default:
                return ['message' => 'รูปแบบไฟล์ไม่ถูกต้อง'];
        }

        if ($inserted) {
            return ['message' => 'ข้อมูลถูกเพิ่มเรียบร้อยแล้ว'];
        } else {
            return ['message' => 'ข้อมูลไม่ถูกเพิ่ม กรุณาสอบข้อมูลอีกครั้ง'];
        }
    }
}