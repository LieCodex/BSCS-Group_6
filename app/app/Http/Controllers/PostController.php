<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function createPost(Request $request){
        $incomingFields = $request->validate([
            'title' =>'required',
            'body'=>'required'
        ]);
        //secruity so can post codes
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id']= auth()->id();
        Post::create($incomingFields);
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
        ]);
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);

        $post->update($incomingFields);// no need for sql queries automatically does it pretty handy ayee
        return redirect('/');

    }

    public function deletePost(Post $post){
        if (auth()->user()->id != $post['user_id']){
            return redirect('/');
        }
        $post->delete();
        return redirect('/');
    }
}
