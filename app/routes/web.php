<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\PostLikesController;
use App\Http\Controllers\CommentLikesController;
use App\Http\Controllers\NotificationController;


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
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

    // Comment routes
    Route::post('/posts/{post}/comments', [CommentController::class, 'createComment'])->name('comments.create');
    Route::delete('/comments/{comment}', [CommentController::class, 'deleteComment'])->name('comments.delete');
    
    Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');

    Route::post('/posts/{post}/like', [PostLikesController::class, 'like'])->name('posts.like');
    Route::delete('/posts/{post}/like', [PostLikesController::class, 'unlike'])->name('posts.unlike');

    Route::post('/comments/{comment}/like', [CommentLikesController::class, 'like'])->name('comments.like');
    Route::delete('/comments/{comment}/like', [CommentLikesController::class, 'unlike'])->name('comments.unlike');

    //notif routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('dashboard.notifications')->middleware('auth');
    Route::post('/notifications/{notification}/seen', [NotificationController::class, 'markAsSeen'])->name('notifications.seen');
    Route::get('/notifications/unseen-count', [NotificationController::class, 'unseenCount'])->name('notifications.unseenCount');
});

// Home route (no dashboard prefix)
Route::middleware('auth')->get('/home', [PostController::class, 'showAllPosts'])->name('dashboard.home');


// Other dashboard routes
Route::prefix('')->middleware('auth')->group(function () {
    Route::get('/messages', fn() => view('dashboard.messages'))->name('dashboard.messages');
});

// Profile route (only show logged-in user's posts)
Route::get('/profile', [UserController::class, 'Profile'])
    ->middleware('auth')
    ->name('dashboard.profile');

Route::get('/user/{id}', [UserController::class, 'show'])
    ->name('user.profile');


// Registration & Auth
Route::get('/register-form', function () {
    return view('auth.register-form');
})->name('register.form');

Route::get('/login', function () {
    return view('auth.login-form');
})->name('login.form');

Route::get('/posts/{post}', [App\Http\Controllers\PostController::class, 'show'])->name('posts.show');
Route::get('/search', [App\Http\Controllers\SearchController::class, 'search'])->name('search');

Route::post('/register', [UserController::class, 'register'])->name('register');
Route::post('/login', [UserController::class, 'login'])->name('login');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');
Route::post('/profile/update', [UserController::class,'updateProfile'])->name('profile.update');

// Google OAuth
Route::get('auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

Route::post('/follow/{user}', [FollowController::class, 'follow'])->name('follow');
Route::delete('/unfollow/{user}', [FollowController::class, 'unfollow'])->name('unfollow');
