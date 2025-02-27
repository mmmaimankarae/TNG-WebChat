<?php
namespace App\Http\Controllers\controlGetInfo;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class branchInfo extends Controller
{
    function branchInfo(Request $request) {
        try {
            $region = $request->input('region') ?? '1';
            $branchs = DB::table('BRANCH')
                ->where('BrchCode', '!=', 'HO')
                ->where('BrchRegionCode', $region)
                ->get();
            return $branchs;
        } catch (\Exception $e) {
            \Log::error('Find Error (c.controlGetInfo.branchInfo): ' . $e->getMessage());
            return false;
        }
    }
}