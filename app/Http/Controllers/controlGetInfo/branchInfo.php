<?php

namespace App\Http\Controllers\controlGetInfo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class branchInfo extends Controller
{
    function branchInfo(Request $request) {
        $region = $request->input('region');
        $branchs = DB::table('BRANCH')
            ->where('BrchCode', '!=', 'HO')
            ->when($region, function ($query, $region) {
                return $query->where('BrchRegionCode', $region);
            })
            ->get();
        return $branchs;
    }
}