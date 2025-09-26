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
        
        if ($post->user_id !== auth()->id()) {
        $post->user->notifications()->create([
            'actor_id' => auth()->id(),
            'post_id' => $post->id,
            'type' => 'like_post',
            'preview_text' => 'liked your post.',
        ]);
        //milestone 5,10,15 etc
        $totalLikes = $post->like()->count();
        if ($totalLikes % 5 === 0) {
        $exists = $post->user->notifications()
            ->where('post_id', $post->id)
            ->where('type', 'milestone')
            ->where('preview_text', "Your post has reached {$totalLikes} likes!")
            ->exists();
        if (!$exists) {
            $post->user->notifications()->create([
                'post_id' => $post->id,
                'type' => 'milestone',
                'preview_text' => "Your post has reached {$totalLikes} likes!",
            ]);
        }}}
        return response()->json([
            'liked' => true,
            'likes' => $post->likes()->count(),
            'like_url' => route('posts.like', $post->id),
            'unlike_url' => route('posts.unlike', $post->id),
        ]);
    }

    public function unlike(Post $post)
    {
        $user = auth()->user();
        $post->likes()->where('user_id', $user->id)->delete();
        return response()->json([
            'liked' => false,
            'likes' => $post->likes()->count(),
            'like_url' => route('posts.like', $post->id),
            'unlike_url' => route('posts.unlike', $post->id),
        ]);
    }
}