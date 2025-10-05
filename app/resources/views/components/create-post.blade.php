@auth
<div class="p-4 rounded-lg bg-gray-800">
    <form method="POST" action="/create-post" enctype="multipart/form-data">
        @csrf
        <textarea
            id="postBody"
            name="body"
            placeholder="What's happening?"
            class="w-full min-h-[80px] bg-transparent border-none focus:ring-0 resize-none text-lg sm:text-4xl lg:text-base overflow-hidden"
        ></textarea>

        <!-- Preview area with Add button -->
        <div class="flex items-center gap-2 mt-2">
            <div id="imagePreview" class="flex flex-wrap gap-2"></div>
        </div>

        <div class="flex items-center gap-2 mt-2">
            <button 
                type="submit"
                class="text-orange-400 border border-orange-400 rounded-full 
                       px-4 py-1 lg:px-4 lg:py-1 sm:px-12 sm:py-5 
                       text-sm sm:text-3xl lg:text-base
                       hover:bg-orange-500 hover:text-white 
                       focus:outline-none focus:ring-2 focus:ring-orange-300
                       transition-colors duration-200">
                Squeal
            </button>
            <label for="imageInput" 
                class="text-gray-300 border border-gray-300 rounded-full cursor-pointer 
                       px-4 py-1 lg:px-4 lg:py-1 sm:px-12 sm:py-5 
                       sm:text-3xl lg:text-base
                       hover:bg-gray-600 hover:text-white
                       focus:outline-none focus:ring-2 focus:ring-gray-300
                       transition-colors duration-200">
                Images
            </label>
            <input type="file" id="imageInput" name="images[]" multiple accept="image/*" class="hidden">
        </div>
    </form>
</div>
@endauth
<script src="{{ asset('assets/js/image_preview.js') }}"></script>