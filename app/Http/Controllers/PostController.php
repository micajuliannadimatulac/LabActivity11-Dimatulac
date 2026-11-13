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
     * Display published bulletin announcements.
     *
     * Preserves Step 14:
     * - Live search backend
     * - Category filtering
     * - Tag filtering
     * - Eager loading
     * - Comment counts
     * - Pinned-first ordering
     * - Pagination
     */
    public function index(Request $request): View
    {
        // Retrieve published posts with related
        // authors, tags, and comment counts.
        $query = Post::query()
            ->with(['author', 'tags'])
            ->withCount('comments')
            ->where('is_published', true);

        // Partial title search.
        // Example: "dim" matches "Dimatulac".
        if ($request->filled('q')) {
            $search = $request->input('q');

            $query->where(
                'title',
                'like',
                '%' . $search . '%'
            );
        }

        // Filter by category.
        if ($request->filled('category')) {
            $query->where(
                'category',
                $request->input('category')
            );
        }

        // Filter using the many-to-many relationship.
        if ($request->filled('tag')) {
            $tagId = $request->input('tag');

            $query->whereHas(
                'tags',
                function ($tagQuery) use ($tagId) {
                    $tagQuery->where('tags.id', $tagId);
                }
            );
        }

        // Pinned posts appear first.
        // Newest posts appear before older posts.
        $posts = $query
            ->orderByDesc('is_pinned')
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        // Retrieve available categories.
        $categories = Post::query()
            ->where('is_published', true)
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        // Retrieve available tags.
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
     * Display the form for creating an announcement.
     *
     * Only authenticated users with author profiles
     * are allowed to access this page.
     */
    public function create(Request $request): View
    {
        // The auth middleware checks login.
        // This check additionally requires an author profile.
        abort_unless(
            $request->user()->author()->exists(),
            403
        );

        // Provide the predefined categories.
        $categories = [
            'Announcement',
            'Health',
            'Peace & Order',
            'Ayuda',
            'Events',
        ];

        // Retrieve available tags for the form.
        $tags = Tag::query()
            ->orderBy('name')
            ->get();

        // The Blade form will be created in Step 20.
        return view('posts.create', compact(
            'categories',
            'tags'
        ));
    }

    /**
     * Save a new announcement.
     *
     * StorePostRequest validates the input
     * and confirms that the user has an author profile.
     */
    public function store(
        StorePostRequest $request
    ): RedirectResponse {

        // Retrieve only validated form fields.
        $validated = $request->validated();

        // Obtain the author linked to the current user.
        // The author ID is never accepted from the form.
        $author = $request->user()->author()
            ->firstOrFail();

        // Convert the validated publish option
        // into a boolean value.
        $isPublished = (bool) (
            $validated['is_published'] ?? false
        );

        // Use a transaction so the post and its
        // tag assignments succeed or fail together.
        $post = DB::transaction(function () use (
            $author,
            $validated,
            $isPublished
        ) {

            // Create the post through the author's
            // hasMany relationship.
            // Laravel automatically sets author_id.
            $post = $author->posts()->create([
                'title' => $validated['title'],
                'body' => $validated['body'],
                'category' => $validated['category'],
                'is_published' => $isPublished,
                'is_pinned' => false,
                'published_at' => $isPublished
                    ? now()
                    : null,
                'views' => 0,
            ]);

            // Attach the validated tag IDs to the post.
            // sync() updates the post_tag pivot table.
            $post->tags()->sync($validated['tags']);

            return $post;
        });

        // Published posts can be viewed publicly.
        if ($post->is_published) {
            return redirect()
                ->route('posts.show', $post->id)
                ->with(
                    'success',
                    'Announcement published successfully.'
                );
        }

        // Drafts are not publicly accessible.
        // Redirect to the listing instead of a 404 page.
        return redirect()
            ->route('posts.index')
            ->with(
                'success',
                'Announcement saved as a draft.'
            );
    }

    /**
     * Display an individual published announcement.
     *
     * Preserves Step 15:
     * - Draft protection
     * - View counting
     * - Eager-loaded author
     * - Eager-loaded tags
     * - Newest-first comments
     * - Eager-loaded comment users
     */
    public function show($id): View
    {
        // Retrieve the requested announcement.
        // Missing IDs automatically return HTTP 404.
        $post = Post::findOrFail($id);

        // Unpublished posts are not publicly visible.
        abort_unless($post->is_published, 404);

        // Increment the view count.
        $post->increment('views');

        // Eager load all relationships needed by Blade.
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
