<?php

namespace App\Http\Controllers;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Get the user's notifications.
     */
    public function index(): JsonResponse
    {
        \Log::info('Fetching notifications for user: ' . auth()->id());

        $notifications = auth()->user()->notifications()
            ->take(10)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'message' => $notification->data['message'] ?? 'Notification',
                    'created_at' => $notification->created_at->diffForHumans(),
                    'read' => !is_null($notification->read_at),
                    'action_url' => $notification->data['action_url'] ?? null,
                    'type' => $notification->data['type'] ?? null,
                ];
            });

        $unreadCount = auth()->user()->unreadNotifications()->count();

        \Log::info('Found notifications: ', [
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
        $notification = auth()->user()->notifications()->where('id', $id)->firstOrFail();
        $notification->markAsRead();
        return response()->json(['message' => 'Notification marked as read']);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(): JsonResponse
    {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['message' => 'All notifications marked as read']);
    }
}
