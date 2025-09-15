<div class="card mb-3 shadow-sm bg-dark text-light">
    <div class="card-body p-3">
        <!-- Comment Header -->
    <div class="flex items-center gap-2">
        <img 
            src="{{ optional($comment->user)->avatar ?: asset('assets/img/default-avatar.png') }}"
            alt="{{ optional($comment->user)->name ?? 'User' }}"
            class="w-6 h-6 rounded-full object-cover">
        <span class="font-bold text-orange-400">{{ optional($comment->user)->name ?? 'User' }}</span>
        <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
    </div>

        <!-- Comment Body -->
        <p class="m-2">{{ $comment->content }}</p>

        <!-- Actions -->
        <div class="d-flex align-items-center gap-2 mb-2">
            <!-- Delete Button -->
            @if(auth()->id() === $comment->user_id)
                <form action="{{ route('comments.delete', $comment->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="mt-2 bg-orange-500 text-white px-4 py-1 rounded-full">Delete</button>
                </form>
            @endif
        </div>

        <!-- Reply form -->
        <div class="ms-4 mt-3 border-start ps-3">
            <div id="reply-form-{{ $comment->id }}" class="mt-2">
                <form action="{{ route('comments.create', $comment->post_id) }}" method="POST" class="mt-2">
                    @csrf
                    <input type="hidden" name="parent_comment_id" value="{{ $comment->id }}">
                    <textarea name="content" class="w-full p-2 rounded-lg bg-gray-700 text-white border border-gray-600 focus:ring-0"
                            placeholder="Write a reply..." rows="2" required></textarea>
                    <div class="d-flex gap-2">
                        <button type="submit" class="mt-2 bg-orange-500 text-white px-4 py-1 rounded-full">Reply</button>
                    </div>
                </form>
            </div>

            <!-- Replies -->
            @if($comment->replies->count() > 0)
                @foreach($comment->replies as $reply)
                    <div class="card mt-2 bg-secondary text-light border-0">
                        <div class="card-body p-2">
                            <div class="flex items-center gap-2">
                                <img 
                                    src="{{ optional($reply->user)->avatar ?: asset('assets/img/default-avatar.png') }}"
                                    alt="{{ optional($reply->user)->name ?? 'User' }}"
                                    class="w-6 h-6 rounded-full object-cover">
                                <span class="font-bold text-orange-400">{{ optional($reply->user)->name ?? 'User' }}</span>
                                <span class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="m-2">{{ $reply->content }}</p>

                            <!-- Reply Actions -->
                            <div class="d-flex align-items-center gap-2">
                                @if(auth()->id() === $reply->user_id)
                                    <form action="{{ route('comments.delete', $reply->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="mt-2 bg-orange-500 text-white px-4 py-1 rounded-full">Delete</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

    </div>
</div>