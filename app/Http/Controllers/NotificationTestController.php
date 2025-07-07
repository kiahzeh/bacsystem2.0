<?php

namespace App\Http\Controllers;

use App\Services\BrevoService;
use Illuminate\Http\Request;

class NotificationTestController extends Controller
{
    protected $brevoService;

    public function __construct(BrevoService $brevoService)
    {
        $this->brevoService = $brevoService;
    }

    public function showTestForm()
    {
        return view('test-notifications');
    }

    public function sendTestEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        \Log::info('Attempting to send test email to: ' . $request->email);

        $htmlContent = view('emails.test-email')->render();

        try {
            $result = $this->brevoService->sendEmail(
                $request->email,
                'Test Email from BAC System',
                $htmlContent
            );

            \Log::info('Brevo service response: ' . ($result ? 'Success' : 'Failed'));

            if ($result) {
                return back()->with('success', 'Test email sent successfully via Brevo!');
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send test email: ' . $e->getMessage());
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }

        return back()->with('error', 'Failed to send email. Check logs for details.');
    }
}