<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmailOtp;
use App\Notifications\EmailOtpNotification;
use App\Notifications\NewUserRegistered;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash as HashFacade;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'department_id' => 1, // Default department for new users
            'is_approved' => false,
        ]);

        // Generate and send OTP
        $code = (string) random_int(100000, 999999);
        $ttlMinutes = 10;
        EmailOtp::create([
            'user_id' => $user->id,
            'code_hash' => HashFacade::make($code),
            'expires_at' => now()->addMinutes($ttlMinutes),
        ]);

        // Try notifying via email; swallow errors to avoid blocking registration
        try {
            $user->notify(new EmailOtpNotification($code, $ttlMinutes));
        } catch (\Throwable $e) {
            report($e);
        }

        // Notify all admins about the new registration (immediate delivery)
        try {
            $admins = User::query()
                ->where(function($q) {
                    $q->where('role', 'admin')->orWhere('is_admin', true);
                })
                ->get();
            foreach ($admins as $admin) {
                $admin->notifyNow(new NewUserRegistered($user));
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()->route('otp.verify.show')
            ->with('pending_email', $user->email)
            ->with('success', 'Account created. Check your email for the OTP to verify. Admin approval is required after email verification.');
}
}
