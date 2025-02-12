<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Employee extends Model
{
    public function setNewBranch($empCode, $branchCode)
    {
        return DB::table('EMPLOYEE')
                ->where('EmpCode', $empCode)
                ->update([
                    'EmpBrchCode' => $branchCode,
                ]);
    }

    public function setResign($empCode)
    {
        return DB::table('EMPLOYEE')
                ->where('EmpCode', $empCode)
                ->update([
                    'EmpResign' => 'Y',
                ]);
    }

}