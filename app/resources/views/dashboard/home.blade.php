@extends('dashboard.layout')

@section('content')
    <div class="p-6 space-y-6">

        <!-- Success message -->
        @if(session('success'))
            <div class="bg-green-500 text-white p-2 rounded">
                {{ session('success') }}
            </div>
        @endif

    <!-- Post Form -->
    <div class="p-4 border border-gray-700 rounded-lg bg-gray-800">
        <form method="POST" action="/create-post" enctype="multipart/form-data">
            @csrf
            <textarea
                name="body"
                placeholder="What's happening?"
                class="w-full bg-transparent border-none focus:ring-0 resize-none"
            ></textarea>

            <!-- Preview area (hidden until images selected) -->
            <div id="imagePreview" class="flex flex-wrap gap-2 mt-2 hidden"></div>

            <div class="flex items-center gap-2 mt-2">
                <!-- Squeal button -->
                <button 
                    type="submit"
                    class="bg-orange-500 text-white px-4 py-1 rounded-full">

                    Squeal
                </button>

                <!-- Image upload button -->
<label for="imageInput" class="bg-gray-600 text-white px-4 py-1 rounded-full cursor-pointer">
    Images
</label>
<input type="file" id="imageInput" name="images[]" multiple accept="image/*" class="hidden">

            </div>
        </form>
    </div>
    <!-- Posts Feed -->
    @foreach ($posts as $post)
    <div class="p-4 border border-gray-700 rounded-lg bg-gray-800 relative">

        <!-- Header -->
        <div class="flex justify-between items-start">
            <h2 class="font-bold text-orange-400">{{ $post->user->name }}</h2>

            <!-- Hamburger -->
            <div class="relative">
                <button onclick="toggleMenu({{ $post->id }})" class="text-gray-400 hover:text-white">
                    ⋮
                </button>
                <!-- Dropdown -->
                <div id="menu-{{ $post->id }}" class="hidden absolute right-0 mt-2 w-32 bg-gray-900 border border-gray-700 rounded-lg shadow-lg z-10">
                    <!-- Edit -->
                    <a href="{{ route('posts.edit.form', $post->id) }}"
                    class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700">
                    Edit
                    </a>
                    <!-- Delete -->
                    <form action="{{ route('posts.delete', $post->id) }}" method="POST" onsubmit="return confirm('Delete this post?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-gray-700">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Body -->
        <p class="text-gray-300 mt-2">{{ $post->body }}</p>

        <!-- Images -->
    @if($post->images->count())
        <div class="flex flex-wrap gap-2 mt-2">
            @foreach($post->images as $image)
                <img src="{{ $image->image_path }}" 
                    class="w-24 h-24 object-cover rounded-lg border border-gray-700">
            @endforeach
        </div>
    @endif

    <!-- Timestamp -->
    <p class="text-xs text-gray-500 mt-2">Posted {{ $post->created_at->diffForHumans() }}</p>

    <!-- Comment button -->
    <button onclick="toggleComments({{ $post->id }})"
            class="mt-2 text-sm text-blue-400 hover:underline">
        View Comments
    </button>

</div>
@endforeach

    </div>
@endsection

<script>
function toggleMenu(postId) {
    const menu = document.getElementById(`menu-${postId}`);
    document.querySelectorAll('[id^="menu-"]').forEach(m => {
        if (m !== menu) m.classList.add('hidden');
    });
    menu.classList.toggle('hidden');
}

document.getElementById('imageInput')?.addEventListener('change', function(e) {
    let preview = document.getElementById('imagePreview');
    preview.innerHTML = ''; // clear previous previews

    if (e.target.files.length > 0) {
        preview.classList.remove('hidden'); // show preview container
    } else {
        preview.classList.add('hidden'); // hide if no images selected
    }

    Array.from(e.target.files).forEach(file => {
        let reader = new FileReader();
        reader.onload = ev => {
            let img = document.createElement('img');
            img.src = ev.target.result;
            img.className = "w-24 h-24 object-cover rounded-lg border border-gray-700";
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
});
</script>
