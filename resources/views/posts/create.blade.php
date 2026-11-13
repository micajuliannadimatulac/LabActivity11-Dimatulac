{{-- Use the existing Barangay Bulletin layout. --}}
@extends('layouts.app')

@section('title', 'Create Announcement - Purok Dimatulac')

@section('content')

<div class="mx-auto max-w-3xl">

    {{-- Return to the public bulletin. --}}
    <a href="{{ route('posts.index') }}"
       class="mb-6 inline-block font-medium
              text-[#7a5534] hover:underline">
        ← Back to announcements
    </a>

    <div class="rounded-xl bg-white p-6 shadow-sm md:p-10">

        {{-- Form heading. --}}
        <div class="mb-8 border-b border-[#e5dccd] pb-6">

            <h1 class="text-3xl font-bold text-[#7a5534]">
                Create New Announcement
            </h1>

            <p class="mt-2 text-gray-600">
                Share important updates with the
                Purok Dimatulac community.
            </p>

            <p class="mt-3 text-sm text-[#8a765e]">
                Posting as:
                <strong>{{ auth()->user()->name }}</strong>
            </p>

        </div>

        {{-- Display all server-side validation errors. --}}
        @if ($errors->any())

            <div class="mb-6 rounded-lg border border-red-200
                        bg-red-50 p-5 text-red-700"
                 role="alert">

                <h2 class="mb-2 font-semibold">
                    Please correct the following:
                </h2>

                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>

        @endif

        {{-- Submit to PostController@store. --}}
        <form method="POST"
              action="{{ route('posts.store') }}"
              class="space-y-6">

            {{-- Required Laravel CSRF token. --}}
            @csrf

            {{-- Announcement title. --}}
            <div>

                <label for="title"
                       class="mb-2 block font-semibold
                              text-[#7a5534]">
                    Announcement Title
                    <span class="text-red-600">*</span>
                </label>

                <input type="text"
                       id="title"
                       name="title"
                       value="{{ old('title') }}"
                       maxlength="120"
                       required
                       placeholder="Enter announcement title"
                       class="w-full rounded-lg border px-4 py-3
                              text-gray-800 focus:outline-none
                              focus:ring-2 focus:ring-[#d5954e]
                              @error('title')
                                  border-red-500
                              @else
                                  border-gray-300
                              @enderror">

                <p class="mt-2 text-xs text-gray-500">
                    Maximum 120 characters.
                </p>

                @error('title')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>

            {{-- Predefined category selection. --}}
            <div>

                <label for="category"
                       class="mb-2 block font-semibold
                              text-[#7a5534]">
                    Category
                    <span class="text-red-600">*</span>
                </label>

                <select id="category"
                        name="category"
                        required
                        class="w-full rounded-lg border px-4 py-3
                               text-gray-800 focus:outline-none
                               focus:ring-2 focus:ring-[#d5954e]
                               @error('category')
                                   border-red-500
                               @else
                                   border-gray-300
                               @enderror">

                    <option value="">
                        Select a category
                    </option>

                    @foreach ($categories as $category)

                        <option value="{{ $category }}"
                            @selected(old('category') === $category)>
                            {{ $category }}
                        </option>

                    @endforeach

                </select>

                @error('category')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>

            {{-- Full announcement body. --}}
            <div>

                <label for="body"
                       class="mb-2 block font-semibold
                              text-[#7a5534]">
                    Announcement Details
                    <span class="text-red-600">*</span>
                </label>

                <textarea id="body"
                          name="body"
                          rows="8"
                          minlength="20"
                          required
                          placeholder="Write the full announcement here..."
                          class="w-full rounded-lg border px-4 py-3
                                 text-gray-800 focus:outline-none
                                 focus:ring-2 focus:ring-[#d5954e]
                                 @error('body')
                                     border-red-500
                                 @else
                                     border-gray-300
                                 @enderror">{{ old('body') }}</textarea>

                <p class="mt-2 text-xs text-gray-500">
                    Minimum 20 characters.
                    Include important dates, locations,
                    and instructions when applicable.
                </p>

                @error('body')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>

            {{-- Select tags using checkboxes. --}}
            <fieldset>

                <legend class="mb-3 font-semibold text-[#7a5534]">
                    Announcement Tags
                    <span class="text-red-600">*</span>
                </legend>

                <p class="mb-4 text-sm text-gray-600">
                    Select at least one relevant tag.
                    Multiple selections are allowed.
                </p>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                    @foreach ($tags as $tag)

                        <label class="flex cursor-pointer
                                      items-center gap-3
                                      rounded-lg border border-[#e5dccd]
                                      bg-[#fffdf8] p-4
                                      hover:bg-[#f2f5e2]">

                            <input type="checkbox"
                                   name="tags[]"
                                   value="{{ $tag->id }}"
                                   @checked(in_array(
                                       $tag->id,
                                       old('tags', [])
                                   ))
                                   class="h-4 w-4 accent-[#7a5534]">

                            <span class="text-sm font-medium
                                         text-[#7a5534]">
                                #{{ $tag->name }}
                            </span>

                        </label>

                    @endforeach

                </div>

                @error('tags')
                    <p class="mt-3 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

                @error('tags.*')
                    <p class="mt-3 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </fieldset>

            {{-- Publication status. --}}
            <div class="rounded-lg border border-[#e5dccd]
                        bg-[#f2f5e2] p-5">

                <h2 class="mb-3 font-semibold text-[#7a5534]">
                    Publication Options
                </h2>

                {{-- Hidden value ensures unchecked = 0. --}}
                <input type="hidden"
                       name="is_published"
                       value="0">

                <label class="flex cursor-pointer
                              items-start gap-3">

                    <input type="checkbox"
                           id="is_published"
                           name="is_published"
                           value="1"
                           @checked(old('is_published', '1') == '1')
                           class="mt-1 h-4 w-4 accent-[#7a5534]">

                    <span>
                        <span class="block font-medium text-[#7a5534]">
                            Publish immediately
                        </span>

                        <span class="mt-1 block text-sm text-gray-600">
                            Uncheck to save the announcement
                            as an unpublished draft.
                        </span>
                    </span>

                </label>

                @error('is_published')
                    <p class="mt-3 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>

            {{-- Submission actions. --}}
            <div class="flex flex-wrap gap-3
                        border-t border-[#e5dccd] pt-6">

                <button type="submit"
                        class="rounded-lg bg-[#d5954e]
                               px-6 py-3 font-semibold text-white
                               hover:bg-[#bf7d36]">
                    Save Announcement
                </button>

                <a href="{{ route('posts.index') }}"
                   class="rounded-lg bg-gray-200
                          px-6 py-3 font-semibold text-gray-800
                          hover:bg-gray-300">
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

@endsection
