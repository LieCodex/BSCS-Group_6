<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Squeal Dashboard</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-900 text-white flex">

    <!-- Sidebar -->
    <aside class="w-60 h-screen bg-gray-800 p-6 flex flex-col justify-between">
        <div>
            <a href="{{ route('dashboard.home') }}">
                    <img src="assets/img/squel logo orange.png" alt="logo" class="h-16 w-16 mb-6">
             </a>
            
            <nav class="space-y-6">
                <a href="{{ route('dashboard.home') }}" class="flex items-center gap-3 text-lg hover:text-orange-400">
                <img src="assets/img/home.png" alt="Home" class="h-6 w-6"> Home
                </a>

                <a href="{{ route('dashboard.notifications') }}" class="flex items-center gap-3 text-lg hover:text-orange-400">
                <img src="assets/img/notifications.png" alt="Notifications" class="h-6 w-6"> Notifications
                </a>

                <a href="{{ route('dashboard.messages') }}" class="flex items-center gap-3 text-lg hover:text-orange-400">
                <img src="assets/img/messages.png" alt="Messages" class="h-6 w-6"> Messages
                </a>

                <a href="{{ route('dashboard.profile') }}" class="flex items-center gap-3 text-lg hover:text-orange-400">
                <img src="assets/img/profile.png" alt="Profile" class="h-6 w-6"> Profile
                </a>
            </nav>
        </div>
       <div class="flex items-center justify-between mb-3">
    <div x-data="{ open: false }" class="relative flex items-center gap-3">
    <!-- Avatar Button -->
    @if(auth()->user()->avatar)
        <button @click="open = !open" class="focus:outline-none">
            <img src="{{ auth()->user()->avatar }}"
                 alt="{{ auth()->user()->name }}"
                 class="w-8 h-8 rounded-full object-cover">
        </button>
    @endif

    <!-- User Info -->
    <div>
        <p class="font-bold">{{ auth()->user()->name }}</p>
    </div>

    <!-- Dropdown Menu -->
    <div x-cloak
         x-show="open"
        x-transition
         @click.outside="open = false"
         class="absolute right-0 top-full mb-2 w-30 bg-white border rounded-lg shadow-lg z-50 py-2"
>
        <ul class="py-2 text-gray-700">

            <li>
                <a href="" class="block px-4 py-2 hover:bg-gray-100">
                    Settings
                </a>
            </li>
            <li>
                <form method="POST" action="">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100">
                        Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>

</div>


    </aside>

<!-- Feed -->
<main class="flex-1 max-w-2xl border-x border-gray-700">
    @yield('content')
</main>

    <!-- Right Sidebar -->
    <aside class="w-80 p-6 hidden lg:block">
        <input type="text" placeholder="Search Squeal" class="w-full p-2 rounded-full bg-gray-800 border-none" />
        
        <div class="mt-6 bg-gray-800 p-4 rounded-xl">
            <h3 class="font-bold text-lg mb-3">Who to follow</h3>

                <button class="bg-orange-500 px-3 py-1 rounded-full text-sm">Follow</button>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="https://i.pravatar.cc/43" class="rounded-full" />
                    <div>
                        <p class="font-bold">Jenny Wilson</p>
                        <p class="text-gray-400 text-sm">@gabrielcantar</p>
                    </div>
                </div>
                <button class="bg-orange-500 px-3 py-1 rounded-full text-sm">Follow</button>
            </div>
        </div>
    </aside>
<script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>