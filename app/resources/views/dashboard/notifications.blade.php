@extends('layouts.master')

@section('content')
<div class="max-w-3xl mx-auto p-4 mt-5">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">Notifications</h1>

        @if($notifications->where('is_seen', false)->count() > 0)
            <form action="{{ route('notifications.markAllSeen') }}" method="POST">
                @csrf
                <button type="submit"
                    class="bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold 
                           px-4 py-2 rounded-lg transition">
                    Mark All as Seen
                </button>
            </form>
        @endif
    </div>

    @forelse($notifications as $notification)
        <form action="{{ route('notifications.seen', $notification->id) }}" method="POST">
            @csrf
            <button type="submit" 
                    class="w-full text-left block p-4 mb-3 rounded-lg border transition
                           {{ $notification->is_seen 
                                ? 'bg-gray-800 border-gray-700 text-gray-300 hover:bg-gray-700' 
                                : 'bg-gray-700 border-orange-400 text-white hover:bg-gray-600' }}">
                <div class="flex justify-between items-center">
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
                    <span class="text-xs text-gray-500">
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
