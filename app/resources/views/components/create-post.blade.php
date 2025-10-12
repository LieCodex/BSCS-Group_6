<!-- Alpine (only include once in your app) -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

@auth
<div x-data="{ open: false }" class="relative">
    <!-- Compact trigger -->
    <div
        class="bg-gray-900 border border-gray-600 rounded-2xl lg:pt-6 lg:pb-6 lg:pl-4 sm:pt-10 sm:pb-10 sm:pl-7 flex items-center gap-4 cursor-pointer hover:bg-gray-800 transition"
        @click="open = true"
    >
        <img
            src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
            alt="User Avatar"
            class="lg:w-10 lg:h-10 sm:w-25 sm:h-25 rounded-full object-cover"
        >
        <span class="text-gray-400 lg:text-base sm:text-4xl">What's happening?</span>
    </div>

    <!-- Modal Overlay -->
    <div
        x-show="open"
        x-transition.opacity
        @click.self="open = false"
        x-cloak
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 overflow-y-auto"
    >
        <!-- Modal Card -->
        <div
            x-show="open"
            x-transition
            class="bg-gray-900 border border-gray-800 rounded-2xl w-full lg:max-w-xl lg:mx-3 lg:my-8 sm:max-w-3xl sm:mx-4 sm:my-8 relative shadow-2xl flex flex-col modal-card"
        >
            <!-- Top Bar -->
            <div class="flex items-center justify-between px-6 pt-5 pb-2 border-b border-gray-800">
                <button @click="open = false" class="text-gray-400 hover:text-white lg:text-2xl sm:text-4xl font-semibold">✕</button>
            </div>

            <!-- User + Form -->
            <div class="flex gap-4 lg:px-6 lg:py-4 sm:px-10 sm:py-10 flex-1 overflow-auto">
                <img
                    src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                    alt="User Avatar"
                    class="lg:w-12 lg:h-12 sm:w-22 sm:h-22 rounded-full object-cover mt-1"
                >

                <form method="POST" action="/create-post" enctype="multipart/form-data" class="flex-1 flex flex-col justify-between" id="createPostForm">
                    @csrf

                    <textarea
                        id="postBody"
                        name="body"
                        placeholder="What's happening?"
                        rows="3"
                        class="w-full bg-transparent text-gray-100 lg:text-lg sm:text-4xl resize-none border-none focus:ring-0 placeholder-gray-500 mt-2 overflow-hidden"
                    ></textarea>

                    <!-- Image preview (starts hidden) -->
                    <div id="imagePreview" class="flex flex-wrap gap-2 mt-3 hidden"></div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between mt-4 border-t border-gray-800 pt-3">
                        <div class="flex items-center gap-3">
                            <label for="imageInput" class="cursor-pointer text-gray-400 hover:text-orange-400 transition-colors flex items-center gap-2">
                                <!-- Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="lg:w-6 lg:h-6 sm:w-12 sm:h-12">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v-9a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v9a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 16.5z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l3.75-3.75L10.5 12l3.75-3.75L21 12" />
                                </svg>
                                <span class="hidden sm:inline sm:text-2xl lg:text-base">Images</span>
                            </label>

                            <input type="file" id="imageInput" name="images[]" multiple accept="image/*" class="hidden">
                        </div>

                        <button type="submit"                 
                        class="text-orange-400 border border-orange-400 rounded-full 
                        lg:text-sm sm:text-2xl px-5 py-2
                        hover:bg-orange-500 hover:text-white 
                        focus:outline-none focus:ring-2 focus:ring-orange-300
                        transition-colors duration-200">Squeal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endauth
