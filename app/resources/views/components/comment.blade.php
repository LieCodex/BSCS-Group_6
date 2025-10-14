<div class="card mb-3 shadow-sm bg-dark text-light max-w-full break-words">
    <div class="card-body p-3">
        <!-- Comment Header -->
        <div class="flex items-center gap-2 justify-between">
            <div class="flex items-center gap-2">
                <img 
                    src="{{ optional($comment->user)->avatar ?: asset('assets/img/default-avatar.svg') }}"
                    alt="{{ optional($comment->user)->name ?? 'User' }}"
                    class="lg:w-6 lg:h-6 sm:w-12 sm:h-12 rounded-full object-cover">
                    <a href="{{ route('user.profile', optional($comment->user)->id) }}" 
                    class="font-bold text-orange-400 hover:underline lg:text-base sm:text-3xl">
                    {{ optional($comment->user)->name ?? 'User' }}
                    </a>
                <span class="text-xs text-gray-500 lg:text-base sm:text-2xl">{{ $comment->created_at->diffForHumans() }}</span>
            </div>

            <!-- 3 dots menu -->
            @if(auth()->id() === $comment->user_id)
                <div class="relative">
                    <button onclick="toggleCommentMenu({{ $comment->id }})" 
                            class="text-gray-400 hover:text-white px-2 py-1 rounded-full focus:outline-none">⋮</button>
                    <div id="comment-menu-{{ $comment->id }}" 
                         class="hidden absolute right-0 mt-2 w-32 bg-gray-900 border border-gray-700 rounded-lg shadow-lg z-20">
                        <a href="{{ route('comments.edit', $comment->id) }}" 
                           class="block w-full text-left px-4 py-2 text-sm text-gray-200 hover:bg-gray-700">Edit</a>
                        <form action="{{ route('comments.delete', $comment->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-gray-700">Delete</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <div class="ml-6 lg:text-base sm:text-3xl">

        <!-- Comment Body -->
        <div class="m-2 break-words">
            {{ $comment->content }}
        </div>

        <div class="flex items-center gap-2 mt-3 flex-wrap">
        @auth
            @if($comment->isLikedBy(auth()->user()))
                <!-- Unlike -->
                <form action="{{ route('comments.unlike', $comment->id) }}" method="POST" class="like-form" data-post-id="{{ $comment->id }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" id="like-btn-{{ $comment->id }}" class="group inline-flex items-center text-orange-400 px-3 py-1 rounded-full bg-gray-800 hover:border-orange-400">
                        <svg xmlns="http://www.w3.org/2000/svg" 
                            id="like-icon-{{ $comment->id }}"
                            class="w-6 h-6 mr-1 sm:w-15 sm:h-15 lg:w-6 lg:h-6 text-orange-400" 
                            fill="{{ $comment->isLikedBy(auth()->user()) ? 'currentColor' : 'none' }}"
                            stroke="{{ $comment->isLikedBy(auth()->user()) ? 'orange' : 'white' }}"
                            stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" 
                                d="M4.318 6.318a4.5 4.5 0 016.364 0L12 
                                7.636l1.318-1.318a4.5 4.5 0 116.364 
                                6.364L12 20.364l-7.682-7.682a4.5 
                                4.5 0 010-6.364z"/>
                        </svg>
                            <span id="like-count-{{ $comment->id }}" class="{{ $comment->isLikedBy(auth()->user()) ? 'text-orange-400' : 'text-white' }}">
                            {{ $comment->likes->count() }}
                            </span>
                    </button>
                </form>
            @else
                <!-- Like -->
                <form action="{{ route('comments.like', $comment->id) }}" method="POST" class="like-form" data-post-id="{{ $comment->id }}">
                    @csrf
                    <button type="submit" id="like-btn-{{ $comment->id }}" class="group inline-flex items-center text-white px-3 py-1 rounded-full bg-gray-800 hover:border-orange-400">
                        <svg xmlns="http://www.w3.org/2000/svg" 
                        id="like-icon-{{ $comment->id }}"
                            class="w-6 h-6 mr-1 sm:w-11 sm:h-11 lg:w-6 lg:h-6 transition text-white group-hover:text-orange-400" 
                            fill="{{ $comment->isLikedBy(auth()->user()) ? 'currentColor' : 'none' }}"
                            stroke="{{ $comment->isLikedBy(auth()->user()) ? 'orange' : 'white' }}"
                            stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" 
                                d="M4.318 6.318a4.5 4.5 0 016.364 0L12 
                                7.636l1.318-1.318a4.5 4.5 0 116.364 
                                6.364L12 20.364l-7.682-7.682a4.5 
                                4.5 0 010-6.364z"/>
                        </svg>
                            <span id="like-count-{{ $comment->id }}" class="{{ $comment->isLikedBy(auth()->user()) ? 'text-orange-400' : 'text-white' }}">
                            {{ $comment->likes->count() }}
                            </span>
                    </button>
                </form>
            @endif

            <!-- Reply button -->
            <button type="button" 
                    onclick="toggleReplyForm({{ $comment->id }})"
                    class="group inline-flex items-center text-white px-3 py-1 rounded-full bg-gray-800 hover:border-orange-400">
                <svg xmlns="http://www.w3.org/2000/svg" 
                     class="w-6 h-6 mr-1 sm:w-11 sm:h-11 lg:w-6 lg:h-6 transition text-white group-hover:text-orange-400" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          d="M7 8h10M7 12h6m-6 4h4m10-2.586V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h9l4 4v-5.586a2 2 0 0 0 .586-1.414z"/>
                </svg>

            </button>
        </div>
        </div>
        @endauth

        <!-- Reply form -->
        <div class="ms-4 mt-3 border-start ps-3">
            <div id="reply-form-{{ $comment->id }}" class="mt-2 hidden">
                <form action="{{ route('comments.create', $comment->post_id) }}" method="POST" class="mt-2">
                    @csrf
                    <input type="hidden" name="parent_comment_id" value="{{ $comment->id }}">
                    <div class="relative">
                        <textarea name="content" rows="2" required
                                  class="w-full h-16 max-h-40 p-2 rounded-lg bg-gray-700 text-white border border-gray-600 focus:ring-0 pr-32 resize-none overflow-auto lg:text-base sm:text-3xl"
                                  placeholder="Write a reply..."
                                  onfocus="this.nextElementSibling.classList.remove('hidden');"
                                  onblur="if(!this.value) this.nextElementSibling.classList.add('hidden');"
                                  oninput="autoGrow(this)"
                                  style="box-sizing: border-box; padding-bottom: 2.5rem;"></textarea>
                        <div class="absolute right-2 bottom-2 flex gap-2 hidden z-10">
                            <button type="submit" class="bg-orange-500 text-white px-4 py-1 rounded-full">Reply</button>
                            <button type="button" 
                                    class="bg-gray-500 text-white px-4 py-1 rounded-full" 
                                    onclick="this.form.reset(); this.parentElement.classList.add('hidden'); toggleReplyForm({{ $comment->id }}, false);">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Replies -->
        @if($comment->replies->count() > 0)
            @foreach($comment->replies as $reply)
                <div class="card mt-2 ml-12 bg-secondary text-light border-0 max-w-full break-words">
                    <div class="card-body p-2">
                        <div class="flex items-center gap-2 justify-between flex-wrap">
                            <div class="flex items-center gap-2">
                                <img 
                                    src="{{ optional($reply->user)->avatar ?: asset('assets/img/default-avatar.svg') }}"
                                    alt="{{ optional($reply->user)->name ?? 'User' }}"
                                    class="lg:w-6 lg:h-6 sm:w-12 sm:h-12 rounded-full object-cover">
                                    <a href="{{ route('user.profile', optional($comment->user)->id) }}" 
                                    class="font-bold text-orange-400 hover:underline lg:text-base sm:text-3xl">
                                    {{ optional($comment->user)->name ?? 'User' }}
                                    </a>
                                <span class="text-xs text-gray-500 sm:text-2xl lg:text-base">{{ $reply->created_at->diffForHumans() }}</span>
                            </div>
                            <!-- 3 dots menu button for reply -->
                            @if(auth()->id() === $reply->user_id)
                                <div class="relative">
                                    <button onclick="toggleCommentMenu('reply-{{ $reply->id }}')" 
                                            class="text-gray-400 hover:text-white px-2 py-1 rounded-full focus:outline-none">⋮</button>
                                    <div id="comment-menu-reply-{{ $reply->id }}" 
                                         class="hidden absolute right-0 mt-2 w-32 bg-gray-900 border border-gray-700 rounded-lg shadow-lg z-20">
                                        <a href="{{ route('comments.edit', $reply->id) }}" 
                                           class="block w-full text-left px-4 py-2 text-sm text-gray-200 hover:bg-gray-700">Edit</a>
                                        <form action="{{ route('comments.delete', $reply->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-gray-700">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Reply Body -->
                        <div class="ml-6 break-words lg:text-base sm:text-3xl">
                        <p class="m-2">{{ $reply->content }}</p>

                        @if($reply->isLikedBy(auth()->user()))
                            <!-- Unlike -->
                            <form action="{{ route('comments.unlike', $reply->id) }}" method="POST" class="like-form" data-post-id="{{ $reply->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" id="like-btn-{{ $reply->id }}" class="group inline-flex items-center text-orange-400 px-3 py-1 rounded-full bg-gray-800 hover:border-orange-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                        id="like-icon-{{ $reply->id }}"
                                        class="w-6 h-6 mr-1 sm:w-11 sm:h-11 lg:w-6 lg:h-6 text-orange-400" 
                                        fill="{{ $reply->isLikedBy(auth()->user()) ? 'currentColor' : 'none' }}"
                                        stroke="{{ $reply->isLikedBy(auth()->user()) ? 'orange' : 'white' }}"
                                        stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" 
                                            d="M4.318 6.318a4.5 4.5 0 016.364 0L12 
                                            7.636l1.318-1.318a4.5 4.5 0 116.364 
                                            6.364L12 20.364l-7.682-7.682a4.5 
                                            4.5 0 010-6.364z"/>
                                    </svg>
                                        <span id="like-count-{{ $reply->id }}" class="{{ $reply->isLikedBy(auth()->user()) ? 'text-orange-400' : 'text-white' }}">
                                        {{ $reply->likes->count() }}
                                        </span>
                                </button>
                            </form>
                        @else
                            <!-- Like -->
                            <form action="{{ route('comments.like', $reply->id) }}" method="POST" class="like-form" data-post-id="{{ $reply->id }}">
                                @csrf
                                <button type="submit" id="like-btn-{{ $reply->id }}" class="group inline-flex items-center text-white px-3 py-1 rounded-full bg-gray-800 hover:border-orange-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                    id="like-icon-{{ $reply->id }}"
                                        class="w-6 h-6 mr-1 sm:w-11 sm:h-11 lg:w-6 lg:h-6 transition text-white group-hover:text-orange-400" 
                                        fill="{{ $reply->isLikedBy(auth()->user()) ? 'currentColor' : 'none' }}"
                                        stroke="{{ $reply->isLikedBy(auth()->user()) ? 'orange' : 'white' }}"
                                        stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" 
                                            d="M4.318 6.318a4.5 4.5 0 016.364 0L12 
                                            7.636l1.318-1.318a4.5 4.5 0 116.364 
                                            6.364L12 20.364l-7.682-7.682a4.5 
                                            4.5 0 010-6.364z"/>
                                    </svg>
                                        <span id="like-count-{{ $reply->id }}" class="{{ $reply->isLikedBy(auth()->user()) ? 'text-orange-400' : 'text-white' }}">
                                        {{ $reply->likes->count() }}
                                        </span>
                                </button>
                            </form>
                        @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
<script src="{{ asset('assets/js/comment.js') }}?v={{ filemtime(public_path('assets/js/comment.js')) }}"></script>
