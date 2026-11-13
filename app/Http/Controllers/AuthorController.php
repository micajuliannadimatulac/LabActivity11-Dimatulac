<?php

namespace App\Http\Controllers;

use App\Models\Author;

class AuthorController extends Controller
{
    /**
     * Display a public author profile.
     *
     * Laravel automatically retrieves the Author model
     * using the {author} route parameter.
     */
    public function show(Author $author)
    {
        // Count only published announcements.
        $publishedPostsCount = $author->posts()
            ->where('is_published', true)
            ->count();

        // HAS MANY THROUGH RELATIONSHIP:
        //
        // Author -> Posts -> Comments
        //
        // Count comments received on published posts
        // without manually joining database tables.
        $commentsReceivedCount = $author->comments()
            ->where('posts.is_published', true)
            ->count();

        // Retrieve the author's published announcements.
        // Eager load tags and count comments to avoid
        // unnecessary additional database queries.
        $posts = $author->posts()
            ->where('is_published', true)
            ->with('tags')
            ->withCount('comments')
            ->orderByDesc('is_pinned')
            ->latest('published_at')
            ->paginate(9);

        // Display the profile and its published posts.
        return view('authors.show', compact(
            'author',
            'posts',
            'publishedPostsCount',
            'commentsReceivedCount'
        ));
    }
}
