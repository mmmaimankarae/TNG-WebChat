<?php
namespace App\Http\Controllers\controlGetInfo;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Nosql;
use App\Http\Controllers\controlGetInfo\quotationInfo;

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
                'CusPhoneCode' => '6706157AY',
                'CusName' => '',
                'CusPhone' => '',
                'TaskCode' => $taskCode
            ];
        }
        
        return [ 'quotations' => $quotations, 'cusInfo' => $cusInfo ];
    }

    public function getCusInfo($taskCode) {
        try {
            $results = DB::table('CUSTOMER as c')
            ->join('CUSTOMER_PHONE as cp', 'cp.CusPhoneCode', '=', 'c.CusCode')
            ->join('TASK as t', 'c.CusCode', '=', 't.TaskCusCode')
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