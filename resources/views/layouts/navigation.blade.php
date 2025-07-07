<nav x-data="{ open: false }" class="glassmorphism-navbar fixed top-0 w-full z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <img src="{{asset('images/logo.png') }}" alt="BAC Logo" class="h-12 w-auto">
                        <span class="ml-3 text-xl font-bold text-white">BAC Procurement</span>
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

            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                <!-- Notifications -->
                <div class="relative" 
                    x-data="notificationDropdown()" 
                    x-init="init()"
                    @click.away="open = false">
                    <button @click="open = !open" class="flex items-center text-white hover:text-violet-200 transition-colors p-2 rounded-lg hover:bg-white/10">
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
                        <div class="px-4 py-3 border-b border-white/20 flex justify-between items-center bg-gradient-to-r from-violet-500/30 to-purple-500/30 rounded-t-xl">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-violet-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <h3 class="text-base font-bold text-white">Notifications</h3>
                                <span x-show="unreadCount > 0" x-text="`(${unreadCount})`" class="text-xs font-medium text-violet-300 bg-violet-500/40 px-2 py-0.5 rounded-full"></span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button @click="fetchNotifications()" 
                                        class="text-xs text-violet-300 hover:text-violet-100 bg-violet-500/20 hover:bg-violet-500/30 px-2 py-1 rounded transition-colors font-medium">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                </button>
                                <button x-show="unreadCount > 0" @click="markAllAsRead()" 
                                        class="text-xs text-violet-300 hover:text-violet-100 bg-violet-500/30 hover:bg-violet-500/40 px-2 py-1 rounded transition-colors font-medium">
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
                                        <div class="px-4 py-3 hover:bg-white/10 transition-all duration-200 border-l-4" 
                                             :class="{ 
                                                 'border-l-violet-400 bg-violet-500/25': !notification.read,
                                                 'border-l-gray-400 bg-transparent': notification.read 
                                             }">
                                            <div class="flex items-start space-x-2">
                                                <!-- Status Indicator -->
                                                <div class="flex-shrink-0 mt-1">
                                                    <div class="w-1.5 h-1.5 rounded-full" 
                                                         :class="{ 
                                                             'bg-violet-400 animate-pulse': !notification.read,
                                                             'bg-gray-400': notification.read 
                                                         }"></div>
                                                </div>
                                                
                                                <!-- Content -->
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-xs font-medium text-white leading-tight" x-text="notification.message"></p>
                                                    <p class="text-xs text-white/60 mt-1 font-medium" x-text="notification.created_at"></p>
                                                    
                                                    <!-- Action Link -->
                                                    <template x-if="notification.action_url">
                                                        <a :href="notification.action_url" 
                                                           class="inline-flex items-center mt-2 text-xs font-medium text-violet-300 hover:text-violet-100 transition-colors group">
                                                            <span>View Details</span>
                                                            <svg class="w-3 h-3 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                            </svg>
                                                        </a>
                                                    </template>
                                                </div>
                                                
                                                <!-- Mark as Read Button -->
                                                <button x-show="!notification.read" 
                                                        @click="markAsRead(notification.id)" 
                                                        class="flex-shrink-0 text-xs text-violet-300 hover:text-violet-100 bg-violet-500/20 hover:bg-violet-500/30 px-2 py-1 rounded transition-colors font-medium">
                                                    Mark read
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <!-- Empty State -->
                            <template x-if="!loading && notifications.length === 0">
                                <div class="px-4 py-8 text-center">
                                    <svg class="w-8 h-8 text-white/30 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    <p class="text-white/60 font-medium text-sm">No notifications yet</p>
                                    <p class="text-white/40 text-xs mt-1">You're all caught up!</p>
                                </div>
                            </template>
                        </div>

                        <!-- Footer -->
                        <div class="px-4 py-2 border-t border-white/20 bg-gradient-to-r from-gray-500/30 to-gray-600/30 rounded-b-xl">
                            <a href="#" class="text-xs text-violet-300 hover:text-violet-100 font-medium transition-colors">
                                View all notifications →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Settings Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-white/20 text-sm leading-4 font-medium rounded-md text-white bg-white/10 hover:bg-white/20 focus:outline-none transition ease-in-out duration-150 backdrop-blur-sm">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>{{ Auth::user()->name }}</span>
                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();"
                                    class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-violet-200 hover:bg-white/10 focus:outline-none focus:bg-white/10 focus:text-violet-200 transition duration-150 ease-in-out">
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
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
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
    return {
        open: false,
        notifications: [],
        unreadCount: 0,
        loading: false,
        
        async init() {
            console.log('Initializing notifications...');
            await this.fetchNotifications();
            setInterval(() => this.fetchNotifications(), 30000);
        },
        
        async fetchNotifications() {
            try {
                this.loading = true;
                console.log('Fetching notifications...');
                
                const response = await fetch('{{ route('notifications.index') }}', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    credentials: 'same-origin'
                });
                
                console.log('Response status:', response.status);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                console.log('Raw API response:', data);
                console.log('Notifications array:', data.notifications);
                console.log('Unread count:', data.unreadCount);
                
                this.notifications = data.notifications || [];
                this.unreadCount = data.unreadCount || 0;
                
                console.log('Updated Alpine data:');
                console.log('- notifications array length:', this.notifications.length);
                console.log('- unreadCount:', this.unreadCount);
                console.log('- notifications content:', this.notifications);
                
            } catch (error) {
                console.error('Error fetching notifications:', error);
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
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                });
                if (response.ok) {
                    await this.fetchNotifications();
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        },
        
        async markAllAsRead() {
            try {
                const response = await fetch('{{ route('notifications.mark-all-as-read') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                });
                if (response.ok) {
                    await this.fetchNotifications();
                }
            } catch (error) {
                console.error('Error marking all notifications as read:', error);
            }
        }
    }
}
</script>
