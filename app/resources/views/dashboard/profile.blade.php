@extends('layouts.master')

@section('content')
<div class="p-6 space-y-6">

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
                        class="text-orange-400 border border-orange-400 rounded-full 
                                px-5 py-2 text-sm
                                hover:bg-orange-500 hover:text-white
                                focus:outline-none focus:ring-2 focus:ring-orange-300
                                transition-colors duration-200">
                        Follow
                        </button>
                    </form>
                @endif
                    <!-- Chat -->
                <form action="{{ route('dashboard.messages') }}" method="GET">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <button 
                        class="text-orange-400 border border-orange-400 rounded-full 
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
            {{ $user->bio ?? "This user hasn't added a bio yet." }}
        </p>
    

    <!-- Profile Details -->
    <div class="text-gray-400 text-sm space-y-2">
        <div class="flex items-center gap-3 mt-3">
        <p class="text-white font-medium"> {{ $user->followers()->count() }}<span class="text-gray-300"> Followers</span></p>
        <p class="text-white font-medium"> {{ $user->following()->count() }}<span class="text-gray-300"> Following</span></p>
        </div>
        <p><span>Joined</span> {{ $user->created_at->format('F Y') }}</p>    
    </div>
    </div>

    <!-- Divider -->
    <hr class="my-6 border-gray-700">

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-70 hidden items-center justify-center z-50">
        <div class="relative max-w-xl max-h-[90vh]">
            <button id="closeModal" class="absolute top-2 right-2 text-white text-3xl">&times;</button>
            <img id="modalImage" src="" alt="Full Image"
                class="rounded-lg object-contain w-full h-full transform scale-100 transition-transform duration-200 cursor-grab"/>

            <!-- Zoom Controls -->
            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2">
                <button 
                    id="zoomIn" 
                    class="bg-gray-800/70 hover:bg-gray-800 text-white px-4 py-1 rounded transition">
                    +
                </button>
                <button 
                    id="zoomOut" 
                    class="bg-gray-800/70 hover:bg-gray-800 text-white px-4 py-1 rounded transition">
                    −
                </button>
            </div>
        </div>
    </div>

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