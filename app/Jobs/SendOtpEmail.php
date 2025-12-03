<?php

namespace App\Jobs;

use App\Notifications\EmailOtpNotification;
use App\Models\User;
use App\Services\BrevoService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendOtpEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly User $user,
        public readonly string $code,
        public readonly int $ttlMinutes
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $useApi = app()->isProduction() && (bool) config('services.brevo.key');

            if ($useApi) {
                $brevo = app(BrevoService::class);
                $subject = 'Your Verification Code';
                $html = '<p>Hello ' . e($this->user->name) . ',</p>' .
                    '<p>Use the verification code below to verify your email address:</p>' .
                    '<p><strong>Verification Code: ' . e($this->code) . '</strong></p>' .
                    '<p>This code expires in ' . e((string) $this->ttlMinutes) . ' minutes.</p>' .
                    '<p>If you did not attempt to sign up, you can ignore this email.</p>';

                $senderEmail = config('mail.from.address');
                $senderName = config('mail.from.name');

                $sent = $brevo->sendRawEmail($this->user->email, $subject, $html, $senderEmail, $senderName);

                Log::info('[SendOtpEmail] Attempted Brevo API send', [
                    'user_id' => $this->user->id,
                    'email' => $this->user->email,
                    'api' => true,
                    'success' => $sent,
                ]);

                if (!$sent) {
                    // Fallback to Laravel Notification (SMTP/failover->log)
                    $this->user->notify(new EmailOtpNotification($this->code, $this->ttlMinutes));
                    Log::info('[SendOtpEmail] Fallback notification dispatched', [
                        'user_id' => $this->user->id,
                        'email' => $this->user->email,
                        'ttl_minutes' => $this->ttlMinutes,
                    ]);
                }
            } else {
                // Default path (local/dev or missing API key)
                $this->user->notify(new EmailOtpNotification($this->code, $this->ttlMinutes));
                Log::info('[SendOtpEmail] OTP notification dispatched', [
                    'user_id' => $this->user->id,
                    'email' => $this->user->email,
                    'ttl_minutes' => $this->ttlMinutes,
                ]);
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
