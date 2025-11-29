<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Aws\Rekognition\RekognitionClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Str;


class PostController extends Controller
{
    // Show create post form
public function createPost(Request $request)
{
    $incomingFields = $request->validate([
        'body'  => 'nullable|max:500',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240'
    ]);

    $incomingFields['body']    = strip_tags($incomingFields['body']);
    $incomingFields['user_id'] = auth()->id();

    // Create the post
    $post = Post::create($incomingFields);

    // Setup Rekognition client
    $rekognition = new RekognitionClient([
        'version' => 'latest',
        'region'  => config('filesystems.disks.s3.region'),
        'credentials' => [
            'key'    => config('filesystems.disks.s3.key'),
            'secret' => config('filesystems.disks.s3.secret'),
        ],
    ]);

    // Handle image uploads
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $file) {
            if ($file && $file->isValid()) {
                $extension = strtolower($file->getClientOriginalExtension());

                // Upload to DigitalOcean Spaces
                $filename = Str::random(12) . '_' . time() . '.' . $extension;
                $path = Storage::disk('spaces')->putFileAs(
                    'post_images',
                    $file,
                    $filename,
                    ['visibility' => 'public']
                );

                if ($path) {
                    $url = Storage::disk('spaces')->url($path);

                    if ($extension !== 'gif') {
                        // ✅ Run Rekognition Moderation for non-GIFs
                        $result = $rekognition->detectModerationLabels([
                            'Image' => [
                                'Bytes' => file_get_contents($file->getRealPath()),
                            ],
                            'MinConfidence' => 80,
                        ]);

                        $labels = $result['ModerationLabels'];
                        $flagged = false;

                        foreach ($labels as $label) {
                            if (in_array($label['Name'], [
                                'Explicit Nudity',
                                'Sexual Activity',
                                'Violence',
                                'Drugs'
                            ])) {
                                $flagged = true;
                                break;
                            }
                        }

                        if ($flagged) {
                            // Delete image and post if unsafe
                            Storage::disk('spaces')->delete($path);
                            $post->delete();

                            return redirect()
                                ->route('dashboard.home')
                                ->with('success', 'Image rejected due to unsafe content.');
                        }
                    }

                    // Save image record (GIFs skip moderation, still saved)
                    $post->images()->create(['image_path' => $url]);
                }
            }
        }
    }
    if ($post->body && preg_match('/@' . preg_quote(env('AI_BOT_USERNAME')) . '\b/i', $post->body)) {
    
        // Build prompt for Gemini
        $prompt = "You are a friendly, helpful social media bot named Squeal. A user mentioned you in a new post. 
        Their post is: \"{$post->body}\". Write a brief, friendly, and helpful reply as a comment.";

        // Ask Gemini
        $gemini = app(\App\Services\GeminiService::class);
        $aiReply = $gemini->askGemini($prompt);

        $post->comments()->create([
            'user_id' => config('services.gemini.bot_user_id'), 
            'content' => $aiReply, 
        ]);
    }
    return redirect()->route('dashboard.home')
        ->with('success', 'Post created successfully!');
    
}

    // Show edit form
    public function showEditScreen(Post $post) {
        if (auth()->id() !== $post->user_id) {
            return redirect('/');
        }
        return view('components.edit-post', ['post' => $post]);
    }

    // Update post
    public function updatePost(Post $post, Request $request) {
        if (auth()->id() !== $post->user_id) {
            return redirect('/');
        }

        $incomingFields = $request->validate([
            'body'  => 'nullable|max:500',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240'
        ]);

        $incomingFields['body']  = strip_tags($incomingFields['body']);

        $post->update($incomingFields);

        // Handle new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file && $file->isValid()) {
                    $filename = Str::random(12) . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $path = Storage::disk('spaces')->putFileAs('post_images', $file, $filename, ['visibility' => 'public']);
                    if ($path) {
                        $url = Storage::disk('spaces')->url($path);
                        $post->images()->create(['image_path' => $url]);
                    }
                }
            }
        }

        return redirect()->route('dashboard.home')->with('success', 'Post created successfully!');
    }

    // Delete post
    public function deletePost(Post $post) {
        if (auth()->id() !== $post->user_id) {
            return redirect('/');
        }

        foreach ($post->images as $image) {
            $parsedUrl = parse_url($image->image_path, PHP_URL_PATH);
            $parsedUrl = ltrim($parsedUrl, '/');
            $key = str_replace('squeal-spaces-file-storage/', '', $parsedUrl);

            if (!empty($key)) {
                Storage::disk('spaces')->delete($key);
            }

            $image->delete();
        }

        $post->delete();

        return redirect('/home')->with('success', 'Post deleted successfully!');
    }



    // Show all posts (global feed)
// Show all posts (global feed)
public function showAllPosts()
{
    $user = auth()->user();

    $followedIds = $user
        ? $user->following()->pluck('followed_id')->toArray()
        : [];

    // Step 1: Base scoring inside DB
    $posts = Post::with(['user', 'images', 'comments', 'likes'])
        ->selectRaw("
            posts.*,
            (
                1
                + (2 * (select count(*) from post_likes where post_likes.post_id = posts.id))
                + (select count(*) from comments where comments.post_id = posts.id)
                + (0.5 * (
                    select count(*) 
                    from comment_likes 
                    join comments on comments.id = comment_likes.comment_id 
                    where comments.post_id = posts.id
                ))
                + 50 / (1 + TIMESTAMPDIFF(HOUR, posts.created_at, NOW()))
            ) as base_score
        ")
        ->orderByDesc('base_score')
        ->paginate(50);

    // Step 2: Refine scoring in PHP
    $posts->getCollection()->transform(function ($post) use ($user, $followedIds) {
        $score = $post->base_score;

        // Downweight if user already liked
        if ($user && $post->isLikedBy($user)) {
            $score *= 0.4;
        }

        // Boost if post author is followed
        if (in_array($post->user_id, $followedIds)) {
            $score *= 1.5;
        }

        // Add some randomness
        $score += (mt_rand(0, 10) / 10 * 0.2 * $score);

        $post->score = $score;
        return $post;
    });

    // Step 3: Resort after refinement
    $posts->setCollection(
        $posts->getCollection()->sortByDesc('score')->values()
    );

    return view('dashboard.home', ['posts' => $posts]);
}




    
  // Show single post with comments
    public function show(Post $post){
    $post->load(['user', 'images', 'comments.user']); 
    return view('components.post', compact('post'));
    }


    public function apiGetPosts(Request $request)
    {
        $user = auth()->user();

        $followedIds = $user
            ? $user->following()->pluck('followed_id')->toArray()
            : [];

        // Step 1: Base scoring inside DB
        $posts = Post::with(['user', 'images', 'comments.user', 'likes'])
            ->selectRaw("
                posts.*,
                (
                    1
                    + (2 * (select count(*) from post_likes where post_likes.post_id = posts.id))
                    + (select count(*) from comments where comments.post_id = posts.id)
                    + (0.5 * (
                        select count(*) 
                        from comment_likes 
                        join comments on comments.id = comment_likes.comment_id 
                        where comments.post_id = posts.id
                    ))
                    + 50 / (1 + TIMESTAMPDIFF(HOUR, posts.created_at, NOW()))
                ) as base_score
            ")
            ->get();

        // Step 2: Refine scoring in PHP
        $posts->transform(function ($post) use ($user, $followedIds) {

            $score = $post->base_score;

            $post->is_liked = $user ? $post->isLikedBy($user) : false;
            // Downweight if user already liked
            if ($user && $post->isLikedBy($user)) {
                $score *= 0.4;
            }

            // Boost if post author is followed
            if (in_array($post->user_id, $followedIds)) {
                $score *= 1.5;
            }

            // Add some randomness
            $score += (mt_rand(0, 10) / 10 * 0.2 * $score);

            $post->score = $score;
            return $post;
        });

        // Step 3: Resort after refinement
        $sorted = $posts->sortByDesc('score')->values();

        // Step 4: Return JSON identical to UI
        return response()->json([
            'success' => true,
            'data' => $sorted
        ]);
    }

    public function apiCreatePost(Request $request)
    {
            $data = $request->validate([
                'body' => 'required|string|max:500',
                'image_urls' => 'array',            // optional array of image URLs
                'image_urls.*' => 'url'             // validate each entry is a URL
            ]);

            $post = auth()->user()->posts()->create([
                'body' => $data['body'],
            ]);

            // Save any image URLs provided
            if (!empty($data['image_urls'])) {
                foreach ($data['image_urls'] as $url) {
                    $post->images()->create(['image_path' => $url]);
                }
            }

            return response()->json([
                'success' => true,
                'post' => $post->load('images', 'user')
            ], 201);
    }
}
