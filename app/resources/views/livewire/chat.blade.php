<div wire:poll.60s="heartbeat" class="flex h-screen border border-gray-700 shadow bg-gray-900 text-sm text-white overflow-hidden " >

    <!-- Sidebar (User List) -->
    <div id="userSidebar" 
        class="fixed inset-y-0 left-0 w-64 bg-gray-900 border-r border-gray-700 transform -translate-x-full lg:translate-x-0 lg:relative lg:w-1/4 transition-transform duration-300 ease-in-out z-50 flex flex-col">
        
        <div class="relative flex justify-center items-center p-4 border-b border-gray-700 flex-shrink-0">
            <span class="font-bold text-orange-400 text-xl pt-2 pb-2">Chats</span>

            <!-- Close button (mobile only) -->
            <button 
                onclick="toggleSidebar()" 
                class="absolute right-4 lg:hidden text-gray-400 hover:text-white"
            >
                ✕
            </button>
        </div>
        
        <div class="divide-y divide-gray-700 overflow-y-auto flex-1">
            @foreach($users as $user)

        <div 
            wire:key="user-{{ $user->id }}"
            wire:click="selectUser({{ $user->id }})" 
            class="p-3 cursor-pointer hover:bg-gray-800 transition flex items-center gap-3
                {{ isset($selectedUser) && $selectedUser->id === $user->id ? 'bg-gray-800 font-semibold' : '' }}">
            <div class="relative w-8 h-8 flex-shrink-0">
                        <img 
                            src="{{ $user->avatar ?: asset('assets/img/default-avatar.svg') }}"
                            alt="{{ $user->name ?? 'Unknown User' }}"
                            class="w-8 h-8 rounded-full object-cover">

                {{-- Online status blob --}}
                    @if($user->isOnline())
                        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border border-gray-900 rounded-full"></span>
                    @endif
        </div>
            <div class="flex flex-col overflow-hidden">
                <div class="flex items-center gap-2">
                    <span class="text-white truncate">{{ $user->name }}</span>
                    
                    @if(isset($unread[$user->id]))
                        <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                    @endif
                </div>
                <div class="text-xs text-gray-400 truncate">{{ $user->email }}</div>
            </div>
        </div>

            @endforeach
        </div>
    </div>

    <!-- Right: Chat Section -->
    <div class="flex-1 flex flex-col relative">
        <!-- Header -->
        <!-- Header -->
<div class="p-4 border-b border-gray-700 bg-gray-800 flex justify-between items-center">
    <!-- Avatar + Name + Email -->
    <div class="flex items-center gap-3">
        <div class="relative w-10 h-10">
            <img 
                src="{{ $selectedUser->avatar ?: asset('assets/img/default-avatar.svg') }}"
                alt="{{ $selectedUser->name ?? 'Unknown User' }}"
                class="w-10 h-10 rounded-full object-cover"
            >

            {{-- Status dot --}}
            @if($selectedUser->isOnline())
                <span class="absolute bottom-0 right-0 block w-3 h-3 bg-green-500 
                             border-2 border-gray-800 rounded-full"></span>
            @else
                <span class="absolute bottom-0 right-0 block w-3 h-3 bg-gray-400 
                             border-2 border-gray-800 rounded-full"></span>
            @endif
        </div>

        <div class="flex flex-col">
            <div class="text-lg font-semibold text-white">{{ $selectedUser->name }}</div>
            <div class="text-xs text-gray-400">{{ $selectedUser->email }}</div>
        </div>
    </div>

    <!-- Mobile: Open sidebar button -->
    <button 
        onclick="toggleSidebar()" 
        class="lg:hidden bg-orange-500 hover:bg-orange-600 text-white px-3 py-1 rounded-full text-xs"
    >
        Chats
    </button>
</div>


<!-- Messages -->
<div id="chatMessages" class="flex-1 p-4 overflow-y-auto space-y-1  bg-gray-900">
    @foreach ($messages as $i => $message)
        @php
            $isMe = $message->sender_id === auth()->id();
            $sender = $isMe ? auth()->user() : $selectedUser;

            // Check if next message exists and is from the same sender
            $next = $messages[$i + 1] ?? null;
            $showAvatar = !$next || $next->sender_id !== $message->sender_id;
        @endphp

        <div class="flex items-end gap-2 {{ $isMe ? 'justify-end' : 'justify-start' }}">
            
            {{-- Avatar (only show if last message in group) --}}
            @if (!$isMe && $showAvatar)
                <img 
                    src="{{ $sender->avatar ?: asset('assets/img/default-avatar.svg') }}" 
                    alt="{{ $sender->name }}" 
                    class="w-8 h-8 rounded-full object-cover"
                >
            @elseif (!$isMe)
                {{-- Spacer to align bubbles without avatar --}}
                <div class="w-8"></div>
            @endif

            {{-- Message bubble --}}
            <div 
                class="max-w-xs px-4 py-2 rounded-2xl shadow cursor-pointer 
                    break-words 
                    {{ $isMe ? 'bg-orange-500 text-white order-1' : 'bg-gray-700 text-gray-100' }}"
                data-message-id="{{ $message->id }}"
                title="{{ $message->created_at->format('M d, Y h:i A') }}"
            >
                {{ $message->message }}
            </div>
            {{-- hidden timestamp (revealed when clicked) --}}
            <div id="timestamp-{{ $message->id }}" 
                class="hidden text-xs text-gray-400 mt-1 ml-2">
                {{ $message->created_at->format('M d, Y h:i A') }}
            </div>
        </div>
    @endforeach
</div>
<button id="newMessagesBtn"
        class="hidden absolute bottom-20 right-40 z-40 bg-orange-500 hover:bg-orange-600 text-white px-3 py-2 rounded-full shadow-lg">
    New messages ↓
</button>


        <!-- Input -->
        <form wire:submit.prevent="submit" class="p-3 border-t border-gray-700 bg-gray-800 flex items-center gap-2">
            <input 
                id="chatInput"
                wire:model.live="newMessage"
                type="text"
                class="flex-1 bg-gray-900 border border-gray-700 rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 text-white placeholder-gray-400"
                placeholder="Type your message..." />
            <button 
                type="submit"
                class="text-orange-500 border border-orange-500 rounded-full 
                    text-sm px-5 py-2
                    hover:bg-orange-500 hover:text-white 
                    focus:outline-none focus:ring-2 focus:ring-orange-300
                    transition-colors duration-200">
                Send
            </button>
        </form>
    </div>
</div>
<script src = "{{ asset('assets/js/chat.js') }}"></script>
