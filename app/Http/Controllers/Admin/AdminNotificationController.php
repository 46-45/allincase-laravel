<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    public function index(Request $request)
    {
        $admin = AdminAuthController::getAdmin($request);
        if (!$admin) return redirect('/admin/login');

        $logs = Notification::with('user')
            ->where('type', 'broadcast')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.notifications.index', compact('admin', 'logs'));
    }

    public function send(Request $request)
    {
        $admin = AdminAuthController::getAdmin($request);
        if (!$admin) return redirect('/admin/login');

        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'target' => 'required|in:all,lawyer,client',
        ]);

        $query = User::where('is_active', true);

        if ($request->target === 'lawyer') {
            $query->where('role', 'lawyer');
        } elseif ($request->target === 'client') {
            $query->where('role', 'client');
        } else {
            $query->whereIn('role', ['lawyer', 'client']);
        }

        $users = $query->get();
        $sent = 0;

        foreach ($users as $user) {
            NotificationService::sendPushNotification(
                $user->id,
                $request->title,
                $request->body,
                'broadcast',
                null,
                ['type' => 'broadcast']
            );
            $sent++;
        }

        return redirect('/admin/notifications')->with('success', "Notifikasi berhasil dikirim ke {$sent} pengguna.");
    }
}
