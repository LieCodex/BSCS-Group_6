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

        // Notification logic
        if ($comment->parent_comment_id) {
            // Reply → notify parent comment owner
            $parentUser = $comment->replies()->first()?->user;
            if ($parentUser && $parentUser->id !== auth()->id()) {
                $parentUser->notifications()->create([
                    'actor_id' => auth()->id(),
                    'comment_id' => $comment->id,
                    'type' => 'reply',
                    'preview_text' => substr($comment->content, 0, 50),
                ]);
            }
        } else {
            // Normal comment → notify post owner
            if ($post->user_id !== auth()->id()) {
                $post->user->notifications()->create([
                    'actor_id' => auth()->id(),
                    'post_id' => $post->id,
                    'comment_id' => $comment->id,
                    'type' => 'comment',
                    'preview_text' => substr($comment->content, 0, 50),
                ]);
            }
        }

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
    // Edit comment
    public function edit(Comment $comment)
    {
        if (auth()->id() !== $comment->user_id) {
            return redirect()->back()->withErrors('Unauthorized action.');
        }
        return view('components.edit-comment', compact('comment'));
    }

    // Update comment
    public function update(Request $request, Comment $comment)
    {
        if (auth()->id() !== $comment->user_id) {
            return redirect()->back()->withErrors('Unauthorized action.');
        }

        $validated = $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        $comment->update([
            'content' => $validated['content']
        ]);

        return redirect()->route('posts.show', $comment->post_id);
}
}
