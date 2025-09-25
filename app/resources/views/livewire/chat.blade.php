<div class="flex h-screen border border-gray-700 shadow bg-gray-900 text-sm text-white overflow-hidden">

    <!-- Sidebar (User List) -->
    <div id="userSidebar" 
        class="fixed inset-y-0 left-0 w-64 bg-gray-900 border-r border-gray-700 transform -translate-x-full lg:translate-x-0 lg:relative lg:w-1/4 transition-transform duration-300 ease-in-out z-50 flex flex-col">
        
        <div class="flex justify-between items-center p-4 border-b border-gray-700 flex-shrink-0">
            <span class="font-bold text-orange-400">Chats</span>
            <!-- Close button (mobile only) -->
            <button onclick="toggleSidebar()" class="lg:hidden text-gray-400 hover:text-white">
                ✕
            </button>
        </div>
        
        <div class="divide-y divide-gray-700 overflow-y-auto flex-1">
            @foreach($users as $user)
            <div wire:click="selectUser({{ $user->id}})" class="p-3 cursor-pointer hover:bg-gray-800 transition
                {{$selectedUser->id === $user->id ? 'bg-gray-800 font-simibold' : ''}}">
                <div class="text-white truncate overflow-hidden whitespace-nowrap">{{ $user->name }}</div>
                <div class="text-xs text-gray-400 truncate overflow-hidden whitespace-nowrap">{{ $user->email}}</div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Right: Chat Section -->
    <div class="flex-1 flex flex-col">
        <!-- Header -->
        <div class="p-4 border-b border-gray-700 bg-gray-800 flex justify-between items-center">
            <div>
                <div class="text-lg font-semibold text-white">{{$selectedUser->name}}</div>
                <div class="text-xs text-gray-400">{{$selectedUser->email}}</div>
            </div>
            <!-- Mobile: Open sidebar button -->
            <button onclick="toggleSidebar()" class="lg:hidden bg-orange-500 hover:bg-orange-600 text-white px-3 py-1 rounded-full text-xs">
                Chats
            </button>
        </div>

        <!-- Messages -->
        <div class="flex-1 p-4 overflow-y-auto space-y-3 bg-gray-900">
            <!-- Message from me -->

            @foreach ($messages as $message)
                <div class="flex {{$message ->sender_id === auth()->id() ? 'justify-end' : 'justify-start'}}">
                    <div class="max-w-xs px-4 py-2 rounded-2xl  {{$message ->sender_id === auth()->id() ? 'bg-orange-500 text-white' : 'bg-gray-700 text-gray-100'}}  shadow">
                        {{ $message->message }}
                    </div>
                </div>
            @endforeach

        </div>

        <!-- Input -->
        <form wire:submit.prevent="submit" class="p-3 border-t border-gray-700 bg-gray-800 flex items-center gap-2">
            <input 
                id="chatInput"
                wire:model.live="newMessage"
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
<script>
document.addEventListener("livewire:init", () => {
    Livewire.on("messageSent", () => {
        document.getElementById("chatInput").value = "";
    });
});
</script>
<!-- Sidebar Toggle Script -->
<script>
function toggleSidebar() {
    const sidebar = document.getElementById('userSidebar');
    sidebar.classList.toggle('-translate-x-full');
}
</script>
