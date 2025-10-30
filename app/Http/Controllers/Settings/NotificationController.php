<?php

namespace App\Http\Controllers\Settings;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

// Models
use App\Models\Settings\Notification;
use App\Models\Settings\NotificationUser;

class NotificationController extends Controller
{
    public function getNotifications(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $limit = $request->query('limit', 5);
            $query = NotificationUser::with('notification')
                ->where('user_id', $user->id)
                ->join('notifications', 'notification_users.notification_id', '=', 'notifications.id')
                ->orderBy('notifications.created_at', 'desc')
                ->select('notification_users.*');
            $notifications = ($limit === 'all')
                ? $query->get()
                : $query->limit((int) $limit)->get();
            $data = $notifications->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->notification->title,
                    'body' => $item->notification->body,
                    'savings_history_id' => $item->notification->savings_history_id,
                    'is_read' => $item->is_read,
                    'created_at' => $item->notification->created_at->diffForHumans(),
                ];
            });
            return response()->json([
                'status' => true,
                'message' => 'Notifications fetched successfully.',
                'data' => $data
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch notifications: ' . $e->getMessage()
            ], 500);
        }
    }

    public function markNotificationAsRead(Request $request, int $id): JsonResponse
    {
        try {
            $user = $request->user();
            $notification = NotificationUser::where('user_id', $user->id)->where('notification_id', $id)->first();
            if (!$notification) {
                return response()->json([
                    'status' => false,
                    'message' => 'Notification not found.'
                ], 404);
            }
            $notification->update(['is_read' => true]);
            return response()->json([
                'status' => true,
                'message' => 'Notification marked as read successfully.'
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to mark notification as read: ' . $e->getMessage()
            ], 500);
        }
    }

    public function markAllNotificationsAsRead(Request $request)
    {
        try {
            $user = $request->user();
            $unreadCount = NotificationUser::where('user_id', $user->id)
                ->where('is_read', false)
                ->count();
            if ($unreadCount === 0) {
                return response()->json([
                    'status' => true,
                    'message' => 'All notifications are already marked as read.'
                ], 200);
            }
            NotificationUser::where('user_id', $user->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);
            return response()->json([
                'status' => true,
                'message' => "All ({$unreadCount}) notifications marked as read successfully."
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to mark all notifications as read: ' . $e->getMessage()
            ], 500);
        }
    }
}
