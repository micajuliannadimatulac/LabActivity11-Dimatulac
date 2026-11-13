@extends('layouts.app')

@section('title', 'Login | Barangay Bulletin')

@section('content')

<div class="max-w-md mx-auto mt-10 mb-12">

    <!-- Login card using the existing cream/brown theme. -->
    <div class="bg-white rounded-2xl shadow-lg
                border border-[#e5dccd] overflow-hidden">

        <!-- Card header. -->
        <div class="bg-[#7a5534] text-white px-8 py-7 text-center">

            <h1 class="text-2xl font-bold">
                Barangay Bulletin
            </h1>

            <p class="text-sm text-[#f2f5e2] mt-2">
                Purok Dimatulac
            </p>

        </div>

        <div class="p-8">

            <h2 class="text-xl font-bold text-[#7a5534] mb-2">
                Sign in
            </h2>

            <p class="text-sm text-[#8a765e] mb-6">
                Access your barangay account to participate
                in community discussions.
            </p>

            <!-- Display validation and login errors. -->
            @if ($errors->any())
                <div class="mb-5 p-4 rounded-lg
                            bg-red-50 border border-red-200
                            text-red-700 text-sm"
                     role="alert">

                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>
            @endif

            <!-- POST form is protected by Laravel CSRF. -->
            <form method="POST"
                  action="{{ route('login.store') }}"
                  class="space-y-5">

                @csrf

                <!-- Email address. -->
                <div>
                    <label for="email"
                           class="block text-sm font-semibold mb-2">
                        Email address
                    </label>

                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        required
                        autofocus
                        class="w-full px-4 py-3 rounded-lg
                               border border-[#d9cbb8]
                               bg-[#fffdf8] text-[#7a5534]
                               focus:outline-none
                               focus:ring-2 focus:ring-[#b99155]"
                        placeholder="Enter your email">
                </div>

                <!-- Password is never repopulated. -->
                <div>
                    <label for="password"
                           class="block text-sm font-semibold mb-2">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        id="password"
                        autocomplete="current-password"
                        required
                        class="w-full px-4 py-3 rounded-lg
                               border border-[#d9cbb8]
                               bg-[#fffdf8] text-[#7a5534]
                               focus:outline-none
                               focus:ring-2 focus:ring-[#b99155]"
                        placeholder="Enter your password">
                </div>

                <button
                    type="submit"
                    class="w-full bg-[#7a5534] text-white
                           font-semibold py-3 rounded-lg
                           hover:bg-[#5e3e25] transition">
                    Sign in
                </button>

            </form>

            <p class="text-center text-sm text-[#8a765e] mt-6">
                Community announcements are available
                to everyone, even without an account.
            </p>

            <a href="{{ route('posts.index') }}"
               class="block text-center mt-4 text-sm
                      font-semibold text-[#7a5534]
                      hover:underline">
                Back to announcements
            </a>

        </div>
    </div>
</div>

@endsection
