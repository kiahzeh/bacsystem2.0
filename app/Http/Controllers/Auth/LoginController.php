<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function credentials(Request $request)
    {
        return [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];
    }

    protected function authenticated(Request $request, $user)
    {
        // Send welcome notification
        $user->notify(new \App\Notifications\WelcomeNotification());

        // Log the login
        activity()
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties(['ip' => $request->ip()])
            ->log('User logged in');
    }

    public function logout(Request $request)
    {
        // Log the logout
        if (Auth::check()) {
            activity()
                ->performedOn(Auth::user())
                ->causedBy(Auth::user())
                ->withProperties(['ip' => $request->ip()])
                ->log('User logged out');
        }

        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
} 