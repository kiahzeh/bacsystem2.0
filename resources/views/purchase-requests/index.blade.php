<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                {{ __('Purchase Requests') }}
            </h2>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('purchase-requests.create') }}"
                    class="glass-badge bg-blue-500/30 hover:bg-blue-500/40 text-white/90 font-semibold py-2 px-4 rounded inline-flex items-center transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Create New Request
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glassmorphism-card overflow-hidden sm:rounded-lg">
                <div class="p-6 text-white glass-text">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto glassmorphism-card rounded-lg overflow-y-auto relative">
                        <table class="min-w-full table-auto border-collapse">
                            <thead>
                                <tr class="glassmorphism-header text-white uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-left font-semibold glass-table-heading">Name</th>
                                    <th class="py-3 px-6 text-left font-semibold glass-table-heading">Order Date</th>
                                    <th class="py-3 px-6 text-left font-semibold glass-table-heading">Department</th>
                                    <th class="py-3 px-6 text-left font-semibold glass-table-heading">Mode of Procurement</th>
                                    <th class="py-3 px-6 text-left font-semibold glass-table-heading">Status</th>
                                    <th class="py-3 px-6 text-left font-semibold glass-table-heading">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-white text-sm">
                                @forelse($purchaseRequests as $request)
                                    <tr class="border-b border-gray-200 hover:bg-white/10 transition-all duration-150">
                                        <td class="py-4 px-6">
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <span class="glass-table-text text-white">{{ $request->name }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span class="glass-table-text text-white">{{ $request->order_date->format('M d, Y') }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                                <span class="glass-table-text text-white">{{ $request->department->name }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">
                                            @php
                                                $mode = $request->mode_of_procurement;
                                                $modeClass = match ($mode) {
                                                    'Alternative' => 'bg-blue-500/20 text-blue-200',
                                                    'Competitive' => 'bg-green-500/20 text-green-200',
                                                    default => 'bg-gray-500/20 text-gray-200'
                                                };
                                            @endphp
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap glass-badge {{ $modeClass }}">
                                                {{ $mode }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-6">
                                            @php
                                                $statusClassMap = [
                                                    'ATP' => 'bg-purple-500/20 text-purple-200',
                                                    'Procurement' => 'bg-blue-500/20 text-blue-200',
                                                    'Posting in PhilGEPS' => 'bg-indigo-500/20 text-indigo-200',
                                                    'Pre-Bid' => 'bg-cyan-500/20 text-cyan-200',
                                                    'Bid Opening' => 'bg-teal-500/20 text-teal-200',
                                                    'Bid Evaluation' => 'bg-emerald-500/20 text-emerald-200',
                                                    'Post Qualification' => 'bg-green-500/20 text-green-200',
                                                    'Confirmation on Approval' => 'bg-lime-500/20 text-lime-200',
                                                    'Issuance of Notice of Award' => 'bg-yellow-500/20 text-yellow-200',
                                                    'Purchase Order' => 'bg-amber-500/20 text-amber-200',
                                                    'Contract' => 'bg-orange-500/20 text-orange-200',
                                                    'Notice to Proceed' => 'bg-red-500/20 text-red-200',
                                                    'Posting of Award in PhilGEPS' => 'bg-pink-500/20 text-pink-200',
                                                    'Forward Purchase or Supply' => 'bg-rose-500/20 text-rose-200',
                                                ];
                                                $statusClass = $statusClassMap[$request->status] ?? 'bg-gray-500/20 text-gray-200';
                                            @endphp
                                            <span class="px-3 py-1 rounded-full text-sm font-semibold whitespace-nowrap glass-badge {{ $statusClass }}">
                                                {{ $request->status }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="flex items-center space-x-3">
                                                <a href="{{ route('purchase-requests.show', $request) }}" 
                                                   class="glass-badge bg-blue-500/30 hover:bg-blue-500/40 text-white/90 flex items-center px-3 py-1 rounded-md transition-all duration-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    <span class="font-medium">View</span>
                                                </a>
                                                <a href="{{ route('purchase-requests.timeline', $request) }}" 
                                                   class="glass-badge bg-purple-500/30 hover:bg-purple-500/40 text-white/90 flex items-center px-3 py-1 rounded-md transition-all duration-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                    </svg>
                                                    <span class="font-medium">Timeline</span>
                                                </a>
                                                @if(auth()->user()->isAdmin())
                                                    <a href="{{ route('purchase-requests.edit', $request) }}" 
                                                       class="glass-badge bg-green-500/30 hover:bg-green-500/40 text-white/90 flex items-center px-3 py-1 rounded-md transition-all duration-200" aria-label="Edit">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                    <a href="{{ route('purchase-requests.delete-confirm', $request) }}" 
                                                       class="glass-badge bg-red-500/20 hover:bg-red-500/30 text-red-200 hover:text-red-100 flex items-center px-3 py-1 rounded-md transition-all duration-200" aria-label="Delete">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-white">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                </svg>
                                                <p class="text-lg text-white">No purchase requests found.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $purchaseRequests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>