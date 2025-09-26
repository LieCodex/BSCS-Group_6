<!DOCTYPE html>
<html lang="en">
<head>
    @livewireStyles
    <meta charset="UTF-8">
    <title>Squeal</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=ABeeZee&display=swap" rel="stylesheet">
</head>
<!-- Bottom Nav (Mobile only) -->
<nav class="fixed bottom-0 left-0 right-0 bg-gray-900 border-t border-gray-700 
            flex justify-around py-2 lg:hidden z-50">

    <!-- Home -->
    <a href="{{ route('dashboard.home') }}" class="flex flex-col items-center text-gray-400 hover:text-orange-400">
        <svg xmlns="http://www.w3.org/2000/svg" 
            class="h-18 w-18 mb-1" fill="none" viewBox="0 0 24 24" 
            stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" 
                  d="M3 9.75L12 3l9 6.75V21a.75.75 0 0 1-.75.75H3.75A.75.75 0 0 1 3 21V9.75z"/>
        </svg>
        <span class="text-4xl">Home</span>
    </a>

    <!-- Notifications -->
    <a href="{{ route('dashboard.notifications') }}" class="flex flex-col items-center text-gray-400 hover:text-orange-400">
        <svg xmlns="http://www.w3.org/2000/svg" 
            class="h-18 w-18 mb-1" fill="none" viewBox="0 0 24 24" 
            stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" 
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6 6 0 0 0-12 0v3.159c0 .538-.214 1.055-.595 1.436L3 17h5m7 4a3 3 0 0 1-6 0"/>
        </svg>
        <span class="text-4xl">Alerts</span>
    </a>

    <!-- Messages -->
    <a href="{{ route('dashboard.messages') }}" class="flex flex-col items-center text-gray-400 hover:text-orange-400">
        <svg xmlns="http://www.w3.org/2000/svg" 
            class="h-18 w-18 mb-1" fill="none" viewBox="0 0 24 24" 
            stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" 
                  d="M21 11.5c0 4.142-3.806 7.5-8.5 7.5-1.143 0-2.228-.19-3.2-.53L5 20l1.2-3.2C5.45 15.5 4.5 13.6 4.5 11.5 4.5 7.358 8.306 4 13 4s8 3.358 8 7.5z"/>
        </svg>
        <span class="text-4xl">Messages</span>
    </a>

    <!-- Profile -->
    <a href="{{ route('dashboard.profile') }}" class="flex flex-col items-center text-gray-400 hover:text-orange-400">
        <svg xmlns="http://www.w3.org/2000/svg" 
            class="h-18 w-18 mb-1" fill="none" viewBox="0 0 24 24" 
            stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" 
                  d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
        </svg>
        <span class="text-4xl">Profile</span>
    </a>
</nav>

<body class="bg-gray-900 text-white flex flex-col lg:flex-row h-screen">

    <!-- Sidebar (Left) -->
    <aside class="hidden lg:flex w-60 h-screen p-6 flex-col justify-between sticky top-0 ml-15">

        <div>
            <a href="{{ route('dashboard.home') }}">
                <img src="{{ asset('assets/img/squeal_logo.png') }}" alt="logo" class="h-20 w-20 mb-6 ml-5">
            </a> 
                <nav class="space-y-6">
                    <!-- Home -->
                    <a href="{{ route('dashboard.home') }}" 
                    class="flex items-center gap-3 text-lg text-white transition group hover:text-orange-400">
                        <svg xmlns="http://www.w3.org/2000/svg" 
                            class="h-6 w-6 transition text-white group-hover:text-orange-400" 
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                                d="M3 9.75L12 3l9 6.75V21a.75.75 0 0 1-.75.75H3.75A.75.75 0 0 1 3 21V9.75z"/>
                        </svg>
                        Home
                    </a>

                    <!-- Notifications -->
                    <a href="{{ route('dashboard.notifications') }}" 
                    class="flex items-center gap-3 text-lg text-white transition group hover:text-orange-400">
                        <svg xmlns="http://www.w3.org/2000/svg" 
                            class="h-6 w-6 transition text-white group-hover:text-orange-400" 
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6 6 0 0 0-12 0v3.159c0 .538-.214 1.055-.595 1.436L3 17h5m7 4a3 3 0 0 1-6 0"/>
                        </svg>
                        Notifications
                    </a>

                    <!-- Messages-->
                    <a href="{{ route('dashboard.messages') }}" 
                    class="flex items-center gap-3 text-lg text-white transition group hover:text-orange-400">
                        <svg xmlns="http://www.w3.org/2000/svg" 
                            class="h-6 w-6 transition text-white group-hover:text-orange-400"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                                d="M21 11.5c0 4.142-3.806 7.5-8.5 7.5-1.143 0-2.228-.19-3.2-.53L5 20l1.2-3.2C5.45 15.5 4.5 13.6 4.5 11.5 4.5 7.358 8.306 4 13 4s8 3.358 8 7.5z"/>
                        </svg>
                        Messages
                    </a>

                    <!-- Profile -->
                    <a href="{{ route('dashboard.profile') }}" 
                    class="flex items-center gap-3 text-lg text-white transition group hover:text-orange-400">
                        <svg xmlns="http://www.w3.org/2000/svg" 
                            class="h-6 w-6 transition text-white group-hover:text-orange-400" 
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                        Profile
                    </a>
                </nav>
        </div>

        <!-- User Dropdown -->
        <div class="relative flex items-center mb-3" x-data="{ open: false }">
            @auth
                <!-- Avatar button -->
                <button @click="open = !open" class="focus:outline-none">
                    <img 
                        src="{{ auth()->user()->avatar ?? asset('assets/img/default-avatar.svg') }}"
                        alt="{{ auth()->user()->name }}"
                        class="w-12 h-12 sm:w-14 sm:h-14 lg:w-10 lg:h-10 rounded-full object-cover">
                </button>

                <!-- Username -->
                <p class="font-medium ml-2">{{ auth()->user()->name }}</p>

                <!-- Dropdown Menu -->
                <div x-cloak
                    x-show="open"
                    x-transition
                    @click.outside="open = false"
                    class="absolute left-10 bottom-full mb-2 w-40 bg-gray-800 rounded-lg shadow-lg z-50 text-white">
                    <ul>
                        <li>
                            <a href="" class="block px-4 py-2 hover:bg-gray-700">Settings</a>
                        </li>
                        <li>
                            <form method="POST" action="/logout">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-700">
                                    Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <div class="flex items-center gap-2">
                    <img 
                        src="{{ asset('assets/img/default-avatar.svg') }}"
                        alt="Guest"
                        class="w-12 h-12 sm:w-14 sm:h-14 lg:w-10 lg:h-10 rounded-full object-cover">
                    <p class="font-medium ml-2">Guest</p>
                </div>
            @endauth
        </div>
    </aside>
<div class="flex justify-center flex-1">
    <!-- Main Feed (middle) -->
<main class="w-full max-w-full lg:max-w-2xl border-x border-gray-700 h-screen overflow-y-auto text-base sm:text-lg lg:text-base">
    @yield('content')
</main>
</div>
    <!-- Sidebar (Right) -->
    @auth
    <aside class="w-80 p-6 hidden lg:block h-screen overflow-y-auto">
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

        
        <div class="mt-6 bg-gray-800 p-4 rounded-xl">
            <h3 class="font-bold text-lg mb-3">Who to follow</h3>
            
            <hr class="border-gray-700 mb-4">

            @php
                // Get users excluding the current logged in user
                $suggestedUsers = \App\Models\User::where('id', '!=', auth()->id())
                    ->take(10)
                    ->get();
            @endphp

            @foreach($suggestedUsers as $user)
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ $user->avatar ?? asset('assets/img/default-avatar.svg') }}" 
                            class="w-12 h-12 sm:w-14 sm:h-14 lg:w-10 lg:h-10 rounded-full object-cover" />
                    <div class="font-medium hover:underline">
                        <a href="{{ route('user.profile', $user->id) }}">
                            {{ $user->name }}
                        </a>
                    </div>  
                    </div>

                    @if(auth()->user()->following->contains($user->id))
                        <!-- Already following -->
                        <form action="{{ route('unfollow', $user->id) }}" method="POST">
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
                        <!-- Not following -->
                        <form action="{{ route('follow', $user->id) }}" method="POST">
                            @csrf
                            <button 
                            class="text-orange-500 border border-orange-500 rounded-full 
                                    px-3 py-1 text-sm 
                                    hover:bg-orange-500 hover:text-white 
                                    focus:outline-none focus:ring-2 focus:ring-orange-300
                                    transition-colors duration-200">
                            Follow
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    </aside>
    @endauth
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('assets/js/master_like.js') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireScripts
</body>
</html>
