<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AiController;
use App\Http\Controllers\ConversationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| These routes are loaded by the RouteServiceProvider and assigned
| to the "api" middleware group. Enjoy building your API!
|
*/

// Example test route
Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});
Route::get('/posts', [PostController::class, 'apiGetPosts']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/posts', [PostController::class, 'apiCreatePost']);
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// AI Chatbot routes
Route::get('/conversations/{id}/messages', [ConversationController::class, 'recentMessages']);
Route::post('/ai-reply', [AiController::class, 'store']);
