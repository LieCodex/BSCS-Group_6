<?php

namespace App\Http\Controllers;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 

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
        //secruity so can post codes
        
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id']= auth()->id();

        $post = Post::create($incomingFields);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('post_images', 'public');
                $post->images()->create(['image_path' => $path]); 
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

        $post->update($incomingFields);// no need for sql queries automatically does it pretty handy ayee

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $path = $file->store('post_images', 'public');
                    $post->images()->create(['image_path' => $path]);
                }
            }

        return redirect('/');

    }

    public function deletePost(Post $post){
        if (auth()->user()->id != $post['user_id']){
            return redirect('/');
        }

        foreach ($post->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        $post->delete();
        return redirect('/');
    }
    //shows all posts
    public function showAllPosts(){
        $posts = Post::with('images')->latest()->get();
        return view('home',['posts' => $posts]);
    }
    //shows only the auth users posts
    public function showUserPosts(){
        $posts = Post::with('images')->where('user_id', auth()->id())->latest()->get();
        return view('home', ['posts'=> $posts]);
    }
    
}

