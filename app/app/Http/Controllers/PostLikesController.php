<?php

namespace App\Http\Controllers;

use App\Models\Post;


class PostLikesController extends Controller
{
    public function like(Post $post)
    {
        $user = auth()->user();
        if (!$post->likes()->where('user_id', $user->id)->exists()) {
            $post->likes()->create(['user_id' => $user->id]);
        }
       return response()->json([
        'liked' => true,
        'likes' => $post->likes()->count()
        ]);
    }

    public function unlike(Post $post)
    {
        $user = auth()->user();
        $post->likes()->where('user_id', $user->id)->delete();
            return response()->json([
                'liked' => false,
                'likes' => $post->likes()->count()
            ]);
    }
}