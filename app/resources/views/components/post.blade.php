@extends('layouts.master')

@section('content')
<div class="p-6 space-y-6">

    <!-- Post Card -->
    <div class="p-4 border border-gray-700 rounded-lg bg-gray-800">
        <!-- User info -->
        <div class="flex items-center gap-2">
            <img 
                src="{{ optional($post->user)->avatar ?: asset('assets/img/default-avatar.png') }}"
                alt="{{ optional($post->user)->name ?? 'Unknown User' }}"
                class="w-8 h-8 rounded-full object-cover">
            <h2 class="font-bold text-orange-400">
                {{ optional($post->user)->name ?? 'Unknown User' }}
            </h2>
        </div>

        <!-- Post body -->
        <p class="text-gray-300 mt-2">{{ $post->body }}</p>

        <!-- Post images -->
        @if($post->images->count())
            <div class="flex flex-wrap gap-2 mt-2">
                @foreach($post->images as $image)
                    <img src="{{ $image->image_path }}" class="w-32 h-32 object-cover rounded-lg border border-gray-700">
                @endforeach
            </div>
        @endif

        <!-- Timestamp -->
        <p class="text-xs text-gray-500 mt-2">Posted {{ $post->created_at->diffForHumans() }}</p>
    </div>

<!-- Comments Section -->
<div class="p-4 border border-gray-700 rounded-lg bg-gray-800 mt-6">
    <h3 class="font-bold mb-3 text-orange-400">
        Comments ({{ $post->comments->count() }})
    </h3>

    <!-- Add Comment Form -->
    @auth
        <form method="POST" action="{{ route('comments.create', $post->id) }}" class="mb-4">
            @csrf
            <textarea name="content" rows="2" 
                class="w-full p-2 rounded-lg bg-gray-700 text-white border border-gray-600 focus:ring-0" 
                placeholder="Write a comment..."></textarea>
            <button type="submit" 
                class="mt-2 bg-orange-500 text-white px-4 py-1 rounded-full">
                Comment
            </button>
        </form>
    @else
        <p class="text-gray-400">Please 
            <a href="{{ route('login.form') }}" class="text-blue-400 underline">login</a> 
            to comment.
        </p>
    @endauth

    <!-- Comments List -->
    @if($post->comments->count())
        @foreach($post->comments->whereNull('parent_comment_id') as $comment)
            <x-comment-card :comment="$comment" />
        @endforeach
    @else
        <p class="text-gray-400">No comments yet.</p>
    @endif
</div>


    </div>

</div>
@endsection
