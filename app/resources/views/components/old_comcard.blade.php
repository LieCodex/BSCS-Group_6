@props(['comment'])

<div class="border-t border-gray-700 py-2 ml-{{ $comment->parent_comment_id ? '8' : '0' }}">
    <div class="flex items-center gap-2">
        <img 
            src="{{ optional($comment->user)->avatar ?: asset('assets/img/default-avatar.png') }}"
            alt="{{ optional($comment->user)->name ?? 'User' }}"
            class="w-6 h-6 rounded-full object-cover">
        <span class="font-bold text-orange-400">{{ optional($comment->user)->name ?? 'User' }}</span>
        <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
    </div>

    <!-- Comment text -->
    <p class="text-gray-300 ml-8">{{ $comment->content }}</p>

    <div class="ml-8 flex items-center gap-3 mt-1 text-sm">
        @auth
            <!-- Reply button -->
            <button onclick="document.getElementById('reply-{{ $comment->id }}').classList.toggle('hidden')"
                class="text-blue-400 hover:underline">
                Reply
            </button>

            <!-- Delete button -->
            @if(auth()->id() === $comment->user_id)
                <form action="{{ route('comments.delete', $comment->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-400 hover:text-red-600">Delete</button>
                </form>
            @endif
        @endauth
    </div>

    <!-- Reply form (hidden until clicked) -->
    <form id="reply-{{ $comment->id }}" 
          action="{{ route('comments.create', $comment->post_id) }}" 
          method="POST" 
          class="ml-8 mt-2 hidden">
        @csrf
        <input type="hidden" name="parent_comment_id" value="{{ $comment->id }}">
        <textarea name="content" rows="1" required
            class="w-full p-2 rounded-lg bg-gray-700 text-white border border-gray-600 focus:ring-0 text-sm"
            placeholder="Write a reply..."></textarea>
        <button type="submit" 
            class="mt-2 bg-gray-600 hover:bg-gray-500 text-white px-3 py-1 rounded-full text-sm">
            Reply
        </button>
    </form>

    <!-- Recursive replies -->
    @if($comment->replies->count())
        <div class="ml-8 mt-2 space-y-2">
            @foreach($comment->replies as $reply)
                <x-comment-card :comment="$reply" />
            @endforeach
        </div>
    @endif
</div>
