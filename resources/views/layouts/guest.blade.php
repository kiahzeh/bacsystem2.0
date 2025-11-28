<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="https://unpkg.com/feather-icons"></script>

        <title>{{ config('app.name', 'Procurement') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Dark Mode Toggle Script -->
        <script>
            // Initial theme check based on localStorage and system preference
            if (localStorage.getItem('theme') === 'dark' ||
                (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            // Toggle function handled in the bottom script for consistency
        </script>
    </head>

    <body class="bg-gray-50 text-gray-900 dark:bg-gray-950 dark:text-gray-100">

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">

<!-- Dark Mode Toggle Button with Icons -->
<div class="absolute top-4 right-4">
    <button onclick="toggleDarkMode()" id="themeToggle" class="p-2 rounded-full glassmorphism-button transition duration-200 transform hover:scale-105 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-0 bg-white text-gray-800" aria-label="Toggle Dark Mode">
        <svg id="sunIcon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true" focusable="false">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v2m0 12v2m4.66-10.66l-1.414 1.414M6.757 17.243l-1.414 1.414M20 12h-2M6 12H4m12.243 5.243l-1.414-1.414M7.171 7.171L5.757 5.757" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>

        <svg id="moonIcon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true" focusable="false">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3a7 7 0 109.79 9.79z" />
        </svg>
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
    const themeToggleBtn = document.getElementById('themeToggle');

    function updateIcons() {
        if (html.classList.contains('dark')) {
            moonIcon.classList.remove('hidden');
            sunIcon.classList.add('hidden');
            themeToggleBtn.classList.remove('bg-white','text-gray-800','hover:ring-blue-500/40');
            themeToggleBtn.classList.add('bg-gray-800','text-yellow-400','hover:ring-yellow-400/40');
        } else {
            moonIcon.classList.add('hidden');
            sunIcon.classList.remove('hidden');
            themeToggleBtn.classList.remove('bg-gray-800','text-yellow-400','hover:ring-yellow-400/40');
            themeToggleBtn.classList.add('bg-white','text-gray-800','hover:ring-blue-500/40');
        }
    }

    function toggleDarkMode() {
        html.classList.toggle('dark');
        localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
        updateIcons();
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (localStorage.getItem('theme') === 'dark' ||
            (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            html.classList.add('dark');
        } else {
            html.classList.remove('dark');
        }

        updateIcons();
    });
</script>

        
    </body>
</html>
