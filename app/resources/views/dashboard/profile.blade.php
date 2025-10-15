@extends('layouts.master')

@section('content')
<div x-data="{ followersModal: false, followingModal: false }" class="p-6 space-y-6 pb-35 lg:pb-0">

    <!-- Header / Banner -->
    <div x-data="{ open: false }" class="relative lg:h-40 sm:h-80 bg-gray-700 absolute rounded-lg">

    <x-edit-profile :user="$user" />
    </div>

    <div class="relative flex items-end justify-between">
        <!-- Avatar -->
        <img src="{{ $user->avatar ? $user->avatar.'?v='.time() : asset('assets/img/default-avatar.svg') }}" 
            alt="{{ $user->name }}" 
            class="lg:w-32 lg:h-32 sm:w-58 sm:h-58 rounded-full border-4 border-gray-900 absolute -top-16 left-6 object-cover">


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
                                lg:px-5 lg:py-2 sm:px-10 sm:py-3 lg:text-base sm:text-3xl
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
                                lg:px-5 lg:py-2 sm:px-10 sm:py-3 lg:text-base sm:text-3xl
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
                            lg:px-5 lg:py-2 sm:px-10 sm:py-3 lg:text-base sm:text-3xl
                            hover:bg-orange-500 hover:text-white
                            focus:outline-none focus:ring-2 focus:ring-orange-300
                            transition-colors duration-200">
                        Chat
                    </button>
                </form>
            </div>
        @endif
    </div>

<div class="sm:mt-50 lg:mt-0">
    <!-- User Info -->
    <div class="mt-18 ml-6">
        <h1 class="lg:text-2xl sm:text-5xl font-semibold text-white">{{ $user->name }}</h1>
        <p class="text-gray-500 lg:text-base sm:text-2xl">{{ $user->email }}</p>
        <p class="mt-3 text-gray-300 lg:text-base sm:text-3xl">
            {{ $user->bio ?? "This user hasn't added a bio yet." }}
        </p>
    

    <!-- Profile Details -->
    <div class="text-gray-400 text-sm space-y-2">
        <div class="flex items-center gap-3 mt-3">
        <button @click="followersModal = true" class="text-white font-medium lg:text-base sm:text-2xl hover:text-orange-400 transition-colors"> {{ $user->followers()->count() }}<span class="text-gray-300 lg:text-base sm:text-2xl"> Followers</span></button>
        <button @click="followingModal = true" class="text-white font-medium lg:text-base sm:text-2xl hover:text-orange-400 transition-colors"> {{ $user->following()->count() }}<span class="text-gray-300 lg:text-base sm:text-2xl"> Following</span></button>
        </div>
        <p class="lg:text-base sm:text-2xl"><span>Joined</span> {{ $user->created_at->format('F Y') }}</p>
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

    <!-- Followers Modal -->
    <div
        x-show="followersModal"
        x-transition.opacity
        @click.self="followersModal = false"
        x-cloak
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 overflow-y-auto"
    >
        <div
            x-show="followersModal"
            x-transition
            class="bg-gray-900 border border-gray-800 rounded-2xl w-full lg:max-w-xl lg:mx-3 lg:my-8 sm:max-w-3xl sm:mx-4 sm:my-8 relative shadow-2xl flex flex-col modal-card max-h-[80vh]"
        >
            <div class="flex items-center justify-between px-6 pt-5 pb-2 border-b border-gray-800">
                <h2 class="text-white lg:text-xl sm:text-4xl font-semibold">Followers</h2>
                <button @click="followersModal = false" class="text-gray-400 hover:text-white lg:text-2xl sm:text-4xl font-semibold">✕</button>
            </div>
            <div class="flex-1 overflow-y-auto px-6 py-4">
                @if($followers->count() > 0)
                    @foreach($followers as $follower)
                        <div class="flex items-center gap-4 py-3 border-b border-gray-800 last:border-b-0">
                            <img
                                src="{{ $follower->avatar ? $follower->avatar.'?v='.time() : asset('assets/img/default-avatar.svg') }}"
                                alt="{{ $follower->name }}"
                                class="lg:w-12 lg:h-12 sm:w-20 sm:h-20 rounded-full object-cover"
                            >
                            <div class="flex-1">
                                <a href="{{ route('user.profile', $follower->id) }}" class="text-white font-medium lg:text-base sm:text-2xl hover:text-orange-400 transition-colors">
                                    {{ $follower->name }}
                                </a>
                                <p class="text-gray-400 lg:text-sm sm:text-xl">{{ $follower->email }}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-8 text-gray-400 lg:text-base sm:text-2xl">
                        No followers yet.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Following Modal -->
    <div
        x-show="followingModal"
        x-transition.opacity
        @click.self="followingModal = false"
        x-cloak
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 overflow-y-auto"
    >
        <div
            x-show="followingModal"
            x-transition
            class="bg-gray-900 border border-gray-800 rounded-2xl w-full lg:max-w-xl lg:mx-3 lg:my-8 sm:max-w-3xl sm:mx-4 sm:my-8 relative shadow-2xl flex flex-col modal-card max-h-[80vh]"
        >
            <div class="flex items-center justify-between px-6 pt-5 pb-2 border-b border-gray-800">
                <h2 class="text-white lg:text-xl sm:text-4xl font-semibold">Following</h2>
                <button @click="followingModal = false" class="text-gray-400 hover:text-white lg:text-2xl sm:text-4xl font-semibold">✕</button>
            </div>
            <div class="flex-1 overflow-y-auto px-6 py-4">
                @if($following->count() > 0)
                    @foreach($following as $followed)
                        <div class="flex items-center gap-4 py-3 border-b border-gray-800 last:border-b-0">
                            <img
                                src="{{ $followed->avatar ? $followed->avatar.'?v='.time() : asset('assets/img/default-avatar.svg') }}"
                                alt="{{ $followed->name }}"
                                class="lg:w-12 lg:h-12 sm:w-20 sm:h-20 rounded-full object-cover"
                            >
                            <div class="flex-1">
                                <a href="{{ route('user.profile', $followed->id) }}" class="text-white font-medium lg:text-base sm:text-2xl hover:text-orange-400 transition-colors">
                                    {{ $followed->name }}
                                </a>
                                <p class="text-gray-400 lg:text-sm sm:text-xl">{{ $followed->email }}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-8 text-gray-400 lg:text-base sm:text-2xl">
                        Not following anyone yet.
                    </div>
                @endif
            </div>
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
<script src="{{ asset('assets/js/home.js') }}?v={{ filemtime(public_path('assets/js/home.js')) }}"></script>
@endsection