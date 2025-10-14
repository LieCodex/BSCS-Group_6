@extends('layouts.master')

@section('content')
    <div class="max-w-2xl mx-auto p-4 mt-5 pb-35 lg:pb-0">
        <h2 class="text-xl font-bold mb-4">Search results for "{{ $q }}"</h2>

        <h3 class="font-semibold mt-6 mb-2 lg:text-base sm:text-3xl">Posts</h3>
        <!-- Posts Feed -->
        @if(isset($posts) && $posts->count())
            @foreach ($posts as $post)
                @include('components.post-card', ['post' => $post])
            @endforeach
        @else
            <div class="text-gray-400 lg:text-base sm:text-2xl">No posts found.</div>
        @endif

        <h3 class="font-semibold mt-6 mb-2 lg:text-base sm:text-3xl ">Users</h3>
        @forelse($users as $user)
            <div class="mb-4 p-4 bg-gray-800 rounded flex items-center gap-6">
                <img 
                    src="{{ $user->avatar ?? asset('assets/img/default-avatar.svg') }}"
                    alt="{{ $user->name }}"
                    class="lg:w-8 lg:h-8 sm:w-25 sm:h-25 rounded-full object-cover">
                <div>
                    <div class="font-medium lg:text-base sm:text-3xl">
                        <a href="{{ route('user.profile', $user->id) }}" class="text-orange-400 hover:underline ">
                            {{ $user->name }}
                        </a>
                    </div>
                    <div class="text-sm text-gray-400 lg:text-sm sm:text-2xl">{{ $user->email }}</div>
                </div>

                @auth
                    @if(auth()->id() !== $user->id)
                        @if(auth()->user()->following->contains($user->id))
                            <form action="{{ route('unfollow', $user->id) }}" method="POST" class="ml-auto">
                                @csrf
                                @method('DELETE')
                                <button 
                                    class="text-gray-300 border border-gray-300 rounded-full 
                                            lg:px-3 lg:py-1 sm:px-6 sm:py-2 lg:text-sm sm:text-3xl 
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
                                            lg:px-3 lg:py-1 sm:px-6 sm:py-2 lg:text-sm sm:text-3xl
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
            <div class="text-gray-400 lg:text-base sm:text-3xl">No users found.</div>
        @endforelse
    </div>
@endsection