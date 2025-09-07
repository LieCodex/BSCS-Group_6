<?php

namespace App\Http\Controllers;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function showCreatePost(){
        return view('create-post');
    }

    public function createPost(Request $request){
    $incomingFields = $request->validate([
        'title' =>'required',
        'body'=>'required',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);
    $incomingFields['title'] = strip_tags($incomingFields['title']);
    $incomingFields['body'] = strip_tags($incomingFields['body']);
    $incomingFields['user_id']= auth()->id();

    // Create the post in database
    $post = Post::create($incomingFields);

    // If images are uploaded, process them
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $file) {
            if ($file && $file->isValid()) {
                // Generate a random filename
                $filename = Str::random(12) . '_' . time() . '.' . $file->getClientOriginalExtension();
                // Upload image to DigitalOcean Spaces
                $path = Storage::disk('spaces')->putFileAs('post_images', $file, $filename, ['visibility' => 'public']);
                
                // If upload succeeded, save URL in DB
                if ($path) {
                    $url = Storage::disk('spaces')->url($path);
                    $post->images()->create(['image_path' => $url]);
                } else {
                
                }
            }
        }
    }
    return redirect('/');
    }

    /**
     * Show edit screen for a given post.
     * Uses Laravel route-model binding to fetch Post automatically.
     */
     public function showEditScreen(Post $post){//automatically database lookup
        if (auth()->user()->id != $post['user_id']){// note temporary solution so that other users cant update whats not theres
            return redirect('/');                   // please do a proper auth when given time
        }
        return view('edit-post',['post'=>$post]);
    }

    /**
     * Handle updating an existing post, including uploading new images.
     */
    public function updatePost(Post $post, Request $request){
        // Prevent editing if not the post owner
    if (auth()->user()->id != $post['user_id']){
        return redirect('/');
    }

    // Validate and sanitize inputs
    $incomingFields = $request->validate([
        'title' => 'required',
        'body' => 'required',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);
    $incomingFields['title'] = strip_tags($incomingFields['title']);
    $incomingFields['body'] = strip_tags($incomingFields['body']);


    // Update post fields in DB
    $post->update($incomingFields);


    // Handle new image uploads
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $file) {
            $filename = Str::random(12) . '_' . time() . '.' . $file->getClientOriginalExtension();
                try {
                    // Upload to Spaces
                    $path = Storage::disk('spaces')->putFileAs('post_images', $file, $filename, ['visibility' => 'public']);
                    // If successful, save URL in DB
                    if ($path) {
                        $url = Storage::disk('spaces')->url($path);
                        $post->images()->create(['image_path' => $url]);
                    } else {
                       
                    }
                } catch (\Exception $e) {
                }
        }
    }

    return redirect('/');
    }

    public function deletePost(Post $post){
    if (auth()->user()->id != $post['user_id']){
        return redirect('/');
    }

    foreach ($post->images as $image) {
    $parsedUrl = parse_url($image->image_path, PHP_URL_PATH);
    $parsedUrl = ltrim($parsedUrl, '/');

    // remove the bucket name from the path
    $key = str_replace('squeal-spaces-file-storage/', '', $parsedUrl);



    if (!empty($key)) {
        Storage::disk('spaces')->delete($key);
    }

    $image->delete();
}

    $post->delete();
    return redirect('/');
}

    
}

