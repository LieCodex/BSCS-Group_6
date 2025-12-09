<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
                ->with([
                    'actor:id,name,avatar',   // actor details
                    'post:id,body',           // post preview
                ])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($n) {
                    return [
                        'notification_id' => $n->id,
                        'post_id' => $n->post_id,
                        'actor_id' => $n->actor_id,
                        'type' => $n->type,
                        'is_seen' => $n->is_seen,
                        'preview_text' => $n->preview_text,
                        'created_at' => $n->created_at->toDateTimeString(),

                        // actor info
                        'actor' => $n->actor ? [
                            'id' => $n->actor->id,
                            'name' => $n->actor->name,
                            'avatar' => $n->actor->avatar,
                        ] : null,

                        // post preview
                        'post' => $n->post ? [
                            'id' => $n->post->id,
                            'body_preview' => Str::limit($n->post->body, 80),
                        ] : null,
                    ];
                });

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
