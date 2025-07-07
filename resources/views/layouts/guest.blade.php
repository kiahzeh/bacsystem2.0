<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="https://unpkg.com/feather-icons"></script>

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Dark Mode Toggle Script -->
        <script>
            // Check for dark mode in localStorage
            if (localStorage.getItem('theme') === 'dark' ||
                (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            // Toggle function
            function toggleDarkMode() {
                const html = document.documentElement;
                if (html.classList.contains('dark')) {
                    html.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    html.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
            }
        </script>
    </head>

    <body class="bg-gray-100 text-black dark:bg-gray-900 dark:text-gray-100">

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">

<!-- Dark Mode Toggle Button with Icons -->
<div class="absolute top-4 right-4">
    <button onclick="toggleDarkMode()" id="themeToggle" class="flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-sm font-semibold rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition">
        <svg id="sunIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-12.66l-.707.707M4.05 19.95l-.707-.707M21 12h-1M4 12H3m16.66 4.66l-.707-.707M4.05 4.05l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>

        <svg id="moonIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3a7 7 0 109.79 9.79z" />
        </svg>

        Toggle Theme
    </button>
</div>


            <!-- Logo -->
            <div class="flex justify-center mb-6">
                <a href="/">
                    <img src="{{ asset('images/logo.png') }}" alt="My Logo" class="h-24 w-auto">
                </a>
            </div>

            <!-- Slot content -->
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-black dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>

        <script>
    const html = document.documentElement;
    const moonIcon = document.getElementById('moonIcon');
    const sunIcon = document.getElementById('sunIcon');

    function updateIcons() {
        if (html.classList.contains('dark')) {
            moonIcon.classList.add('hidden');
            sunIcon.classList.remove('hidden');
        } else {
            moonIcon.classList.remove('hidden');
            sunIcon.classList.add('hidden');
        }
    }

    function toggleDarkMode() {
        html.classList.toggle('dark');
        localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
        updateIcons();
    }

    // Run after DOM is ready
    document.addEventListener('DOMContentLoaded', function () {
        if (localStorage.getItem('theme') === 'dark' ||
            (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            html.classList.add('dark');
        } else {
            html.classList.remove('dark');
        }

        updateIcons(); // Now this runs when icons are ready
    });
</script>

        
    </body>
</html>
