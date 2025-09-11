@extends('dashboard.layout')

@section('content')
<div class="max-w-4xl mx-auto p-6">

    <!-- Header / Banner -->
    <div class="relative h-40 bg-gray-700 rounded-lg">
        <!-- Edit Profile Button -->
        <div class="absolute top-2 right-2">
            <a href="#" class="px-4 py-2 bg-gray-800 text-white rounded-full text-sm hover:bg-gray-700">
                Edit Profile
            </a>
        </div>
    </div>

    <!-- Avatar -->
    <div class="relative">
        <img src="{{ auth()->user()->avatar ?? 'https://via.placeholder.com/150' }}" 
             alt="{{ auth()->user()->name }}" 
             class="w-32 h-32 rounded-full border-4 border-gray-900 absolute -top-16 left-6 object-cover">
    </div>

    <!-- User Info -->
    <div class="mt-24 ml-6">
        <h1 class="text-2xl font-bold text-white">{{ auth()->user()->name }}</h1>
        <p class="text-gray-400">@{{ Str::slug(auth()->user()->name) }}</p>
        <p class="mt-3 text-gray-300">
            {{ auth()->user()->bio ?? 'This user hasn’t added a bio yet.' }}
        </p>
    </div>

    <!-- Profile Details -->
    <div class="mt-5 ml-6 text-gray-400 text-sm space-y-2">
        <p><span class="font-semibold text-white">Email:</span> johndoe@example.com</p>
        <p><span class="font-semibold text-white">Joined:</span> January 2024</p>
        <p><span class="font-semibold text-white">Posts:</span> 42</p>
    </div>

    <!-- Navigation Buttons -->
    <div class="mt-6 ml-6 flex gap-3">
        <a href="{{ url('/my-posts') }}" 
           class="px-4 py-2 border border-gray-600 text-gray-300 rounded-full hover:bg-gray-700 hover:text-white">
            My Posts
        </a>
    </div>

    <!-- Divider -->
    <hr class="my-6 border-gray-700">

    <!-- Posts Feed Preview -->
    <div class="space-y-4">
        <h2 class="text-xl font-bold text-white">Posts</h2>

        <div class="p-4 border border-gray-700 rounded-lg bg-gray-800">
            <p class="text-gray-300">This is my first post on the platform!</p>
            <p class="text-xs text-gray-500 mt-2">Posted 2 days ago</p>
        </div>

        <div class="p-4 border border-gray-700 rounded-lg bg-gray-800">
            <p class="text-gray-300">Loving the new features 🚀</p>
            <p class="text-xs text-gray-500 mt-2">Posted 5 days ago</p>
        </div>
    </div>

</div>
@endsection
