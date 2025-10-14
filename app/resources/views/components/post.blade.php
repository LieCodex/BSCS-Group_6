@extends('layouts.master')

@section('content')
<div class="p-4 sm:p-6 space-y-6 sm:space-y-8">

    {{-- Reuse post-card component --}}
    @include('components.post-card', ['post' => $post])

    <!-- Comments Section -->
    <div class="p-4 sm:p-6 border border-gray-700 rounded-lg bg-gray-800 mt-4 sm:mt-6">
        <h3 class="font-bold mb-4 sm:mb-3 text-orange-400 text-lg lg:text-base sm:text-4xl">
            Comments ({{ $post->comments->count() }})
        </h3>

        <!-- Comment Form -->
        @auth
            <div class="mt-4 sm:mt-6">
                <form action="{{ route('comments.create', $post->id) }}" method="POST">
                    @csrf
                    <div class="relative">
                        <textarea 
                            name="content" 
                            class="w-full h-20 max-h-48 
                                   p-3 sm:p-2 
                                   rounded-lg bg-gray-700 text-white 
                                   border border-gray-600 
                                   focus:ring-0 
                                   pr-36 sm:pr-32 
                                   resize-none overflow-auto 
                                   text-base lg:text-base sm:text-3xl"
                            placeholder="Write a comment..." 
                            rows="2" 
                            required
                            onfocus="this.nextElementSibling.classList.remove('hidden');"
                            onblur="if(!this.value) this.nextElementSibling.classList.add('hidden');"
                            oninput="autoGrow(this)"
                            style="box-sizing: border-box; padding-bottom: 2.8rem;" 
                        ></textarea>
                        <div class="absolute right-2 bottom-2 flex gap-3 sm:gap-2 hidden z-10">
                            <button type="submit" class="bg-orange-500 text-white px-5 py-2 sm:px-4 sm:py-1 rounded-full text-base lg:text-sm sm:text-3xl">Comment</button>
                            <button type="button" class="bg-gray-500 text-white px-5 py-2 sm:px-4 sm:py-1 rounded-full text-base lg:text-sm sm:text-3xl" onclick="this.form.reset(); this.parentElement.classList.add('hidden');">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        @else
            <p class="text-gray-400 text-base sm:text-sm">
                Please 
                <a href="{{ route('login.form') }}" class="text-orange-400 underline">login</a> 
                to comment.
            </p>
        @endauth

        <!-- Comments List -->
        @if($post->comments->count())
            @foreach($post->comments->whereNull('parent_comment_id') as $comment)
                <x-comment :comment="$comment" />
            @endforeach
        @else
            <p class="mt-4 text-gray-400 text-center sm:text-left">No comments yet.</p>
        @endif
    </div>
</div>

<script>
function autoGrow(element) {
    element.style.height = "4rem"; 
    if (element.scrollHeight > element.clientHeight) {
        element.style.height = (element.scrollHeight) + "px";
    }
}
</script>

<script src="{{ asset('assets/js/home.js') }}?v={{ filemtime(public_path('assets/js/home.js')) }}"></script>
@endsection
