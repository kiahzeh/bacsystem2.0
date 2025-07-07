<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class VerifyPassword
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('password_verified')) {
            if ($request->isMethod('post') && $request->has('password')) {
                if (Hash::check($request->password, Auth::user()->password)) {
                    Session::put('password_verified', true);
                    return $next($request);
                }
                return back()->withErrors(['password' => 'Incorrect password']);
            }
            return response()->view('auth.verify-password');
        }

        return $next($request);
    }
} 