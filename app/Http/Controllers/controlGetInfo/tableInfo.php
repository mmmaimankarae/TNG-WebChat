<?php

namespace App\Http\Controllers\controlGetInfo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TableInfo extends Controller
{
    public function roleInfo() {
        try {
            $roles = DB::table('ROLE')->get();
            return $roles;
        } catch (\Exception $e) {
            \Log::error('Find Error (c.controlGetInfo.tableInfo): ' . $e->getMessage());
            return false;
        }
    }

    public function prodTypeInfo() {
        try {
            $prodType = DB::table('PRODUCT_TYPE')->get();
            return $prodType;
        } catch (\Exception $e) {
            \Log::error('Find Error (c.controlGetInfo.tableInfo): ' . $e->getMessage());
            return false;
        }
    }

    public function regionInfo() {
        try {
            $region = DB::table('REGION')->get();
            return $region;
        } catch (\Exception $e) {
            \Log::error('Find Error (c.controlGetInfo.tableInfo): ' . $e->getMessage());
            return false;
        }
    }

    public function branchInfo() {
        try {
            $branch = DB::table('BRANCH')->get();
            return $branch;
        } catch (\Exception $e) {
            \Log::error('Find Error (c.controlGetInfo.tableInfo): ' . $e->getMessage());
            return false;
        }
    }

    public function paymentInfo($region) {
        try {
            $payment = DB::table('BRANCH')
                ->join('PAYMENT_ACCOUNT', 'PAYMENT_ACCOUNT.PayAccBrchCode', '=', 'BRANCH.BrchCode')
                ->where(function ($query) {
                    $query->where('BRANCH.BrchRegionCode', '!=', '1')
                          ->orWhere('BRANCH.BrchCode', 'HO');
                })
                ->where('BRANCH.BrchRegionCode', $region)
                ->get();
            return $payment;
        } catch (\Exception $e) {
            \Log::error('Find Error (c.controlGetInfo.tableInfo): ' . $e->getMessage());
            return false;
        }
    }

    public function paymentDescInfo() {
        try {
            $paymentDesc = DB::table('PAYMENT_DESCRIPTION')
                ->orderBy('PayDescList', 'desc')
                ->first();
            return $paymentDesc->PayDesc;
        } catch (\Exception $e) {
            \Log::error('Find Error (c.controlGetInfo.tableInfo): ' . $e->getMessage());
            return false;
        }
    }
}