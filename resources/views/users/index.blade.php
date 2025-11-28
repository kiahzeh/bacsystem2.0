<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                {{ __('Users') }}
            </h2>
            <a href="{{ route('users.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('Add User') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glassmorphism-card overflow-hidden sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <!-- Search Bar -->
                    <div class="mb-6" x-data="{ 
                        search: '{{ request('search') }}',
                        suggestions: [],
                        showSuggestions: false,
                        selectedIndex: -1,
                        
                        async fetchSuggestions() {
                            if (this.search.length < 1) {
                                this.suggestions = [];
                                this.showSuggestions = false;
                                return;
                            }
                            
                            try {
                                const response = await fetch(`/api/users/search?q=${encodeURIComponent(this.search)}`);
                                const data = await response.json();
                                this.suggestions = data.users || [];
                                this.showSuggestions = this.suggestions.length > 0;
                                this.selectedIndex = -1;
                            } catch (error) {
                                console.error('Error fetching suggestions:', error);
                                this.suggestions = [];
                                this.showSuggestions = false;
                            }
                        },
                        
                        selectSuggestion(user) {
                            this.search = user.name;
                            this.showSuggestions = false;
                            this.submitSearch();
                        },
                        
                        submitSearch() {
                            const form = document.getElementById('searchForm');
                            if (form) {
                                form.submit();
                            }
                        },
                        
                        handleKeydown(event) {
                            if (!this.showSuggestions) return;
                            
                            switch(event.key) {
                                case 'ArrowDown':
                                    event.preventDefault();
                                    this.selectedIndex = Math.min(this.selectedIndex + 1, this.suggestions.length - 1);
                                    break;
                                case 'ArrowUp':
                                    event.preventDefault();
                                    this.selectedIndex = Math.max(this.selectedIndex - 1, -1);
                                    break;
                                case 'Enter':
                                    event.preventDefault();
                                    if (this.selectedIndex >= 0 && this.suggestions[this.selectedIndex]) {
                                        this.selectSuggestion(this.suggestions[this.selectedIndex]);
                                    } else {
                                        this.submitSearch();
                                    }
                                    break;
                                case 'Escape':
                                    this.showSuggestions = false;
                                    this.selectedIndex = -1;
                                    break;
                            }
                        }
                    }" x-init="$watch('search', value => fetchSuggestions())">
                        <form method="GET" action="{{ route('users.index') }}" id="searchForm" class="flex items-center space-x-4">
                            <div class="flex-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input 
                                    type="text" 
                                    name="search" 
                                    x-model="search"
                                    @keydown="handleKeydown($event)"
                                    @click.away="showSuggestions = false"
                                    placeholder="Search users by name, email, username, department, or role..."
                                    class="glassmorphism-input w-full pl-10 pr-4 py-3 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent"
                                    value="{{ request('search') }}"
                                    autocomplete="off"
                                >
                                
                                <!-- Suggestions Dropdown -->
                                <div x-show="showSuggestions" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute z-50 w-full mt-1 bg-white/10 text-white backdrop-blur-xl rounded-lg shadow-2xl border border-white/20 max-h-60 overflow-y-auto">
                                    
                                    <template x-for="(user, index) in suggestions" :key="user.id">
                                        <div @click="selectSuggestion(user)"
                                             @mouseenter="selectedIndex = index"
                                             :class="{
                                                 'bg-violet-500/30 text-white': selectedIndex === index,
                                                 'hover:bg-violet-500/10 text-white': selectedIndex !== index
                                             }"
                                             class="px-4 py-3 cursor-pointer transition-colors duration-150 flex items-center space-x-3">
                                            
                                            <!-- User Avatar -->
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 rounded-full bg-violet-500/40 flex items-center justify-center text-white font-medium text-sm">
                                                    <span x-text="user.name.charAt(0).toUpperCase()"></span>
                                                </div>
                                            </div>
                                            
                                            <!-- User Info -->
                                            <div class="flex-1 min-w-0">
                                                <div class="font-medium" x-text="user.name"></div>
                                                <div class="text-sm opacity-75" x-text="user.email"></div>
                                                <div class="text-xs opacity-60" x-text="user.department_name || 'No Department'"></div>
                                            </div>
                                            
                                            <!-- Role Badge -->
                                            <div class="flex-shrink-0">
                                                <span :class="{
                                                    'bg-purple-500/20 text-purple-200': user.role === 'admin',
                                                    'bg-blue-500/20 text-blue-200': user.role === 'user'
                                                }" 
                                                      class="px-2 py-1 text-xs rounded-full font-medium"
                                                      x-text="user.role.charAt(0).toUpperCase() + user.role.slice(1)">
                                                </span>
                                            </div>
                                        </div>
                                    </template>
                                    
                                    <!-- No Results -->
                                    <div x-show="suggestions.length === 0 && search.length > 0" 
                                         class="px-4 py-3 text-white/70 text-center">
                                        No users found matching "<span x-text="search"></span>"
                                    </div>
                                </div>
                            </div>
                            <button 
                                type="submit"
                                class="glass-badge bg-violet-500/20 text-violet-200 px-6 py-2 rounded-full font-medium transition-colors duration-200 flex items-center hover:bg-violet-500/30"
                            >
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Search
                            </button>
                            @if(request('search'))
                                <a 
                                    href="{{ route('users.index') }}" 
                                    class="glass-badge bg-gray-500/20 text-gray-200 px-4 py-2 rounded-full font-medium transition-colors duration-200 flex items-center hover:bg-gray-500/30"
                                >
                                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Clear
                                </a>
                            @endif
                        </form>
                        
                        @if(request('search'))
                            <div class="mt-3 text-sm text-white/70">
                                <span class="font-medium">Search results for:</span> "{{ request('search') }}"
                                <span class="ml-2">({{ $users->total() }} {{ Str::plural('user', $users->total()) }} found)</span>
                            </div>
                        @endif
                    </div>

                    <div class="overflow-x-auto glassmorphism-card rounded-lg overflow-y-auto relative">
                        <table class="min-w-full table-auto border-collapse">
                            <thead>
                                <tr class="glassmorphism-header text-white uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-left font-semibold glass-table-heading">Name</th>
                                    <th class="py-3 px-6 text-left font-semibold glass-table-heading">Email</th>
                                    <th class="py-3 px-6 text-left font-semibold glass-table-heading">Department</th>
                                    <th class="py-3 px-6 text-left font-semibold glass-table-heading">Role</th>
                                    <th class="py-3 px-6 text-left font-semibold glass-table-heading">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-white text-sm">
                                @forelse($users as $user)
                                    <tr class="border-b border-gray-200 hover:bg-white/10 transition-all duration-150">
                                        <td class="py-4 px-6">
                                            <div class="flex items-center">
                                                @if($user->profile_picture && Storage::disk('public')->exists($user->profile_picture))
                                                    <img src="{{ Storage::url($user->profile_picture) }}" 
                                                         alt="Profile Picture" 
                                                         class="h-8 w-8 rounded-full object-cover mr-2 border border-white/20"
                                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-white hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-white"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                @endif
                                                <div>
                                                    <span class="glass-table-text text-white font-medium">{{ $user->name }}</span>
                                                    @if($user->username)
                                                        <div class="text-xs text-white/60">@{{ $user->username }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-white"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                <span class="glass-table-text text-white">{{ $user->email }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-white"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                                <span class="glass-table-text text-white">{{ $user->department ? $user->department->name : 'No Department' }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">
                                            @php
                                                $roleClass = $user->role === 'admin'
                                                    ? 'bg-purple-500/20 text-purple-200'
                                                    : 'bg-blue-500/20 text-blue-200';
                                            @endphp
                                            <span class="px-3 py-1 rounded-full text-sm font-semibold whitespace-nowrap glass-badge {{ $roleClass }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="flex items-center space-x-3">
                                                <a href="{{ route('users.edit', $user) }}" title="Edit" aria-label="Edit"
                                                    class="inline-flex items-center glass-badge bg-green-500/20 text-green-200 px-3 py-1 rounded-full transition-all duration-200 shadow-sm hover:bg-green-500/30">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    <span class="font-medium">Edit</span>
                                                </a>
                                                @if($user->id !== auth()->id())
                                                    <form action="{{ route('users.destroy', $user) }}" method="POST"
                                                        class="inline-flex items-center"
                                                        onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" title="Delete" aria-label="Delete"
                                                            class="inline-flex items-center glass-badge bg-red-500/20 text-red-200 px-2 py-1 rounded-full transition-all duration-200 shadow-sm hover:bg-red-500/30">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-white">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                                <p class="text-lg text-white">
                                                    @if(request('search'))
                                                        No users found matching "{{ request('search') }}".
                                                    @else
                                                        No users found.
                                                    @endif
                                                </p>
                                                @if(request('search'))
                                                    <a href="{{ route('users.index') }}" class="text-violet-300 hover:text-violet-100 mt-2">
                                                        Clear search and show all users
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>