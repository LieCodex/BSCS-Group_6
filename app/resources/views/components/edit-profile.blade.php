@props(['user'])

<div x-data="{ open: false }" class="relative" x-on:open-edit-profile.window="open = true">
    @if(auth()->check() && auth()->id() === $user->id)
        <!-- Edit Profile Button -->
        <div class="absolute top-2 right-2">
            <button 
                @click="open = true" 
                class= "text-gray-300 border border-gray-300 rounded-full 
                        lg:px-3 lg:py-1 sm:px-6 sm:py-2 lg:text-sm sm:text-3xl
                        hover:bg-gray-600 hover:text-white
                        focus:outline-none focus:ring-2 focus:ring-gray-300
                        transition-colors duration-200">
                Edit Profile
            </button>
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
                class="bg-gray-900 border border-gray-800 rounded-2xl w-full max-w-md mx-4 my-8 relative shadow-2xl flex flex-col modal-card"
            >
                <!-- Top Bar -->
                <div class="flex items-center justify-between px-6 pt-5 pb-2 border-b border-gray-800">
                    <h2 class="text-xl font-medium text-orange-400">Edit Profile</h2>
                    <button @click="open = false" class="text-gray-400 hover:text-white text-2xl font-semibold">✕</button>
                </div>

                <!-- Form -->
                <div class="px-6 py-4">
                    <form 
                        action="{{ route('profile.update') }}" 
                        method="POST" 
                        enctype="multipart/form-data" 
                        class="space-y-4"
                    >
                        @csrf

                        <!-- Avatar -->
                        <div x-data="{ 
                            preview: '{{ $user->avatar ?? asset('images/default-avatar.png') }}', 
                            triggerFileInput() { this.$refs.fileInput.click(); }
                        }">
                            <label class="block text-sm text-gray-300 mb-2">Avatar</label>

                            <div class="flex items-center space-x-4">
                                <!-- Preview -->
                                <img 
                                    :src="preview" 
                                    alt="Current Avatar" 
                                    class="w-16 h-16 rounded-full object-cover border border-gray-700">

                                <!-- Hidden File Input -->
                                <input 
                                    type="file" 
                                    name="avatar" 
                                    id="avatar" 
                                    accept="image/*"
                                    class="hidden"
                                    x-ref="fileInput"
                                    x-on:change="
                                        const file = $event.target.files[0];
                                        if (file) preview = URL.createObjectURL(file);
                                    "
                                >

                                <!-- Upload Button -->
                                <button 
                                    type="button"
                                    class="text-gray-300 border border-gray-300 rounded-full 
                                    px-4 py-2 text-sm
                                    hover:bg-gray-600 hover:text-white
                                    focus:outline-none focus:ring-2 focus:ring-gray-300
                                    transition-colors duration-200"
                                    @click="triggerFileInput()"
                                >
                                    Change
                                </button>
                            </div>

                            @error('avatar')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>


                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm text-gray-300">Name</label>
                            <input 
                                type="text" 
                                name="name" 
                                id="name" 
                                value="{{ old('name', $user->name) }}" 
                                class="w-full rounded-lg p-2 bg-gray-800 text-white focus:ring-2 focus:ring-orange-500 focus:outline-none">
                        </div>

                        <!-- Bio -->
                        <div>
                            <label for="bio" class="block text-sm text-gray-300">Bio</label>
                            <textarea 
                                name="bio" 
                                id="bio" 
                                rows="3" 
                                class="w-full rounded-lg p-2 bg-gray-800 text-white focus:ring-2 focus:ring-orange-500 focus:outline-none resize-none"
                            >{{ old('bio', $user->bio) }}</textarea>
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end space-x-2 border-t border-gray-800 pt-3">
                            <button 
                                type="button" 
                                @click="open = false"
                                class="text-gray-300 border border-gray-300 rounded-full 
                                    px-4 py-2 text-sm
                                    hover:bg-gray-600 hover:text-white
                                    focus:outline-none focus:ring-2 focus:ring-gray-300
                                    transition-colors duration-200">
                                Cancel
                            </button>
                            <button 
                                type="submit" 
                                class="text-orange-400 border border-orange-400 rounded-full 
                                    px-4 py-2 text-sm 
                                    hover:bg-orange-500 hover:text-white 
                                    focus:outline-none focus:ring-2 focus:ring-orange-300
                                    transition-colors duration-200">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
