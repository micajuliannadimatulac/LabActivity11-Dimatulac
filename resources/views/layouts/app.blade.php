<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Barangay Bulletin – Purok Dimatulac')</title>

    <!-- Reuse the Tailwind and Vite setup. -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex flex-col
             bg-[#f2f5e2] text-[#7a5534] font-sans">

    <!-- Brown navigation from Lab 9. -->
    <nav class="bg-[#7a5534] text-white p-4">
        <div class="max-w-6xl mx-auto flex
                    justify-between items-center">

            <a href="{{ route('posts.index') }}"
               class="text-xl font-bold">
                Barangay Bulletin
            </a>

            <a href="{{ route('posts.index') }}"
               class="hover:text-[#f2f5e2]">
                Home
            </a>
        </div>
    </nav>

    <!-- Content from the individual Blade pages. -->
    <main class="max-w-6xl mx-auto w-full p-6 flex-grow">
        @yield('content')
    </main>

    <!-- Light footer matching Lab 9. -->
    <footer class="border-t border-[#e5dccd]
                   bg-white text-[#8a765e] text-center p-4">
        <p>
            &copy; {{ date('Y') }}
            Barangay Bulletin – Purok Dimatulac
        </p>
    </footer>

</body>
</html>
