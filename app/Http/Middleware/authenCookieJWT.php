<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Cookie;

class authenCookieJWT
{
    public function handle($request, Closure $next)
    {
        if ($jwt = Cookie::get('access-jwt')) {
            try {
                $decoded = JWT::decode($jwt, new Key(env('JWT_SECRET'), 'HS256'));
                // dd($decoded);
                $request->attributes->add(['decoded' => $decoded]);
            } catch (\Exception $e) {
                return redirect('/');
            }
        } else {
            return redirect('/');
        }

        return $next($request);
    }
}