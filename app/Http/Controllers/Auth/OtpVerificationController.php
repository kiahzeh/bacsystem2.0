<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailOtp;
use App\Models\User;
use App\Notifications\EmailOtpNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OtpVerificationController extends Controller
{
    public function show()
    {
        return view('auth.verify-otp');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'email' => ['required','email'],
            'code' => ['required','digits:6'],
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->with('error', 'Invalid email or code.');
        }

        $otp = EmailOtp::where('user_id', $user->id)
            ->where('expires_at', '>=', now())
            ->latest('id')
            ->first();

        if (!$otp || !Hash::check($request->code, $otp->code_hash)) {
            return back()->with('error', 'Invalid or expired code.');
        }

        // Mark email as verified
        if (!$user->email_verified_at) {
            $user->forceFill(['email_verified_at' => now()])->save();
        }

        return redirect()->route('approval.status', ['email' => $user->email])
            ->with('success', 'Email verified. Your account is awaiting admin approval.');
    }

    public function resend(Request $request)
    {
        $request->validate([
            'email' => ['required','email'],
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->with('error', 'No account found for that email.');
        }

        $code = (string) random_int(100000, 999999);
        $ttlMinutes = 10;
        EmailOtp::create([
            'user_id' => $user->id,
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes($ttlMinutes),
        ]);

        try {
            $user->notify(new EmailOtpNotification($code, $ttlMinutes));
        } catch (\Throwable $e) {
            report($e);
        }

        return back()->with('success', 'A new OTP has been sent to your email.');
    }
}