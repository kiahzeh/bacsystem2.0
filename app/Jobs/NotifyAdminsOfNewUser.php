<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\NewUserRegistered;
use App\Services\BrevoService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotifyAdminsOfNewUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public User $newUser)
    {
    }

    public function handle(): void
    {
        try {
            $admins = User::query()
                ->where('role', 'admin')
                ->orWhere('is_admin', true)
                ->get();

            $useApi = app()->isProduction() && (bool) config('services.brevo.key');

            foreach ($admins as $admin) {
                if ($useApi) {
                    $brevo = app(BrevoService::class);
                    $subject = 'New user registered';
                    $html = '<p>Hello ' . e($admin->name ?? 'Admin') . ',</p>' .
                        '<p>A new user has registered and is pending approval:</p>' .
                        '<p><strong>Name:</strong> ' . e($this->newUser->name) . '<br>' .
                        '<strong>Email:</strong> ' . e($this->newUser->email) . '</p>' .
                        '<p>Please review the account in the Users page.</p>';

                    $senderEmail = config('mail.from.address');
                    $senderName = config('mail.from.name');

                    $sent = $brevo->sendRawEmail($admin->email, $subject, $html, $senderEmail, $senderName);

                    Log::info('[NotifyAdminsOfNewUser] Attempted Brevo API send', [
                        'admin_id' => $admin->id,
                        'email' => $admin->email,
                        'api' => true,
                        'success' => $sent,
                    ]);

                    // Always create database notification; disable mail to avoid duplicates
                    $admin->notifyNow(new NewUserRegistered($this->newUser, false));

                    if (!$sent) {
                        // Fallback to Notification mail channel (SMTP/failover->log)
                        $admin->notifyNow(new NewUserRegistered($this->newUser, true));
                        Log::info('[NotifyAdminsOfNewUser] Fallback notification dispatched', [
                            'admin_id' => $admin->id,
                            'email' => $admin->email,
                        ]);
                    }
                } else {
                    // Default path (local/dev or missing API key)
                    $admin->notifyNow(new NewUserRegistered($this->newUser, true));
                }
            }

            Log::info('[NotifyAdminsOfNewUser] Notifications sent', [
                'new_user_id' => $this->newUser->id,
                'admin_count' => $admins->count(),
            ]);
        } catch (\Throwable $e) {
            Log::error('[NotifyAdminsOfNewUser] Failed to notify admins', [
                'new_user_id' => $this->newUser->id ?? null,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
