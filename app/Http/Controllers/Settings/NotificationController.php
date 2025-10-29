<?php

namespace App\Http\Controllers\Settings;

use Exception;
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
                    'is_read' => $item->is_read,
                    'created_at' => $item->notification->created_at->diffForHumans(),
                ];
            });
            return response()->json([
                'status' => true,
                'message' => 'Notifications fetched successfully.',
                'data' => $data
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch notifications: ' . $e->getMessage()
            ], 500);
        }
    }
}
