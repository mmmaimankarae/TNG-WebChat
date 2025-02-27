<?php
namespace App\Http\Controllers\controlDupView;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\controlGetInfo\invoiceInfo;

class dupFormInvoice extends Controller
{
    private invoiceInfo $invoiceInfo;

    public function __construct(invoiceInfo $invoiceInfo)
    {
        $this->invoiceInfo = $invoiceInfo;
    }

    public function default(Request $request) {
        $taskCode = $request->input('taskCode');
        if ($taskCode) {
            $invoiceInfo = $this->invoiceInfo->invoiceInfo($taskCode);
            return view('invoice', ['invoiceInfo' => $invoiceInfo , 'info' => true]);
        }
        return view('invoice', ['info' => false]);
    }
}