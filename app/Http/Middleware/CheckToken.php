<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');

        if ($token && preg_match('/Bearer\s(\S+)/', $token, $matches)) {
            if (Auth::guard('api')->setToken($matches[1])->check()) {
                return $next($request);
            } else {
                \Log::error('Invalid token: ', ['token' => $matches[1]]);
            }
        } else {
            \Log::error('No token provided');
        }

        return redirect('/login'); // Redirect to login if token is invalid
    }
}
