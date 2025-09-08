<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    public function createComment(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'parent_comment_id' => ['nullable', 'exists:comments,id']
        ]);

        // Validate parent_comment belongs to the same post
        if (!empty($validated['parent_comment_id'])) {
            $parent = Comment::find($validated['parent_comment_id']);
            if (!$parent || $parent->post_id !== $post->id) {
                return back()->withErrors('Invalid parent comment for this post.');
            }
        }

        // Create the comment
        $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
            'parent_comment_id' => $validated['parent_comment_id'] ?? null
        ]);

        return redirect()->back()->with('success', 'Comment added successfully.');
    }

    // Delete comment
    public function deleteComment(Comment $comment)
    {
        if (auth()->id() !== $comment->user_id) {
            return redirect()->back()->withErrors('Unauthorized action.');
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }
}
