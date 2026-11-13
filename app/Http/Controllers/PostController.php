<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display published bulletin announcements.
     *
     * Supports title search, category filtering,
     * tag filtering, eager loading, comment counts,
     * pinned-first sorting, and pagination.
     */
    public function index(Request $request)
    {
        // Begin with published announcements only.
        // Eager load authors and tags to avoid N+1 queries.
        // withCount adds a comments_count attribute
        // without loading every comment record.
        $query = Post::query()
            ->with(['author', 'tags'])
            ->withCount('comments')
            ->where('is_published', true);

        // Search by announcement title.
        if ($request->filled('q')) {
            $search = $request->input('q');

            $query->where(
                'title',
                'like',
                '%' . $search . '%'
            );
        }

        // Filter by the existing announcement category.
        if ($request->filled('category')) {
            $query->where(
                'category',
                $request->input('category')
            );
        }

        // Filter posts using the many-to-many tags relationship.
        // whereHas only returns posts with a matching tag.
        if ($request->filled('tag')) {
            $tagId = $request->input('tag');

            $query->whereHas('tags', function ($tagQuery) use ($tagId) {
                $tagQuery->where('tags.id', $tagId);
            });
        }

        // Keep pinned posts first, followed by newest posts.
        // Show 9 posts per page and preserve filter parameters.
        $posts = $query
            ->orderByDesc('is_pinned')
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        // Retrieve categories from published posts only.
        $categories = Post::query()
            ->where('is_published', true)
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        // Retrieve all available tags for the filter dropdown.
        $tags = Tag::query()
            ->orderBy('name')
            ->get();

        // Send the listing and filter options to the view.
        return view('posts.index', compact(
            'posts',
            'categories',
            'tags'
        ));
    }

    /**
     * Display one published bulletin announcement.
     *
     * This method is preserved from Lab Activity 10.
     * We will enhance it with eager loading in Step 15.
     */
    public function show($id)
    {
        // Find the post or return a 404 error.
        $post = Post::findOrFail($id);

        // Draft posts must remain inaccessible to visitors.
        abort_unless($post->is_published, 404);

        // Increase the announcement's view count.
        $post->views++;

        // Save the updated view count.
        $post->save();

        // Display the existing individual post page.
        return view('posts.show', compact('post'));
    }
}
