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