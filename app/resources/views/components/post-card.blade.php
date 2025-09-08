<div class="card post-card mb-3">
    <div class="card-body">
        <!-- User info -->
        <div class="d-flex align-items-center mb-2">
            @if($post->user && $post->user->avatar)
                <img src="{{ $post->user->avatar }}" alt="{{ $post->user->name }}" class="rounded-circle me-2" style="width:32px; height:32px; object-fit:cover;">
            @else
                <span class="me-2"><i class="fas fa-user-circle fa-2x text-secondary"></i></span>
            @endif
            <strong>{{ $post->user ? $post->user->name : 'Unknown User' }}</strong>
        </div>

        <h5 class="card-title">{{ $post->title }}</h5>
        <p class="card-text">{{ $post->body }}</p>

        <!-- Display post images -->
        @if($post->images->count() > 0)
            <div class="d-flex flex-wrap mb-2">
                @foreach($post->images as $img)
                    <button 
                        type="button"
                        class="img-thumbnail-btn"
                        data-bs-toggle="modal" 
                        data-bs-target="#imageModal"
                        data-img="{{ $img->image_path }}"
                        style="border:none; padding:0; margin:5px; background:none;">
                        <img src="{{ $img->image_path }}" 
                            alt="Post Image" 
                            style="width:100px; height:100px; object-fit:cover;">
                    </button>
                @endforeach
            </div>
        @endif
        
        @if(auth()->check() && $post->user_id === auth()->id())
            <a href="/edit-post/{{ $post->id }}" class="btn btn-warning btn-sm">Edit</a>
            <form action="/delete-post/{{ $post->id }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm">Delete</button>
            </form>
        @endif

        <hr>

        <!-- Comment Form -->
        <form action="{{ route('comments.create', $post->id) }}" method="POST" class="mt-2">
            @csrf
            <textarea name="content" class="form-control mb-2" placeholder="Write a comment..." rows="2" required></textarea>
            <button type="submit" class="btn btn-primary btn-sm">Comment</button>
        </form>

        <!-- Display Comments -->
        @if($post->comments->count() > 0)
            <div class="mt-3">
                @foreach($post->comments->whereNull('parent_comment_id') as $comment)
                    <x-comment-card :comment="$comment" />
                @endforeach
            </div>
        @endif

    </div>
</div>
