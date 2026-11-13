{{-- Reuse the existing Barangay Bulletin layout. --}}
@extends('layouts.app')

@section('title', $post->title . ' - Purok Dimatulac')

@section('content')

<div class="mx-auto max-w-4xl">

    {{-- Return to the public announcement listing. --}}
    <a href="{{ route('posts.index') }}"
       class="mb-6 inline-block font-medium
              text-[#7a5534] hover:underline">
        ← Back to announcements
    </a>

    {{-- Main announcement card. --}}
    <article class="rounded-xl bg-white p-6
                    shadow-sm md:p-10">

        {{-- Category and pinned indicator. --}}
        <div class="mb-5 flex flex-wrap gap-2">

            <span class="rounded-full bg-[#f2f5e2]
                         px-3 py-1 text-sm font-medium
                         text-[#7a5534]">
                {{ $post->category }}
            </span>

            @if ($post->is_pinned)
                <span class="rounded-full bg-yellow-100
                             px-3 py-1 text-sm font-medium
                             text-yellow-800">
                    📌 Pinned Announcement
                </span>
            @endif

        </div>

        {{-- Complete announcement title. --}}
        <h1 class="mb-5 text-3xl font-bold
                   text-gray-900 md:text-4xl">
            {{ $post->title }}
        </h1>

        {{-- Author, publication date, and views. --}}
        <div class="mb-8 flex flex-wrap gap-5
                    border-b border-gray-200 pb-5
                    text-sm text-gray-500">

            {{-- The author was eager loaded in show(). --}}
            <span>
                Posted by:
                <strong class="text-[#7a5534]">
                    {{ $post->author->name }}
                </strong>
                ({{ $post->author->position }})
            </span>

            <span>
                Published:
                {{ $post->published_at?->format('F d, Y') }}
            </span>

            <span>
                Views: {{ $post->views }}
            </span>

        </div>

        {{-- Complete announcement body. --}}
        {{-- Blade escaping prevents injected HTML. --}}
        <div class="whitespace-pre-line break-words
                    leading-8 text-gray-700">{{ $post->body }}</div>

        {{-- Display related tags from the pivot table. --}}
        <div class="mt-8 border-t border-gray-200 pt-5">

            <h2 class="mb-3 font-semibold text-[#7a5534]">
                Announcement Tags
            </h2>

            <div class="flex flex-wrap gap-2">

                @forelse ($post->tags as $tag)

                    <a href="{{ route('posts.index', ['tag' => $tag->id]) }}"
                       class="rounded-full bg-[#f2f5e2]
                              px-3 py-1 text-sm font-medium
                              text-[#7a5534]
                              hover:bg-[#e2e8ca]">
                        #{{ $tag->name }}
                    </a>

                @empty

                    <p class="text-sm text-gray-500">
                        No tags assigned.
                    </p>

                @endforelse

            </div>
        </div>

    </article>

    {{-- Resident comments section. --}}
    <section class="mt-8 rounded-xl bg-white
                    p-6 shadow-sm md:p-10">

        <div class="mb-6 flex flex-wrap items-center
                    justify-between gap-3">

            <h2 class="text-2xl font-bold text-[#7a5534]">
                Community Comments
            </h2>

            <span class="rounded-full bg-[#f2f5e2]
                         px-3 py-1 text-sm text-[#7a5534]">
                {{ $post->comments->count() }} comments
            </span>

        </div>

        {{-- Comments are already sorted newest first. --}}
        <div class="space-y-5">

            @forelse ($post->comments as $comment)

                <div class="rounded-lg border border-[#e5dccd]
                            bg-[#fffdf8] p-5">

                    {{-- Commenter's registered name. --}}
                    <div class="mb-3 flex flex-wrap
                                justify-between gap-2">

                        <strong class="text-[#7a5534]">
                            {{ $comment->user->name }}
                        </strong>

                        <span class="text-xs text-gray-500">
                            {{ $comment->created_at->format('M d, Y h:i A') }}
                        </span>

                    </div>

                    {{-- Comment text with preserved line breaks. --}}
                    <p class="whitespace-pre-line break-words
                              text-sm leading-7 text-gray-700">{{ $comment->body }}</p>

                </div>

            @empty

                <div class="rounded-lg bg-[#f2f5e2]
                            p-6 text-center">

                    <p class="text-gray-600">
                        No comments yet.
                        Be the first to join the discussion!
                    </p>

                </div>

            @endforelse

        </div>

        {{-- Comment submission will be added in Step 19. --}}
        @guest
            <div class="mt-8 border-t border-gray-200 pt-5">

                <p class="text-sm text-gray-600">
                    Want to participate in the discussion?
                </p>

                <a href="{{ route('login') }}"
                   class="mt-3 inline-block rounded-lg
                          bg-[#d5954e] px-5 py-3
                          font-medium text-white
                          hover:bg-[#bf7d36]">
                    Sign in to comment
                </a>

            </div>
        @endguest

    </section>

    {{-- Bottom navigation. --}}
    <div class="mt-8">

        <a href="{{ route('posts.index') }}"
           class="inline-block rounded-lg bg-[#d5954e]
                  px-5 py-3 font-medium text-white
                  hover:bg-[#bf7d36]">
            Back to Bulletin
        </a>

    </div>

</div>

@endsection
