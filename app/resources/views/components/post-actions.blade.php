<div class="flex items-center gap-3 mt-3">
    <!-- Likes button -->
    @auth
        @if($post->isLikedBy(auth()->user()))
            <!-- Unlike -->
            <form action="{{ route('posts.unlike', $post->id) }}" method="POST" class="like-form" data-post-id="{{ $post->id }}">
                @csrf
                @method('DELETE')
                <button 
                    type="submit" 
                    class="group inline-flex items-center text-white px-4 py-2 rounded-full bg-gray-800 hover:border-orange-500 text-sm sm:text-base lg:text-sm">

                    <svg xmlns="http://www.w3.org/2000/svg" 
                        id="like-icon-{{ $post->id }}"
                        class="w-6 h-6 mr-1 sm:w-15 sm:h-15 lg:w-6 lg:h-6 text-orange-400" 
                        fill="{{ $post->isLikedBy(auth()->user()) ? 'currentColor' : 'none' }}"
                        stroke="{{ $post->isLikedBy(auth()->user()) ? 'orange' : 'white' }}"
                        stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                            d="M4.318 6.318a4.5 4.5 0 016.364 0L12 
                            7.636l1.318-1.318a4.5 4.5 0 116.364 
                            6.364L12 20.364l-7.682-7.682a4.5 
                            4.5 0 010-6.364z"/>
                    </svg>
                    <span id="like-count-{{ $post->id }}" class="{{ $post->isLikedBy(auth()->user()) ? 'text-orange-400' : 'text-white' }} sm:text-3xl lg:text-sm">
                        {{ $post->likes->count() }}
                    </span>
                </button>
            </form>
        @else
            <!-- Like -->
            <form action="{{ route('posts.like', $post->id) }}" method="POST" class="like-form" data-post-id="{{ $post->id }}">
                @csrf
                <button 
                    type="submit" 
                    class="group inline-flex items-center text-white px-4 py-2 rounded-full bg-gray-800 hover:border-orange-500 text-sm sm:text-base lg:text-sm">

                    <svg xmlns="http://www.w3.org/2000/svg" 
                        id="like-icon-{{ $post->id }}"
                        class="w-6 h-6 mr-1 sm:w-15 sm:h-15 lg:w-6 lg:h-6 transition text-white group-hover:text-orange-400" 
                        fill="{{ $post->isLikedBy(auth()->user()) ? 'currentColor' : 'none' }}"
                        stroke="{{ $post->isLikedBy(auth()->user()) ? 'orange' : 'white' }}"
                        stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                            d="M4.318 6.318a4.5 4.5 0 016.364 0L12 
                            7.636l1.318-1.318a4.5 4.5 0 116.364 
                            6.364L12 20.364l-7.682-7.682a4.5 
                            4.5 0 010-6.364z"/>
                    </svg>
                    <span id="like-count-{{ $post->id }}" class="{{ $post->isLikedBy(auth()->user()) ? 'text-orange-400' : 'text-white' }} sm:text-3xl lg:text-sm">
                        {{ $post->likes->count() }}
                    </span>
                </button>
            </form>
        @endif
    @endauth

    <!-- Comments button -->
    @auth
        <form action="{{ route('posts.show', $post->id) }}" method="GET">
            <button 
                type="submit" 
                class="group inline-flex items-center text-white px-4 py-2 rounded-full bg-gray-800 hover:border-orange-500 text-sm sm:text-base lg:text-sm">

                <svg xmlns="http://www.w3.org/2000/svg" 
                    class="w-6 h-6 mr-1 sm:w-15 sm:h-15 lg:w-6 lg:h-6 transition text-white group-hover:text-orange-400" 
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                        d="M7 8h10M7 12h6m-6 4h4m10-2.586V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h9l4 4v-5.586a2 2 0 0 0 .586-1.414z"/>
                </svg>
                <span class="transition text-white group-hover:text-orange-400 text-base sm:text-3xl lg:text-sm">
                    {{ $post->comments->count() }}
                </span>
            </button>
        </form>
    @endauth
</div>