<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ApprovalStatusController extends Controller
{
    public function show(Request $request)
    {
        $email = $request->query('email');
        $user = null;
        $status = null;

        if ($email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $status = [
                    'email_verified' => (bool) $user->email_verified_at,
                    'is_approved' => (bool) ($user->is_approved ?? false),
                ];
            }
        }

        return view('auth.approval-status', [
            'email' => $email,
            'user' => $user,
            'status' => $status,
        ]);
    }
}