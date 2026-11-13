<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    /**
     * Store a new comment on a published announcement.
     *
     * StoreCommentRequest automatically checks
     * authorization and validates the comment.
     *
     * Post $post is automatically resolved by
     * Laravel route model binding.
     */
    public function store(
        StoreCommentRequest $request,
        Post $post
    ): RedirectResponse {

        // Do not allow comments on unpublished drafts.
        // Return HTTP 404 if the post is not public.
        abort_unless($post->is_published, 404);

        // Retrieve only fields approved by validation.
        // This prevents unvalidated input from being saved.
        $validated = $request->validated();

        // Create the comment through the post relationship.
        // Laravel automatically assigns post_id.
        // user_id comes from the authenticated session,
        // not from user-submitted form data.
        $post->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
        ]);

        // Redirect back to the announcement after saving.
        // The success message is stored in the session.
        return redirect()
            ->route('posts.show', $post->id)
            ->with('success', 'Comment posted.');
    }
}
