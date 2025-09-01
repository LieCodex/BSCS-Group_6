<?php

use App\Models\Post;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GoogleController;

Route::get('/', function(){
    $posts = auth()->check() ? auth()->user()->userPosts()->get() : Post::all();
    return view('home', ['posts'=>$posts]);
});
Route::post('/register', [UserController::class, 'register']);
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/login', [UserController::class, 'login']);

//blog post routes
Route::get('/create-post', [PostController::class,'showCreatePost']);
Route::post('/create-post', [PostController::class, 'createPost']);
Route::get('/edit-post/{post}',[PostController::class, 'showEditScreen']);
Route::put('/edit-post/{post}',[PostController::class, 'updatePost']);
Route::delete('delete-post/{post}',[PostController::class,'deletePost']);


//google route
Route::get('auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'callback']);

