<?php

namespace App\Http\Controllers;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /**
     * Get the user's notifications.
     */
    public function index(): JsonResponse
    {
        Log::info('Fetching notifications for user: ' . auth()->id());

        $notifiableType = User::class;
        $notifications = DB::table('notifications')
            ->where('notifiable_id', auth()->id())
            ->where('notifiable_type', $notifiableType)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($notification) {
                $data = [];
                if (isset($notification->data)) {
                    $decoded = is_string($notification->data) ? json_decode($notification->data, true) : $notification->data;
                    $data = is_array($decoded) ? $decoded : [];
                }

                return [
                    'id' => $notification->id,
                    'message' => $data['message'] ?? 'Notification',
                    'created_at' => Carbon::parse($notification->created_at)->diffForHumans(),
                    'read' => !is_null($notification->read_at),
                    'action_url' => $data['action_url'] ?? null,
                    'type' => $data['type'] ?? null,
                ];
            });

        // Compute unread count without calling unreadNotifications() as a method
        $unreadCount = DB::table('notifications')
            ->where('notifiable_id', auth()->id())
            ->where('notifiable_type', $notifiableType)
            ->whereNull('read_at')
            ->count();
           

        Log::info('Found notifications: ', [
            'count' => $notifications->count(),
            'unread_count' => $unreadCount,
            'notifications' => $notifications->toArray()
        ]);

        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead($id): JsonResponse
    {
        $notification = DatabaseNotification::where('id', $id)
            ->where('notifiable_id', auth()->id())
            ->where('notifiable_type', User::class)
            ->firstOrFail();

        $notification->markAsRead();
        return response()->json(['message' => 'Notification marked as read']);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(): JsonResponse
    {
        DatabaseNotification::where('notifiable_id', auth()->id())
            ->where('notifiable_type', User::class)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        return response()->json(['message' => 'All notifications marked as read']);
    }
}
