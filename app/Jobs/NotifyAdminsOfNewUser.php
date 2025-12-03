<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\NewUserRegistered;
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

            foreach ($admins as $admin) {
                // Run synchronously here; job itself is dispatched after response
                $admin->notifyNow(new NewUserRegistered($this->newUser));
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
