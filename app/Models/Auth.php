<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class Auth extends Model
{
    public static function resetPassword($accName, $password = null)
    {
        if ($password === null) {
            $password = env('DEFAULT_PASS');
        }

        $hashedPassword = Hash::make($password, ['rounds' => 12]);
        $updated = DB::table('ACCOUNT')
                ->where('AccName', $accName)
                ->update(['AccPass' => $hashedPassword]);

        if ($updated) {
            return true;
        } else {
            return false;
        }
    }
}