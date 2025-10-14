@extends('layouts.master')

@section('content')
    <div class="max-w-2xl mx-auto p-4 mt-5 pb-35 lg:pb-0">
        <form method="GET" action="{{ route('search') }}" class="mb-4 relative">
            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                <!-- Search -->
                <svg xmlns="http://www.w3.org/2000/svg" 
                    class="h-5 w-5" 
                    fill="none" 
                    viewBox="0 0 24 24" 
                    stroke="currentColor" 
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                        d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
                </svg>
            </span>

            <input 
                type="text" 
                name="q" 
                placeholder="Search squeal" 
                class="w-full pl-10 p-2 rounded-full bg-gray-800 border-none text-white placeholder-gray-400 focus:ring-2 focus:ring-orange-400"
                required
            >

            <button type="submit" class="hidden"></button>
        </form>
        
        <h2 class="text-xl font-bold mb-4">Search results for "{{ $q }}"</h2>

        <h3 class="font-semibold mt-6 mb-2">Posts</h3>
        @forelse($posts as $post)
            <div class="p-4 border border-gray-700 rounded-lg bg-gray-800 relative mb-4">
                <!-- User info -->
                <div class="flex items-center justify-between">
                    <div class="font-bold flex items-center gap-2">
                        <img 
                            src="{{ optional($post->user)->avatar ?: asset('assets/img/default-avatar.svg') }}"
                            alt="{{ optional($post->user)->name ?? 'Guest' }}"
                            class="w-8 h-8 rounded-full object-cover">
                            <h2 class="font-medium text-orange-400">
                                <a href="{{ route('user.profile', optional($post->user)->id) }}">
                                    {{ optional($post->user)->name ?? 'Unknown User' }}
                                </a>
                            </h2>
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

                <!-- Post Actions Component -->
                @include('components.post-actions', ['post' => $post])
            </div>
        @empty
            <div class="text-gray-400">No posts found.</div>
        @endforelse

        <h3 class="font-semibold mt-6 mb-2">Users</h3>
        @forelse($users as $user)
            <div class="mb-4 p-4 bg-gray-800 rounded flex items-center gap-6">
                <img 
                    src="{{ $user->avatar ?? asset('assets/img/default-avatar.svg') }}"
                    alt="{{ $user->name }}"
                    class="w-8 h-8 rounded-full object-cover">
                <div>
                    <div class="font-medium">
                        <a href="{{ route('user.profile', $user->id) }}" class="text-orange-400 hover:underline">
                            {{ $user->name }}
                        </a>
                    </div>
                    <div class="text-sm text-gray-400">{{ $user->email }}</div>
                </div>

                @auth
                    @if(auth()->id() !== $user->id)
                        @if(auth()->user()->following->contains($user->id))
                            <form action="{{ route('unfollow', $user->id) }}" method="POST" class="ml-auto">
                                @csrf
                                @method('DELETE')
                                <button 
                                    class="text-gray-300 border border-gray-300 rounded-full 
                                            px-3 py-1 text-sm
                                            hover:bg-gray-600 hover:text-white
                                            focus:outline-none focus:ring-2 focus:ring-gray-300
                                            transition-colors duration-200">
                                    Unfollow
                                </button>
                            </form>
                        @else
                            <form action="{{ route('follow', $user->id) }}" method="POST" class="ml-auto">
                                @csrf
                                <button 
                                    class="text-orange-400 border border-orange-400 rounded-full 
                                            px-3 py-1 text-sm
                                            hover:bg-orange-500 hover:text-white
                                            focus:outline-none focus:ring-2 focus:ring-orange-300
                                            transition-colors duration-200">
                                    Follow
                                </button>
                            </form>
                        @endif
                    @endif
                @endauth
            </div>
        @empty
            <div class="text-gray-400">No users found.</div>
        @endforelse
    </div>
@endsection