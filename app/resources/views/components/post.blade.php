@extends('layouts.master')

@section('content')
<div class="p-6 space-y-6">

    <!-- Post Card -->
    <div class="p-4 border border-gray-700 rounded-lg bg-gray-800">
        <!-- User info -->
        <div class="flex items-center gap-2">
            <img 
                src="{{ optional($post->user)->avatar ?: asset('assets/img/default-avatar.svg') }}"
                alt="{{ optional($post->user)->name ?? 'Unknown User' }}"
                class="w-8 h-8 rounded-full object-cover">
                <h2 class="font-bold text-orange-400">
                    <a href="{{ route('user.profile', optional($post->user)->id) }}">
                        {{ optional($post->user)->name ?? 'Unknown User' }}
                    </a>
                </h2>
        </div>

        <!-- Post body -->
        <p class="text-gray-300 mt-3">{{ $post->body }}</p>

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

        <!-- Comment Form -->
        @auth
            <div class="mt-4">
                <form action="{{ route('comments.create', $post->id) }}" method="POST">
                    @csrf
                    <div class="relative">
                        <textarea 
                            name="content" 
                            class="w-full h-16 max-h-40 p-2 rounded-lg bg-gray-700 text-white border border-gray-600 focus:ring-0 pr-32 resize-none overflow-auto"
                            placeholder="Write a comment..." 
                            rows="2" 
                            required
                            onfocus="this.nextElementSibling.classList.remove('hidden');"
                            onblur="if(!this.value) this.nextElementSibling.classList.add('hidden');"
                            oninput="autoGrow(this)"
                            style="box-sizing: border-box; padding-bottom: 2.5rem;" 
                        ></textarea>
                        <div class="absolute right-2 bottom-2 flex gap-2 hidden z-10">
                            <button type="submit" class="bg-orange-500 text-white px-4 py-1 rounded-full">Comment</button>
                            <button type="button" class="bg-gray-500 text-white px-4 py-1 rounded-full" onclick="this.form.reset(); this.parentElement.classList.add('hidden');">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        @else
            <p class="text-gray-400">Please 
                <a href="{{ route('login.form') }}" class="text-orange-400 underline">login</a> 
                to comment.
            </p>
        @endauth

        <!-- Comments List -->
        @if($post->comments->count())
            @foreach($post->comments->whereNull('parent_comment_id') as $comment)
                <x-comment :comment="$comment" />
            @endforeach
        @else
            <p class="ml-56 mt-2 text-gray-400">No comments yet.</p>
        @endif
    </div>
</div>

<script>
function autoGrow(element) {
    element.style.height = "4rem"; 
    if (element.scrollHeight > element.clientHeight) {
        element.style.height = (element.scrollHeight) + "px";
    }
}
</script>
@endsection
