<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }" x-init="
    if (darkMode) { document.documentElement.classList.add('dark'); }
    $watch('darkMode', value => {
        if (value) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        }
    })
">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Procurement Monitoring') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Custom Styles -->
    <style>
        /* Custom Scrollbar for Notification Dropdown */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(30, 58, 138, 0.5);
            border-radius: 3px;
            transition: background 0.2s ease;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(30, 58, 138, 0.7);
        }
        
        /* Firefox Scrollbar */
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: rgba(30, 58, 138, 0.5) rgba(255, 255, 255, 0.1);
        }
        
        /* Glassmorphism Dropdown Enhancement */
        .glassmorphism-dropdown {
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-800 via-blue-900 to-indigo-900 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 bg-fixed bg-cover bg-center bg-no-repeat">

        @include('layouts.navigation')

        <!-- Global Back Button -->
        <div class="mx-4 mt-16" style="position: relative; z-index: 9999;">
            <button type="button" onclick="window.history.back();" class="px-4 py-2 rounded-lg shadow-lg font-semibold text-white glassmorphism-navy transition hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-400" style="backdrop-filter: blur(16px); background: rgba(30, 58, 138, 0.7); border: 1px solid rgba(255,255,255,0.2);">
                &#8592; Back
            </button>
        </div>

        <!-- Page Heading -->
        @if (isset($header))
        <header class="glassmorphism-header relative z-40 mt-16 rounded-xl mx-4">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                    
                </div>
            </header>

        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

</body>

</html>
