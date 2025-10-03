<div class="p-6 sm:p-6 lg:p-4 rounded-lg bg-gray-800 relative mb-4">
    <!-- User info -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <img 
                src="{{ optional($post->user)->avatar ?: asset('assets/img/default-avatar.svg') }}"
                alt="{{ optional(auth()->user())->name ?? 'Guest' }}"
                class="w-14 h-14 sm:w-20 sm:h-20 lg:w-10 lg:h-10 rounded-full object-cover">

            <div>
                <!-- Name + timestamp -->
                <div class="flex items-center gap-2">
                    <h2 class="font-bold text-orange-400">
                        <a href="{{ route('user.profile', optional($post->user)->id) }}">
                            {{ optional($post->user)->name ?? 'Unknown User' }}
                        </a>
                    </h2>

                    <p class="text-xs sm:text-3xl lg:text-xs text-gray-500">•</p>

                    <p class="text-xs sm:text-3xl lg:text-xs text-gray-500">
                        {{ $post->created_at->diffForHumans() }}
                    </p>
                </div>

                <!-- Email -->
                <h2 class="font-extralight text-sm text-gray-500">
                    <a href="{{ route('user.profile', optional($post->user)->id) }}">
                        {{ optional($post->user)->email ?? 'Unknown User' }}
                    </a>
                </h2>
            </div>
        </div>
        
        <!-- Edit/Delete Menu -->
        <div class="relative">
            @auth
                @if(auth()->id() === $post->user_id)
                    <button onclick="toggleMenu({{ $post->id }})" class="text-gray-400 hover:text-white">⋮</button>
                    <div id="menu-{{ $post->id }}" 
                        class="hidden absolute right-0 mt-2 w-32 bg-gray-900 border border-gray-700 
                                rounded-lg shadow-lg z-50">
                        <a href="{{ route('posts.edit.form', $post->id) }}" 
                        class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700">Edit</a>
                        <form action="{{ route('posts.delete', $post->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-gray-700">
                                Delete
                            </button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>
    </div>

    <!-- Post body -->
    <p class="text-gray-300 mt-4 break-words">    
    {!! preg_replace_callback(
        '/https?:\/\/[^\s]+/',
        function ($matches) {
            $url = $matches[0];
            $display = preg_replace('#^https?://www.#', '', $url);
            return '<a href="' . $url . '" class="text-blue-400 hover:underline" target="_blank">' . $display . '</a>';
        },
        e($post->body)
    ) !!}</p>

    <!-- Post images carousel -->
    @if($post->images->count())
        @include('components.post-images', ['post' => $post])
    @endif

    <!-- Post Actions -->
    @include('components.post-actions', ['post' => $post])
</div>

