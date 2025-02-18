<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class Authenticate extends Model
{
    public static function resetPassword($accName, $password = null)
    {
        if ($password === null) {
            $password = env('DEFAULT_PASS');
        }

        $hashedPassword = Hash::make($password, ['rounds' => 12]);
        try {
            $updated = DB::table('ACCOUNT')
                ->where('AccName', $accName)
                ->update(['AccPass' => $hashedPassword]);

            return $updated;
        } catch (\Exception $e) {
            \Log::error('Model Authenticate, Database error: ' . $e->getMessage());
            return false;
        }
    }
}