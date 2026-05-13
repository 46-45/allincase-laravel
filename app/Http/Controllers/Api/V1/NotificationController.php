<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $limit = $request->query('limit', 20);
        $offset = $request->query('offset', 0);

        $notifications = Notification::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->skip($offset)
            ->take($limit)
            ->get();

        return response()->json($notifications);
    }

    public function unreadCount(Request $request)
    {
        $user = $request->user();

        $count = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        return response()->json(['unread_count' => $count]);
    }

    public function markRead(Request $request)
    {
        $request->validate(['notification_ids' => 'required|array']);

        $user = $request->user();

        Notification::where('user_id', $user->id)
            ->whereIn('id', $request->notification_ids)
            ->update(['is_read' => true]);

        return response()->noContent();
    }

    public function markAllRead(Request $request)
    {
        $user = $request->user();

        Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->noContent();
    }
}
