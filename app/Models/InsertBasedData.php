<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InsertBasedData extends Model
{   
    
    public function insertEmp($data)
    {
        $data = array_map(function($item) {
            return [
                'EmpCode' => $item[0],
                'EmpFirstName' => $item[1],
                'EmpSureName' => $item[2],
                'EmpBrchCode' => $item[3],
                'EmpRoleCode' => $item[4],
            ];
        }, $data);
        $inserted = DB::table('EMPLOYEE')->insert($data);

        if ($inserted) {
            $updated = $this->insertAccount($data);
            if ($updated) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    private function insertAccount($data)
    {
        $newData = [];
        $password = env('DEFAULT_PASS');

        foreach ($data as $item) {
            $hashedPassword = Hash::make($password, ['rounds' => 12]);
            $newData[] = [
                'AccName' => $item[array_key_first($item)],
                'AccPass' => $hashedPassword,
                'AccEmpCode' => $item[array_key_first($item)],
            ];
        }
        $inserted = DB::table('ACCOUNT')->insert($newData);
        return $inserted;
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