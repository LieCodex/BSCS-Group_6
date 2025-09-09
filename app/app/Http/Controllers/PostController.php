<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Str;

class PostController extends Controller
{
    // Show create post form
    public function showCreatePost() {
        return view('create-post');
    }

    // Create a post
    public function createPost(Request $request) {
        $incomingFields = $request->validate([
            'body'  => 'required|max:500',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $incomingFields['body']    = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();

        // Create post
        $post = Post::create($incomingFields);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file && $file->isValid()) {
                    $filename = Str::random(12) . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $path = Storage::disk('spaces')->putFileAs('post_images', $file, $filename, ['visibility' => 'public']);
                    
                    if ($path) {
                        $url = Storage::disk('spaces')->url($path);
                        $post->images()->create(['image_path' => $url]);
                    }
                }
            }
        }

        return redirect()->route('dashboard.home')->with("success, Post created succesfully");
    }

    // Show edit form
    public function showEditScreen(Post $post) {
        if (auth()->id() !== $post->user_id) {
            return redirect('/');
        }
        return view('dashboard.edit-post', ['post' => $post]);
    }

    // Update post
    public function updatePost(Post $post, Request $request) {
        if (auth()->id() !== $post->user_id) {
            return redirect('/');
        }

        $incomingFields = $request->validate([
            'body'  => 'required|max:500',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $incomingFields['body']  = strip_tags($incomingFields['body']);

        $post->update($incomingFields);

        // Handle new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file && $file->isValid()) {
                    $filename = Str::random(12) . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $path = Storage::disk('spaces')->putFileAs('post_images', $file, $filename, ['visibility' => 'public']);
                    if ($path) {
                        $url = Storage::disk('spaces')->url($path);
                        $post->images()->create(['image_path' => $url]);
                    }
                }
            }
        }

        return redirect('/dashboard')->with('success', 'Post updated successfully!');
    }

    // Delete post
    public function deletePost(Post $post) {
        if (auth()->id() !== $post->user_id) {
            return redirect('/');
        }

        foreach ($post->images as $image) {
            $parsedUrl = parse_url($image->image_path, PHP_URL_PATH);
            $parsedUrl = ltrim($parsedUrl, '/');
            $key = str_replace('squeal-spaces-file-storage/', '', $parsedUrl);

            if (!empty($key)) {
                Storage::disk('spaces')->delete($key);
            }

            $image->delete();
        }

        $post->delete();

        return redirect('/dashboard')->with('success', 'Post deleted successfully!');
    }

    // Show all posts
    public function showAllPosts() {
        $posts = Post::with('images')->latest()->get();
        return view('home', ['posts' => $posts]);
    }

    // Show logged-in user's posts
    public function showUserPosts() {
        $posts = Post::with('images')->where('user_id', auth()->id())->latest()->get();
        return view('home', ['posts' => $posts]);
    }
}
