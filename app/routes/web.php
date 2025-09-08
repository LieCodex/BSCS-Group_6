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

    // blog post routes
Route::post('/create-post', [PostController::class, 'createPost']);
Route::get('/edit-post/{post}', [PostController::class, 'showEditScreen']);
Route::put('/edit-post/{post}', [PostController::class, 'updatePost']);
Route::delete('/delete-post/{post}', [PostController::class,'deletePost']);


// Dashboard routes (no login required)
Route::prefix('dashboard')->group(function () {
    Route::get('/', function () {
        $posts = \App\Models\Post::latest()->get();
        return view('dashboard.home', compact('posts'));
    })->name('dashboard.home');

    Route::get('/profile', fn() => view('dashboard.profile'))->name('dashboard.profile');
    Route::get('/messages', fn() => view('dashboard.messages'))->name('dashboard.messages');
    Route::get('/notifications', fn() => view('dashboard.notifications'))->name('dashboard.notifications');
    Route::get('/bookmarks', fn() => view('dashboard.bookmarks'))->name('dashboard.bookmarks');
});



Route::get('auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'callback']);

//React routes
Route::get('/welcome_react', function () {
    return view('welcome_react');
});