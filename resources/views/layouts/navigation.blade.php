<nav x-data="{ open: false }" class="glassmorphism-navbar fixed top-0 w-full z-50" data-notifications-index-url="{{ route('notifications.index') }}" data-notifications-mark-all-url="{{ route('notifications.mark-all-as-read') }}">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <img src="{{asset('images/logo.png') }}" alt="Procurement Monitoring Logo" class="h-12 w-auto">
                        <span class="ml-3 text-xl font-bold text-white">Procurement Monitoring</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="nav-link-glass">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    
                    @if(auth()->user()->isAdmin())
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')" class="nav-link-glass">
                            {{ __('Users') }}
                        </x-nav-link>
                        <x-nav-link :href="route('departments.index')" :active="request()->routeIs('departments.*')" class="nav-link-glass">
                            {{ __('Departments') }}
                        </x-nav-link>
                        <x-nav-link :href="route('processes.index')" :active="request()->routeIs('processes.*')" class="nav-link-glass">
                            {{ __('Processes') }}
                        </x-nav-link>
                    @endif
                    
                    <x-nav-link :href="route('purchase-requests.index')" :active="request()->routeIs('purchase-requests.*')" class="nav-link-glass">
                        {{ __('Purchase Requests') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Right side elements with proper spacing -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                <!-- Notifications -->
                <div class="relative" 
                    x-data="notificationDropdown()" 
                    x-init="init()"
                    @click.away="open = false">
                    <button @click="open = !open; if (open) fetchNotifications()" class="flex items-center text-white hover:text-blue-200 transition-colors p-2 rounded-lg hover:bg-white/10">
                        <span class="relative inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full min-w-[20px] h-5"></span>
                        </span>
                    </button>

                    <div x-show="open"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-3 w-80 glassmorphism-dropdown rounded-xl shadow-2xl py-2 z-50 border border-white/20 backdrop-blur-xl">
                        
                        <!-- Header -->
                        <div class="px-4 py-3 border-b border-white/20 flex justify-between items-center bg-gradient-to-r from-blue-600/30 to-blue-700/30 rounded-t-xl">
                            <div class="flex items-center space-x-2">
                                                                  <svg class="w-4 h-4 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <h3 class="text-base font-bold text-white">Notifications</h3>
                                <span x-show="unreadCount > 0" x-text="`(${unreadCount})`" class="text-xs font-medium text-blue-300 bg-blue-500/40 px-2 py-0.5 rounded-full"></span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button @click="fetchNotifications()" 
                                        class="text-xs text-blue-300 hover:text-blue-100 bg-blue-500/20 hover:bg-blue-500/30 px-2 py-1 rounded transition-colors font-medium">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                </button>
                                <button x-show="unreadCount > 0" @click="markAllAsRead()" 
                                        class="text-xs text-blue-300 hover:text-blue-100 bg-blue-500/30 hover:bg-blue-500/40 px-2 py-1 rounded transition-colors font-medium">
                                    Mark all read
                                </button>
                            </div>
                        </div>

                        <!-- Notifications List -->
                        <div class="max-h-64 overflow-y-auto custom-scrollbar">
                            <!-- Loading State -->
                            <div x-show="loading" class="px-4 py-6 text-center">
                                <div class="inline-flex items-center space-x-2 text-white/70">
                                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span class="text-xs font-medium">Loading...</span>
                                </div>
                            </div>

                            <!-- Notifications -->
                            <template x-if="!loading && notifications.length > 0">
                                <div class="space-y-1">
                                    <template x-for="notification in notifications" :key="notification.id">
                                        <div class="px-4 py-3 hover:bg-white/5 transition-colors cursor-pointer" 
                                             @click="markAsRead(notification.id); if (notification.action_url) window.location.href = notification.action_url;">
                                            <div class="flex items-start space-x-3">
                                                <div class="flex-shrink-0">
                                                    <div class="w-2 h-2 rounded-full" 
                                                         :class="notification.read ? 'bg-gray-400' : 'bg-blue-400'"></div>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm text-white leading-5" x-text="notification.message"></p>
                                                    <p class="text-xs text-white/60 mt-1" x-text="notification.created_at"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <!-- Empty State -->
                            <template x-if="!loading && notifications.length === 0">
                                <div class="px-4 py-6 text-center">
                                    <div class="text-white/50">
                                        <svg class="mx-auto h-8 w-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                        </svg>
                                        <p class="text-sm font-medium">No notifications</p>
                                        <p class="text-xs">You're all caught up!</p>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Footer -->
                        <div class="px-4 py-2 border-t border-white/20">
                            <a href="#" class="text-xs text-blue-300 hover:text-blue-100 font-medium transition-colors">
                                View all notifications →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Theme Toggle Button -->
                <div>
                    <button
                        @click="darkMode = !darkMode"
                        class="p-2 rounded-full glassmorphism-button transition duration-200 transform hover:scale-105 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-0"
                        :class="darkMode ? 'bg-gray-800 text-yellow-400 hover:ring-yellow-400/40' : 'bg-white text-gray-800 hover:ring-blue-500/40'"
                        aria-label="Toggle Dark Mode"
                    >
                        <!-- Sun Icon (Light) -->
                        <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true" focusable="false">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v2m0 12v2m4.66-10.66l-1.414 1.414M6.757 17.243l-1.414 1.414M20 12h-2M6 12H4m12.243 5.243l-1.414-1.414M7.171 7.171L5.757 5.757" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <!-- Moon Icon (Dark) -->
                        <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z" />
                        </svg>
                    </button>
                </div>

                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <div @click="open = !open">
                        <button class="inline-flex items-center px-3 py-2 border border-white/20 text-sm leading-4 font-medium rounded-md text-white bg-white/10 hover:bg-white/20 focus:outline-none transition ease-in-out duration-150 backdrop-blur-sm cursor-pointer">
                            <div class="flex items-center">
                                @if(Auth::user()->profile_picture && Storage::disk('public')->exists(Auth::user()->profile_picture))
                                    <img src="{{ Storage::url(Auth::user()->profile_picture) }}" 
                                         alt="Profile Picture" 
                                         class="h-8 w-8 rounded-full object-cover mr-2 border border-white/20"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                @endif
                                <div class="text-left">
                                    <div class="font-medium">{{ Auth::user()->name }}</div>
                                    @if(Auth::user()->username)
                                        <div class="text-xs text-white/70">@{{ Auth::user()->username }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </div>

                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-56 rounded-md shadow-lg glassmorphism-dropdown"
                         style="display: none; z-index: 9999;">
                        <div class="py-1 rounded-md ring-1 ring-white/20">
                            <!-- Profile Link -->
                            <a href="{{ route('profile.edit') }}" 
                               class="block w-full px-4 py-3 text-start text-sm leading-5 text-white hover:bg-white/10 focus:outline-none focus:bg-white/10 transition duration-150 ease-in-out flex items-center cursor-pointer border-b border-white/10"
                               @click="open = false">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <div>
                                    <div class="font-medium">Profile Settings</div>
                                    <div class="text-xs text-white/60">Manage your account</div>
                                </div>
                            </a>

                            <!-- Settings Link -->
                            <a href="#" 
                               class="block w-full px-4 py-3 text-start text-sm leading-5 text-white hover:bg-white/10 focus:outline-none focus:bg-white/10 transition duration-150 ease-in-out flex items-center cursor-pointer border-b border-white/10"
                               @click="open = false">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <div>
                                    <div class="font-medium">Settings</div>
                                    <div class="text-xs text-white/60">App preferences</div>
                                </div>
                            </a>

                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); this.closest('form').submit();"
                                   class="block w-full px-4 py-3 text-start text-sm leading-5 text-white hover:bg-white/10 focus:outline-none focus:bg-white/10 transition duration-150 ease-in-out flex items-center cursor-pointer"
                                   @click="open = false">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    <div>
                                        <div class="font-medium text-red-400">Log Out</div>
                                        <div class="text-xs text-white/60">Sign out of your account</div>
                                    </div>
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-blue-200 hover:bg-white/10 focus:outline-none focus:bg-white/10 focus:text-blue-200 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            
            @if(auth()->user()->isAdmin())
                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                    {{ __('Users') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('departments.index')" :active="request()->routeIs('departments.*')">
                    {{ __('Departments') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('processes.index')" :active="request()->routeIs('processes.*')">
                    {{ __('Processes') }}
                </x-responsive-nav-link>
            @endif
            
            <x-responsive-nav-link :href="route('purchase-requests.index')" :active="request()->routeIs('purchase-requests.*')">
                {{ __('Purchase Requests') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-white/20">
            <div class="px-4">
                <div class="flex items-center">
                    @if(Auth::user()->profile_picture && Storage::disk('public')->exists(Auth::user()->profile_picture))
                        <img src="{{ Storage::url(Auth::user()->profile_picture) }}" 
                             alt="Profile Picture" 
                             class="h-10 w-10 rounded-full object-cover mr-3 border border-white/20"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mr-3 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    @endif
                    <div>
                        <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                        @if(Auth::user()->username)
                            <div class="font-medium text-sm text-white/70">@{{ Auth::user()->username }}</div>
                        @endif
                        <div class="font-medium text-sm text-white/50">{{ Auth::user()->email }}</div>
                    </div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
function notificationDropdown() {
    // Read route URLs from the nav element to avoid Blade in JS
    const navEl = document.querySelector('.glassmorphism-navbar');
    const notificationsIndexUrl = navEl?.dataset.notificationsIndexUrl || '/notifications';
    const notificationsMarkAllUrl = navEl?.dataset.notificationsMarkAllUrl || '/notifications/mark-all-as-read';

    return {
        open: false,
        notifications: [],
        unreadCount: 0,
        loading: false,
        controller: null,
        
        async init() {
            window.addEventListener('beforeunload', () => {
                try { this.controller?.abort(); } catch (_) {}
            });
            // Poll only when dropdown is open to reduce aborted fetch noise
            setInterval(() => { if (this.open) this.fetchNotifications(); }, 30000);
        },
        
        async fetchNotifications() {
            // Skip fetching if page is being hidden/unloaded to avoid abort noise
            if (document.visibilityState === 'hidden') return;
            
            try {
                this.loading = true;
                
                // Abort any in-flight request before starting a new one
                try { this.controller?.abort(); } catch (_) {}
                this.controller = new AbortController();
                
                const response = await fetch(notificationsIndexUrl, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    credentials: 'same-origin',
                    signal: this.controller.signal
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                
                this.notifications = data.notifications || [];
                this.unreadCount = data.unreadCount || 0;
                
            } catch (error) {
                // Ignore aborts triggered by navigation/unload
                if (error?.name === 'AbortError') return;
                // Fail silently to avoid noisy console during navigations
                this.notifications = [];
                this.unreadCount = 0;
            } finally {
                this.loading = false;
            }
        },
        
        async markAsRead(id) {
            try {
                const response = await fetch(`/notifications/${id}/mark-as-read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    credentials: 'same-origin'
                });
                if (response.ok) {
                    await this.fetchNotifications();
                }
            } catch (error) {
                if (error?.name === 'AbortError') return;
                console.warn('Error marking notification as read:', error?.message || error);
            }
        },
        
        async markAllAsRead() {
            try {
                const response = await fetch(notificationsMarkAllUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    credentials: 'same-origin'
                });
                if (response.ok) {
                    await this.fetchNotifications();
                }
            } catch (error) {
                if (error?.name === 'AbortError') return;
                console.warn('Error marking all notifications as read:', error?.message || error);
            }
        }
    }
}
</script>
