@extends('layouts.master')

@section('content')
<<<<<<< HEAD
    <div class="p-6 space-y-6">

        <!-- Success message -->
        @if(session('success'))
            <div class="bg-green-500 text-white p-2 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Post Form -->
        <div class="p-4 border border-gray-700 rounded-lg bg-gray-800">
            <form method="POST" action="/create-post">
                @csrf
                <textarea 
                    name="body"
                    placeholder="What's happening?"
                    class="w-full bg-transparent border-none focus:ring-0 resize-none"
                ></textarea>

                <button 
                    type="submit"
                    class="mt-2 bg-orange-500 text-white px-4 py-1 rounded-full"
                >
                    Squeal
                </button>
            </form>
        </div>

            <!-- Posts Feed -->
            @foreach ($posts as $post)
            <div class="p-4 border border-gray-700 rounded-lg bg-gray-800 relative">

                <!-- Header -->
                <div class="flex justify-between items-start">
                    <h2 class="font-bold text-orange-400">{{ $post->user->name }}</h2>

                    <!-- Hamburger -->
                    <div class="relative">
                        <button onclick="toggleMenu({{ $post->id }})" class="text-gray-400 hover:text-white">
                            ⋮
                        </button>
                        <!-- Dropdown -->
                        <div id="menu-{{ $post->id }}" class="hidden absolute right-0 mt-2 w-32 bg-gray-900 border border-gray-700 rounded-lg shadow-lg z-10">
                            <!-- Edit -->
                            <a href="{{ route('posts.edit.form', $post->id) }}"
                            class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700">
                            Edit
                            </a>
                            <!-- Delete -->
                            <form action="{{ route('posts.delete', $post->id) }}" method="POST" onsubmit="return confirm('Delete this post?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-gray-700">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Body -->
                <p class="text-gray-300 mt-2">{{ $post->body }}</p>

                <!-- Images -->
@if($post->images->count())
    <div class="flex flex-wrap gap-2 mt-2">
        @foreach($post->images as $image)
            <img src="{{ $image->image_path }}" 
                 class="w-24 h-24 object-cover rounded-lg border border-gray-700">
        @endforeach
    </div>
@endif

    <!-- Timestamp -->
    <p class="text-xs text-gray-500 mt-2">Posted {{ $post->created_at->diffForHumans() }}</p>

    <!-- Comment button -->
    <button onclick="toggleComments({{ $post->id }})"
            class="mt-2 text-sm text-blue-400 hover:underline">
        View Comments
    </button>

=======
<div class="p-6 space-y-6">
    
    @guest
        <!-- Guest Welcome Section -->
        <div class="bg-gray-900 border-2 border-orange-500 rounded-lg p-8 shadow-lg mt-3">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl font-bold mb-4 text-white">Welcome to <span class="text-orange-500">Squeal!</span></h1>
                <p class="text-lg mb-6 text-gray-300">
                    Share your ideas, connect with others, and build meaningful conversations.
                </p>
                <div class="flex gap-4 justify-center">
                    <a href="{{ route('register.form') }}" class="bg-orange-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-orange-400 transition">
                        Sign Up
                    </a>
                    <a href="{{ route('login.form') }}" class="bg-gray-900 text-orange-400 px-6 py-3 rounded-lg font-semibold hover:bg-gray-800 transition border-2 border-orange-500">
                        Sign In
                    </a>
                </div>
            </div>
        </div>
    @endguest

    @auth
        @include('components.create-post')
    @endauth

    <!-- Posts Feed -->
    @if(isset($posts) && $posts->count())
        @foreach ($posts as $post)
            @include('components.post-card', ['post' => $post])
        @endforeach
        
        <div class="mt-6">
            {{ $posts->links() }}
        </div>
    @else
        @auth
            <div class="p-4 border border-gray-700 rounded-lg bg-gray-800 text-center text-gray-400">
                No posts yet.
            </div>
        @endauth
    @endif
>>>>>>> origin
</div>

<<<<<<< HEAD
    </div>
@endsection

<script>
function toggleMenu(postId) {
    const menu = document.getElementById(`menu-${postId}`);
    document.querySelectorAll('[id^="menu-"]').forEach(m => {
        if (m !== menu) m.classList.add('hidden');
    });
    menu.classList.toggle('hidden');
}
</script>
=======
<script src="{{ asset('assets/js/home.js') }}?v={{ filemtime(public_path('assets/js/home.js')) }}"></script>
@endsection
>>>>>>> origin
