<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Squeal</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-[#17202A] text-white flex h-screen">

    <!-- Sidebar (Left) -->
    <aside class="w-60 h-screen p-6 flex flex-col justify-between sticky top-0 ml-32">
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
                        class="w-8 h-8 rounded-full object-cover">
                </button>

                <!-- Username -->
                <p class="font-bold ml-2">{{ auth()->user()->name }}</p>

                <!-- Dropdown Menu -->
                <div x-cloak
                    x-show="open"
                    x-transition
                    @click.outside="open = false"
                    class="absolute left-10 bottom-full mb-2 w-40 bg-white border rounded-lg shadow-lg z-50 py-2 text-gray-700">
                    <ul>
                        <li>
                            <a href="" class="block px-4 py-2 hover:bg-gray-100">Settings</a>
                        </li>
                        <li>
                            <form method="POST" action="/logout">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100">
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
                        class="w-8 h-8 rounded-full object-cover">
                    <p class="font-bold ml-2">Guest</p>
                </div>
            @endauth
        </div>
    </aside>

    <!-- Main Feed (middle) -->
    <main class="flex-1 max-w-2xl border-x border-gray-700 h-screen overflow-y-auto">
        @yield('content')
    </main>

    <!-- Sidebar (Right) -->
    @auth
    <aside class="w-80 p-6 hidden lg:block h-screen overflow-y-auto">
        <form method="GET" action="{{ route('search') }}" class="mb-4">
    <input type="text" name="q" placeholder="Search posts or users..." class="w-full p-2 rounded-full bg-gray-800 border-none text-white" required>
    <button type="submit" class="hidden"></button>
</form>
        
        <div class="mt-6 bg-gray-800 p-4 rounded-xl">
            <h3 class="font-bold text-lg mb-3">Who to follow</h3>

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
                            class="w-10 h-10 rounded-full object-cover" />
                        <div>
                            <p class="font-bold">{{ $user->name }}</p>
                        </div>
                    </div>

                    @if(auth()->user()->following->contains($user->id))
                        <!-- Already following -->
                        <form action="{{ route('unfollow', $user->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="bg-gray-600 px-3 py-1 rounded-full text-sm">Unfollow</button>
                        </form>
                    @else
                        <!-- Not following -->
                        <form action="{{ route('follow', $user->id) }}" method="POST">
                            @csrf
                            <button class="bg-orange-400 px-3 py-1 rounded-full text-sm">Follow</button>
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
</body>
</html>
