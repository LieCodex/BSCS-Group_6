@extends('layouts.master')

@section('content')
<div class="max-w-3xl mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6">Notifications</h1>

    @forelse($notifications as $notification)
        <form action="{{ route('notifications.seen', $notification->id) }}" method="POST">
            @csrf
            <button type="submit" 
                    class="w-full text-left block p-4 mb-2 rounded-lg border transition
                           {{ $notification->is_seen ? 'bg-white border-gray-200 hover:bg-gray-100' 
                                                     : 'bg-blue-50 border-blue-300 font-semibold hover:bg-blue-100' }}">
                <div class="flex justify-between items-center">
                    <div class="text-gray-800">
                        @if($notification->type === 'like_post')
                            <span class="font-medium">{{ $notification->actor?->name ?? 'Someone' }}</span> liked your post.
                        @elseif($notification->type === 'like_comment')
                            <span class="font-medium">{{ $notification->actor?->name ?? 'Someone' }}</span> liked your comment.
                        @elseif($notification->type === 'comment')
                            <span class="font-medium">{{ $notification->actor?->name ?? 'Someone' }}</span> commented: 
                            <span class="italic">{{ Str::limit($notification->preview_text, 50) }}</span>
                        @elseif($notification->type === 'reply')
                            <span class="font-medium">{{ $notification->actor?->name ?? 'Someone' }}</span> replied: 
                            <span class="italic">{{ Str::limit($notification->preview_text, 50) }}</span>
                        @elseif($notification->type === 'milestone')
                            {{ $notification->preview_text }}
                        @endif
                    </div>
                    <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                </div>
            </button>
        </form>
    @empty
        <p class="text-gray-500 text-center">No notifications yet.</p>
    @endforelse
</div>
@endsection
