<?php

namespace App\Jobs;

use App\Notifications\EmailOtpNotification;
use App\Models\User;
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
            $this->user->notify(new EmailOtpNotification($this->code, $this->ttlMinutes));
            Log::info('[SendOtpEmail] OTP notification dispatched', [
                'user_id' => $this->user->id,
                'email' => $this->user->email,
                'ttl_minutes' => $this->ttlMinutes,
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
