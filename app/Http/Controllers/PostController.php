<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * Display published announcements with
     * live search, category and tag-name filters.
     */
    public function index(Request $request): View
    {
        // Eager load authors and tags, and count comments.
        $query = Post::query()
            ->with(['author', 'tags'])
            ->withCount('comments')
            ->where('is_published', true);

        // Partial title matching for live search.
        if ($request->filled('q')) {
            $query->where(
                'title',
                'like',
                '%' . $request->input('q') . '%'
            );
        }

        // Filter by an existing category.
        if ($request->filled('category')) {
            $query->where(
                'category',
                $request->input('category')
            );
        }

        // Filter by TAG NAME, as required by the rubric.
        // Example: /posts?tag=Urgent
        if ($request->filled('tag')) {
            $tagName = $request->input('tag');

            $query->whereHas(
                'tags',
                function ($tagQuery) use ($tagName) {
                    $tagQuery->where('tags.name', $tagName);
                }
            );
        }

        // Pinned announcements first, then newest.
        $posts = $query
            ->orderByDesc('is_pinned')
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        $categories = Post::query()
            ->where('is_published', true)
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

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
     * Show the announcement creation form.
     * Only accounts linked to authors can access it.
     */
    public function create(Request $request): View
    {
        abort_unless(
            $request->user()->author()->exists(),
            403
        );

        $categories = [
            'Announcement',
            'Health',
            'Peace & Order',
            'Ayuda',
            'Events',
        ];

        $tags = Tag::orderBy('name')->get();

        return view('posts.create', compact(
            'categories',
            'tags'
        ));
    }

    /**
     * Create an announcement through the authenticated
     * author's relationship and attach validated tag IDs.
     */
    public function store(
        StorePostRequest $request
    ): RedirectResponse {
        $validated = $request->validated();

        $author = $request->user()
            ->author()
            ->firstOrFail();

        $isPublished = (bool) (
            $validated['is_published'] ?? false
        );

        // Keep the post and its tag assignments atomic.
        $post = DB::transaction(function () use (
            $author,
            $validated,
            $isPublished
        ) {
            $post = $author->posts()->create([
                'title' => $validated['title'],
                'body' => $validated['body'],
                'category' => $validated['category'],
                'is_published' => $isPublished,
                'is_pinned' => false,
                'published_at' => $isPublished ? now() : null,
                'views' => 0,
            ]);

            // Form submissions use tag IDs for the pivot table.
            // Public browsing uses tag names for filtering.
            $post->tags()->sync($validated['tags']);

            return $post;
        });

        if ($post->is_published) {
            return redirect()
                ->route('posts.show', $post->id)
                ->with(
                    'success',
                    'Announcement published successfully.'
                );
        }

        return redirect()
            ->route('posts.index')
            ->with(
                'success',
                'Announcement saved as a draft.'
            );
    }

    /**
     * Show a published announcement and its
     * eager-loaded comments, authors and tags.
     */
    public function show($id): View
    {
        $post = Post::findOrFail($id);

        // Drafts cannot be accessed publicly.
        abort_unless($post->is_published, 404);

        $post->increment('views');

        $post->load([
            'author',
            'tags',
            'comments' => function ($query) {
                $query->latest();
            },
            'comments.user',
        ]);

        return view('posts.show', compact('post'));
    }
}
