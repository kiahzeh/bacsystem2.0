<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <!-- Quick access: My Status (email verification and approval) -->
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
        <div class="flex justify-end">
            <a href="{{ route('my.status') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                My Status
            </a>
        </div>
    </div>

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
        /* Default to dark text in light mode, switch to white in dark mode */
        .glass-text { color: #1f2937; font-weight: 500; }
        .glass-heading { color: #111827; font-weight: 600; }
        .glass-table-text { color: #1f2937; }
        .glass-table-heading { color: #111827; font-weight: 600; }

        .dark .glass-text { color: #ffffff; }
        .dark .glass-heading { color: #ffffff; }
        .dark .glass-table-text { color: #ffffff; }
        .dark .glass-table-heading { color: #ffffff; }
    </style>

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
                    <div class="glassmorphism-card rounded-lg p-6 border-l-4 border-blue-500">
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

                    <div class="glassmorphism-card rounded-lg p-6 border-l-4 border-green-500">
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

                    <div class="glassmorphism-card rounded-lg p-6 border-l-4 border-yellow-500">
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

                    <div class="glassmorphism-card rounded-lg p-6 border-l-4 border-purple-500">
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

                <!-- Additional Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <div class="glassmorphism-card rounded-lg p-6 border-l-4 border-indigo-500">
                        <div class="flex items-center">
                            <div class="p-2 bg-indigo-100 rounded-lg">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18M3 7h18M3 11h18M3 15h18M3 19h18"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-white">Pending PRs</p>
                                <p class="text-2xl font-bold text-white">{{ $pendingCount ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="glassmorphism-card rounded-lg p-6 border-l-4 border-teal-500">
                        <div class="flex items-center">
                            <div class="p-2 bg-teal-100 rounded-lg">
                                <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-white">Completed PRs</p>
                                <p class="text-2xl font-bold text-white">{{ $completedCount ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="glassmorphism-card rounded-lg p-6 border-l-4 border-pink-500">
                        <div class="flex items-center">
                            <div class="p-2 bg-pink-100 rounded-lg">
                                <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v16h16V4H4zm4 4h8v8H8V8z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-white">Documents</p>
                                <p class="text-2xl font-bold text-white">{{ $documentsCount ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="glassmorphism-card rounded-lg p-6 border-l-4 border-orange-500">
                        <div class="flex items-center">
                            <div class="p-2 bg-orange-100 rounded-lg">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5V4H2v16h5M7 20V4m10 0v16"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-white">Departments</p>
                                <p class="text-2xl font-bold text-white">{{ $departmentsCount ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="glassmorphism-card rounded-lg p-6 border-l-4 border-emerald-500">
                        <div class="flex items-center">
                            <div class="p-2 bg-emerald-100 rounded-lg">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m8-8H4m16 8H4"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-white">New PRs Today</p>
                                <p class="text-2xl font-bold text-white">{{ $todayNewPRs ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="glassmorphism-card rounded-lg p-6 border-l-4 border-sky-500">
                        <div class="flex items-center">
                            <div class="p-2 bg-sky-100 rounded-lg">
                                <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 6h18M3 18h18"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-white">PRs This Month</p>
                                <p class="text-2xl font-bold text-white">{{ $thisMonthPRs ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="glassmorphism-card rounded-lg p-6 border-l-4 border-gray-500">
                        <div class="flex items-center">
                            <div class="p-2 bg-gray-100 rounded-lg">
                                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3M12 22a10 10 0 100-20 10 10 0 000 20z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-white">Avg Cycle Time (days)</p>
                                <p class="text-2xl font-bold text-white">{{ $avgCycleTimeDays ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="glassmorphism-card rounded-lg p-6 border-l-4 border-orange-500">
                        <div class="flex items-center">
                            <div class="p-2 bg-orange-100 rounded-lg">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4M12 16h.01M8.257 3.099c.765-1.36 2.721-1.36 3.486 0l7.451 13.247c.724 1.288-.186 2.904-1.743 2.904H2.55c-1.557 0-2.467-1.616-1.743-2.904L8.257 3.1z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-white">Pending Documents</p>
                                <p class="text-2xl font-bold text-white">{{ $pendingDocumentsCount ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="glassmorphism-card rounded-lg p-6 border-l-4 border-red-500">
                        <div class="flex items-center">
                            <div class="p-2 bg-red-100 rounded-lg">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-white">Unread Notifications</p>
                                <p class="text-2xl font-bold text-white">{{ $unreadNotificationsCount ?? 0 }}</p>
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
                                    class="glassmorphism-input w-full pl-10 pr-24 py-3 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent"
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
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-white/60" x-show="!query">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="11" cy="11" r="8" />
                                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                                </svg>
                            </span>
                            <button
                                type="submit"
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 px-4 py-1 rounded-full glass-badge bg-blue-500/20 text-blue-200 hover:bg-blue-500/30 transition"
                            >
                                Search
                            </button>
                            <div x-show="show && results.length > 0" class="absolute z-50 mt-1 w-full rounded-lg shadow-lg max-h-60 overflow-y-auto bg-white/10 text-white border border-white/20 backdrop-blur-xl">
                                <template x-for="item in results" :key="item.id">
                                    <a :href="'/purchase-requests/' + item.id" class="block px-4 py-2 hover:bg-violet-500/10 cursor-pointer text-white">
                                        <span class="font-semibold" x-text="item.pr_number"></span>
                                        <span class="ml-2 text-white/70" x-text="item.name"></span>
                                        <span class="ml-2 text-xs glass-badge bg-violet-500/20 text-violet-200 rounded px-2 py-1" x-text="item.mode_of_procurement"></span>
                                        <span class="ml-2 text-xs glass-badge rounded px-2 py-1" :class="{
                                            'bg-emerald-500/20 text-emerald-200': item.status === 'Approved',
                                            'bg-amber-500/20 text-amber-200': item.status === 'Pending',
                                            'bg-rose-500/20 text-rose-200': item.status === 'Rejected',
                                            'bg-slate-500/20 text-slate-200': !['Approved','Pending','Rejected'].includes(item.status)
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
                        <div class="glassmorphism-card rounded-lg" x-data="{ collapseLists: false }">
                            <div class="p-6 border-b border-white/20">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-xl font-bold text-white glass-heading">Purchase Requests Overview</h2>
                                    <div class="flex space-x-2 items-center">
                                        <a href="{{ route('purchase-requests.create') }}" class="inline-flex items-center glass-badge bg-blue-500/20 text-blue-200 hover:bg-blue-500/30 font-semibold py-2 px-4 rounded-full">
                                            + New PR
                                        </a>
                                        <a href="{{ route('reports.monthly.export') }}" class="inline-flex items-center glass-badge bg-green-500/20 text-green-200 hover:bg-green-500/30 font-semibold py-2 px-4 rounded-full">
                                            Export Report
                                        </a>
                                        <button type="button"
                                                @click="collapseLists = !collapseLists"
                                                class="px-3 py-2 rounded-md text-sm font-semibold bg-white/10 hover:bg-white/20 text-white border border-white/20 transition">
                                            <span x-show="!collapseLists">Collapse Lists</span>
                                            <span x-show="collapseLists">Expand Lists</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <!-- Monthly PR Trend Chart -->
                                <div class="mb-6">
                                    <h3 class="text-lg font-bold mb-4 glass-heading">Monthly PR Trend</h3>
                                    <div class="relative h-64 bg-white/5 rounded-lg p-4 border border-white/10">
                                        <canvas id="prMonthlyChart" data-labels='@json($prMonthlyLabels ?? [])' data-series='@json($prMonthlyData ?? [])'></canvas>
                                    </div>
                                </div>
                                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                                <script>
                                    (function(){
                                        const canvas = document.getElementById('prMonthlyChart');
                                        if (!canvas) return;
                                        const labels = JSON.parse(canvas.dataset.labels || '[]');
                                        const series = JSON.parse(canvas.dataset.series || '[]');
                                        const ctx = canvas.getContext('2d');
                                        const isDark = document.documentElement.classList.contains('dark');
                                        const tickColor = isDark ? '#E5E7EB' : '#374151'; // gray-200 vs gray-700
                                        const gridColor = isDark ? 'rgba(229, 231, 235, 0.15)' : 'rgba(55, 65, 81, 0.15)';
                                        new Chart(ctx, {
                                            type: 'bar',
                                            data: {
                                                labels: labels,
                                                datasets: [{
                                                    label: 'PRs Created',
                                                    data: series,
                                                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                                                    borderColor: 'rgba(59, 130, 246, 1)',
                                                    borderWidth: 1
                                                }]
                                            },
                                            options: {
                                                responsive: true,
                                                maintainAspectRatio: false,
                                                scales: {
                                                    x: {
                                                        ticks: { color: tickColor },
                                                        grid: { color: gridColor }
                                                    },
                                                    y: {
                                                        beginAtZero: true,
                                                        ticks: { precision: 0, color: tickColor },
                                                        grid: { color: gridColor }
                                                    }
                                                },
                                                plugins: {
                                                    legend: { display: false }
                                                }
                                            }
                                        });
                                    })();
                                </script>
                                <!-- Alternative Bids Section -->
                                <div class="mb-6" x-show="!collapseLists" x-transition>
                                    <h3 class="text-lg font-bold mb-4 glass-heading">Alternative Bids</h3>
                                    @if ($alternativeBids->count())
                                        <div class="space-y-3">
                                            @foreach ($alternativeBids as $bid)
                                <div class="border border-white/20 rounded-lg p-4 hover:bg-white/10 transition glassmorphism-card">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex-1">
                                                            <div class="flex items-center space-x-3">
                                                                <h4 class="font-semibold text-white glass-text">{{ $bid->pr_number }}</h4>
                                                                <span class="px-2 py-1 text-xs rounded-full glass-badge bg-violet-500/20 text-violet-200">
                                                                    Alternative
                                                                </span>
                                                            </div>
                                                            <p class="text-white glass-text mt-1">{{ $bid->name }}</p>
                                                            <div class="flex items-center space-x-4 mt-2 text-sm text-white glass-text">
                                                                <span>Status: {{ $bid->status }}</span>
                                                                <span>Order Date: {{ $bid->order_date ? \Carbon\Carbon::parse($bid->order_date)->format('Y-m-d') : '—' }}</span>
                                                                <span>Funding: {{ $bid->funding ?? 'N/A' }}</span>
                                                            </div>
                                                        </div>
                                                        <a href="{{ route('purchase-requests.show', $bid) }}" class="text-white/70 hover:text-white transition">
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
                                        <p class="glass-text">No alternative bids found.</p>
                                    @endif
                                </div>

                                <!-- Competitive Bids Section -->
                                <div class="mb-6" x-data="{ selectAll: false, searchTerm: '' }" x-show="!collapseLists" x-transition>
                                    <h3 class="text-lg font-bold mb-4 glass-heading">Competitive Bids</h3>
                                    
                                    @if ($competitiveBids->count())
                                        <!-- Search Bar for Competitive Bids -->
                                        <div class="mb-4">
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <svg class="h-5 w-5 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                    </svg>
                                                </div>
                                                <input 
                                                    type="text" 
                                                    x-model="searchTerm"
                                                    placeholder="Search competitive bids by PR number, name, status, or funding..."
                                                    class="glassmorphism-input w-full pl-10 pr-4 py-3 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent"
                                                >
                                            </div>
                                        </div>
                                        
                                        <form action="{{ route('consolidation.store') }}" method="POST">
                                            @csrf
                                            <!-- Select All Option -->
                                            <div class="mb-4 p-3 bg-white/10 rounded-lg border border-white/20">
                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                    <input type="checkbox" 
                                                           x-model="selectAll"
                                                           @change="
                                                               const checkboxes = document.querySelectorAll('input[name=\'pr_numbers[]\']:not([style*=\'display: none\'])');
                                                               checkboxes.forEach(cb => cb.checked = selectAll);
                                                           "
                                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                    <span class="glass-text font-semibold">Select All Competitive Bids</span>
                                                    <span class="text-gray-600 dark:text-white/70 text-sm">(<span x-text="document.querySelectorAll('input[name=\'pr_numbers[]\']:not([style*=\'display: none\'])').length"></span> items)</span>
                                                </label>
                                            </div>
                                            
                                                    <div class="space-y-3">
                                                @foreach ($competitiveBids as $bid)
                                                    <div class="border border-white/20 rounded-lg p-4 hover:bg-white/10 transition glassmorphism-card"
                                                         x-show="searchTerm === '' || 
                                                                 '{{ strtolower($bid->pr_number) }}'.includes(searchTerm.toLowerCase()) ||
                                                                 '{{ strtolower($bid->name) }}'.includes(searchTerm.toLowerCase()) ||
                                                                 '{{ strtolower($bid->status) }}'.includes(searchTerm.toLowerCase()) ||
                                                                 '{{ strtolower($bid->funding ?? '') }}'.includes(searchTerm.toLowerCase())">
                                                        <div class="flex items-center justify-between">
                                                            <div class="flex-1">
                                                                <div class="flex items-center space-x-3">
                                                                    <h4 class="font-semibold text-white glass-text">{{ $bid->pr_number }}</h4>
                                                                    <span class="px-2 py-1 text-xs rounded-full whitespace-nowrap glass-badge bg-emerald-500/20 text-emerald-200">
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
                                                                <input type="checkbox" 
                                                                       name="pr_numbers[]" 
                                                                       value="{{ $bid->id }}" 
                                                                       @change="
                                                                           const checkboxes = document.querySelectorAll('input[name=\'pr_numbers[]\']:not([style*=\'display: none\'])');
                                                                           const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
                                                                           selectAll = checkedCount === checkboxes.length;
                                                                       "
                                                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
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
                                            
                                            <!-- No Results Message -->
                                            <div x-show="searchTerm !== '' && document.querySelectorAll('input[name=\'pr_numbers[]\']:not([style*=\'display: none\'])').length === 0" 
                                                 class="text-center py-8">
                                                <svg class="mx-auto h-12 w-12 text-white/40 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                </svg>
                                                <p class="text-gray-600 dark:text-white/60 text-lg">No competitive bids found matching "<span x-text="searchTerm"></span>"</p>
                                                <button type="button" @click="searchTerm = ''" class="text-violet-300 hover:text-violet-100 mt-2">
                                                    Clear search
                                                </button>
                                            </div>
                                            
                                            <div class="mt-4">
                                                <div class="mb-4">
                                                    <label for="cpr_number" class="block text-sm font-medium text-white mb-2">
                                                        CPR Number (Consolidated Purchase Request Number)
                                                    </label>
                                                    <input type="text" 
                                                           id="cpr_number" 
                                                           name="cpr_number" 
                                                           class="w-full px-3 py-2 glassmorphism-input text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                           placeholder="Enter CPR number (e.g., CPR-2024-001)"
                                                           required>
                                                    @error('cpr_number')
                                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <button type="submit" class="inline-flex items-center glass-badge bg-blue-500/20 text-blue-200 hover:bg-blue-500/30 font-semibold py-2 px-4 rounded-full">
                                                    Consolidate Selected
                                                </button>
                                            </div>
                                        </form>
                                    @else
                                    <p class="glass-text">No competitive purchase requests available.</p>
                                    @endif
                                </div>

                                <!-- Others Bids Section -->
                                <div class="mb-6" x-show="!collapseLists" x-transition>
                                    <h3 class="text-lg font-bold mb-4 glass-heading">Others</h3>
                                    @if (isset($othersBids) && $othersBids->count())
                                        <div class="space-y-3">
                                            @foreach ($othersBids as $bid)
                                                <div class="border border-white/20 rounded-lg p-4 hover:bg-white/10 transition glassmorphism-card">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex-1">
                                                            <div class="flex items-center space-x-3">
                                                                <h4 class="font-semibold text-white glass-text">{{ $bid->pr_number }}</h4>
                                                        <span class="px-2 py-1 text-xs rounded-full whitespace-nowrap glass-badge bg-gray-500/20 text-gray-200">
                                                            Others
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
                                        <p class="glass-text">No others purchase requests found.</p>
                                    @endif
                                </div>

                                <!-- Completed PRs Section -->
                                <div class="mb-6" x-show="!collapseLists" x-transition>
                                    <h3 class="text-lg font-bold mb-4 glass-heading">Completed PRs</h3>
                                    @if (isset($completedPRs) && $completedPRs->count())
                                        <div class="space-y-3">
                                            @foreach ($completedPRs as $pr)
                                <div class="border border-white/20 rounded-lg p-4 hover:bg-white/10 transition glassmorphism-card">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex-1">
                                                            <div class="flex items-center space-x-3">
                                                                <h4 class="font-semibold text-white glass-text">{{ $pr->pr_number }}</h4>
                                                                <span class="px-2 py-1 text-xs rounded-full whitespace-nowrap glass-badge bg-green-500/20 text-green-200">
                                                                    Completed
                                                                </span>
                                                            </div>
                                                            <p class="text-white glass-text mt-1">{{ $pr->name }}</p>
                                                            <div class="flex items-center space-x-4 mt-2 text-sm text-white glass-text">
                                                                <span>Completed: 
                                                                    @if($pr->completion_date)
                                                                        @if($pr->completion_date instanceof \Carbon\Carbon)
                                                                            {{ $pr->completion_date->format('M d, Y') }}
                                                                        @else
                                                                            {{ $pr->completion_date }}
                                                                        @endif
                                                                    @else
                                                                        N/A
                                                                    @endif
                                                                </span>
                                                                <span>Final Amount: ₱{{ number_format($pr->final_amount ?? 0, 2) }}</span>
                                                                <span>Vendor: {{ $pr->awarded_vendor ?? 'N/A' }}</span>
                                                            </div>
                                                        </div>
                                                        <a href="{{ route('purchase-requests.show', $pr) }}" class="text-green-300 hover:text-green-100">
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
                                        <p class="glass-text">No completed purchase requests found.</p>
                                    @endif
                                </div>

                                <!-- Consolidated PRs Section -->
                                <div x-show="!collapseLists" x-transition>
                                    <h3 class="text-lg font-bold mb-4 glass-heading">Consolidated PRs</h3>
                                    @if($consolidatedPRs->count() > 0)
                                        <div class="space-y-3">
                                            @foreach ($consolidatedPRs as $cpr)
                                <div class="border border-white/20 rounded-lg p-4 hover:bg-white/10 transition glassmorphism-card">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex-1">
                                                            <h4 class="font-semibold text-white glass-text">{{ $cpr->cpr_number }}</h4>
                                                            <p class="text-white glass-text mt-1">
                                                                <strong>PRs:</strong>
                                                                @foreach ($cpr->purchaseRequests as $pr)
                                        <span class="inline-block glass-badge bg-violet-500/20 text-violet-200 px-2 py-1 rounded-full text-xs mr-1 whitespace-nowrap">{{ $pr->pr_number }}</span>
                                                                @endforeach
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="glass-text">No consolidated PRs found.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        
                        <!-- Quick Actions -->
                        <div class="glassmorphism-card rounded-lg">
                            <div class="p-6 border-b border-white/20">
                                <h3 class="text-lg font-bold glass-heading">Quick Actions</h3>
                            </div>
                            <div class="p-6">
                                <div class="space-y-3">
                                    <a href="{{ route('purchase-requests.create') }}" class="flex items-center p-3 glass-text rounded-lg hover:bg-white/10 transition border border-white/20">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Create New PR
                                    </a>
                                    <a href="{{ route('purchase-requests.index') }}" class="flex items-center p-3 glass-text rounded-lg hover:bg-white/10 transition border border-white/20">
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
                        <div class="glassmorphism-card rounded-lg">
                            <div class="p-6 border-b border-white/20 bg-gradient-to-r from-violet-500/10 to-purple-500/10 rounded-t-lg">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-5 h-5 text-violet-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    <h3 class="text-lg font-bold text-white">Recent Notifications</h3>
                                    @if(($unreadNotificationsCount ?? 0) > 0)
                                        <span class="text-sm font-medium text-violet-300 bg-violet-500/20 px-2 py-1 rounded-full">
                                            {{ $unreadNotificationsCount }} unread
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
                        <div class="glassmorphism-card rounded-lg">
                            <div class="p-6 border-b border-white/20">
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
                    <div class="glassmorphism-card rounded-lg p-6 border-l-4 border-blue-500">
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

                    <div class="glassmorphism-card rounded-lg p-6 border-l-4 border-yellow-500">
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

                    <div class="glassmorphism-card rounded-lg p-6 border-l-4 border-green-500">
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

                    <div class="glassmorphism-card rounded-lg p-6 border-l-4 border-purple-500">
                        <div class="flex items-center">
                            <div class="p-2 bg-purple-100 rounded-lg">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-white">Notifications</p>
                                <p class="text-2xl font-bold text-white">{{ $unreadNotificationsCount ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- My Purchase Requests -->
                    <div class="lg:col-span-2">
                        <div class="glassmorphism-card rounded-lg">
                            <div class="p-6 border-b border-white/20">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-xl font-bold text-white glass-heading">My Purchase Requests</h2>
                                </div>
                            </div>
                            <div class="p-6">
                                <!-- Debug Info (remove in production) -->
                                @if(config('app.debug'))
                                    <div class="mb-4 p-3 bg-yellow-500/20 border border-yellow-400/30 rounded text-xs text-yellow-200">
                                        <strong>Debug:</strong> Found {{ $purchaseRequests ? $purchaseRequests->count() : 0 }} purchase requests
                                        @if(auth()->user())
                                            | User ID: {{ auth()->id() }} | Dept: {{ auth()->user()->department_id }} | Admin: {{ auth()->user()->isAdmin() ? 'Yes' : 'No' }}
                                        @endif
                                    </div>
                                @endif
                                
                                @if($purchaseRequests && $purchaseRequests->count() > 0)
                                    <div class="space-y-4">
                                        @foreach($purchaseRequests as $pr)
                                            <div class="glassmorphism-card rounded-lg p-4 hover:bg-white/10 transition">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex-1">
                                                        <div class="flex items-center space-x-3">
                                                            <h3 class="font-semibold text-white glass-text">{{ $pr->pr_number }}</h3>
                                                            <span class="px-2 py-1 text-xs rounded-full
                                                                @if($pr->mode_of_procurement == 'Alternative') bg-blue-100 text-blue-800
                                                                @elseif($pr->mode_of_procurement == 'Competitive') bg-green-100 text-green-800
                                                                @else bg-gray-100 text-gray-800 @endif">
                                                                {{ $pr->mode_of_procurement }}
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
                                        <p class="mt-1 text-sm text-white glass-text">You don’t have any purchase requests yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        
                        <!-- Quick Actions -->
                        <div class="glassmorphism-card rounded-lg">
                            <div class="p-6 border-b border-white/20">
                                <h3 class="text-lg font-bold text-white glass-heading">Quick Actions</h3>
                            </div>
                            <div class="p-6">
                                <div class="space-y-3">
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
                        <div class="glassmorphism-card rounded-lg">
                            <div class="p-6 border-b border-white/20">
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
                        <div class="glassmorphism-card rounded-lg">
                            <div class="p-6 border-b border-white/20">
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

</x-app-layout>
