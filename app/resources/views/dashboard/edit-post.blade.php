@extends('dashboard.layout')

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
                <label for="imageInput" class="custom-file-upload">
                    <i class="fas fa-cloud-upload-alt"></i> Upload Images
                </label>
                <input 
                    type="file" 
                    name="images[]" 
                    id="imageInput" 
                    multiple 
                    accept="image/*"
                >
            </div>

            <!-- Preview -->
            <div id="editPreviewContainer" class="flex flex-wrap gap-2 mt-2"></div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-2">
                <button type="submit" class="bg-orange-500 text-white px-4 py-1 rounded-full hover:bg-orange-600">
                    Save Changes
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
document.getElementById('editImageInput')?.addEventListener('change', function(e) {
    let previewContainer = document.getElementById('editPreviewContainer');
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
