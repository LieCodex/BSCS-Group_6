@extends('layouts.master')

@section('content')

<div class="flex h-screen border border-gray-700 shadow bg-gray-900 text-sm text-white overflow-hidden">

    <!-- Sidebar (User List) -->
    <div id="userSidebar" 
        class="fixed inset-y-0 left-0 w-64 bg-gray-900 border-r border-gray-700 transform -translate-x-full lg:translate-x-0 lg:relative lg:w-1/4 transition-transform duration-300 ease-in-out z-50">
        
        <div class="flex justify-between items-center p-4 border-b border-gray-700">
            <span class="font-bold text-orange-400">Chats</span>
            <!-- Close button (mobile only) -->
            <button onclick="toggleSidebar()" class="lg:hidden text-gray-400 hover:text-white">
                ✕
            </button>
        </div>
        
        <div class="divide-y divide-gray-700">
            <div class="p-3 cursor-pointer hover:bg-gray-800 transition">
                <div class="text-white">Test User</div>
                <div class="text-xs text-gray-400">test@gmail.com</div>
            </div>
            <div class="p-3 cursor-pointer hover:bg-gray-800 transition">
                <div class="text-white">Another User</div>
                <div class="text-xs text-gray-400">another@gmail.com</div>
            </div>
        </div>
    </div>

    <!-- Right: Chat Section -->
    <div class="flex-1 flex flex-col">
        <!-- Header -->
        <div class="p-4 border-b border-gray-700 bg-gray-800 flex justify-between items-center">
            <div>
                <div class="text-lg font-semibold text-white">Test User</div>
                <div class="text-xs text-gray-400">test@gmail.com</div>
            </div>
            <!-- Mobile: Open sidebar button -->
            <button onclick="toggleSidebar()" class="lg:hidden bg-orange-500 hover:bg-orange-600 text-white px-3 py-1 rounded-full text-xs">
                Chats
            </button>
        </div>

        <!-- Messages -->
        <div class="flex-1 p-4 overflow-y-auto space-y-3 bg-gray-900">
            <!-- Message from me -->
            <div class="flex justify-end">
                <div class="max-w-xs px-4 py-2 rounded-2xl bg-orange-500 text-white shadow">
                    Hi, This is test
                </div>
            </div>

            <!-- Message from other -->
            <div class="flex justify-start">
                <div class="max-w-xs px-4 py-2 rounded-2xl bg-gray-700 text-gray-100 shadow">
                    Hello, got your message!
                </div>
            </div>
        </div>

        <!-- Input -->
        <form class="p-3 border-t border-gray-700 bg-gray-800 flex items-center gap-2">
            <input 
                type="text"
                class="flex-1 bg-gray-900 border border-gray-700 rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 text-white placeholder-gray-400"
                placeholder="Type your message..." />
            <button type="submit"
                class="bg-orange-500 hover:bg-orange-600 text-white text-sm px-5 py-2 rounded-full transition">
                Send
            </button>
        </form>
    </div>
</div>

<!-- Sidebar Toggle Script -->
<script>
function toggleSidebar() {
    const sidebar = document.getElementById('userSidebar');
    sidebar.classList.toggle('-translate-x-full');
}
</script>

@endsection
