<?php

namespace App\Http\Controllers\controlGetInfo;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class empInfo extends Controller
{
    private $decoded;

    public function __construct() {
        $this->decoded = request()->attributes->get('decoded');
    }

    public function getAccName() {
        $accCode = $this->decoded->accCode;
        $accName = $this->getNameFromDB($accCode);
        return $accName;
    }

    private function getNameFromDB($accCode) {
        $accName = DB::table('ACCOUNT')
            ->where('accCode', $accCode)
            ->select('AccName')
            ->first();
        return $accName;
    }
}