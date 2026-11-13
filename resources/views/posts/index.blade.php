{{-- Reuse the shared navigation, footer, and Vite assets. --}}
@extends('layouts.app')

{{-- Set the browser tab title. --}}
@section('title', 'Bulletin Posts - Purok Dimatulac')

@section('content')

    {{-- Page heading --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-[#7a5534]">
            Purok Dimatulac Bulletin
        </h1>

        <p class="mt-2 text-gray-600">
            Stay informed about barangay announcements,
            programs, and community events.
        </p>
    </div>

    {{-- Search and category filter form --}}
    <form method="GET"
          action="{{ route('posts.index') }}"
          class="mb-8 grid grid-cols-1 gap-4 rounded-xl
                 bg-white p-5 shadow-sm md:grid-cols-4">

        {{-- Search by title --}}
        <div class="md:col-span-2">
            <label for="q"
                   class="mb-2 block font-medium text-gray-700">
                Search announcements
            </label>

            <input type="text"
                   id="q"
                   name="q"
                   value="{{ request('q') }}"
                   placeholder="Search by title..."
                   class="w-full rounded-lg border border-gray-300
                          px-4 py-2 focus:border-[#d5954e]
                          focus:outline-none">
        </div>

        {{-- Filter by category --}}
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

        {{-- Submit and reset controls --}}
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

    {{-- Show how many records match the search. --}}
    <p class="mb-5 text-sm text-gray-600">
        @if ($posts->total() > 0)
            Showing {{ $posts->firstItem() }}–{{ $posts->lastItem() }}
            of {{ $posts->total() }} posts
        @else
            Showing 0 posts
        @endif
    </p>

    {{-- Responsive grid: 1, 2, or 3 columns. --}}
    <div class="grid grid-cols-1 gap-6
                sm:grid-cols-2 lg:grid-cols-3">

        @forelse ($posts as $post)

            {{-- Individual announcement card --}}
            <article class="flex flex-col rounded-xl bg-white
                            p-6 shadow-sm transition
                            hover:shadow-lg">

                {{-- Category and pinned indicator --}}
                <div class="mb-4 flex flex-wrap items-center gap-2">

                    <span class="rounded-full bg-green-100
                                 px-3 py-1 text-xs font-semibold
                                 text-[#7a5534]">
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

                {{-- Post title --}}
                <h2 class="mb-3 text-xl font-bold text-gray-900">
                    <a href="{{ route('posts.show', $post->id) }}"
                       class="hover:text-[#bf7d36]">
                        {{ $post->title }}
                    </a>
                </h2>

                {{-- Preview of the announcement body --}}
                <p class="mb-5 flex-grow text-sm leading-6
                          text-gray-600">
                    {{ \Illuminate\Support\Str::limit($post->body, 130) }}
                </p>

                {{-- Publication date and views --}}
                <div class="mb-4 flex flex-wrap justify-between
                            gap-2 text-xs text-gray-500">

                    <span>
                        {{ $post->published_at?->format('M d, Y') }}
                    </span>

                    <span>
                        {{ $post->views }} views
                    </span>
                </div>

                {{-- Open the full announcement --}}
                <a href="{{ route('posts.show', $post->id) }}"
                   class="font-semibold text-green-700
                          hover:underline">
                    Read more →
                </a>
            </article>

        @empty

            {{-- Displayed when no posts match the filters. --}}
            <div class="rounded-xl bg-white p-10 text-center
                        shadow-sm sm:col-span-2 lg:col-span-3">

                <p class="text-lg font-semibold text-gray-700">
                    No posts match your search.
                </p>

                <a href="{{ route('posts.index') }}"
                   class="mt-3 inline-block text-green-700
                          hover:underline">
                    View all announcements
                </a>
            </div>

        @endforelse

    </div>

    {{-- Pagination links preserve search and category filters. --}}
    <div class="mt-8">
        {{ $posts->links() }}
    </div>

@endsection
