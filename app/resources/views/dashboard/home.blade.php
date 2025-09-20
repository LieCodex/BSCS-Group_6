@extends('layouts.master')

@section('content')
<div class="p-6 space-y-6">

    <!-- Success message -->
    @if(session('success'))
        <div id="successmsg" class="bg-orange-400 text-white p-2 rounded">
            {{ session('success') }}
        </div>
    @endif
    
    <!-- Show post form only for logged-in users -->
    @auth
    <div class="p-4 border border-gray-700 rounded-lg bg-gray-800">
        <form method="POST" action="/create-post" enctype="multipart/form-data">
            @csrf
            <textarea
                name="body"
                placeholder="What's happening?"
                class="w-full bg-transparent border-none focus:ring-0 resize-none"
            ></textarea>

            <!-- Preview area -->
            <div id="imagePreview" class="flex flex-wrap gap-2 mt-2 hidden"></div>

            <div class="flex items-center gap-2 mt-2">
                <button type="submit" class="bg-orange-400 text-white px-4 py-1 rounded-full">
                    Squeal
                </button>

                <label for="imageInput" class="bg-gray-600 text-white px-4 py-1 rounded-full cursor-pointer">
                    Images
                </label>
                <input type="file" id="imageInput" name="images[]" multiple accept="image/*" class="hidden">
            </div>
        </form>
    </div>
    @endauth

    <!-- Guest message -->
    @guest
        <div class="p-4 border border-gray-700 rounded-lg bg-gray-800 text-center text-gray-400">
            <p>Welcome to <span class="text-orange-400 font-bold">Squeal</span> !</p>
            <p>Please <a href="{{ route('login') }}" class="text-orange-400">login</a> or 
               <a href="{{ route('register.form') }}" class="text-orange-400">register</a> 
               to squeal with others.</p>
        </div>
    @endguest

    <!-- Posts Feed -->
    @if(isset($posts) && $posts->count())
        @foreach ($posts as $post)
            <div class="p-4 border border-gray-700 rounded-lg bg-gray-800 relative">

                <!-- User info -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                <img 
                    src="{{ optional($post->user)->avatar ?: asset('assets/img/default-avatar.svg') }}"
                    alt="{{ optional(auth()->user())->name ?? 'Guest' }}"
                    class="w-8 h-8 rounded-full object-cover">
                        <h2 class="font-bold text-orange-400">
                            {{ optional($post->user)->name ?? 'Unknown User' }}
                        </h2>
                    </div>

                    <div class="relative">
                        @auth
                            @if(auth()->id() === $post->user_id)
                                <button onclick="toggleMenu({{ $post->id }})" class="text-gray-400 hover:text-white">⋮</button>
                                <div id="menu-{{ $post->id }}" class="hidden absolute right-0 mt-2 w-32 bg-gray-900 border border-gray-700 rounded-lg shadow-lg z-10">
                                    <a href="{{ route('posts.edit.form', $post->id) }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700">Edit</a>
                                    <form action="{{ route('posts.delete', $post->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-gray-700">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- Post body -->
                <p class="text-gray-300 mt-2">{{ $post->body }}</p>

                <!-- Post images -->
                @if($post->images->count())
                    <div class="flex flex-wrap gap-2 mt-2">
                        @foreach($post->images as $image)
                            <img src="{{ $image->image_path }}" class="w-24 h-24 object-cover rounded-lg border border-gray-700">
                        @endforeach
                    </div>
                @endif

                <!-- Timestamp -->
                <p class="text-xs text-gray-500 mt-2">Posted {{ $post->created_at->diffForHumans() }}</p>

                <!-- Buttons wrapper -->
                <div class="flex items-center gap-3 mt-3">

                        <!-- Likes button -->
                    @auth
                        @if($post->isLikedBy(auth()->user()))
                            <!-- Unlike -->
                            <form action="{{ route('posts.unlike', $post->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="group inline-flex items-center text-orange-400 px-3 py-1 rounded-full bg-gray-800 hover:border-orange-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                        class="w-6 h-6 mr-1 text-orange-400" 
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 
                                        4 0 115.656 5.656L10 18.657l-6.828-6.829a4 
                                        4 0 010-5.656z"/>
                                    </svg>
                                    <span class="text-orange-400">{{ $post->likes->count() }}</span>
                                </button>
                            </form>
                        @else
                            <!-- Like -->
                            <form action="{{ route('posts.like', $post->id) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="group inline-flex items-center text-white px-3 py-1 rounded-full bg-gray-800 hover:border-orange-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                        class="w-6 h-6 mr-1 transition text-white group-hover:text-orange-400" 
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" 
                                            d="M4.318 6.318a4.5 4.5 0 016.364 0L12 
                                            7.636l1.318-1.318a4.5 4.5 0 116.364 
                                            6.364L12 20.364l-7.682-7.682a4.5 
                                            4.5 0 010-6.364z"/>
                                    </svg>
                                    <span class="transition text-white group-hover:text-orange-400">{{ $post->likes->count() }}</span>
                                </button>
                            </form>
                        @endif
                    @endauth

                    <!-- Comments button -->
                    @auth
                        <form action="{{ route('posts.show', $post->id) }}" method="GET">
                            <button type="submit" class="group inline-flex items-center text-white px-3 py-1 rounded-full bg-gray-800 hover:border-orange-400">
                                <svg xmlns="http://www.w3.org/2000/svg" 
                                    class="w-6 h-6 mr-1 transition text-white group-hover:text-orange-400" 
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" 
                                        d="M7 8h10M7 12h6m-6 4h4m10-2.586V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h9l4 4v-5.586a2 2 0 0 0 .586-1.414z"/>
                                </svg>
                                <span class="transition text-white group-hover:text-orange-400">
                                    {{ $post->comments->count() }}
                                </span>
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        @endforeach
    @else
        @auth
            <div class="p-4 border border-gray-700 rounded-lg bg-gray-800 text-center text-gray-400">
                No posts yet.
            </div>
        @endauth
    @endif
</div>
<script src="{{ asset('assets/js/post_menu.js') }}"></script>
@endsection
