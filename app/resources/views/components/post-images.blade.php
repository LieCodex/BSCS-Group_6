<div id="post-carousel-{{ $post->id }}" class="relative w-full mt-4 rounded-lg overflow-hidden bg-black" data-carousel="static">
    <!-- Carousel wrapper -->
    <div class="relative h-[400px] md:h-[400px] overflow-hidden rounded-lg">
        @foreach($post->images as $index => $image)
            <div class="{{ $index === 0 ? '' : 'hidden' }} duration-700 ease-in-out" data-carousel-item>
                <img 
                    src="{{ $image->image_path }}" 
                    class="absolute block w-full h-full object-cover top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 cursor-pointer"
                    onclick="openModal('{{ $image->image_path }}')"
                />
            </div>
        @endforeach
    </div>

    @if($post->images->count() > 1)
        <!-- Slider indicators -->
        <div class="absolute z-30 flex -translate-x-1/2 bottom-3 left-1/2 space-x-3">
            @foreach($post->images as $index => $image)
                <button 
                    type="button" 
                    class="w-3 h-3 rounded-full bg-white/50 aria-[current=true]:bg-white" 
                    aria-label="Slide {{ $index+1 }}" 
                    data-carousel-slide-to="{{ $index }}">
                </button>
            @endforeach
        </div>

        <!-- Slider controls -->
        <button type="button" class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50">
                <svg class="w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                </svg>
                <span class="sr-only">Previous</span>
            </span>
        </button>
        <button type="button" class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50">
                <svg class="w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <span class="sr-only">Next</span>
            </span>
        </button>
    @endif
</div>
