@extends('layouts.master')

@section('content')
<div class="p-6 space-y-6">

    <!-- Success message -->
    @if(session('success'))
        <div id="successmsg" class="bg-orange-400 text-white p-2 rounded">
            {{ session('success') }}
        </div>
    @endif
    
    <!-- Show post form only for logged-in users -->
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


            <!-- Preview area -->
            <div id="imagePreview" class="flex flex-wrap gap-2 mt-2 hidden"></div>

            <div class="flex items-center gap-2 mt-2">
            <button type="submit" class="bg-orange-400 text-white px-4 py-1 lg:px-4 lg:py-1 sm:px-12 sm:py-5 rounded-full text-sm sm:text-3xl lg:text-base">
                Squeal
            </button>

                <label for="imageInput" class="bg-gray-600 text-white px-4 py-1 lg:px-4 lg:py-1 sm:px-12 sm:py-5 rounded-full cursor-pointer sm:text-3xl lg:text-base">
                    Images
                </label>
                <input type="file" id="imageInput" name="images[]" multiple accept="image/*" class="hidden">
            </div>
        </form>
    </div>
    @endauth

    <!-- Guest message -->
    @guest
        <div class="p-4 border border-gray-700 rounded-lg bg-gray-800 text-center text-gray-400">
            <p>Welcome to <span class="text-orange-400 font-bold">Squeal</span> !</p>
            <p>Please <a href="{{ route('login') }}" class="text-orange-400">login</a> or 
               <a href="{{ route('register.form') }}" class="text-orange-400">register</a> 
               to squeal with others.</p>
        </div>
    @endguest

    <!-- Posts Feed -->
    @if(isset($posts) && $posts->count())
        @foreach ($posts as $post)
            <div class="p-6 sm:p-6 lg:p-4 rounded-lg bg-gray-800 relative">


                <!-- User info -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
<img 
    src="{{ optional($post->user)->avatar ?: asset('assets/img/default-avatar.svg') }}"
    alt="{{ optional(auth()->user())->name ?? 'Guest' }}"
    class="w-14 h-14 sm:w-20 sm:h-20 lg:w-10 lg:h-10 rounded-full object-cover">


                    <h2 class="font-bold text-orange-400">
                        <a href="{{ route('user.profile', optional($post->user)->id) }}">
                            {{ optional($post->user)->name ?? 'Unknown User' }}
                        </a>
                    </h2>
                    </div>

                    <div class="relative">
                        @auth
                            @if(auth()->id() === $post->user_id)
                                <button onclick="toggleMenu({{ $post->id }})" class="text-gray-400 hover:text-white">⋮</button>
                                <div id="menu-{{ $post->id }}" class="hidden absolute right-0 mt-2 w-32 bg-gray-900 border border-gray-700 rounded-lg shadow-lg z-10">
                                    <a href="{{ route('posts.edit.form', $post->id) }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700">Edit</a>
                                    <form action="{{ route('posts.delete', $post->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-gray-700">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- Post body -->
                <p class="text-gray-300 sm:mt-10 sm:mb-10 lg:mt-1 lg:mb-1 text-lg sm:text-4xl lg:text-base">{{ $post->body }}</p>

                <!-- Post images -->
                    @if($post->images->count())
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach($post->images as $image)
                                <img 
                                src="{{ $image->image_path }}" 
                                class="post-image w-auto h-auto max-w-[160px] max-h-[160px] sm:max-w-[400px] sm:max-h-[400px] lg:max-w-[200px] lg:max-h-[200px] object-contain rounded-lg border border-gray-700 cursor-pointer"
                                onclick="openModal('{{ $image->image_path }}')"
                            />

                            @endforeach
                        </div>
                    @endif
    
                    <!-- Single reusable modal -->
                    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-70 hidden items-center justify-center z-50">
                        <div class="relative max-w-xl max-h-[90vh]">
                            <button id="closeModal" class="absolute top-2 right-2 text-white text-3xl">&times;</button>
                            <img id="modalImage" src="" alt="Full Image"
                                class="rounded-lg object-contain w-full h-full transform scale-100 transition-transform duration-200 cursor-grab"/>

                            
                            <!-- Zoom Controls -->
                            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2">
                                <button id="zoomIn" class="bg-gray-800 text-white px-4 py-1 rounded">+</button>
                                <button id="zoomOut" class="bg-gray-800 text-white px-4 py-1 rounded">−</button>
                            </div>
                        </div>
                    </div>
                                    <!-- Timestamp -->
                <p class="text-xs sm:text-3xl lg:text-xs text-gray-500 mt-2">Posted {{ $post->created_at->diffForHumans() }}</p>

                <!-- Buttons wrapper -->
                <div class="flex items-center gap-3 mt-3">

                    <!-- Likes button -->
                    @auth
                        @if($post->isLikedBy(auth()->user()))
                            <!-- Unlike -->
                            <form action="{{ route('posts.unlike', $post->id) }}" method="POST" class="like-form" data-post-id="{{ $post->id }}">
                                @csrf
                                @method('DELETE')
                                <button 
                                    type="submit" 
                                    class="group inline-flex items-center text-white px-4 py-2 rounded-full bg-gray-800 hover:border-orange-400 text-sm sm:text-base lg:text-sm">

                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                        id="like-icon-{{ $post->id }}"
                                        class="w-6 h-6 mr-1 sm:w-15 sm:h-15 lg:w-6 lg:h-6 text-orange-400" 
                                        fill="{{ $post->isLikedBy(auth()->user()) ? 'currentColor' : 'none' }}"
                                        stroke="{{ $post->isLikedBy(auth()->user()) ? 'orange' : 'white' }}"
                                        stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" 
                                            d="M4.318 6.318a4.5 4.5 0 016.364 0L12 
                                            7.636l1.318-1.318a4.5 4.5 0 116.364 
                                            6.364L12 20.364l-7.682-7.682a4.5 
                                            4.5 0 010-6.364z"/>
                                    </svg>
                                       <span id="like-count-{{ $post->id }}" class="{{ $post->isLikedBy(auth()->user()) ? 'text-orange-400' : 'text-white' }} sm:text-3xl lg:text-sm">
                                        {{ $post->likes->count() }}
                                        </span>
                                </button>
                            </form>
                        @else
                            <!-- Like -->
                            <form action="{{ route('posts.like', $post->id) }}" method="POST" class="like-form" data-post-id="{{ $post->id }}">
                                @csrf
                                <button 
                                type="submit" 
                                class="group inline-flex items-center text-white px-4 py-2 rounded-full bg-gray-800 hover:border-orange-400 text-sm sm:text-base lg:text-sm">

                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                    id="like-icon-{{ $post->id }}"
                                        class="w-6 h-6 mr-1 mr-1 sm:w-15 sm:h-15 lg:w-6 lg:h-6 transition text-white group-hover:text-orange-400" 
                                        fill="{{ $post->isLikedBy(auth()->user()) ? 'currentColor' : 'none' }}"
                                        stroke="{{ $post->isLikedBy(auth()->user()) ? 'orange' : 'white' }}"
                                        stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" 
                                            d="M4.318 6.318a4.5 4.5 0 016.364 0L12 
                                            7.636l1.318-1.318a4.5 4.5 0 116.364 
                                            6.364L12 20.364l-7.682-7.682a4.5 
                                            4.5 0 010-6.364z"/>
                                    </svg>
                                        <span id="like-count-{{ $post->id }}" class="{{ $post->isLikedBy(auth()->user()) ? 'text-orange-400' : 'text-white' }} sm:text-3xl lg:text-sm">
                                        {{ $post->likes->count() }}
                                        </span>
                                </button>
                            </form>
                        @endif
                    @endauth

                    <!-- Comments button -->
                    @auth
                        <form action="{{ route('posts.show', $post->id) }}" method="GET">
                           <button 
                            type="submit" 
                            class="group inline-flex items-center text-white px-4 py-2 rounded-full bg-gray-800 hover:border-orange-400 text-sm sm:text-base lg:text-sm">

                                <svg xmlns="http://www.w3.org/2000/svg" 
                                    class="w-6 h-6 mr-1 mr-1 sm:w-15 sm:h-15 lg:w-6 lg:h-6 transition text-white group-hover:text-orange-400" 
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" 
                                        d="M7 8h10M7 12h6m-6 4h4m10-2.586V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h9l4 4v-5.586a2 2 0 0 0 .586-1.414z"/>
                                </svg>
                                <span class="transition text-white group-hover:text-orange-400 text-base sm:text-3xl lg:text-sm">
                                    {{ $post->comments->count() }}
                                </span>
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        @endforeach
    @else
        @auth
            <div class="p-4 border border-gray-700 rounded-lg bg-gray-800 text-center text-gray-400">
                No posts yet.
            </div>
        @endauth
    @endif
</div>
<script src="{{ asset('assets/js/home.js') }}"></script>

<script>
const modal = document.getElementById('imageModal');
const modalImg = document.getElementById('modalImage');
const closeModalBtn = document.getElementById('closeModal');
const zoomInBtn = document.getElementById('zoomIn');
const zoomOutBtn = document.getElementById('zoomOut');

let currentScale = 1;
let currentX = 0;
let currentY = 0;
const SCALE_STEP = 0.2;
const MAX_SCALE = 3;
const MIN_SCALE = 0.5;

// Drag variables
let isDragging = false;
let startX = 0;
let startY = 0;

// Open modal
function openModal(src) {
    modalImg.src = src;
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    // Reset zoom and position
    currentScale = 1;
    currentX = 0;
    currentY = 0;
    updateTransform();
}

// Close modal
function closeModal() {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    currentScale = 1;
    currentX = 0;
    currentY = 0;
    updateTransform();
}

// Update transform for scale + translation
function updateTransform() {
    modalImg.style.transform = `scale(${currentScale}) translate(${currentX}px, ${currentY}px)`;
}

// Zoom handlers
function zoomIn() {
    currentScale = Math.min(currentScale + SCALE_STEP, MAX_SCALE);
    updateTransform();
}

function zoomOut() {
    currentScale = Math.max(currentScale - SCALE_STEP, MIN_SCALE);
    updateTransform();
}

// Event listeners
closeModalBtn.addEventListener('click', closeModal);
zoomInBtn.addEventListener('click', zoomIn);
zoomOutBtn.addEventListener('click', zoomOut);

// Close modal if clicking outside image
modal.addEventListener('click', (e) => {
    if (e.target === modal) closeModal();
});

// Mouse wheel zoom
modalImg.addEventListener('wheel', (e) => {
    e.preventDefault();
    if (e.deltaY < 0) zoomIn();
    else zoomOut();
});

// Drag-to-pan
modalImg.addEventListener('mousedown', (e) => {
    if (currentScale <= 1) return; // no drag if not zoomed
    isDragging = true;
    startX = e.clientX - currentX;
    startY = e.clientY - currentY;
    modalImg.style.cursor = 'grabbing';
});

document.addEventListener('mousemove', (e) => {
    if (!isDragging) return;
    currentX = e.clientX - startX;
    currentY = e.clientY - startY;
    updateTransform();
});

document.addEventListener('mouseup', () => {
    if (!isDragging) return;
    isDragging = false;
    modalImg.style.cursor = 'grab';
});
</script>
@endsection
