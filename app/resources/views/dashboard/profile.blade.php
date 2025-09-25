@extends('layouts.master')

@section('content')
<div class="max-w-4xl mx-auto p-6">

    <!-- Header / Banner -->
    <div x-data="{ open: false }" class="relative h-40 bg-gray-700 rounded-lg">
        <!-- Edit Profile Button -->
        @if(auth()->check() && auth()->id() === $user->id)
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
                x-cloak>
                
                <div class="bg-gray-800 text-white rounded-lg shadow-lg w-full max-w-md p-6">
                    <h2 class="text-xl font-medium mb-4">Edit Bio</h2>

                    <!-- Bio Form -->
                    <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <!-- Bio -->
                        <div>
                            <label for="bio" class="block text-sm text-gray-300">Bio</label>
                            <textarea name="bio" id="bio" rows="3" 
                                class="w-full rounded-lg p-2 bg-gray-700 text-white">{{ old('bio', $user->bio) }}</textarea>
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
        @endif
    </div>

    <div class="relative flex items-end justify-between">
        <!-- Avatar -->
        <img src="{{ $user->avatar ?? asset('assets/img/default-avatar.svg') }}" 
            alt="{{ $user->name }}" 
            class="w-32 h-32 rounded-full border-4 border-gray-900 absolute -top-16 left-6 object-cover">

        @if(auth()->check() && auth()->id() !== $user->id)
            <div class="flex items-center gap-3 absolute -top-10 right-6 mt-15">
                <!-- Follow -->
                @if(auth()->user()->following->contains($user->id))
                    <!-- Already following -->
                    <form action="{{ route('unfollow', $user->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button 
                        class="text-gray-300 border border-gray-300 rounded-full 
                                px-5 py-2 text-sm
                                hover:bg-gray-600 hover:text-white
                                focus:outline-none focus:ring-2 focus:ring-gray-300
                                transition-colors duration-200">
                        Unfollow
                        </button>
                    </form>
                @else
                    <!-- Not following -->
                    <form action="{{ route('follow', $user->id) }}" method="POST">
                        @csrf
                        <button 
                        class="text-orange-500 border border-orange-500 rounded-full 
                                px-5 py-2 text-sm
                                hover:bg-orange-500 hover:text-white
                                focus:outline-none focus:ring-2 focus:ring-orange-300
                                transition-colors duration-200">
                        Follow
                        </button>
                    </form>
                @endif
                    <!-- Chat -->
                    <form action="{{ route('dashboard.messages') }}">
                        @csrf
                        <button 
                        class="text-orange-500 border border-orange-500 rounded-full 
                                px-5 py-2 text-sm
                                hover:bg-orange-500 hover:text-white
                                focus:outline-none focus:ring-2 focus:ring-orange-300
                                transition-colors duration-200">
                        Chat
                        </button>
                    </form>
            </div>
        @endif
    </div>


    <!-- User Info -->
    <div class="mt-18 ml-6">
        <h1 class="text-2xl font-semibold text-white">{{ $user->name }}</h1>
        <p class="text-gray-500">{{ $user->email }}</p>
        <p class="mt-3 text-gray-300">
            {{ $user->bio ?? 'This user hasn’t added a bio yet.' }}
        </p>
    </div>

    <!-- Profile Details -->
    <div class="mt-2 ml-6 text-gray-400 text-sm space-y-2">
        <div class="flex items-center gap-3 mt-3">
        <p class="text-white font-medium"> {{ $user->followers()->count() }}<span class="text-gray-300"> Followers</span></p>
        <p class="text-white font-medium"> {{ $user->following()->count() }}<span class="text-gray-300"> Following</span></p>
        </div>
        <p><span>Joined</span> {{ $user->created_at->format('F Y') }}</p>    
    </div>

    <!-- Divider -->
    <hr class="my-6 border-gray-700">

    <!-- Posts Feed -->
    @if($posts->count())
        @foreach($posts as $post)
            <div class="p-4 border border-gray-700 rounded-lg bg-gray-800 relative mb-4">
                <!-- User info -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <img 
                            src="{{ optional($post->user)->avatar ?: asset('assets/img/default-avatar.svg') }}"
                            alt="{{ optional($post->user)->name ?? 'Guest' }}"
                            class="w-8 h-8 rounded-full object-cover">
                        <a href="{{ route('user.profile', optional($post->user)->id) }}" 
                           class="font-semibold text-orange-400 hover:underline">
                            {{ optional($post->user)->name ?? 'Unknown User' }}
                        </a>
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
                            <form action="{{ route('posts.unlike', $post->id) }}" method="POST" class="like-form" data-post-id="{{ $post->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" id="like-btn-{{ $post->id }}" class="group inline-flex items-center text-orange-400 px-3 py-1 rounded-full bg-gray-800 hover:border-orange-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                        id="like-icon-{{ $post->id }}"
                                        class="w-6 h-6 mr-1 text-orange-400" 
                                        fill="currentColor"
                                        stroke="orange"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" 
                                            d="M4.318 6.318a4.5 4.5 0 016.364 0L12 
                                            7.636l1.318-1.318a4.5 4.5 0 116.364 
                                            6.364L12 20.364l-7.682-7.682a4.5 
                                            4.5 0 010-6.364z"/>
                                    </svg>
                                    <span id="like-count-{{ $post->id }}" class="text-orange-400">
                                        {{ $post->likes->count() }}
                                    </span>
                                </button>
                            </form>
                        @else
                            <!-- Like -->
                            <form action="{{ route('posts.like', $post->id) }}" method="POST" class="like-form" data-post-id="{{ $post->id }}">
                                @csrf
                                <button type="submit" id="like-btn-{{ $post->id }}" class="group inline-flex items-center text-white px-3 py-1 rounded-full bg-gray-800 hover:border-orange-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                        id="like-icon-{{ $post->id }}"
                                        class="w-6 h-6 mr-1 transition text-white group-hover:text-orange-400" 
                                        fill="none"
                                        stroke="white"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" 
                                            d="M4.318 6.318a4.5 4.5 0 016.364 0L12 
                                            7.636l1.318-1.318a4.5 4.5 0 116.364 
                                            6.364L12 20.364l-7.682-7.682a4.5 
                                            4.5 0 010-6.364z"/>
                                    </svg>
                                    <span id="like-count-{{ $post->id }}" class="text-white group-hover:text-orange-400">
                                        {{ $post->likes->count() }}
                                    </span>
                                </button>
                            </form>
                        @endif
                    @endauth

                    <!-- Comments button -->
                    @auth
                        <form action="{{ route('posts.show', $post->id) }}" method="GET">
                            <button type="submit" class="group inline-flex items-center text-white px-3 py-1 rounded-full bg-gray-800 hover:border-orange-400">
                                <svg xmlns="http://www.w3.org/2000/svg" 
                                    class="w-5 h-5 mr-1 transition text-white group-hover:text-orange-400" 
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
        <div class="p-4 border border-gray-700 rounded-lg bg-gray-800 text-center text-gray-400">
            No posts yet.
        </div>
    @endif

</div>
@endsection
