@extends('layouts.master')

@section('content')
<div class="p-6 space-y-6">

    <!-- Success message -->
    @if(session('success'))
        <div id="successmsg" class="bg-orange-500 text-white p-2 rounded">
            {{ session('success') }}
        </div>
    @endif
    <script>
    // Hide the success message after 2 seconds
    setTimeout(function() {
        var msg = document.getElementById('successmsg');
        if (msg) {
            msg.style.display = 'none';
        }
        }, 2000); 
    </script>
    
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
                <button type="submit" class="bg-orange-500 text-white px-4 py-1 rounded-full">
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
            <p>Please <a href="{{ route('login') }}" class="text-blue-400 underline">login</a> or 
               <a href="{{ route('register.form') }}" class="text-blue-400 underline">register</a> 
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
                    src="{{ optional($post->user)->avatar ?: asset('assets/img/default-avatar.png') }}"
                    alt="{{ optional(auth()->user())->name ?? 'Guest' }}"
                    class="w-8 h-8 rounded-full object-cover">
                        <h2 class="font-bold text-orange-400">
                            {{ optional($post->user)->name ?? 'Unknown User' }}
                        </h2>
                    </div>

                    <div class="relative">
                        @auth
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

                <!-- Comments button -->
                @auth
                    <a href="{{ route('posts.show', $post->id) }}"
                    class="inline-flex items-center border border-white text-white px-3 py-1 rounded-full mt-3 hover:bg-white hover:text-black transition w-fit">
                        <img src="{{ asset('assets/img/comment.svg') }}" 
                            alt="Comment Icon" 
                            class="w-5 h-5 mr-1">
                        <span class="ml-1">{{ $post->comments->count() }}</span>
                    </a>
                @endauth
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
