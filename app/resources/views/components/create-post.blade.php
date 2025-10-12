<!-- Alpine (only include once in your app) -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

@auth
<div x-data="{ open: false }" class="relative">
    <!-- Compact trigger -->
    <div
        class="bg-gray-900 border border-gray-800 rounded-2xl p-4 flex items-center gap-4 cursor-pointer hover:bg-gray-800 transition"
        @click="open = true"
    >
        <img
            src="{{ auth()->user()->profile_picture ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
            alt="User Avatar"
            class="w-10 h-10 rounded-full object-cover"
        >
        <span class="text-gray-400">What's happening?</span>
    </div>

    <!-- Modal Overlay -->
    <div
        x-show="open"
        x-transition.opacity
        @click.self="open = false"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 overflow-y-auto"
    >
        <!-- Modal Card -->
        <div
            x-show="open"
            x-transition
            class="bg-gray-900 border border-gray-800 rounded-2xl w-full max-w-xl mx-4 my-8 relative shadow-2xl flex flex-col modal-card"
        >
            <!-- Top Bar -->
            <div class="flex items-center justify-between px-6 pt-5 pb-2 border-b border-gray-800">
                <button @click="open = false" class="text-gray-400 hover:text-white text-2xl font-semibold">✕</button>
                <button type="button" id="openDraftsBtn" class="text-orange-400 hover:text-orange-500 text-sm font-medium transition">Drafts</button>
            </div>

            <!-- User + Form -->
            <div class="flex gap-4 px-6 py-4 flex-1 overflow-auto">
                <img
                    src="{{ auth()->user()->profile_picture ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                    alt="User Avatar"
                    class="w-12 h-12 rounded-full object-cover mt-1"
                >

                <form method="POST" action="/create-post" enctype="multipart/form-data" class="flex-1 flex flex-col justify-between" id="createPostForm">
                    @csrf

                    <textarea
                        id="postBody"
                        name="body"
                        placeholder="What's happening?"
                        rows="3"
                        class="w-full bg-transparent text-gray-100 text-lg resize-none border-none focus:ring-0 placeholder-gray-500 mt-2 overflow-hidden"
                    ></textarea>

                    <!-- Image preview (starts hidden) -->
                    <div id="imagePreview" class="flex flex-wrap gap-2 mt-3 hidden"></div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between mt-4 border-t border-gray-800 pt-3">
                        <div class="flex items-center gap-3">
                            <label for="imageInput" class="cursor-pointer text-gray-400 hover:text-orange-400 transition-colors flex items-center gap-2">
                                <!-- Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v-9a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v9a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 16.5z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l3.75-3.75L10.5 12l3.75-3.75L21 12" />
                                </svg>
                                <span class="hidden sm:inline">Images</span>
                            </label>

                            <input type="file" id="imageInput" name="images[]" multiple accept="image/*" class="hidden">
                        </div>

                        <button type="submit" class="bg-orange-500 text-white font-semibold rounded-full px-5 py-2 hover:bg-orange-600 focus:ring-2 focus:ring-orange-300 transition">Squeal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endauth
