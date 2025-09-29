@extends('layouts.master')

@section('content')
<div class="p-6 space-y-6">

    <!-- Success message -->
    @if(session('success'))
        <div id="successmsg" class="bg-orange-500 text-white p-2 rounded">
            {{ session('success') }}
        </div>
    @endif
    
    @include('components.create-post')

    <!-- Posts Feed -->
    @if(isset($posts) && $posts->count())
        @foreach ($posts as $post)
            <div class="p-6 sm:p-6 lg:p-4 rounded-lg bg-gray-800 relative">

                <!-- User info -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
            <img 
                src="{{ optional($post->user)->avatar ?: asset('assets/img/default-avatar.svg') }}"
                alt="{{ optional(auth()->user())->name ?? 'Guest' }}"
                class="w-14 h-14 sm:w-20 sm:h-20 lg:w-10 lg:h-10 rounded-full object-cover mt-5">


                    <h2 class="font-bold text-orange-400">
                        <a href="{{ route('user.profile', optional($post->user)->id) }}">
                            {{ optional($post->user)->name ?? 'Unknown User' }}
                        </a>
                    </h2>
                    
                    <h2 class="font-extralight text-sm mt-1 text-gray-500">
                        <a href="{{ route('user.profile', optional($post->user)->id) }}">
                            {{ optional($post->user)->email ?? 'Unknown User' }}
                        </a>
                    </h2>
                    <p class="text-xs sm:text-3xl lg:text-xs text-gray-500 mt-1"> • {{ $post->created_at->diffForHumans() }}</p>
                    </div>

                    <div class="relative">
                        @auth
                            @if(auth()->id() === $post->user_id)
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
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- Post body -->
                <p class="text-gray-300 -mt-5 ml-11.5 break-words mr-11.5">{{ $post->body }}</p>

                <!-- Post images -->
                    @if($post->images->count())
                        <div class="flex flex-wrap justify-center items-center gap-2 mt-2 mr-25 ">
                            @foreach($post->images as $image)
                                <img 
                                src="{{ $image->image_path }}" 
                                class="post-image w-auto h-auto max-w-[160px] max-h-[160px] sm:max-w-[400px] sm:max-h-[400px] lg:max-w-[400px] lg:max-h-[600px] lg:min-h-[300px] lg:min-w-[400px]object-contain rounded-lg border border-gray-700 cursor-pointer"
                                onclick="openModal('{{ $image->image_path }}')"
                            />

                            @endforeach
                        </div>
                    @endif
    
                    <!-- Single reusable modal -->
                    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-70 hidden items-center justify-center z-50">
                        <div class="relative max-w-xl max-h-[90vh]">
                            <button id="closeModal" class="absolute top-2 right-2 text-white text-3xl">&times;</button>
                            <img id="modalImage" src="" alt="Full Image"
                                class="rounded-lg object-contain w-full h-full transform scale-100 transition-transform duration-200 cursor-grab"/>

                            
                            <!-- Zoom Controls -->
                            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2">
                                <button id="zoomIn" class="bg-gray-800 text-white px-4 py-1 rounded">+</button>
                                <button id="zoomOut" class="bg-gray-800 text-white px-4 py-1 rounded">−</button>
                            </div>
                        </div>
                    </div>
                <!-- Timestamp -->
                

                <!-- Buttons wrapper -->
                <div class="flex items-center gap-3 mt-3 ml-7">

                    <!-- Likes button -->
                    @auth
                        @if($post->isLikedBy(auth()->user()))
                            <!-- Unlike -->
                            <form action="{{ route('posts.unlike', $post->id) }}" method="POST" class="like-form" data-post-id="{{ $post->id }}">
                                @csrf
                                @method('DELETE')
                                <button 
                                    type="submit" 
                                    class="group inline-flex items-center text-white px-4 py-2 rounded-full bg-gray-800 hover:border-orange-500 text-sm sm:text-base lg:text-sm">

                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                        id="like-icon-{{ $post->id }}"
                                        class="w-6 h-6 mr-1 sm:w-15 sm:h-15 lg:w-6 lg:h-6 text-orange-400" 
                                        fill="{{ $post->isLikedBy(auth()->user()) ? 'currentColor' : 'none' }}"
                                        stroke="{{ $post->isLikedBy(auth()->user()) ? 'orange' : 'white' }}"
                                        stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" 
                                            d="M4.318 6.318a4.5 4.5 0 016.364 0L12 
                                            7.636l1.318-1.318a4.5 4.5 0 116.364 
                                            6.364L12 20.364l-7.682-7.682a4.5 
                                            4.5 0 010-6.364z"/>
                                    </svg>
                                       <span id="like-count-{{ $post->id }}" class="{{ $post->isLikedBy(auth()->user()) ? 'text-orange-400' : 'text-white' }} sm:text-3xl lg:text-sm">
                                        {{ $post->likes->count() }}
                                        </span>
                                </button>
                            </form>
                        @else
                            <!-- Like -->
                            <form action="{{ route('posts.like', $post->id) }}" method="POST" class="like-form" data-post-id="{{ $post->id }}">
                                @csrf
                                <button 
                                type="submit" 
                                class="group inline-flex items-center text-white px-4 py-2 rounded-full bg-gray-800 hover:border-orange-500 text-sm sm:text-base lg:text-sm">

                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                    id="like-icon-{{ $post->id }}"
                                        class="w-6 h-6 mr-1  sm:w-15 sm:h-15 lg:w-6 lg:h-6 transition text-white group-hover:text-orange-400" 
                                        fill="{{ $post->isLikedBy(auth()->user()) ? 'currentColor' : 'none' }}"
                                        stroke="{{ $post->isLikedBy(auth()->user()) ? 'orange' : 'white' }}"
                                        stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" 
                                            d="M4.318 6.318a4.5 4.5 0 016.364 0L12 
                                            7.636l1.318-1.318a4.5 4.5 0 116.364 
                                            6.364L12 20.364l-7.682-7.682a4.5 
                                            4.5 0 010-6.364z"/>
                                    </svg>
                                        <span id="like-count-{{ $post->id }}" class="{{ $post->isLikedBy(auth()->user()) ? 'text-orange-400' : 'text-white' }} sm:text-3xl lg:text-sm">
                                        {{ $post->likes->count() }}
                                        </span>
                                </button>
                            </form>
                        @endif
                    @endauth

                    <!-- Comments button -->
                    @auth
                        <form action="{{ route('posts.show', $post->id) }}" method="GET">
                           <button 
                            type="submit" 
                            class="group inline-flex items-center text-white px-4 py-2 rounded-full bg-gray-800 hover:border-orange-500 text-sm sm:text-base lg:text-sm">

                                <svg xmlns="http://www.w3.org/2000/svg" 
                                    class="w-6 h-6 mr-1 sm:w-15 sm:h-15 lg:w-6 lg:h-6 transition text-white group-hover:text-orange-400" 
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" 
                                        d="M7 8h10M7 12h6m-6 4h4m10-2.586V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h9l4 4v-5.586a2 2 0 0 0 .586-1.414z"/>
                                </svg>
                                <span class="transition text-white group-hover:text-orange-400 text-base sm:text-3xl lg:text-sm">
                                    {{ $post->comments->count() }}
                                </span>
                            </button>
                        </form>
                    @endauth
                </div>
            </div>

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
