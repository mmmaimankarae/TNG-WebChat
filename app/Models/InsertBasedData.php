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
                'EmpRoleCode' => $item[5],
                'EmpResign' => $item[4],
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

    public function insertBranch($data, $accCode)
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
                'BrchRegionCode' => $item[11],
                'BrchClosed' => $item[10],
            ];
        }, $data);

        foreach ($data as $item) {
            if($item['BrchClosed'] == '') {
                $item['BrchClosed'] = 'N';
            }

            /* ตรวจสอบว่าเคยมีข้อมูลนั่นๆ อยู่ไหม */
            $existingBranch = DB::table('BRANCH')->where('BrchCode', $item['BrchCode'])->first();

            if ($existingBranch) {
                /* ตรวจสอบว่าข้อมูลมีการเปลี่ยนแปลงหรือไม่ */
                $updateData = [];
                foreach ($item as $key => $value) {
                    if ($existingBranch->$key != $value) {
                        $updateData[$key] = $value;
                    }
                }

                if (!empty($updateData)) {
                    $updateData['BrchUpdateDate'] = date('Y-m-d H:i:s');

                    try {
                        DB::table('BRANCH')->where('BrchCode', $item['BrchCode'])->update($updateData);
                    } catch (\Exception $e) {
                        \Log::error('Update Error (m.InsertBasedData): ' . $e->getMessage());
                        return false;
                    }
                }
            } else {
                try {
                    $item['BrchUpdateDate'] = date('Y-m-d H:i:s');
                    DB::table('BRANCH')->insert($item);
                    if ($item['BrchRegionCode'] != '1') {
                        $this->insertPayment($item['BrchCode'], $accCode);
                    }
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
                'LogTable' => 'BRANCH',
            ]);
        } catch (\Exception $e) {
            \Log::error('Model InsertBasedData, Database error: ' . $e->getMessage());
            return false;
        }
        return true;
    }

    private function insertPayment($branchCode, $accCode)
    {
        try {
            DB::table('PAYMENT_ACCOUNT')->insert([
                'PayAccBrchCode' => $branchCode,
                'PayAccUpdateDate' => date('Y-m-d H:i:s'),
            ]);

            try {
                DB::table('LOG_DATA')->insert([
                    'LogAccCode' => $accCode,
                    'LogDate' => date('Y-m-d H:i:s'),
                    'LogTable' => 'PAYMENT_ACCOUNT',
                ]);
            } catch (\Exception $e) {
                \Log::error('Model InsertBasedData, Database error: ' . $e->getMessage());
                return false;
            }
        } catch (\Exception $e) {
            \Log::error('Insert Error (m.InsertBasedData): ' . $e->getMessage());
            return false;
        }
    }

    public function insertProd($data, $accCode)
    {
        $data = array_map(function($item) {
            return [
                'ProdCode' => $item[0],
                'ProdDesc' => $item[1],
                'ProdMeasure' => $item[2],
                'ProdGroupCode' => $item[4],
                'ProdCancle' => $item[3],
            ];
        }, $data);

        foreach ($data as $item) {
            if($item['ProdCancle'] == '') {
                $item['ProdCancle'] = 'N';
            }

            /* ตรวจสอบว่าเคยมีข้อมูลนั่นๆ อยู่ไหม */
            $existingProduct = DB::table('PRODUCT')->where('ProdCode', $item['ProdCode'])->first();

            if ($existingProduct) {
                /* ตรวจสอบว่าข้อมูลมีการเปลี่ยนแปลงหรือไม่ */
                $updateData = [];
                foreach ($item as $key => $value) {
                    if ($existingProduct->$key != $value) {
                        $updateData[$key] = $value;
                    }
                }

                if (!empty($updateData)) {
                    $updateData['ProdUpdateDate'] = date('Y-m-d H:i:s');

                    try {
                        DB::table('PRODUCT')->where('ProdCode', $item['ProdCode'])->update($updateData);
                    } catch (\Exception $e) {
                        \Log::error('Update Error (m.InsertBasedData): ' . $e->getMessage());
                        return false;
                    }
                }
            } else {
                try {
                    $item['ProdUpdateDate'] = date('Y-m-d H:i:s');
                    DB::table('PRODUCT')->insert($item);
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
                'LogTable' => 'PRODUCT',
            ]);
        } catch (\Exception $e) {
            \Log::error('Model InsertBasedData, Database error: ' . $e->getMessage());
            return false;
        }
        return true;
    }

    public function insertProdType($data, $accCode)
    {
        $data = array_map(function($item) {
            return [
                'ProdTypeCode' => $item[0],
                'ProdTypeName' => $item[1],
                'ProdTypeCancle' => $item[2],
            ];
        }, $data);

        foreach ($data as $item) {
            if($item['ProdTypeCancle'] == '') {
                $item['ProdTypeCancle'] = 'N';
            }

            /* ตรวจสอบว่าเคยมีข้อมูลนั่นๆ อยู่ไหม */
            $existingProductType = DB::table('PRODUCT_TYPE')->where('ProdTypeCode', $item['ProdTypeCode'])->first();

            if ($existingProductType) {
                /* ตรวจสอบว่าข้อมูลมีการเปลี่ยนแปลงหรือไม่ */
                $updateData = [];
                foreach ($item as $key => $value) {
                    if ($existingProductType->$key != $value) {
                        $updateData[$key] = $value;
                    }
                }

                if (!empty($updateData)) {
                    $updateData['ProdTypeUpdateDate'] = date('Y-m-d H:i:s');

                    try {
                        DB::table('PRODUCT_TYPE')->where('ProdTypeCode', $item['ProdTypeCode'])->update($updateData);
                    } catch (\Exception $e) {
                        \Log::error('Update Error (m.InsertBasedData): ' . $e->getMessage());
                        return false;
                    }
                }
            } else {
                try {
                    $item['ProdTypeUpdateDate'] = date('Y-m-d H:i:s');
                    DB::table('PRODUCT_TYPE')->insert($item);
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
                'LogTable' => 'PRODUCT_TYPE',
            ]);
        } catch (\Exception $e) {
            \Log::error('Model InsertBasedData, Database error: ' . $e->getMessage());
            return false;
        }
        return true;
    }

    public function updatePayment($branch, $path, $accCode) {
        try {
            //UPDATE PAYMENT_ACCOUNT SET PayAccPath = '' WHERE PayAccBrchCode;
            DB::table('PAYMENT_ACCOUNT')
                ->where('PayAccBrchCode', $branch)
                ->update([
                    'PayAccPath' => $path,
                    'PayAccUpdateDate' => date('Y-m-d H:i:s')
                ]);

            try {
                DB::table('LOG_DATA')->insert([
                    'LogAccCode' => $accCode,
                    'LogDate' => date('Y-m-d H:i:s'),
                    'LogTable' => 'PAYMENT_ACCOUNT',
                ]);
            } catch (\Exception $e) {
                \Log::error('Model InsertBasedData, Database error: ' . $e->getMessage());
                return false;
            }
                
        } catch (\Exception $e) {
            \Log::error('Update Error (m.InsertBasedData): ' . $e->getMessage());
            return false;
        }
    }

    public function insertPaymentDesc($desc, $accCode) {
        try {
            DB::table('PAYMENT_DESCRIPTION')->insert([
                'PayDesc' => $desc,
                'PayDescUpdateDate' => date('Y-m-d H:i:s')
            ]);
            
            try {
                DB::table('LOG_DATA')->insert([
                    'LogAccCode' => $accCode,
                    'LogDate' => date('Y-m-d H:i:s'),
                    'LogTable' => 'PAYMENT_DESCRIPTION',
                ]);
            } catch (\Exception $e) {
                \Log::error('Model InsertBasedData, Database error: ' . $e->getMessage());
                return false;
            }
        } catch (\Exception $e) {
            \Log::error('Insert Error (m.InsertBasedData): ' . $e->getMessage());
            return false;
        }
    }
}