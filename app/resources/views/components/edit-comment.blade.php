@extends('layouts.master')

@section('content')
<div class="max-w-xl mx-auto mt-10 bg-gray-800 p-6 rounded-lg shadow">
    <h2 class="text-xl font-medium text-orange-400 mb-4">Edit Comment</h2>
    <form action="{{ route('comments.update', $comment->id) }}" method="POST">
        @csrf
        @method('PUT')
        <textarea 
            name="content" 
            class="w-full h-24 p-2 rounded-lg bg-gray-700 text-white border border-gray-600 focus:ring-0 resize-none"
            required
        >{{ old('content', $comment->content) }}</textarea>
        <div class="flex gap-2 mt-4">
            <button type="submit" class="bg-orange-400 text-white px-4 py-1 rounded-full">Save</button>
            <a href="{{ url()->previous() }}" class="bg-gray-500 text-white px-4 py-1 rounded-full">Cancel</a>
        </div>
    </form>
</div>
@endsection
