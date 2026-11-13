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

{{-- Search and filtering form. --}}
<form id="bulletin-filter-form"
      method="GET"
      action="{{ route('posts.index') }}"
      class="mb-8 grid grid-cols-1 gap-4 rounded-xl
             bg-white p-5 shadow-sm md:grid-cols-4">

    <div>
        <label for="q"
               class="mb-2 block font-medium text-gray-700">
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

            {{-- Tag names are used in public filter URLs. --}}
            @foreach ($tags as $tag)
                <option value="{{ $tag->name }}"
                    @selected(request('tag') === $tag->name)>
                    {{ $tag->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="flex items-end gap-2">
        <button type="submit"
                class="rounded-lg bg-[#d5954e]
                       px-5 py-2 font-medium text-white
                       hover:bg-[#bf7d36]">
            Search
        </button>

        <a href="{{ route('posts.index') }}"
           class="rounded-lg bg-gray-200 px-4 py-2
                  text-gray-800 hover:bg-gray-300">
            Reset
        </a>
    </div>
</form>

<p id="search-status"
   role="status"
   aria-live="polite"
   class="mb-3 text-sm text-[#7a5534]"></p>

{{-- JavaScript updates this container without reloading. --}}
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
                        <span class="rounded-full bg-yellow-100
                                     px-3 py-1 text-xs font-semibold
                                     text-yellow-800">
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

                <p class="mb-3 text-sm text-[#8a765e]">
                    Posted by
                    <a href="{{ route('authors.show', $post->author->id) }}"
                       class="font-semibold text-[#7a5534]
                              hover:underline">
                        {{ $post->author->name }}
                    </a>
                    ({{ $post->author->position }})
                </p>

                <p class="mb-4 flex-grow text-sm leading-6
                          text-gray-600">
                    {{ \Illuminate\Support\Str::limit($post->body, 130) }}
                </p>

                {{-- Tags are clickable and use tag names. --}}
                <div class="mb-4 flex flex-wrap gap-2">
                    @foreach ($post->tags as $tag)
                        <a href="{{ route('posts.index', ['tag' => $tag->name]) }}"
                           class="rounded-full bg-[#f2f5e2]
                                  px-3 py-1 text-xs font-medium
                                  text-[#7a5534] hover:bg-[#e2e8ca]">
                            #{{ $tag->name }}
                        </a>
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

    <div class="mt-8">
        {{ $posts->links() }}
    </div>
</div>

{{-- Automatic search and filtering. --}}
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
    let requestNumber = 0;

    // Build the URL using current search and filter values.
    function getFilterUrl() {
        const url = new URL(form.action, window.location.origin);
        const params = new URLSearchParams(new FormData(form));

        for (const [key, value] of [...params.entries()]) {
            if (!value.trim()) {
                params.delete(key);
            }
        }

        url.search = params.toString();

        return url.toString();
    }

    // Load updated results without reloading the page.
    async function updateResults(url, updateHistory = true) {
        if (activeRequest) {
            activeRequest.abort();
        }

        const controller = new AbortController();
        activeRequest = controller;
        const currentNumber = ++requestNumber;

        status.textContent = 'Searching...';

        try {
            const response = await fetch(url, {
                signal: controller.signal,
                headers: {
                    'Accept': 'text/html',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Search request failed.');
            }

            const html = await response.text();
            const parsed = new DOMParser()
                .parseFromString(html, 'text/html');

            const newResults = parsed
                .getElementById('bulletin-results');

            if (!newResults) {
                throw new Error('Search results were not found.');
            }

            // Ignore outdated responses.
            if (currentNumber !== requestNumber) {
                return;
            }

            results.innerHTML = newResults.innerHTML;

            if (updateHistory) {
                window.history.replaceState({}, '', url);
            }

            status.textContent = '';

        } catch (error) {
            if (error.name !== 'AbortError'
                && currentNumber === requestNumber) {
                status.textContent =
                    'Could not update results. Please try again.';
                console.error(error);
            }
        }
    }

    // Update while typing, after a 300ms pause.
    search.addEventListener('input', function () {
        clearTimeout(debounceTimer);

        if (activeRequest) {
            activeRequest.abort();
        }

        debounceTimer = setTimeout(function () {
            updateResults(getFilterUrl());
        }, 300);
    });

    // Apply category and tag filters automatically.
    [category, tag].forEach(function (select) {
        select.addEventListener('change', function () {
            clearTimeout(debounceTimer);
            updateResults(getFilterUrl());
        });
    });

    // Keep Search working without a full reload.
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        clearTimeout(debounceTimer);
        updateResults(getFilterUrl());
    });

    // Handle pagination links.
    results.addEventListener('click', function (event) {
        const link = event.target.closest('a[href]');

        if (!link) {
            return;
        }

        const url = new URL(link.href, window.location.origin);
        const formUrl = new URL(form.action, window.location.origin);

        if (url.pathname === formUrl.pathname
            && url.searchParams.has('page')) {
            event.preventDefault();
            clearTimeout(debounceTimer);
            updateResults(url.toString());
        }
    });
});
</script>

@endsection
