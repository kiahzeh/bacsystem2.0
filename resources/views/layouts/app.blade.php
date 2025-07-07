<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'BAC') }}</title>

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
            background: rgba(139, 92, 246, 0.5);
            border-radius: 3px;
            transition: background 0.2s ease;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(139, 92, 246, 0.7);
        }
        
        /* Firefox Scrollbar */
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: rgba(139, 92, 246, 0.5) rgba(255, 255, 255, 0.1);
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

<body class="font-sans antialiased">
<div class="min-h-screen bg-fixed bg-cover bg-center bg-no-repeat overflow-hidden" style="background-image: linear-gradient(to bottom right, #8b5cf6, #a855f7, #7c3aed);">


        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
        <header class="glassmorphism-header relative z-40 mt-16 rounded-xl mx-4">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                    
                </div>
            </header>
            <li>
</li>

        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</body>

</html>
