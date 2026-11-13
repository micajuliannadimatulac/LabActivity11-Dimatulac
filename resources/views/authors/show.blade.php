{{-- Reuse the existing Barangay Bulletin layout. --}}
@extends('layouts.app')

@section('title', $author->name . ' - Purok Dimatulac')

@section('content')

<div class="mx-auto max-w-5xl">

    {{-- Return to the bulletin listing. --}}
    <a href="{{ route('posts.index') }}"
       class="mb-6 inline-block font-medium
              text-[#7a5534] hover:underline">
        ← Back to announcements
    </a>

    {{-- Author profile information. --}}
    <section class="mb-8 rounded-xl bg-white p-8 shadow-sm">

        <div class="flex flex-wrap items-center gap-5">

            {{-- Avatar showing the author's first initial. --}}
            <div class="flex h-20 w-20 shrink-0 items-center
                        justify-center rounded-full bg-[#7a5534]
                        text-3xl font-bold text-white">

                {{ strtoupper(substr($author->name, 0, 1)) }}

            </div>

            <div>
                <h1 class="text-3xl font-bold text-[#7a5534]">
                    {{ $author->name }}
                </h1>

                <p class="mt-2 text-lg text-gray-600">
                    {{ $author->position }}
                </p>

                <p class="mt-1 text-sm text-gray-500">
                    Barangay Bulletin - Purok Dimatulac
                </p>
            </div>

        </div>

        {{-- Author activity statistics. --}}
        <div class="mt-8 grid grid-cols-1 gap-4
                    border-t border-[#e5dccd] pt-6 sm:grid-cols-2">

            <div class="rounded-lg bg-[#f2f5e2] p-5 text-center">

                <p class="text-3xl font-bold text-[#7a5534]">
                    {{ $publishedPostsCount }}
                </p>

                <p class="mt-2 text-sm text-gray-600">
                    Published Announcements
                </p>

            </div>

            <div class="rounded-lg bg-[#f2f5e2] p-5 text-center">

                <p class="text-3xl font-bold text-[#7a5534]">
                    {{ $commentsReceivedCount }}
                </p>

                <p class="mt-2 text-sm text-gray-600">
                    Comments Received
                </p>

            </div>

        </div>

    </section>

    {{-- Author's published announcements. --}}
    <section>

        <h2 class="mb-5 text-2xl font-bold text-[#7a5534]">
            Announcements by {{ $author->name }}
        </h2>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

            @forelse ($posts as $post)

                <article class="flex flex-col rounded-xl
                                bg-white p-6 shadow-sm
                                transition hover:shadow-lg">

                    {{-- Category and pinned indicator. --}}
                    <div class="mb-4 flex flex-wrap gap-2">

                        <span class="rounded-full bg-[#f2f5e2]
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

                    {{-- Announcement title. --}}
                    <h3 class="mb-3 text-xl font-bold text-gray-900">

                        <a href="{{ route('posts.show', $post->id) }}"
                           class="hover:text-[#bf7d36]">
                            {{ $post->title }}
                        </a>

                    </h3>

                    {{-- Preview of the announcement. --}}
                    <p class="mb-4 flex-grow text-sm leading-6
                              text-gray-600">
                        {{ \Illuminate\Support\Str::limit($post->body, 140) }}
                    </p>

                    {{-- Related tags are eager loaded. --}}
                    <div class="mb-4 flex flex-wrap gap-2">

                        @foreach ($post->tags as $tag)

                            <a href="{{ route('posts.index', ['tag' => $tag->id]) }}"
                               class="rounded-full bg-[#f2f5e2]
                                      px-3 py-1 text-xs
                                      text-[#7a5534]
                                      hover:bg-[#e2e8ca]">
                                #{{ $tag->name }}
                            </a>

                        @endforeach

                    </div>

                    {{-- Publication date and comment count. --}}
                    <div class="mb-4 flex flex-wrap justify-between
                                gap-2 text-xs text-gray-500">

                        <span>
                            {{ $post->published_at?->format('M d, Y') }}
                        </span>

                        <span>
                            {{ $post->comments_count }} comments
                        </span>

                    </div>

                    <a href="{{ route('posts.show', $post->id) }}"
                       class="font-semibold text-green-700
                              hover:underline">
                        Read more →
                    </a>

                </article>

            @empty

                <div class="rounded-xl bg-white p-10 text-center
                            shadow-sm sm:col-span-2">

                    <p class="text-gray-600">
                        This author has no published announcements yet.
                    </p>

                </div>

            @endforelse

        </div>

        {{-- Paginate the author's published posts. --}}
        <div class="mt-8">
            {{ $posts->links() }}
        </div>

    </section>

</div>

@endsection
