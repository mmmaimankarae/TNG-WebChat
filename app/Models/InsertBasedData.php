<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InsertBasedData extends Model
{
    public function insertEmp($data, $accCode)
    {
        $data = array_map(function($item) {
            return [
                'EmpCode' => $item[0],
                'EmpFirstName' => $item[1],
                'EmpSureName' => $item[2],
                'EmpBrchCode' => $item[3],
                'EmpRoleCode' => $item[4],
                'EmpResign' => $item[5],
            ];
        }, $data);

        foreach ($data as $item) {
            if($item['EmpResign'] == '') {
                $item['EmpResign'] = 'N';
            }

            /* ตรวจสอบว่าเคยมีกาข้อมูลนั่นๆ อยู่ไหม */
            $existingEmployee = DB::table('EMPLOYEE')->where('EmpCode', $item['EmpCode'])->first();

            if ($existingEmployee) {
                /* ตรวจสอบว่าข้อมูลมีการเปลี่ยนแปลงหรือไม่ */
                $updateData = [];
                foreach ($item as $key => $value) {
                    if ($existingEmployee->$key != $value) {
                        $updateData[$key] = $value;
                    }
                }

                if (!empty($updateData)) {
                    $updateData['EmpUpdateDate'] = date('Y-m-d H:i:s');

                    try {
                        DB::table('EMPLOYEE')->where('EmpCode', $item['EmpCode'])->update($updateData);
                    } catch (\Exception $e) {
                        \Log::error('Update Error (m.InsertBasedData): ' . $e->getMessage());
                        return false;
                    }
                }
            } else {
                try {
                    $item['EmpUpdateDate'] = date('Y-m-d H:i:s');
                    DB::table('EMPLOYEE')->insert($item);
                    $this->insertAccount($item);
                } catch (\Exception $e) {
                    \Log::error('Insert Error (m.InsertBasedData): ' . $e->getMessage());
                    return false;
                }
            }
        }

        try {
            DB::table('LOG_DATA')->insert([
                'LogAccCode' => $accCode,
                'LogDate' => date('Y-m-d H:i:s'),
                'LogTable' => 'EMPLOYEE',
            ]);
        } catch (\Exception $e) {
            \Log::error('Model InsertBasedData, Database error: ' . $e->getMessage());
            return false;
        }
        return true;
    }

    private function insertAccount($data)
    {
        $password = env('DEFAULT_PASS');
        $hashedPassword = Hash::make($password, ['rounds' => 12]);

        $newData = [
            'AccName' => $data['EmpCode'],
            'AccPass' => $hashedPassword,
        ];

        try {
            DB::table('ACCOUNT')->insert($newData);
        } catch (\Exception $e) {
            \Log::error('Insert Error (m.InsertBasedData): ' . $e->getMessage());
            return false;
        }
    }

    public function insertBranch($data)
    {
        $data = array_map(function($item) {
            return [
                'BrchCode' => $item[0],
                'BrchName' => $item[1],
                'BrchLat' => $item[2],
                'BrchLong' => $item[3],
                'BrchAddr' => $item[4],
                'BrchDistrict' => $item[5],
                'BrchCity' => $item[6],
                'BrchCountry' => $item[7],
                'BrchZipCode' => $item[8],
                'BrchPhone' => $item[9],
                'BrchRegionCode' => $item[10],
            ];
        }, $data);
        $inserted = DB::table('BRANCH')->insert($data);

        if ($inserted) {
            return true;
        } else {
            return false;
        }
    }

    public function insertProd($data)
    {
        $data = array_map(function($item) {
            return [
                'ProdCode' => $item[0],
                'ProdDesc' => $item[1],
                'ProdMeasure' => $item[2],
                'ProdGroupCode' => $item[3],
            ];
        }, $data);
        $inserted = DB::table('PRODUCT')->insert($data);
        return $inserted;
    }

    public function insertProdType($code, $name)
    {
        $data = [
            'ProdTypeCode' => $code,
            'ProdTypeName' => $name,
        ];
        $inserted = DB::table('PRODUCTTYPE')->insert($data);
        return $inserted;
    }
}