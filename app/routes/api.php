    <?php

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Api\AuthController;
    use App\Http\Controllers\PostController;
    use App\Http\Controllers\CommentController;
    use App\Http\Controllers\CommentLikesController;
    use App\Http\Controllers\FollowController;
    use App\Http\Controllers\NotificationController;
    use App\Http\Controllers\PostLikesController;
    use App\Http\Controllers\SearchController;
    use App\Http\Controllers\UserController;
    use App\Http\Controllers\AiController;
    use App\Http\Controllers\ConversationController;
    use App\Http\Controllers\WebhookController;
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
    Route::post('/webhook/reverb-message', [WebhookController::class, 'handleReverbMessage']);
    // Example test route
    Route::get('/ping', function () {
        return response()->json(['message' => 'pong']);
    });
    Route::get('/posts', [PostController::class, 'apiGetPosts']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/posts', [PostController::class, 'apiCreatePost']);
        Route::put('/posts/{post}', [PostController::class, 'apiUpdatePost']);
        Route::delete('/posts/{post}', [PostController::class, 'apiDeletePost']);
    });
    // Route::post('/register', [AuthController::class, 'register']);
    // Route::post('/login', [AuthController::class, 'login']);

    Route::post('/register', [UserController::class, 'apiRegister']);
    Route::post('/login', [UserController::class, 'apiLogin']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/posts/{post}/comments', [CommentController::class, 'apiCreateComment']);
        Route::delete('/comments/{comment}', [CommentController::class, 'apiDeleteComment']);
        Route::get('/comments/{comment}', [CommentController::class, 'apiEdit']);
        Route::put('/comments/{comment}', [CommentController::class, 'apiUpdate']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/comments/{comment}/like', [CommentLikesController::class, 'apiLike']);
        Route::post('/comments/{comment}/unlike', [CommentLikesController::class, 'apiUnlike']);
    });


    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/users/{user}/follow', [FollowController::class, 'apiFollow']);
        Route::post('/users/{user}/unfollow', [FollowController::class, 'apiUnfollow']);
        Route::get('/users/{user}/is-following', [FollowController::class, 'apiIsFollowing']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/notifications', [NotificationController::class, 'apiIndex']);
        Route::post('/notifications/{notification}/seen', [NotificationController::class, 'apiMarkAsSeen']);
        Route::get('/notifications/unseen-count', [NotificationController::class, 'apiUnseenCount']);
        Route::post('/notifications/mark-all-seen', [NotificationController::class, 'apiMarkAllAsSeen']);
    });



    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/posts/{post}/like', [PostLikesController::class, 'apiLike']);
        Route::post('/posts/{post}/unlike', [PostLikesController::class, 'apiUnlike']);
    });



    Route::middleware('auth:sanctum')->get('/search', [SearchController::class, 'apiSearch']);


    Route::middleware('auth:sanctum')->group(function() {
        Route::get('/user/profile', [UserController::class, 'apiProfile']);
        Route::put('/user/profile', [UserController::class, 'apiUpdateProfile']);
        Route::get('/user/{id}', [UserController::class, 'apiShow']);
    });

// AI Chatbot routes
Route::get('/conversations/{id}/messages', [ConversationController::class, 'recentMessages']);
Route::post('/ai-reply', [AiController::class, 'store']);