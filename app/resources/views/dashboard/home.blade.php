@extends('dashboard.layout')

@section('content')
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
                <textarea name="body"
                          placeholder="What's happening?"
                          class="w-full bg-transparent border-none focus:ring-0 resize-none"></textarea>
                <button class="mt-2 bg-orange-500 text-white px-4 py-1 rounded-full">Squeal</button>
            </form>
        </div>

        <!-- Posts Feed -->
        @foreach ($posts as $post)
            <div class="p-4 border border-gray-700 rounded-lg bg-gray-800">
                <h2 class="font-bold text-orange-400">{{ $post->user->name }}</h2>
                <p class="text-gray-300 mt-2">{{ $post->body }}</p>
                <p class="text-xs text-gray-500 mt-2">Posted {{ $post->created_at->diffForHumans() }}</p>
            </div>
        @endforeach
    </div>
@endsection
