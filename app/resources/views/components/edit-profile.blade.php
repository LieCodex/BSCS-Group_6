@props(['user'])

<div x-data="{ open: false }" class="relative" x-on:open-edit-profile.window="open = true">
    @if(auth()->check() && auth()->id() === $user->id)
        <!-- Edit Profile Button -->
        <div class="absolute top-2 right-2">
            <button 
                @click="open = true" 
                class="px-4 py-2 bg-gray-800 text-white rounded-full text-sm hover:bg-gray-700 transition">
                Edit Profile
            </button>
        </div>

        <!-- Modal -->
        <div 
            x-show="open" 
            x-cloak
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div class="bg-gray-800 text-white rounded-lg shadow-lg w-full max-w-md p-6">
                <h2 class="text-xl font-medium mb-4 text-center text-orange-400">Edit Profile</h2>

                <!-- Form -->
                <form 
                    action="{{ route('profile.update') }}" 
                    method="POST" 
                    enctype="multipart/form-data" 
                    class="space-y-4"
                >
                    @csrf

                   <!-- Avatar -->
                    <div>
                        <label for="avatar" class="block text-sm text-gray-300">Avatar</label>
                        <div class="flex items-center space-x-4 mt-2" x-data="{ preview: '{{ $user->avatar ?? asset('images/default-avatar.png') }}' }">
                            
                            <!-- Preview Image -->
                            <img 
                                :src="preview" 
                                alt="Current Avatar" 
                                class="w-16 h-16 rounded-full object-cover border border-gray-700">

                            <!-- File Input -->
                            <input 
                                type="file" 
                                name="avatar" 
                                id="avatar" 
                                accept="image/*"
                                class="text-sm text-gray-400"
                                x-on:change="
                                    const file = $event.target.files[0];
                                    if (file) preview = URL.createObjectURL(file);
                                "
                            >
                        </div>

                        <!-- Validation Error -->
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
                            class="w-full rounded-lg p-2 bg-gray-700 text-white focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    </div>

                    <!-- Bio -->
                    <div>
                        <label for="bio" class="block text-sm text-gray-300">Bio</label>
                        <textarea 
                            name="bio" 
                            id="bio" 
                            rows="3" 
                            class="w-full rounded-lg p-2 bg-gray-700 text-white focus:ring-2 focus:ring-orange-500 focus:outline-none resize-none"
                        >{{ old('bio', $user->bio) }}</textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-2">
                        <button 
                            type="button" 
                            @click="open = false"
                            class="px-4 py-2 bg-gray-600 rounded-lg hover:bg-gray-500 transition">
                            Cancel
                        </button>

                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-blue-600 rounded-lg hover:bg-blue-500 transition">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
