<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Str;
use App\Http\Controllers\FollowController;

class PostController extends Controller
{
    // Show create post form
    public function showCreatePost() {
        return view('components.create-post');
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
        return view('components.edit-post', ['post' => $post]);
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

        return redirect()->route('dashboard.home')->with('success', 'Post created successfully!');
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

        return redirect('/home')->with('success', 'Post deleted successfully!');
    }



    // Show all posts (global feed)
// Show all posts (global feed)
public function showAllPosts()
{
    $user = auth()->user();

    $followedIds = $user
        ? $user->following()->pluck('followed_id')->toArray()
        : [];

    // Step 1: Base scoring inside DB
    $posts = Post::with(['user', 'images', 'comments', 'likes'])
        ->selectRaw("
            posts.*,
            (
                1
                + (2 * (select count(*) from post_likes where post_likes.post_id = posts.id))
                + (select count(*) from comments where comments.post_id = posts.id)
                + (0.5 * (
                    select count(*) 
                    from comment_likes 
                    join comments on comments.id = comment_likes.comment_id 
                    where comments.post_id = posts.id
                ))
                + 50 / (1 + TIMESTAMPDIFF(HOUR, posts.created_at, NOW()))
            ) as base_score
        ")
        ->orderByDesc('base_score')
        ->paginate(5);

    // Step 2: Refine scoring in PHP
    $posts->getCollection()->transform(function ($post) use ($user, $followedIds) {
        $score = $post->base_score;

        // Downweight if user already liked
        if ($user && $post->isLikedBy($user)) {
            $score *= 0.4;
        }

        // Boost if post author is followed
        if (in_array($post->user_id, $followedIds)) {
            $score *= 1.5;
        }

        // Add some randomness
        $score += (mt_rand(0, 10) / 10 * 0.2 * $score);

        $post->score = $score;
        return $post;
    });

    // Step 3: Resort after refinement
    $posts->setCollection(
        $posts->getCollection()->sortByDesc('score')->values()
    );

    return view('dashboard.home', ['posts' => $posts]);
}




    
  // Show single post with comments
    public function show(Post $post){
    $post->load(['user', 'images', 'comments.user']); 
    return view('components.post', compact('post'));
    }

}
