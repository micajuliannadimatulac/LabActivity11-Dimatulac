<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display published bulletin posts.
     * Supports searching, filtering, sorting, and pagination.
     */
    public function index(Request $request)
    {
        // Begin with published posts only.
        // Draft posts must not appear publicly.
        $query = Post::query()
            ->where('is_published', true);

        // Search for posts containing the entered keyword.
        if ($request->filled('q')) {
            $search = $request->input('q');

            $query->where('title', 'like', '%' . $search . '%');
        }

        // Filter posts by the selected category.
        if ($request->filled('category')) {
            $query->where(
                'category',
                $request->input('category')
            );
        }

        // Show pinned posts first.
        // Then sort by publication date, newest first.
        // Display 9 posts per page.
        $posts = $query
            ->orderByDesc('is_pinned')
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        // Retrieve available categories from published posts.
        $categories = Post::query()
            ->where('is_published', true)
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        // Send the posts and categories to the Blade view.
        return view('posts.index', compact(
            'posts',
            'categories'
        ));
    }

    /**
     * Display one published bulletin post.
     */
    public function show($id)
    {
        // Find the post or return a 404 error.
        $post = Post::findOrFail($id);

        // Prevent visitors from viewing unpublished drafts.
        abort_unless($post->is_published, 404);

        // Increase the view count by one.
        $post->views++;

        // Save the updated view count to the database.
        $post->save();

        // Display the individual post page.
        return view('posts.show', compact('post'));
    }
}
