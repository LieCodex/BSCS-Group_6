@extends('layouts.master')

@section('content')
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
</div>

<script src="{{ asset('assets/js/home.js') }}"></script>
@endsection