<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Barangay Bulletin – Purok Dimatulac')
    </title>

    <!-- Reuse the existing Tailwind and Vite setup. -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex flex-col
             bg-[#f2f5e2] text-[#7a5534] font-sans">

    <!-- Shared brown navigation bar. -->
    <nav class="bg-[#7a5534] text-white p-4">

        <div class="max-w-6xl mx-auto flex flex-wrap
                    justify-between items-center gap-4">

            <!-- Site branding. -->
            <a href="{{ route('posts.index') }}"
               class="text-xl font-bold">
                Barangay Bulletin
            </a>

            <div class="flex flex-wrap items-center gap-4">

                <!-- Public homepage link. -->
                <a href="{{ route('posts.index') }}"
                   class="hover:text-[#f2f5e2]">
                    Home
                </a>

                <!-- Visible only to logged-in users. -->
                @auth

                    <span class="text-sm text-[#f2f5e2]">
                        Hello, {{ auth()->user()->name }}
                    </span>

                    <!-- Logout must use POST for security. -->
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

                <!-- Visible only to guests. -->
                @guest

                    <a href="{{ route('login') }}"
                       class="rounded-lg bg-[#d5954e]
                              px-4 py-2 text-sm font-semibold
                              hover:bg-[#bf7d36]">
                        Login
                    </a>

                @endguest

            </div>
        </div>
    </nav>

    <!-- Flash message after login or logout. -->
    @if (session('success'))
        <div class="max-w-6xl w-full mx-auto px-6 pt-5">

            <div class="rounded-lg border border-green-200
                        bg-green-50 px-5 py-4
                        text-green-800"
                 role="status">

                {{ session('success') }}

            </div>
        </div>
    @endif

    <!-- Individual page content. -->
    <main class="max-w-6xl mx-auto w-full p-6 flex-grow">
        @yield('content')
    </main>

    <!-- Preserve the existing footer. -->
    <footer class="border-t border-[#e5dccd]
                   bg-white text-[#8a765e]
                   text-center p-4">

        <p>
            &copy; {{ date('Y') }}
            Barangay Bulletin – Purok Dimatulac
        </p>

    </footer>

</body>
</html>
