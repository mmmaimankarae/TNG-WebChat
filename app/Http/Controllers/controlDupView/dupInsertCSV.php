<?php

namespace App\Http\Controllers\controlDupView;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InsertBasedData;

class dupInsertCSV extends Controller
{
    public function uploadCSV(Request $request) {
        $file = $request->file('data_csv');
        $filePath = $file->getRealPath();
        $file = fopen($filePath, 'r');
        $header = fgetcsv($file);
        $count = count($header);
        $data = [];

        if ($count === 5) {
            while ($row = fgetcsv($file)) {
                $data = array_merge($data, [$row]);
            }
            $inserted = (new InsertBasedData)->insertEmp($data);
            if ($inserted) {
                session()->flash('messageInsert', 'ข้อมูลถูกเพิ่มเรียบร้อยแล้ว');
                return redirect()->back();
            } else {
                session()->flash('messageInsert', 'ข้อมูลไม่ถูกเพิ่ม กรุณาสอบข้อมูลอีกครั้ง');
                return redirect()->back();
            }
        } else if ($count === 11) {
            while ($row = fgetcsv($file)) {
                $data = array_merge($data, [$row]);
            }
            $inserted = (new InsertBasedData)->insertBranch($data);
            if ($inserted) {
                session()->flash('messageInsert', 'ข้อมูลถูกเพิ่มเรียบร้อยแล้ว');
                return redirect()->back();
            } else {
                session()->flash('messageInsert', 'ข้อมูลไม่ถูกเพิ่ม กรุณาสอบข้อมูลอีกครั้ง');
                return redirect()->back();
            }
        } else if ($count === 4) {
            return $this->sampleProd();
        }
    }

    public function addProdType(Request $request) {
        $inserted = (new InsertBasedData)->insertProdType($request->input('typeCode'), $request->input('typeName'));
        if ($inserted) {
            session()->flash('messageInsertPd', 'ข้อมูลถูกเพิ่มเรียบร้อยแล้ว');
            return redirect()->back();
        } else {
            session()->flash('messageInsertPd', 'ข้อมูลไม่ถูกเพิ่ม กรุณาสอบข้อมูลอีกครั้ง');
            return redirect()->back();
        }
    }
}
