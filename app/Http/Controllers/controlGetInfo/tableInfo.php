<?php

namespace App\Http\Controllers\controlGetInfo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TableInfo extends Controller
{
    public function roleInfo() {
        $roles = DB::table('ROLE')->get();
        return $roles;
    }

    public function prodTypeInfo() {
        $prodType = DB::table('PRODUCTTYPE')->get();
        return $prodType;
    }
}