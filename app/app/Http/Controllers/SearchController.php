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

        return view('dashboard.search-results', compact('posts', 'users', 'q'));
    }
}