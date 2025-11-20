<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        // Use the global $notifications if desired, or fetch fresh
        $notifications = Notification::where('user_id', auth()->id())
                                     ->orderBy('created_at', 'desc')
                                     ->get();

        return view('dashboard.notifications', compact('notifications'));
    }

    public function markAsSeen(Notification $notification)
    {
        $notification->update(['is_seen' => true]);

        if ($notification->post_id) {
            return redirect()->route('posts.show', $notification->post_id);
        }

        if ($notification->comment_id) {
            return redirect()->route('posts.show', $notification->comment->post_id);
        }

        return back();
    }

    public function unseenCount()
    {
        $count = Notification::where('user_id', auth()->id())
                             ->where('is_seen', false)
                             ->count();

        return response()->json(['count' => $count]);
    }
    public function markAllAsSeen()
    {
        Notification::where('user_id', auth()->id())
            ->where('is_seen', false)
            ->update(['is_seen' => true]);

        return back()->with('success', 'All notifications marked as seen.');
    }



    public function apiIndex()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $notifications
        ]);
    }

    // Mark a single notification as seen
    public function apiMarkAsSeen(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $notification->update(['is_seen' => true]);

        return response()->json([
            'success' => true,
            'notification' => $notification
        ]);
    }

    // Count unseen notifications
    public function apiUnseenCount()
    {
        $count = Notification::where('user_id', auth()->id())
            ->where('is_seen', false)
            ->count();

        return response()->json([
            'success' => true,
            'unseen_count' => $count
        ]);
    }

    // Mark all notifications as seen
    public function apiMarkAllAsSeen()
    {
        Notification::where('user_id', auth()->id())
            ->where('is_seen', false)
            ->update(['is_seen' => true]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as seen.'
        ]);
    }

}
