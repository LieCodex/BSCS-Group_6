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
}
