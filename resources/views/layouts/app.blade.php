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

<body class="min-h-screen bg-gradient-to-br from-blue-800 via-blue-900 to-indigo-900 dark:from-gray-950 dark:via-gray-900 dark:to-gray-950 bg-fixed bg-cover bg-center bg-no-repeat">

        @include('layouts.navigation')

        <!-- Global Back Button (only when header is not present) -->
        @if (!isset($header))
        <div class="mx-4 mt-16 inline-block" style="position: relative; z-index: 10; pointer-events: none;">
            <button type="button" onclick="window.history.back();" class="px-2 py-1 sm:px-2.5 sm:py-1.5 rounded-md text-xs sm:text-sm font-medium text-white bg-transparent hover:bg-white/10 hover:underline transition focus:outline-none focus:ring-1 focus:ring-blue-300" style="pointer-events: auto;">
                &lsaquo; Back
            </button>
        </div>
        @endif

        <!-- Page Heading -->
        @if (isset($header))
        <header class="glassmorphism-header relative z-40 mt-16 rounded-xl mx-4">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <button type="button" onclick="window.history.back();" class="px-2 py-1 sm:px-2.5 sm:py-1.5 rounded-md text-xs sm:text-sm font-medium text-white bg-transparent hover:bg-white/10 hover:underline transition focus:outline-none focus:ring-1 focus:ring-blue-300">
                            &lsaquo; Back
                        </button>
                        <div class="flex-1">
                            {{ $header }}
                        </div>
                        <a href="{{ route('profile.edit') }}" class="shrink-0 inline-block rounded-full focus:outline-none focus:ring-1 focus:ring-blue-300" aria-label="Open Profile Settings" title="Profile Settings">
                            @php
                                $avatar = Auth::user()->profile_picture;
                                $avatarUrl = null;
                                if ($avatar) {
                                    if (\Illuminate\Support\Str::startsWith($avatar, ['http://', 'https://'])) {
                                        $avatarUrl = $avatar;
                                    } elseif (Storage::disk('public')->exists($avatar)) {
                                        $avatarUrl = Storage::url($avatar);
                                    } elseif (\Illuminate\Support\Str::startsWith($avatar, ['images/', '/images/'])) {
                                        $avatarUrl = asset(ltrim($avatar, '/'));
                                    }
                                }
                            @endphp

                            @if($avatarUrl)
                                <img src="{{ $avatarUrl }}" 
                                     alt="Profile Picture" 
                                     class="h-8 w-8 sm:h-9 sm:w-9 rounded-full object-cover border border-white/20"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 sm:h-9 sm:w-9 rounded-full bg-white/10 text-white hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <circle cx="12" cy="7" r="4" stroke-width="2"></circle>
                                    <path d="M4 21c0-4 4-7 8-7s8 3 8 7" stroke-width="2"></path>
                                </svg>
                            @else
                                <div class="h-8 w-8 sm:h-9 sm:w-9 rounded-full bg-white/10 border border-white/20"></div>
                            @endif
                        </a>
                    </div>
                </div>
            </header>

        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

</body>

</html>
