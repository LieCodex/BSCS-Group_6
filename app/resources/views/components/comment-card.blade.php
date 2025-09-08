<div class="border rounded p-2 mb-2">
    <strong>{{ $comment->user->name ?? 'Unknown User' }}</strong>
    <p class="mb-1">{{ $comment->content }}</p>

        <!-- Delete comment -->
    @if(auth()->id() === $comment->user_id)
        <form action="{{ route('comments.delete', $comment->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger btn-sm">Delete</button>
        </form>
    @endif


    <!-- Show replies -->
    @if($comment->replies->count() > 0)
        <div class="ms-4 mt-2">
            @foreach($comment->replies as $reply)
                <div class="border rounded p-2 mb-2">
                    <strong>{{ $reply->user->name ?? 'Unknown User' }}</strong>
                    <p class="mb-1">{{ $reply->content }}</p>

                    @if(auth()->id() === $reply->user_id)
                        <form action="{{ route('comments.delete', $reply->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <!-- Reply form -->
    <form action="{{ route('comments.create', $comment->post_id) }}" method="POST" class="ms-3 mb-2">
        @csrf
        <input type="hidden" name="parent_comment_id" value="{{ $comment->id }}">
        <textarea name="content" class="form-control mb-1" placeholder="Write a reply..." rows="1" required></textarea>
        <button type="submit" class="btn btn-secondary btn-sm">Reply</button>
    </form>


</div>
