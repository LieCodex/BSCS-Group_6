<!-- filepath: c:\laragon\www\RizGroup\app\resources\views\components\comment.blade.php -->
<div class="card mb-3 shadow-sm bg-dark text-light">
    <div class="card-body p-3">
        <!-- Comment Header -->
        <div class="flex items-center gap-2 justify-between">
            <div class="flex items-center gap-2">
                <img 
                    src="{{ optional($comment->user)->avatar ?: asset('assets/img/default-avatar.png') }}"
                    alt="{{ optional($comment->user)->name ?? 'User' }}"
                    class="w-6 h-6 rounded-full object-cover">
                <span class="font-bold text-orange-400">{{ optional($comment->user)->name ?? 'User' }}</span>
                <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
            </div>
            <!-- 3 dots menu button for main comment -->
            @if(auth()->id() === $comment->user_id)
                <div class="relative">
                    <button onclick="toggleCommentMenu({{ $comment->id }})" class="text-gray-400 hover:text-white px-2 py-1 rounded-full focus:outline-none">⋮</button>
                    <div id="comment-menu-{{ $comment->id }}" class="hidden absolute right-0 mt-2 w-32 bg-gray-900 border border-gray-700 rounded-lg shadow-lg z-20">
                        <a href="{{ route('comments.edit', $comment->id) }}" class="block w-full text-left px-4 py-2 text-sm text-gray-200 hover:bg-gray-700">Edit</a>
                        <form action="{{ route('comments.delete', $comment->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-gray-700">Delete</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <!-- Comment Body -->
        <div class="m-2">
            {{ $comment->content }}
        </div>

        <!-- Reply form  -->
        <div class="ms-4 mt-3 border-start ps-3">
            <div id="reply-form-{{ $comment->id }}" class="mt-2">
                <form action="{{ route('comments.create', $comment->post_id) }}" method="POST" class="mt-2">
                    @csrf
                    <input type="hidden" name="parent_comment_id" value="{{ $comment->id }}">
                    <div class="relative">
                        <textarea 
                            name="content" 
                            class="w-full h-16 max-h-40 p-2 rounded-lg bg-gray-700 text-white border border-gray-600 focus:ring-0 pr-32 resize-none overflow-auto"
                            placeholder="Write a reply..." 
                            rows="2" 
                            required
                            onfocus="this.nextElementSibling.classList.remove('hidden');"
                            onblur="if(!this.value) this.nextElementSibling.classList.add('hidden');"
                            oninput="autoGrow(this)"
                            style="box-sizing: border-box; padding-bottom: 2.5rem;" 
                        ></textarea>
                        <div class="absolute right-2 bottom-2 flex gap-2 hidden z-10">
                            <button type="submit" class="bg-orange-400 text-white px-4 py-1 rounded-full">Reply</button>
                            <button type="button" class="bg-gray-500 text-white px-4 py-1 rounded-full" onclick="this.form.reset(); this.parentElement.classList.add('hidden');">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Replies -->
            @if($comment->replies->count() > 0)
                @foreach($comment->replies as $reply)
                    <div class="card mt-2 bg-secondary text-light border-0">
                        <div class="card-body p-2">
                            <div class="flex items-center gap-2 justify-between">
                                <div class="flex items-center gap-2">
                                    <img 
                                        src="{{ optional($reply->user)->avatar ?: asset('assets/img/default-avatar.png') }}"
                                        alt="{{ optional($reply->user)->name ?? 'User' }}"
                                        class="w-6 h-6 rounded-full object-cover">
                                    <span class="font-bold text-orange-400">{{ optional($reply->user)->name ?? 'User' }}</span>
                                    <span class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                                </div>
                                <!-- 3 dots menu button for reply -->
                                @if(auth()->id() === $reply->user_id)
                                    <div class="relative">
                                        <button onclick="toggleCommentMenu('reply-{{ $reply->id }}')" class="text-gray-400 hover:text-white px-2 py-1 rounded-full focus:outline-none">⋮</button>
                                        <div id="comment-menu-reply-{{ $reply->id }}" class="hidden absolute right-0 mt-2 w-32 bg-gray-900 border border-gray-700 rounded-lg shadow-lg z-20">
                                            <a href="{{ route('comments.edit', $reply->id) }}" class="block w-full text-left px-4 py-2 text-sm text-gray-200 hover:bg-gray-700">Edit</a>
                                            <form action="{{ route('comments.delete', $reply->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-gray-700">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <p class="m-2">{{ $reply->content }}</p>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/comment.js') }}"></script>
