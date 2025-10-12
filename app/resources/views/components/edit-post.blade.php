@extends('layouts.master')

@section('content')
<div class="p-6 space-y-6">
    <!-- Success message -->
    @if(session('success'))
        <div class="bg-green-500 text-white p-2 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Edit Post -->
    <div class="p-4 border border-gray-700 rounded-lg bg-gray-800">
        <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Body -->
            <textarea 
                name="body"
                class="w-full bg-transparent border border-gray-600 rounded-lg p-2 text-gray-200 focus:ring-2 focus:ring-orange-500 focus:outline-none resize-none"
                rows="3"
                required
            >{{ $post->body }}</textarea>

            <!-- Existing Images -->
            @if($post->images->count())
                <div class="flex flex-wrap gap-2">
                    @foreach($post->images as $image)
                        <div class="relative">
                            <img src="{{ $image->image_path }}" 
                                 class="w-24 h-24 object-cover rounded-lg border border-gray-700">
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Upload new images -->
            <div class="form-group">
                <label for="imageInput" 
                class= "text-orange-400 border border-orange-400 rounded-full 
                        px-4 py-1 lg:px-4 lg:py-1 sm:px-12 sm:py-5 
                        text-sm sm:text-3xl lg:text-base
                        hover:bg-orange-500 hover:text-white 
                        focus:outline-none focus:ring-2 focus:ring-orange-300
                        transition-colors duration-200">
                    Upload Images
                </label>

                <input 
                    type="file" 
                    name="images[]" 
                    id="imageInput" 
                    multiple 
                    accept="image/*" 
                    class="hidden"
                >
            </div>

            <!-- Preview -->
            <div id="editPreviewContainer" class="flex flex-wrap gap-2 mt-2"></div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-2">
                <button type="submit"             
                class= "text-orange-400 border border-orange-400 rounded-full 
                        px-4 py-1 lg:px-4 lg:py-1 sm:px-12 sm:py-5 
                        text-sm sm:text-3xl lg:text-base
                        hover:bg-orange-500 hover:text-white 
                        focus:outline-none focus:ring-2 focus:ring-orange-300
                        transition-colors duration-200">
                        Save Changes
                </button>
                <a href="{{ route('dashboard.home') }}"                    
                class= "text-gray-300 border border-gray-300 rounded-full cursor-pointer 
                        px-4 py-1 lg:px-4 lg:py-1 sm:px-12 sm:py-5 
                        sm:text-3xl lg:text-base
                        hover:bg-gray-600 hover:text-white
                        focus:outline-none focus:ring-2 focus:ring-gray-300
                        transition-colors duration-200">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<!-- JS for preview -->
<script src="{{ asset('assets/js/edit_image.js') }}?v={{ filemtime(public_path('assets/js/edit_image.js')) }}"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const imageInput = document.getElementById("imageInput");
    const previewContainer = document.getElementById("editPreviewContainer");

    imageInput.addEventListener("change", (event) => {
        previewContainer.innerHTML = ""; // clear previous previews

        const files = event.target.files;
        if (files.length > 0) {
            Array.from(files).forEach((file, index) => {
                const reader = new FileReader();

                reader.onload = (e) => {
                    const wrapper = document.createElement("div");
                    wrapper.classList.add("relative", "w-24", "h-24");

                    // Image
                    const img = document.createElement("img");
                    img.src = e.target.result;
                    img.classList.add("w-24", "h-24", "object-cover", "rounded-lg", "border", "border-gray-700");

                    // Remove button
                    const removeBtn = document.createElement("button");
                    removeBtn.innerHTML = "✕";
                    removeBtn.type = "button";
                    removeBtn.classList.add(
                        "absolute", "top-0", "right-0", "bg-black", "text-white",
                        "rounded-full", "w-6", "h-6", "flex", "items-center", "justify-center", "text-xs"
                    );

                    removeBtn.addEventListener("click", () => {
                        wrapper.remove();

                        // Update input.files (filtering out the removed file)
                        const dataTransfer = new DataTransfer();
                        Array.from(files).forEach((f, i) => {
                            if (i !== index) {
                                dataTransfer.items.add(f);
                            }
                        });
                        imageInput.files = dataTransfer.files;
                    });

                    wrapper.appendChild(img);
                    wrapper.appendChild(removeBtn);
                    previewContainer.appendChild(wrapper);
                };

                reader.readAsDataURL(file);
            });
        }
    });
});


</script>
@endsection
