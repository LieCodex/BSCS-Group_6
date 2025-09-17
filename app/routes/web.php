<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\CommentController;

Route::get('/', function () {
    // If user is authenticated, redirect to Tailwind dashboard
    if (auth()->check()) {
        return redirect()->route('dashboard.home');
    }
    return view('dashboard.home');
});

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

// Home route (no dashboard prefix)
Route::middleware('auth')->get('/home', function () {
    $posts = \App\Models\Post::latest()->get();
    return view('dashboard.home', compact('posts'));
})->name('dashboard.home');

// Other dashboard routes
Route::prefix('dashboard')->middleware('auth')->group(function () {
    Route::get('/profile', fn() => view('dashboard.profile'))->name('dashboard.profile');
    Route::get('/messages', fn() => view('dashboard.messages'))->name('dashboard.messages');
    Route::get('/notifications', fn() => view('dashboard.notifications'))->name('dashboard.notifications');
});

// Profile route (only show logged-in user's posts)
Route::get('/profile', [PostController::class, 'showUserPosts'])
    ->middleware('auth')
    ->name('dashboard.profile');

// Registration & Auth
Route::get('/register-form', function () {
    return view('auth.register-form');
})->name('register.form');

Route::get('/login', function () {
    return view('auth.login-form');
})->name('login.form');

Route::post('/register', [UserController::class, 'register'])->name('register');
Route::post('/login', [UserController::class, 'login'])->name('login');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');
Route::put('/profile/update', [UserController::class,'updateProfile'])->name('profile.update');

// Google OAuth
Route::get('auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');