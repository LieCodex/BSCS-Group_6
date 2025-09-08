<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\CommentController;

// Home and post listings
Route::get('/', [PostController::class, 'showAllPosts'])->name('posts.all');
Route::get('/my-posts', [PostController::class, 'showUserPosts'])
    ->middleware('auth')
    ->name('posts.mine');

// Post routes (protected by auth)
Route::middleware('auth')->group(function () {
    Route::get('/create-post', [PostController::class, 'showCreatePost'])->name('posts.create.form');
    Route::post('/create-post', [PostController::class, 'createPost'])->name('posts.create');

    Route::get('/edit-post/{post}', [PostController::class, 'showEditScreen'])->name('posts.edit.form');
    Route::put('/edit-post/{post}', [PostController::class, 'updatePost'])->name('posts.update');

    Route::delete('/delete-post/{post}', [PostController::class, 'deletePost'])->name('posts.delete');

    // Comment routes
    Route::post('/posts/{post}/comments', [CommentController::class, 'createComment'])->name('comments.create');
    Route::delete('/comments/{comment}', [CommentController::class, 'deleteComment'])->name('comments.delete');
});

// Dashboard routes
Route::prefix('dashboard')->middleware('auth')->group(function () {
    Route::get('/', function () {
        $posts = \App\Models\Post::latest()->get();
        return view('dashboard.home', compact('posts'));
    })->name('dashboard.home');

    Route::get('/profile', fn() => view('dashboard.profile'))->name('dashboard.profile');
    Route::get('/messages', fn() => view('dashboard.messages'))->name('dashboard.messages');
    Route::get('/notifications', fn() => view('dashboard.notifications'))->name('dashboard.notifications');
    Route::get('/bookmarks', fn() => view('dashboard.bookmarks'))->name('dashboard.bookmarks');
});

// Registration & Auth
Route::get('/register-form', function () {
    return view('register-form');
})->name('register.form');

Route::post('/register', [UserController::class, 'register'])->name('register');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');
Route::post('/login', [UserController::class, 'login'])->name('login');

// Google OAuth
Route::get('auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
