<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <style>
        body {
            background-color: #f8fafc;
        }

        .collapse {
            display: none;
        }

        .collapse.show {
            display: block;
        }

        /* Glassmorphism Effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.15);
        }

        .glass-input {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem;
            color: #1f2937;
            font-weight: 500;
            transition: all 0.2s ease-in-out;
        }

        .glass-input:focus {
            outline: none;
            border-color: rgba(59, 130, 246, 0.5);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            background: rgba(255, 255, 255, 0.95);
        }

        .glass-input::placeholder {
            color: #6b7280;
        }

        /* Text Readability for Glassmorphism */
        .glass-text {
            color: white;
            font-weight: 500;
        }

        .glass-heading {
            color: white;
            font-weight: 600;
        }

        .glass-table-text {
            color: white;
        }

        .glass-table-heading {
            color: white;
            font-weight: 600;
        }
    </style>

    <div class="min-h-screen bg-gradient-to-br from-violet-600 via-purple-600 to-indigo-700 overflow-hidden">

    @if(auth()->user()->isAdmin())
        <!-- ADMIN DASHBOARD -->
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <!-- Welcome Section -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-white mb-2">Welcome back, {{ auth()->user()->name }}! 👋</h1>
                    <p class="text-white">Here's an overview of your procurement system today.</p>
                </div>

                <!-- Admin Overview Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="glass-card rounded-lg p-6 border-l-4 border-blue-500">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-white">Total PRs</p>
                                <p class="text-2xl font-bold text-white">{{ $totalPRs ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="glass-card rounded-lg p-6 border-l-4 border-green-500">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-white">Alternative Bids</p>
                                <p class="text-2xl font-bold text-white">{{ $alternativeCount ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="glass-card rounded-lg p-6 border-l-4 border-yellow-500">
                        <div class="flex items-center">
                            <div class="p-2 bg-yellow-100 rounded-lg">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-white">Competitive Bids</p>
                                <p class="text-2xl font-bold text-white">{{ $competitiveCount ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="glass-card rounded-lg p-6 border-l-4 border-purple-500">
                        <div class="flex items-center">
                            <div class="p-2 bg-purple-100 rounded-lg">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2M7 7h10"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-white">Consolidated PRs</p>
                                <p class="text-2xl font-bold text-white">{{ $consolidatedCount ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Live Search Bar -->
                <div class="mb-8">
                    <div class="relative w-full max-w-xl mx-auto">
                        <form method="GET" action="{{ route('dashboard') }}" x-data="{ results: [], show: false, query: '' }" @click.away="show = false">
                            <input
                                type="text"
                                name="search"
                                placeholder="Search purchase requests by PR #..."
                                value="{{ request('search') }}"
                                class="pl-10 pr-24 py-3 w-full rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none glass-input"
                                autocomplete="off"
                                x-model="query"
                                @input.debounce.300ms="
                                    if (query.length > 0) {
                                        fetch('{{ route('search.pr') }}?q=' + encodeURIComponent(query))
                                            .then(res => res.json())
                                            .then(data => { results = data; show = true; });
                                    } else {
                                        results = []; show = false;
                                    }
                                "
                                @focus="if(results.length > 0) show = true"
                            >
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" x-show="!query">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="11" cy="11" r="8" />
                                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                                </svg>
                            </span>
                            <button
                                type="submit"
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 px-4 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                            >
                                Search
                            </button>
                            <div x-show="show && results.length > 0" class="absolute z-50 bg-white border border-gray-200 mt-1 w-full rounded shadow-lg max-h-60 overflow-y-auto">
                                <template x-for="item in results" :key="item.id">
                                    <a :href="'/purchase-requests/' + item.id" class="block px-4 py-2 hover:bg-blue-100 cursor-pointer">
                                        <span class="font-semibold text-blue-700" x-text="item.pr_number"></span>
                                        <span class="text-gray-600 ml-2" x-text="item.name"></span>
                                        <span class="ml-2 text-xs rounded px-2 py-1" :class="{
                                            'bg-green-100 text-green-800': item.status === 'Approved',
                                            'bg-yellow-100 text-yellow-800': item.status === 'Pending',
                                            'bg-red-100 text-red-800': item.status === 'Rejected',
                                            'bg-gray-100 text-gray-800': !['Approved','Pending','Rejected'].includes(item.status)
                                        }" x-text="item.status"></span>
                                    </a>
                                </template>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- Purchase Requests Overview -->
                    <div class="lg:col-span-2">
                        <div class="glass-card rounded-lg">
                            <div class="p-6 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-xl font-bold text-white glass-heading">Purchase Requests Overview</h2>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('purchase-requests.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            + New PR
                                        </a>
                                        <a href="{{ route('reports.monthly.export') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                            Export Report
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <!-- Alternative Bids Section -->
                                <div class="mb-6">
                                    <h3 class="text-lg font-bold mb-4 text-white glass-heading">Alternative Bids</h3>
                                    @if ($alternativeBids->count())
                                        <div class="space-y-3">
                                            @foreach ($alternativeBids as $bid)
                                                <div class="border border-gray-300 rounded-lg p-4 hover:bg-gray-50 transition glass-card">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex-1">
                                                            <div class="flex items-center space-x-3">
                                                                <h4 class="font-semibold text-white glass-text">{{ $bid->pr_number }}</h4>
                                                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                                                    Alternative
                                                                </span>
                                                            </div>
                                                            <p class="text-white glass-text mt-1">{{ $bid->name }}</p>
                                                            <div class="flex items-center space-x-4 mt-2 text-sm text-white glass-text">
                                                                <span>Status: {{ $bid->status }}</span>
                                                                <span>Order Date: {{ $bid->order_date }}</span>
                                                                <span>Funding: {{ $bid->funding ?? 'N/A' }}</span>
                                                            </div>
                                                        </div>
                                                        <a href="{{ route('purchase-requests.show', $bid) }}" class="text-blue-300 hover:text-blue-100">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-white glass-text">No alternative bids found.</p>
                                    @endif
                                </div>

                                <!-- Competitive Bids Section -->
                                <div class="mb-6">
                                    <h3 class="text-lg font-bold mb-4 text-white glass-heading">Competitive Bids</h3>
                                    @if ($competitiveBids->count())
                                        <form action="{{ route('consolidation.store') }}" method="POST">
                                            @csrf
                                            <div class="space-y-3">
                                                @foreach ($competitiveBids as $bid)
                                                    <div class="border border-gray-300 rounded-lg p-4 hover:bg-gray-50 transition glass-card">
                                                        <div class="flex items-center justify-between">
                                                            <div class="flex-1">
                                                                <div class="flex items-center space-x-3">
                                                                    <h4 class="font-semibold text-white glass-text">{{ $bid->pr_number }}</h4>
                                                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                                                        Competitive
                                                                    </span>
                                                                </div>
                                                                <p class="text-white glass-text mt-1">{{ $bid->name }}</p>
                                                                <div class="flex items-center space-x-4 mt-2 text-sm text-white glass-text">
                                                                    <span>Status: {{ $bid->status }}</span>
                                                                    <span>Order Date: {{ $bid->order_date }}</span>
                                                                    <span>Funding: {{ $bid->funding ?? 'N/A' }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="flex items-center space-x-2">
                                                                <input type="checkbox" name="pr_numbers[]" value="{{ $bid->id }}" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                                <a href="{{ route('purchase-requests.show', $bid) }}" class="text-blue-300 hover:text-blue-100">
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="mt-4">
                                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                                    Consolidate Selected
                                                </button>
                                            </div>
                                        </form>
                                    @else
                                        <p class="text-white glass-text">No competitive purchase requests available.</p>
                                    @endif
                                </div>

                                <!-- Consolidated PRs Section -->
                                <div>
                                    <h3 class="text-lg font-bold mb-4 text-white glass-heading">Consolidated PRs</h3>
                                    @if($consolidatedPRs->count() > 0)
                                        <div class="space-y-3">
                                            @foreach ($consolidatedPRs as $cpr)
                                                <div class="border border-gray-300 rounded-lg p-4 hover:bg-gray-50 transition glass-card">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex-1">
                                                            <h4 class="font-semibold text-white glass-text">{{ $cpr->cpr_number }}</h4>
                                                            <p class="text-white glass-text mt-1">
                                                                <strong>PRs:</strong>
                                                                @foreach ($cpr->purchaseRequests as $pr)
                                                                    <span class="inline-block bg-gray-100 text-gray-700 px-2 py-1 rounded text-sm mr-1">{{ $pr->pr_number }}</span>
                                                                @endforeach
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-white glass-text">No consolidated PRs found.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        
                        <!-- Quick Actions -->
                        <div class="glass-card rounded-lg">
                            <div class="p-6 border-b border-gray-300">
                                <h3 class="text-lg font-bold text-white glass-heading">Quick Actions</h3>
                            </div>
                            <div class="p-6">
                                <div class="space-y-3">
                                    <a href="{{ route('purchase-requests.create') }}" class="flex items-center p-3 text-white glass-text rounded-lg hover:bg-white/10 transition border border-white/20">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Create New PR
                                    </a>
                                    <a href="{{ route('purchase-requests.index') }}" class="flex items-center p-3 text-white glass-text rounded-lg hover:bg-white/10 transition border border-white/20">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        View All PRs
                                    </a>
                                    <a href="#" class="flex items-center p-3 text-white glass-text rounded-lg hover:bg-white/10 transition border border-white/20">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        Upload Documents
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Notifications -->
                        <div class="glass-card rounded-lg">
                            <div class="p-6 border-b border-white/20 bg-gradient-to-r from-violet-500/10 to-purple-500/10 rounded-t-lg">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-5 h-5 text-violet-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    <h3 class="text-lg font-bold text-white">Recent Notifications</h3>
                                    @if(auth()->user()->unreadNotifications->count() > 0)
                                        <span class="text-sm font-medium text-violet-300 bg-violet-500/20 px-2 py-1 rounded-full">
                                            {{ auth()->user()->unreadNotifications->count() }} unread
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="p-6">
                                @if(auth()->user()->notifications->count() > 0)
                                    <div class="space-y-4">
                                        @foreach(auth()->user()->notifications->take(5) as $notification)
                                            <div class="flex items-start space-x-3 p-3 rounded-lg transition-all duration-200 border-l-4 {{ !$notification->read_at ? 'border-l-violet-400 bg-violet-500/10' : 'border-l-gray-400 bg-transparent' }}">
                                                <!-- Status Indicator -->
                                                <div class="flex-shrink-0 mt-1">
                                                    <div class="w-2 h-2 rounded-full {{ !$notification->read_at ? 'bg-violet-400' : 'bg-gray-400' }}"></div>
                                                </div>
                                                
                                                <!-- Content -->
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-white leading-relaxed">
                                                        {{ $notification->data['message'] ?? 'Notification' }}
                                                    </p>
                                                    <p class="text-xs text-white/60 mt-2 font-medium">
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </p>
                                                    
                                                    <!-- Action Link -->
                                                    @if(isset($notification->data['action_url']))
                                                        <a href="{{ $notification->data['action_url'] }}" 
                                                           class="inline-flex items-center mt-3 text-xs font-medium text-violet-300 hover:text-violet-100 transition-colors group">
                                                            <span>View Details</span>
                                                            <svg class="w-3 h-3 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                            </svg>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mt-6 text-center">
                                        <a href="#" class="text-violet-300 hover:text-violet-100 font-medium transition-colors">
                                            View all notifications →
                                        </a>
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <svg class="w-12 h-12 text-white/30 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                        </svg>
                                        <p class="text-white/60 font-medium">No notifications yet</p>
                                        <p class="text-white/40 text-sm mt-1">You're all caught up!</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- System Stats -->
                        <div class="glass-card rounded-lg">
                            <div class="p-6 border-b border-gray-300">
                                <h3 class="text-lg font-bold text-white glass-heading">System Overview</h3>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-white glass-text">Total Users</span>
                                        <span class="font-semibold text-white glass-text">{{ \App\Models\User::count() }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-white glass-text">Departments</span>
                                        <span class="font-semibold text-white glass-text">{{ \App\Models\Department::count() }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-white glass-text">Active PRs</span>
                                        <span class="font-semibold text-white glass-text">{{ \App\Models\PurchaseRequest::where('status', '!=', 'Completed')->count() }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-white glass-text">Documents</span>
                                        <span class="font-semibold text-white glass-text">{{ \App\Models\Document::count() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    @else
        <!-- USER DASHBOARD -->
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <!-- Welcome Section -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-white mb-2">Welcome back, {{ auth()->user()->name }}! 👋</h1>
                    <p class="text-white">Here's what's happening with your purchase requests today.</p>
                </div>

                <!-- Personal Overview Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="glass-card rounded-lg p-6 border-l-4 border-blue-500">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">My PRs</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_requests'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="glass-card rounded-lg p-6 border-l-4 border-yellow-500">
                        <div class="flex items-center">
                            <div class="p-2 bg-yellow-100 rounded-lg">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Pending</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_requests'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="glass-card rounded-lg p-6 border-l-4 border-green-500">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-white">Documents</p>
                                <p class="text-2xl font-bold text-white">{{ $stats['total_documents'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="glass-card rounded-lg p-6 border-l-4 border-purple-500">
                        <div class="flex items-center">
                            <div class="p-2 bg-purple-100 rounded-lg">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-white">Notifications</p>
                                <p class="text-2xl font-bold text-white">{{ auth()->user()->unreadNotifications->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- My Purchase Requests -->
                    <div class="lg:col-span-2">
                        <div class="glass-card rounded-lg">
                            <div class="p-6 border-b border-gray-300">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-xl font-bold text-white glass-heading">My Purchase Requests</h2>
                                    <a href="{{ route('purchase-requests.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        + New PR
                                    </a>
                                </div>
                            </div>
                            <div class="p-6">
                                @if($purchaseRequests->count() > 0)
                                    <div class="space-y-4">
                                        @foreach($purchaseRequests as $pr)
                                            <div class="glass-effect rounded-lg p-4 hover:bg-gray-50/50 transition">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex-1">
                                                        <div class="flex items-center space-x-3">
                                                            <h3 class="font-semibold text-white glass-text">{{ $pr->pr_number }}</h3>
                                                            <span class="px-2 py-1 text-xs rounded-full
                                                                @if($pr->type == 'alternative') bg-blue-100 text-blue-800
                                                                @else bg-green-100 text-green-800 @endif">
                                                                {{ ucfirst($pr->type) }}
                                                            </span>
                                                        </div>
                                                        <p class="text-white glass-text mt-1">{{ $pr->name }}</p>
                                                        <div class="flex items-center space-x-4 mt-2 text-sm text-white glass-text">
                                                            <span>Status: {{ $pr->status }}</span>
                                                            <span>Created: {{ $pr->created_at->format('M j, Y') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="flex space-x-2">
                                                        <a href="{{ route('purchase-requests.show', $pr) }}" class="text-blue-300 hover:text-blue-100">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                            </svg>
                                                        </a>
                                                        <a href="{{ route('purchase-requests.edit', $pr) }}" class="text-green-300 hover:text-green-100">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mt-6 text-center">
                                        <a href="{{ route('purchase-requests.index') }}" class="text-blue-300 hover:text-blue-100 font-medium">
                                            View all my purchase requests →
                                        </a>
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-white glass-text">No purchase requests</h3>
                                        <p class="mt-1 text-sm text-white glass-text">Get started by creating a new purchase request.</p>
                                        <div class="mt-6">
                                            <a href="{{ route('purchase-requests.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                                Create your first PR
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        
                        <!-- Quick Actions -->
                        <div class="glass-card rounded-lg">
                            <div class="p-6 border-b border-gray-300">
                                <h3 class="text-lg font-bold text-white glass-heading">Quick Actions</h3>
                            </div>
                            <div class="p-6">
                                <div class="space-y-3">
                                    <a href="{{ route('purchase-requests.create') }}" class="flex items-center p-3 text-white glass-text rounded-lg hover:bg-white/10 transition border border-white/20">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Create New PR
                                    </a>
                                    <a href="{{ route('purchase-requests.index') }}" class="flex items-center p-3 text-white glass-text rounded-lg hover:bg-white/10 transition border border-white/20">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        View All PRs
                                    </a>
                                    <a href="#" class="flex items-center p-3 text-white glass-text rounded-lg hover:bg-white/10 transition border border-white/20">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        Upload Documents
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Department Info -->
                        <div class="glass-card rounded-lg">
                            <div class="p-6 border-b border-gray-300">
                                <h3 class="text-lg font-bold text-white glass-heading">Department Info</h3>
                            </div>
                            <div class="p-6">
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-sm font-medium text-white glass-text">Department</p>
                                        <p class="text-white glass-text">{{ auth()->user()->department->name ?? 'N/A' }}</p>
                                    </div>
                                    @if(auth()->user()->department && auth()->user()->department->head)
                                        <div>
                                            <p class="text-sm font-medium text-white glass-text">Department Head</p>
                                            <p class="text-white glass-text">{{ auth()->user()->department->head->name }}</p>
                                            <p class="text-sm text-white glass-text">{{ auth()->user()->department->head->email }}</p>
                                        </div>
                                    @endif
                                    @if($departmentStats)
                                        <div class="pt-3 border-t border-gray-300">
                                            <p class="text-sm font-medium text-white glass-text mb-2">Department Stats</p>
                                            <div class="grid grid-cols-2 gap-2 text-sm">
                                                <div>
                                                    <p class="text-white glass-text">Total PRs</p>
                                                    <p class="font-semibold text-white glass-text">{{ $departmentStats['total_requests'] }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-white glass-text">Pending</p>
                                                    <p class="font-semibold text-white glass-text">{{ $departmentStats['pending_requests'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div class="glass-card rounded-lg">
                            <div class="p-6 border-b border-gray-300">
                                <h3 class="text-lg font-bold text-white glass-heading">Recent Activity</h3>
                            </div>
                            <div class="p-6">
                                @if($recentAuditLogs->count() > 0)
                                    <div class="space-y-3">
                                        @foreach($recentAuditLogs->take(5) as $log)
                                            <div class="flex items-start space-x-3">
                                                <div class="flex-shrink-0">
                                                    <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm text-white glass-text">{{ $log->description }}</p>
                                                    <p class="text-xs text-white glass-text">{{ $log->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-white glass-text text-sm">No recent activity</p>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    @endif

    </div>



</x-app-layout>
