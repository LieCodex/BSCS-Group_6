<div class="card mb-3 shadow-sm bg-dark text-light">
    <div class="card-body p-3">
        <!-- Comment Header -->
        <div class="d-flex align-items-center mb-2">
            <div class="me-2">
                <img src="{{ $comment->user->avatar ?? asset('assets/img/default-avatar.png') }}"
                     class="rounded-circle" width="32" height="32" alt="Avatar">
            </div>
            <div>
                <strong class="text-warning">{{ $comment->user->name ?? 'Unknown User' }}</strong>
                <small class="text-muted d-block">{{ $comment->created_at->diffForHumans() }}</small>
            </div>
        </div>

        <!-- Comment Body -->
        <p class="mb-2">{{ $comment->content }}</p>

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

        <!-- Replies -->
        @if($comment->replies->count() > 0)
            <div class="ms-4 mt-3 border-start ps-3">
                @foreach($comment->replies as $reply)
                    <div class="card mb-2 bg-secondary text-light border-0">
                        <div class="card-body p-2">
                            <div class="d-flex align-items-center mb-1">
                                <div class="me-2">
                                    <img src="{{ $reply->user->avatar ?? asset('assets/img/default-avatar.png') }}"
                                         class="rounded-circle" width="24" height="24" alt="Avatar">
                                </div>
                                <div>
                                    <strong class="text-warning">{{ $reply->user->name ?? 'Unknown User' }}</strong>
                                    <small class="text-muted d-block">{{ $reply->created_at->diffForHumans() }}</small>
                                </div>
                            </div>

                            <p class="mb-2">{{ $reply->content }}</p>

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
            </div>
        @endif

        <!-- Reply form -->
        <div id="reply-form-{{ $comment->id }}" class="d-none">
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

    </div>
</div>