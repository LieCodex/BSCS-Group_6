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
            <div class="text-orange-500 text-3xl font-bold mb-8">🦊</div>
            <nav class="space-y-6">
                <a href="{{ route('dashboard.home') }}" class="flex items-center gap-3 text-lg hover:text-orange-400"><span>🏠</span> Home</a>
                <a href="{{ route('dashboard.notifications') }}" class="flex items-center gap-3 text-lg hover:text-orange-400"><span>🔔</span> Notifications</a>
                <a href="{{ route('dashboard.messages') }}" class="flex items-center gap-3 text-lg hover:text-orange-400"><span>✉️</span> Messages</a>
                <a href="{{ route('dashboard.bookmarks') }}" class="flex items-center gap-3 text-lg hover:text-orange-400"><span>🔖</span> Bookmarks</a>
                <a href="{{ route('dashboard.profile') }}" class="flex items-center gap-3 text-lg hover:text-orange-400"><span>👤</span> Profile</a>
            </nav>
        </div>
        <div class="flex items-center gap-3">
            <img src="https://i.pravatar.cc/40" class="rounded-full" />
            <div>
                <p class="font-bold">Davide Biscuso</p>
                <p class="text-sm text-gray-400">@biscuttu</p>
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
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-3">
                    <img src="https://i.pravatar.cc/42" class="rounded-full" />
                    <div>
                        <p class="font-bold">Bessie Cooper</p>
                        <p class="text-gray-400 text-sm">@alessandroveronezi</p>
                    </div>
                </div>
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

</body>
</html>