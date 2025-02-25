<?php

namespace App\Http\Controllers\controlDupView;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\InsertBasedData;
use Illuminate\Support\Facades\Validator;

class dupInsertCSV extends Controller
{
    public function description(Request $request)
    {
        /* เพิ่มการตรวจสอบแบบกำหนดเอง */
        Validator::extend('contains', function ($attribute, $value, $parameters, $validator) {
            return is_string($value) && strpos($value, '** จำนวนเงิน **') !== false;
        });
        
        $request->validate([
            'desc' => ['required', 'contains'],
        ], [
            'desc.contains' => 'ข้อความแจ้งการชำระเงินต้องมีคำว่า "** จำนวนเงิน **"',
        ]);

        $desc = $request->input('desc');
        $accCode = $request->input('accCode');
        $insertBasedData = new InsertBasedData();
        $inserted = $insertBasedData->insertPaymentDesc($desc, $accCode);
        return redirect()->back()->withErrors("ข้อความถูกเพิ่มเรียบร้อยแล้ว");
    }

    public function uploadCSV(Request $request)
    {
        $table = $request->input('table');
        if ($table == 'payment') {
            // save รูปที่ได้มา
            $file = $request->file('file');
            $accCode = $request->input('accCode');
            $branchCode = $request->input('brchCode'); 

            $newFileName = $branchCode . '.' . $file->getClientOriginalExtension();
            $filePath = 'public\payments\\' . $newFileName;

            $file->storeAs('public\payments', $newFileName);
            $relativePath = 'storage\payments\\' . $newFileName;

            $insertBasedData = new InsertBasedData();
            $inserted = $insertBasedData->updatePayment($branchCode, $relativePath, $accCode);
            return redirect()->back();
        }
        else {
            $file = $request->file('data_csv');
            $accCode = $request->input('accCode');
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
        }
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