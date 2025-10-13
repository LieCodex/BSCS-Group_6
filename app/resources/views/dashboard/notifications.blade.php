@extends('layouts.master')

@section('content')
<div class="mx-auto p-4 sm:0 mt-5 pb-35 lg:pb-0">
    <div class="flex justify-between items-center mb-6">
        <h1 class="lg:text-2xl sm:text-4xl font-bold text-white">Notifications</h1>

        @if($notifications->where('is_seen', false)->count() > 0)
            <form action="{{ route('notifications.markAllSeen') }}" method="POST">
                @csrf
                <button type="submit"
                    class="bg-orange-500 hover:bg-orange-600 text-white lg:text-sm sm:text-3xl font-semibold 
                           lg:px-4 lg:py-2 sm:px-5 sm:py-5 rounded-lg transition">
                    Mark All as Seen
                </button>
            </form>
        @endif
    </div>

    @forelse($notifications as $notification)
        <form action="{{ route('notifications.seen', $notification->id) }}" method="POST">
            @csrf
            <button type="submit" 
                    class="w-full text-left block lg:p-4 sm:p-10 mb-3 rounded-lg border transition
                           {{ $notification->is_seen 
                                ? 'bg-gray-800 border-gray-700 text-gray-300 hover:bg-gray-700' 
                                : 'bg-gray-700 border-orange-400 text-white hover:bg-gray-600' }}">
                <div class="flex justify-between items-center lg:text-xl sm:text-3xl">
                    <div>
                        @if($notification->type === 'like_post')
                            <span class="font-medium text-orange-400"> 
                                {{ $notification->actor?->name ?? 'Someone' }}
                            </span> liked your post.
                        @elseif($notification->type === 'like_comment')
                            <span class="font-medium text-orange-400">
                                {{ $notification->actor?->name ?? 'Someone' }}
                            </span> liked your comment.
                        @elseif($notification->type === 'comment')
                            <span class="font-medium text-orange-400">
                                {{ $notification->actor?->name ?? 'Someone' }}
                            </span> commented: 
                            <span class="italic text-gray-400">
                                {{ Str::limit($notification->preview_text, 50) }}
                            </span>
                        @elseif($notification->type === 'reply')
                            <span class="font-medium text-orange-400">
                                {{ $notification->actor?->name ?? 'Someone' }}
                            </span> replied: 
                            <span class="italic text-gray-400">
                                {{ Str::limit($notification->preview_text, 50) }}
                            </span>
                        @elseif($notification->type === 'milestone')
                            <span class="text-orange-300">
                                {{ $notification->preview_text }}
                            </span>
                        @endif
                    </div>
                    <span class="lg:text-xs sm:text-2xl text-gray-500">
                        {{ $notification->created_at->diffForHumans() }}
                    </span>
                </div>
            </button>
        </form>
    @empty
        <p class="text-gray-500 text-center">No notifications yet.</p>
    @endforelse
</div>
@endsection
