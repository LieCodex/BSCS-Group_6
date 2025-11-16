<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $q = $request->input('q');

        $posts = Post::where('body', 'like', "%{$q}%")
            ->with('user', 'images')
            ->get();

        $users = User::where('name', 'like', "%{$q}%")
            ->orWhere('email', 'like', "%{$q}%")
            ->get();

        return view('components.search-results', compact('posts', 'users', 'q'));
    }

    public function apiSearch(Request $request)
    {
        $q = $request->input('q');

        // Search posts
        $posts = Post::where('body', 'like', "%{$q}%")
            ->with(['user', 'images', 'likes', 'comments'])
            ->get()
            ->map(function ($post) {
                $post->is_liked = auth()->user() ? $post->isLikedBy(auth()->user()) : false;
                return $post;
            });

        // Search users
        $users = User::where('name', 'like', "%{$q}%")
            ->orWhere('email', 'like', "%{$q}%")
            ->get();

        return response()->json([
            'success' => true,
            'query' => $q,
            'posts' => $posts,
            'users' => $users,
        ]);
    }
}