<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Barangay Bulletin - Purok Dimatulac')
    </title>

    {{-- Preserve the existing Tailwind and Vite setup. --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex flex-col
             bg-[#f2f5e2] text-[#7a5534] font-sans">

    {{-- Shared brown navigation bar. --}}
    <nav class="bg-[#7a5534] p-4 text-white">

        <div class="mx-auto flex max-w-6xl
                    flex-wrap items-center
                    justify-between gap-4">

            {{-- Site branding. --}}
            <a href="{{ route('posts.index') }}"
               class="text-xl font-bold">
                Barangay Bulletin
            </a>

            <div class="flex flex-wrap items-center gap-4">

                {{-- Public bulletin link. --}}
                <a href="{{ route('posts.index') }}"
                   class="hover:text-[#f2f5e2]">
                    Home
                </a>

                {{-- Authenticated account navigation. --}}
                @auth

                    {{-- Only linked authors can create posts. --}}
                    @if (auth()->user()->author()->exists())

                        <a href="{{ route('posts.create') }}"
                           class="rounded-lg bg-[#d5954e]
                                  px-4 py-2 text-sm font-semibold
                                  text-white hover:bg-[#bf7d36]">
                            + New Announcement
                        </a>

                    @endif

                    <span class="text-sm text-[#f2f5e2]">
                        Hello, {{ auth()->user()->name }}
                    </span>

                    {{-- Secure logout uses POST and CSRF. --}}
                    <form method="POST"
                          action="{{ route('logout') }}">

                        @csrf

                        <button type="submit"
                                class="rounded-lg bg-[#d5954e]
                                       px-4 py-2 text-sm
                                       font-semibold text-white
                                       hover:bg-[#bf7d36]">
                            Logout
                        </button>

                    </form>

                @endauth

                {{-- Guest navigation. --}}
                @guest

                    <a href="{{ route('login') }}"
                       class="rounded-lg bg-[#d5954e]
                              px-4 py-2 text-sm font-semibold
                              text-white hover:bg-[#bf7d36]">
                        Login
                    </a>

                @endguest

            </div>

        </div>

    </nav>

    {{-- Success message from controllers. --}}
    @if (session('success'))

        <div class="mx-auto w-full max-w-6xl px-6 pt-5">

            <div class="rounded-lg border border-green-200
                        bg-green-50 px-5 py-4 text-green-800"
                 role="status">

                {{ session('success') }}

            </div>

        </div>

    @endif

    {{-- Individual page content. --}}
    <main class="mx-auto w-full max-w-6xl
                 flex-grow p-6">

        @yield('content')

    </main>

    {{-- Existing footer. --}}
    <footer class="border-t border-[#e5dccd]
                   bg-white p-4 text-center
                   text-[#8a765e]">

        <p>
            &copy; {{ date('Y') }}
            Barangay Bulletin - Purok Dimatulac
        </p>

    </footer>

</body>
</html>
