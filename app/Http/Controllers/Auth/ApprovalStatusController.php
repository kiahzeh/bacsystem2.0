<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use App\Models\User;

class ApprovalStatusController extends Controller
{
    public function show(Request $request)
    {
        // If hitting the debug route or providing a token, enforce token and return JSON
        $token = $request->query('token');
        $expected = Config::get('app.admin_debug_token');
        $isDebugPath = str_contains($request->path(), '_debug');

        if ($isDebugPath || $token !== null) {
            if (!$expected || $token !== $expected) {
                return response()->json([
                    'ok' => false,
                    'error' => 'forbidden',
                    'message' => 'Missing or invalid token',
                ], 403);
            }

            $email = (string) Config::get('app.admin_email');
            $user = $email ? User::where('email', $email)->first() : null;

            $exists = (bool) $user;
            $isAdmin = $exists ? ((bool)($user->is_admin ?? false) || (($user->role ?? null) === 'admin')) : false;
            $isApproved = $exists ? (bool)($user->is_approved ?? false) : false;
            $isVerified = $exists ? (bool)($user->email_verified_at !== null) : false;
            $passwordMatches = $exists ? Hash::check((string) Config::get('app.admin_password'), $user->password) : false;

            return response()->json([
                'ok' => true,
                'admin_email_config' => $email ?: null,
                'exists' => $exists,
                'status' => $exists ? [
                    'id' => $user->id,
                    'email' => $user->email,
                    'is_admin' => $isAdmin,
                    'is_approved' => $isApproved,
                    'is_verified' => $isVerified,
                    'password_matches_env' => $passwordMatches,
                ] : null,
                'hints' => [
                    'set ADMIN_EMAIL and ADMIN_PASSWORD; redeploy with ADMIN_RESET_PASSWORD_ON_BOOT=true',
                    'check logs for [AdminBootstrap] and [Login] lines',
                ],
            ]);
        }

        // Otherwise render the guest-facing approval status page using provided email
        $emailQuery = (string) $request->query('email', '');
        $email = $emailQuery !== '' ? $emailQuery : (string) (auth()->user()->email ?? '');
        $user = $email ? User::where('email', $email)->first() : null;
        $status = $user ? [
            'email_verified' => $user->email_verified_at !== null,
            'is_approved' => (bool) ($user->is_approved ?? false),
        ] : [
            'email_verified' => false,
            'is_approved' => false,
        ];

        return view('auth.approval-status', [
            'email' => $email,
            'user' => $user,
            'status' => $status,
        ]);
    }
}
