<?php
namespace App\Http\Controllers\controlDupView;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\controlGetInfo\{invoiceInfo, empInfo};

class dupFormInvoice extends Controller
{
    private invoiceInfo $invoiceInfo;
    private empInfo $empInfo;

    public function __construct(invoiceInfo $invoiceInfo)
    {
        $this->invoiceInfo = $invoiceInfo;
    }

    public function default(Request $request) {
        $taskCode = $request->input('taskCode');
        $this->empInfo = new empInfo();
        $empCode = $this->empInfo->getAccName()->AccName;
        if ($taskCode) {
            $invoiceInfo = $this->invoiceInfo->invoiceInfo($taskCode);
            return view('invoice', ['invoiceInfo' => $invoiceInfo , 'info' => true, 'empCode' => $empCode]);
        }
        return view('invoice', ['info' => false, 'empCode' => $empCode]);
    }
}