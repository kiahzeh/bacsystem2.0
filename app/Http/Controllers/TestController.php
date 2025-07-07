<?php

namespace App\Http\Controllers;

use App\Mail\TestMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TestController extends Controller
{
    public function testEmail(Request $request)
    {
        try {
            $email = $request->input('email', auth()->user()->email);
            Mail::to($email)->send(new TestMail());

            return back()->with('success', "Test email sent successfully to {$email}. Please check your email or Mailpit interface.");
        } catch (\Exception $e) {
            return back()->with('error', 'Error sending email: ' . $e->getMessage());
        }
    }
}