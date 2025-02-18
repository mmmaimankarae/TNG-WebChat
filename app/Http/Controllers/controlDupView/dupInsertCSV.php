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
        $filePath = $file->getRealPath();
        $file = fopen($filePath, 'r');
        $header = fgetcsv($file);
        $count = count($header);
        $data = [];

        while ($row = fgetcsv($file)) {
            $data[] = $row;
        }

        fclose($file);

        $result = $this->processData($count, $data, $accCode);

        if (isset($result['messageType'])) {
            session()->flash('messageInsertPd', $result['messageType']);
            return redirect()->back();
        }
        session()->flash('messageInsert', $result['message']);
        return redirect()->back();
    }

    private function processData($count, $data, $accCode)
    {
        $insertBasedData = new InsertBasedData();

        switch ($count) {
            case 6:
                $inserted = $insertBasedData->insertEmp($data, $accCode);
                break;
            case 12:
                $inserted = $insertBasedData->insertBranch($data, $accCode);
                break;
            case 5:
                $inserted = $insertBasedData->insertProd($data, $accCode);
                break;
            case 3:
                $inserted = $insertBasedData->insertProdType($data, $accCode);
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