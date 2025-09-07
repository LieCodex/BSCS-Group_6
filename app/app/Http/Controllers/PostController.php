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

    $post = Post::create($incomingFields);

    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $file) {
            if ($file && $file->isValid()) {
                $filename = Str::random(12) . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = Storage::disk('spaces')->putFileAs('post_images', $file, $filename, ['visibility' => 'public']);
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

     public function showEditScreen(Post $post){//automatically database lookup
        if (auth()->user()->id != $post['user_id']){// note temporary solution so that other users cant update whats not theres
            return redirect('/');                   // please do a proper auth when given time
        }
        return view('edit-post',['post'=>$post]);
    }

    public function updatePost(Post $post, Request $request){
    if (auth()->user()->id != $post['user_id']){
        return redirect('/');
    }
    $incomingFields = $request->validate([
        'title' => 'required',
        'body' => 'required',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);
    $incomingFields['title'] = strip_tags($incomingFields['title']);
    $incomingFields['body'] = strip_tags($incomingFields['body']);

    $post->update($incomingFields);

    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $file) {
            $filename = Str::random(12) . '_' . time() . '.' . $file->getClientOriginalExtension();
                try {
                    $path = Storage::disk('spaces')->putFileAs('post_images', $file, $filename, ['visibility' => 'public']);
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

