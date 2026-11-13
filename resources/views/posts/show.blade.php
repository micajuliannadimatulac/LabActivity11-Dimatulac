{{-- Use the shared Barangay Bulletin layout. --}}
@extends('layouts.app')

{{-- Use the post title as the browser tab title. --}}
@section('title', $post->title . ' - Purok Dimatulac')

@section('content')

    <div class="mx-auto max-w-4xl">

        {{-- Return to the bulletin listing. --}}
        <a href="{{ route('posts.index') }}"
           class="mb-6 inline-block font-medium
                  text-[#7a5534] hover:underline">
            ← Back to announcements
        </a>

        {{-- Full announcement --}}
        <article class="rounded-xl bg-white p-6
                        shadow-sm md:p-10">

            {{-- Category and pinned badge --}}
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

            {{-- Full post title --}}
            <h1 class="mb-5 text-3xl font-bold
                       text-gray-900 md:text-4xl">
                {{ $post->title }}
            </h1>

            {{-- Publication information --}}
            <div class="mb-8 flex flex-wrap gap-5
                        border-b border-gray-200 pb-5
                        text-sm text-gray-500">

                <span>
                    Published:
                    {{ $post->published_at?->format('F d, Y') }}
                </span>

                <span>
                    Views: {{ $post->views }}
                </span>
            </div>

            {{-- Full body, preserving paragraph line breaks. --}}
            <div class="whitespace-pre-line break-words
                        leading-8 text-gray-700">{{ $post->body }}</div>

        </article>

        {{-- Bottom navigation --}}
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
