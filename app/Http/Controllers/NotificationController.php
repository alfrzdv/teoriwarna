<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // View all notifications
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orWhereNull('user_id') // Include admin notifications for all users
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    // Mark as read
    public function markAsRead(Notification $notification)
    {
        // Ensure notification belongs to current user or is a general notification
        if ($notification->user_id !== null && $notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->update(['read_at' => now()]);

        return back()->with('success', 'Notifikasi ditandai sebagai sudah dibaca.');
    }

    // Mark all as read
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('success', 'Semua notifikasi ditandai sebagai sudah dibaca.');
    }

    // Delete notification
    public function destroy(Notification $notification)
    {
        // Ensure notification belongs to current user or is a general notification
        if ($notification->user_id !== null && $notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->delete();

        return back()->with('success', 'Notifikasi berhasil dihapus.');
    }
}
