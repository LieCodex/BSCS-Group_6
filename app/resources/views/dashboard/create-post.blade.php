@extends('dashboard.layout')

@section('content')
<div class="p-6 space-y-6">
    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-500 text-white p-2 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Create Post -->
    <div class="p-4 border border-gray-700 rounded-lg bg-gray-800">
        <form method="POST" action="{{ route('posts.create') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Post Body -->
            <textarea 
                name="body"
                placeholder="What's happening?"
                class="w-full bg-transparent border border-gray-600 rounded-lg p-2 text-gray-200 focus:ring-2 focus:ring-orange-500 focus:outline-none resize-none"
                rows="3"
                required
            ></textarea>

            <!-- Image Upload -->
            <div>
                <label for="imageInput" class="cursor-pointer bg-gray-700 text-gray-200 px-4 py-2 rounded-lg inline-flex items-center hover:bg-gray-600">
                📷 Upload Images
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
            <div id="previewContainer" class="flex flex-wrap gap-2 mt-2"></div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-2">
                <button type="submit" class="bg-orange-500 text-white px-4 py-1 rounded-full hover:bg-orange-600">
                    Squeal
                </button>
                <a href="{{ route('dashboard.home') }}" class="bg-gray-600 text-white px-4 py-1 rounded-full hover:bg-gray-500">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<!-- JS for preview -->
<script>
document.getElementById('imageInput')?.addEventListener('change', function(e) {
    let previewContainer = document.getElementById('previewContainer');
    previewContainer.innerHTML = ''; // reset
    Array.from(e.target.files).forEach(file => {
        let reader = new FileReader();
        reader.onload = e => {
            let img = document.createElement('img');
            img.src = e.target.result;
            img.className = "w-24 h-24 object-cover rounded-lg";
            previewContainer.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endsection
