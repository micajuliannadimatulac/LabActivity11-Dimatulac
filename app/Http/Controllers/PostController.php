<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display the public bulletin listing.
     *
     * Includes eager loading, searching, filtering,
     * comment counts, sorting, and pagination.
     */
    public function index(Request $request)
    {
        // Load authors and tags together with the posts.
        // Count comments without loading all comment records.
        $query = Post::query()
            ->with(['author', 'tags'])
            ->withCount('comments')
            ->where('is_published', true);

        // Search for partial matches in post titles.
        // For example, "dim" can match "Dimatulac".
        if ($request->filled('q')) {
            $search = $request->input('q');

            $query->where(
                'title',
                'like',
                '%' . $search . '%'
            );
        }

        // Apply the selected category filter.
        if ($request->filled('category')) {
            $query->where(
                'category',
                $request->input('category')
            );
        }

        // Filter by the many-to-many tags relationship.
        if ($request->filled('tag')) {
            $tagId = $request->input('tag');

            $query->whereHas(
                'tags',
                function ($tagQuery) use ($tagId) {
                    $tagQuery->where('tags.id', $tagId);
                }
            );
        }

        // Pinned announcements first, then newest posts.
        // Preserve search filters across pagination.
        $posts = $query
            ->orderByDesc('is_pinned')
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        // Retrieve the available published categories.
        $categories = Post::query()
            ->where('is_published', true)
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        // Retrieve tags for the filter dropdown.
        $tags = Tag::query()
            ->orderBy('name')
            ->get();

        return view('posts.index', compact(
            'posts',
            'categories',
            'tags'
        ));
    }

    /**
     * Display one published announcement.
     *
     * Step 15 adds eager loading for the author,
     * tags, comments, and each comment's user.
     */
    public function show($id)
    {
        // Retrieve the requested post.
        // An invalid ID automatically returns HTTP 404.
        $post = Post::findOrFail($id);

        // Unpublished drafts are not publicly accessible.
        abort_unless($post->is_published, 404);

        // Increment the view counter directly in SQL.
        // This avoids overwriting other post attributes.
        $post->increment('views');

        // Eager load relationships to prevent N+1 queries.
        // comments.user loads each comment's writer.
        // Comments are ordered newest first.
        $post->load([
            'author',
            'tags',
            'comments' => function ($query) {
                $query->latest();
            },
            'comments.user',
        ]);

        // Pass the complete post to the Blade page.
        return view('posts.show', compact('post'));
    }
}
