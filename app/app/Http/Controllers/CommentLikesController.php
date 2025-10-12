<?php

namespace App\Http\Controllers;

use App\Models\Comment;

class CommentLikesController extends Controller
{
public function like(Comment $comment)
{
    $user = auth()->user();

    if (!$comment->likes()->where('user_id', $user->id)->exists()) {
        $comment->likes()->create(['user_id' => $user->id]);
    }

    // Notify comment owner
    if ($comment->user_id !== $user->id) {
        $comment->user->notifications()->create([
            'actor_id' => $user->id,
            'comment_id' => $comment->id,
            'type' => 'like_comment',
            'preview_text' => 'liked your comment.',
        ]);
    }

    // Milestone check (5, 10, 15...)
    $totalLikes = $comment->likes()->count();
    if ($totalLikes % 5 === 0) {
        $exists = $comment->user->notifications()
            ->where('comment_id', $comment->id)
            ->where('type', 'milestone')
            ->where('preview_text', "Your comment has reached {$totalLikes} likes!")
            ->exists();
        if (!$exists) {
            $comment->user->notifications()->create([
                'comment_id' => $comment->id,
                'type' => 'milestone',
                'preview_text' => "Your comment has reached {$totalLikes} likes!",
            ]);
        }
    }
    return response()->json([
        'liked' => true,
        'likes' => $comment->likes()->count(),
        'like_url' => route('comments.like', $comment->id),
        'unlike_url' => route('comments.unlike', $comment->id),
    ]);
}

public function unlike(Comment $comment)
{
    $user = auth()->user();

    $comment->likes()->where('user_id', $user->id)->delete();

    return response()->json([
        'liked' => false,
        'likes' => $comment->likes()->count(),
        'like_url' => route('comments.like', $comment->id),
        'unlike_url' => route('comments.unlike', $comment->id),
    ]);
}
};