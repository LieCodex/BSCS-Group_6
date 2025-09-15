@extends('layouts.master')

@section('content')
<div class="max-w-4xl mx-auto p-6">

    <!-- Header / Banner -->
    <div x-data="{ open: false }" class="relative h-40 bg-gray-700 rounded-lg">
        <!-- Edit Profile Button -->
        <div class="absolute top-2 right-2">
            <button 
                @click="open = true" 
                class="px-4 py-2 bg-gray-800 text-white rounded-full text-sm hover:bg-gray-700">
                Edit Profile
            </button>
        </div>

    <!-- Modal -->
        <div 
            x-show="open" 
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            x-cloak
        >
            <div class="bg-gray-800 text-white rounded-lg shadow-lg w-full max-w-md p-6">
                <h2 class="text-xl font-semibold mb-4">Edit Bio</h2>

                <!-- Bio Form -->
                <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Bio -->
                    <div>
                        <label for="bio" class="block text-sm text-gray-300">Bio</label>
                        <textarea name="bio" id="bio" rows="3" 
                            class="w-full rounded-lg p-2 bg-gray-700 text-white">{{ old('bio', auth()->user()->bio) }}</textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-2">
                        <button type="button" 
                                @click="open = false"
                                class="px-4 py-2 bg-gray-600 rounded-lg hover:bg-gray-500">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 rounded-lg hover:bg-blue-500">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

     <!-- Avatar -->
    <div class="relative">
        <img src="{{ auth()->user()->avatar ?? asset('assets/img/default-avatar.svg') }}" 
             alt="{{ auth()->user()->name }}" 
             class="w-32 h-32 rounded-full border-4 border-gray-900 absolute -top-16 left-6 object-cover">
    </div>

    <!-- User Info -->
    <div class="mt-24 ml-6">
        <h1 class="text-2xl font-bold text-white">{{ auth()->user()->name }}</h1>
        <p class="mt-3 text-gray-300">
            {{ auth()->user()->bio ?? 'This user hasn’t added a bio yet.' }}
        </p>
    </div>

    <!-- Profile Details -->
    <div class="mt-5 ml-6 text-gray-400 text-sm space-y-2">
        <p><span class="font-semibold text-white">Email:</span> {{ auth()->user()->email }}</p>
        <p><span class="font-semibold text-white">Joined:</span> {{ auth()->user()->created_at->format('F Y') }}</p>
        <p><span class="font-semibold text-white">Posts:</span> {{ $posts->count() }}</p>
    </div>

    <!-- Divider -->
    <hr class="my-6 border-gray-700">

    <!-- Posts Feed -->
    @if($posts->count())
        @foreach ($posts as $post)
            <div class="p-4 border border-gray-700 rounded-lg bg-gray-800 relative">

                <!-- User info -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <img 
                            src="{{ $post->user->avatar ?? asset('assets/img/default-avatar.svg') }}"
                            alt="{{ $post->user->name }}"
                            class="w-8 h-8 rounded-full object-cover">
                        <h2 class="font-bold text-orange-400">
                            {{ $post->user->name }}
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
        <div class="p-4 border border-gray-700 rounded-lg bg-gray-800 text-center text-gray-400">
            No posts yet.
        </div>
    @endif

    <script src="{{ asset('assets/js/post_menu.js') }}"></script>

</div>
@endsection
