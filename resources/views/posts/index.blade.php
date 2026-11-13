@extends('layouts.app')

@section('title', 'Bulletin Posts - Purok Dimatulac')

@section('content')

<div class="mb-8">
    <h1 class="text-3xl font-bold text-[#7a5534]">
        Purok Dimatulac Bulletin
    </h1>

    <p class="mt-2 text-gray-600">
        Stay informed about barangay announcements,
        programs, and community events.
    </p>
</div>

{{-- Search and filters --}}
<form id="bulletin-filter-form"
      method="GET"
      action="{{ route('posts.index') }}"
      class="mb-8 grid grid-cols-1 gap-4 rounded-xl
             bg-white p-5 shadow-sm md:grid-cols-4">

    <div>
        <label for="q" class="mb-2 block font-medium text-gray-700">
            Search announcements
        </label>

        <input type="search"
               id="q"
               name="q"
               value="{{ request('q') }}"
               placeholder="Type to search..."
               autocomplete="off"
               class="w-full rounded-lg border border-gray-300
                      px-4 py-2 focus:border-[#d5954e]
                      focus:outline-none">
    </div>

    <div>
        <label for="category"
               class="mb-2 block font-medium text-gray-700">
            Category
        </label>

        <select id="category"
                name="category"
                class="w-full rounded-lg border border-gray-300
                       px-4 py-2 focus:border-[#d5954e]
                       focus:outline-none">

            <option value="">All Categories</option>

            @foreach ($categories as $category)
                <option value="{{ $category }}"
                    @selected(request('category') == $category)>
                    {{ $category }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="tag"
               class="mb-2 block font-medium text-gray-700">
            Tag
        </label>

        <select id="tag"
                name="tag"
                class="w-full rounded-lg border border-gray-300
                       px-4 py-2 focus:border-[#d5954e]
                       focus:outline-none">

            <option value="">All Tags</option>

            @foreach ($tags as $tag)
                <option value="{{ $tag->id }}"
                    @selected(request('tag') == $tag->id)>
                    {{ $tag->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="flex items-end gap-2">
        <button type="submit"
                class="rounded-lg bg-[#d5954e] px-5 py-2
                       font-medium text-white hover:bg-[#bf7d36]">
            Search
        </button>

        <a href="{{ route('posts.index') }}"
           class="rounded-lg bg-gray-200 px-4 py-2
                  text-gray-800 hover:bg-gray-300">
            Reset
        </a>
    </div>
</form>

{{-- Announce loading and errors without moving the search field. --}}
<p id="search-status"
   role="status"
   aria-live="polite"
   class="mb-3 text-sm text-[#7a5534]"></p>

{{-- Only this section is replaced by JavaScript. --}}
<div id="bulletin-results">

    <p class="mb-5 text-sm text-gray-600">
        @if ($posts->total() > 0)
            Showing {{ $posts->firstItem() }}–{{ $posts->lastItem() }}
            of {{ $posts->total() }} posts
        @else
            Showing 0 posts
        @endif
    </p>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

        @forelse ($posts as $post)
            <article class="flex flex-col rounded-xl bg-white
                            p-6 shadow-sm transition hover:shadow-lg">

                <div class="mb-4 flex flex-wrap items-center gap-2">
                    <span class="rounded-full bg-green-100 px-3 py-1
                                 text-xs font-semibold text-[#7a5534]">
                        {{ $post->category }}
                    </span>

                    @if ($post->is_pinned)
                        <span class="rounded-full bg-yellow-100 px-3 py-1
                                     text-xs font-semibold text-yellow-800">
                            📌 Pinned
                        </span>
                    @endif
                </div>

                <h2 class="mb-3 text-xl font-bold text-gray-900">
                    <a href="{{ route('posts.show', $post->id) }}"
                       class="hover:text-[#bf7d36]">
                        {{ $post->title }}
                    </a>
                </h2>

                {{-- Author is eager loaded by PostController. --}}
                <p class="mb-3 text-sm text-[#8a765e]">
                    Posted by
                    <span class="font-semibold">
                        {{ $post->author->name }}
                    </span>
                    ({{ $post->author->position }})
                </p>

                <p class="mb-4 flex-grow text-sm leading-6 text-gray-600">
                    {{ \Illuminate\Support\Str::limit($post->body, 130) }}
                </p>

                {{-- Display the post's related tags. --}}
                <div class="mb-4 flex flex-wrap gap-2">
                    @foreach ($post->tags as $tag)
                        <span class="rounded-full bg-[#f2f5e2] px-3 py-1
                                     text-xs font-medium text-[#7a5534]">
                            #{{ $tag->name }}
                        </span>
                    @endforeach
                </div>

                <div class="mb-4 flex flex-wrap justify-between
                            gap-2 text-xs text-gray-500">
                    <span>
                        {{ $post->published_at?->format('M d, Y') }}
                    </span>

                    <span>{{ $post->views }} views</span>

                    <span>{{ $post->comments_count }} comments</span>
                </div>

                <a href="{{ route('posts.show', $post->id) }}"
                   class="font-semibold text-green-700 hover:underline">
                    Read more →
                </a>
            </article>

        @empty
            <div class="rounded-xl bg-white p-10 text-center
                        shadow-sm sm:col-span-2 lg:col-span-3">
                <p class="text-lg font-semibold text-gray-700">
                    No posts match your search.
                </p>

                <a href="{{ route('posts.index') }}"
                   class="mt-3 inline-block text-green-700 hover:underline">
                    View all announcements
                </a>
            </div>
        @endforelse

    </div>

    {{-- Pagination stays inside the refreshed results section. --}}
    <div class="mt-8">
        {{ $posts->links() }}
    </div>

</div>

{{-- Live search with debounce and no full-page reload. --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('bulletin-filter-form');
    const search = document.getElementById('q');
    const category = document.getElementById('category');
    const tag = document.getElementById('tag');
    const results = document.getElementById('bulletin-results');
    const status = document.getElementById('search-status');

    let debounceTimer;
    let activeRequest;

    // Fetch the matching posts while preserving the search field.
    async function updateResults(url) {
        // Cancel an older request when the user types again.
        if (activeRequest) {
            activeRequest.abort();
        }

        activeRequest = new AbortController();
        const currentRequest = activeRequest;

        status.textContent = 'Searching...';

        try {
            const response = await fetch(url, {
                signal: currentRequest.signal,
                headers: {
                    'Accept': 'text/html',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Search request failed.');
            }

            // Parse the returned page without executing its scripts.
            const html = await response.text();
            const documentResult = new DOMParser()
                .parseFromString(html, 'text/html');

            const newResults = documentResult
                .getElementById('bulletin-results');

            if (!newResults) {
                throw new Error('Search results were not found.');
            }

            // Replace only the posts, counts, and pagination.
            // The input remains untouched and keeps keyboard focus.
            results.innerHTML = newResults.innerHTML;

            // Update the URL so filters can be bookmarked.
            window.history.replaceState({}, '', url);

            status.textContent = '';

        } catch (error) {
            if (error.name !== 'AbortError') {
                status.textContent =
                    'Could not update results. Please try again.';
                console.error(error);
            }
        }
    }

    // Build a URL from the current search and filter values.
    function getFilterUrl() {
        const url = new URL(form.action, window.location.origin);
        const params = new URLSearchParams(new FormData(form));

        // Remove empty filters for cleaner URLs.
        for (const [key, value] of params.entries()) {
            if (!value.trim()) {
                params.delete(key);
            }
        }

        url.search = params.toString();

        return url.toString();
    }

    // Wait 300ms after the latest keystroke.
    search.addEventListener('input', function () {
        clearTimeout(debounceTimer);

        // Cancel any outdated request immediately.
        if (activeRequest) {
            activeRequest.abort();
        }

        debounceTimer = setTimeout(function () {
            updateResults(getFilterUrl());
        }, 300);
    });

    // Category and tag filters also update automatically.
    [category, tag].forEach(function (select) {
        select.addEventListener('change', function () {
            clearTimeout(debounceTimer);
            updateResults(getFilterUrl());
        });
    });

    // Keep the Search button working without reloading.
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        clearTimeout(debounceTimer);
        updateResults(getFilterUrl());
    });

    // Handle pagination inside the results area.
    results.addEventListener('click', function (event) {
        const link = event.target.closest('a[href]');

        if (!link) {
            return;
        }

        const url = new URL(link.href, window.location.origin);

        // Intercept only pagination links for this page.
        if (
            url.pathname === new URL(form.action).pathname &&
            url.searchParams.has('page')
        ) {
            event.preventDefault();
            clearTimeout(debounceTimer);
            updateResults(url.toString());
        }
    });
});
</script>

@endsection
