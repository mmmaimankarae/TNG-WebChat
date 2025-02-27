<?php

namespace App\Http\Controllers\controlGetInfo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Nosql;
use App\Http\Controllers\controlGetInfo\{quotationInfo, tableInfo, sidebarInfo};

class invoiceInfo extends Controller
{
    private quotationInfo $quotationInfo;

    public function __construct(quotationInfo $quotationInfo)
    {
        $this->quotationInfo = $quotationInfo;
    }

    public function invoiceInfo($taskCode) {
        $quotations = $this->quotationInfo->quotationInfo($taskCode, "true");
        $cusInfo = $this->getCusInfo($taskCode);

        if (empty($cusInfo)) {
            $cusInfo = [
                'CusPhoneCode' => '',
                'CusName' => '',
                'CusPhone' => '',
                'TaskCode' => $taskCode
            ];
        }
        
        return [ 'quotations' => $quotations, 'cusInfo' => $cusInfo ];
    }

    public function getCusInfo($taskCode) {
        try {
            $results = DB::table('CUSTOMER_PHONE as cp')
            ->join('TASK as t', 'cp.CusPhoneCode', '=', 't.TaskCusCode')
            ->join('CUSTOMER as c', 'cp.CusCode', '=', 'c.CusCode')
            ->where('t.TaskCode', $taskCode)
            ->select('cp.*', 't.TaskCode', 'c.CusName')
            ->first();

            return $results;
        } catch (\Exception $e) {
            \Log::error('Find Error (c.controlGetInfo.getCusInfo): ' . $e->getMessage());
            return [];
        }
    }
}