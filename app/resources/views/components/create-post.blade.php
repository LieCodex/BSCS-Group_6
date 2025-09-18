@extends('layouts.master')

@section('content')
<div class="p-6 space-y-6">

    <!-- Trigger (Textbox) -->
    <textarea 
        id="openModal"
        placeholder="What's happening?"
        class="w-full bg-transparent border border-gray-600 rounded-lg p-2 text-gray-200 focus:ring-2 focus:ring-orange-500 focus:outline-none resize-none cursor-pointer"
        rows="3"
        readonly
        tabindex="0"
        aria-haspopup="dialog"
        aria-controls="postModal"
    ></textarea>

</div>

<!-- Modal -->
<div id="postModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50" role="dialog" aria-modal="true" aria-labelledby="postModalLabel">
    <div class="bg-gray-800 rounded-lg p-6 w-full max-w-lg shadow-lg relative">

        <!-- Close Button -->
        <button id="closeModal" class="absolute top-2 right-2 text-gray-400 hover:text-white text-xl" aria-label="Close">&times;</button>

        <!-- Create Post -->
        <form method="POST" action="{{ route('posts.create') }}" enctype="multipart/form-data" class="space-y-4" id="postForm">
            @csrf

            <h2 id="postModalLabel" class="text-lg font-semibold text-gray-100">Create post</h2>

            <!-- Post Body -->
            <textarea 
                name="body"
                placeholder="What's happening?"
                class="w-full bg-transparent border border-gray-600 rounded-lg p-2 text-gray-200 focus:ring-2 focus:ring-orange-500 focus:outline-none resize-none"
                rows="4"
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
                <button type="button" id="cancelModal" class="bg-gray-600 text-white px-4 py-1 rounded-full hover:bg-gray-500">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- JS -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('postModal');
    const openTrigger = document.getElementById('openModal');
    const closeBtn = document.getElementById('closeModal');
    const cancelBtn = document.getElementById('cancelModal');
    const imageInput = document.getElementById('imageInput');
    const previewContainer = document.getElementById('previewContainer');

    if (!openTrigger) {
        console.warn('Modal open trigger (#openModal) not found.');
        return;
    }

    const openModal = () => {
        modal?.classList.remove('hidden');
        document.body.classList.add('overflow-hidden'); // stop background scroll
        // focus textarea inside modal
        modal?.querySelector('textarea[name="body"]')?.focus();
    };

    const closeModal = () => {
        modal?.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        openTrigger?.focus(); // return focus to trigger
    };

    // click to open
    openTrigger.addEventListener('click', (e) => {
        e.preventDefault();
        openModal();
    });

    // keyboard activation (Enter / Space)
    openTrigger.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            openModal();
        }
    });

    // close handlers (safely with optional chaining)
    closeBtn?.addEventListener('click', closeModal);
    cancelBtn?.addEventListener('click', closeModal);

    // click outside content to close
    modal?.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });

    // close on Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });

    // Image preview (safe)
    imageInput?.addEventListener('change', function(e) {
        if (!previewContainer) return;
        previewContainer.innerHTML = '';
        Array.from(e.target.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = ev => {
                const img = document.createElement('img');
                img.src = ev.target.result;
                img.className = "w-24 h-24 object-cover rounded-lg";
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    });
});
</script>
@endsection
