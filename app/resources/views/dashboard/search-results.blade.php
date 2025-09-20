@extends('layouts.master')

@section('content')
    <div class="max-w-2xl mx-auto p-4">
        <h2 class="text-xl font-bold mb-4">Search results for "{{ $q }}"</h2>

        <h3 class="font-semibold mt-6 mb-2">Posts</h3>
        @forelse($posts as $post)
            <div class="p-4 border border-gray-700 rounded-lg bg-gray-800 relative mb-4">
                <!-- User info -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <img 
                            src="{{ optional($post->user)->avatar ?: asset('assets/img/default-avatar.svg') }}"
                            alt="{{ optional($post->user)->name ?? 'Guest' }}"
                            class="w-8 h-8 rounded-full object-cover">
                        <h2 class="font-bold text-orange-400">
                            {{ optional($post->user)->name ?? 'Unknown User' }}
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

                <!-- Comments button -->
                @auth
                    <form action="{{ route('posts.show', $post->id) }}" method="GET">
                        <button type="submit" class="group inline-flex items-center border border-white text-white px-3 py-1 rounded-full mt-3 bg-gray-800 hover:border-orange-400">
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
                    <div class="font-bold">{{ $user->name }}</div>
                    <div class="text-sm text-gray-400">{{ $user->email }}</div>
                </div>
            </div>
        @empty
            <div class="text-gray-400">No users found.</div>
        @endforelse
    </div>
@endsection