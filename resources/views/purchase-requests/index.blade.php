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
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
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
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto glassmorphism-card rounded-lg overflow-y-auto relative">
                        <table class="min-w-full table-auto border-collapse">
                            <thead>
                                <tr class="bg-gray-50/60 text-white uppercase text-sm leading-normal">
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
                                                $badgeClass = 'bg-gray-100 text-gray-800';
                                                if ($mode === 'Alternative') $badgeClass = 'bg-blue-100 text-blue-800';
                                                elseif ($mode === 'Competitive') $badgeClass = 'bg-green-100 text-green-800';
                                            @endphp
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                                {{ $mode }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <span class="px-3 py-1 rounded-full text-sm font-semibold bg-white text-gray-800 border-2
                                                @switch($request->status)
                                                    @case('ATP')
                                                        border-purple-300
                                                        @break
                                                    @case('Procurement')
                                                        border-blue-300
                                                        @break
                                                    @case('Posting in PhilGEPS')
                                                        border-indigo-300
                                                        @break
                                                    @case('Pre-Bid')
                                                        border-cyan-300
                                                        @break
                                                    @case('Bid Opening')
                                                        border-teal-300
                                                        @break
                                                    @case('Bid Evaluation')
                                                        border-emerald-300
                                                        @break
                                                    @case('Post Qualification')
                                                        border-green-300
                                                        @break
                                                    @case('Confirmation on Approval')
                                                        border-lime-300
                                                        @break
                                                    @case('Issuance of Notice of Award')
                                                        border-yellow-300
                                                        @break
                                                    @case('Purchase Order')
                                                        border-amber-300
                                                        @break
                                                    @case('Contract')
                                                        border-orange-300
                                                        @break
                                                    @case('Notice to Proceed')
                                                        border-red-300
                                                        @break
                                                    @case('Posting of Award in PhilGEPS')
                                                        border-pink-300
                                                        @break
                                                    @case('Forward Purchase or Supply')
                                                        border-rose-300
                                                        @break
                                                    @default
                                                        border-gray-300
                                                @endswitch">
                                                {{ $request->status }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="flex items-center space-x-3">
                                                <a href="{{ route('purchase-requests.show', $request) }}" 
                                                   class="text-white bg-blue-600 hover:bg-blue-700 flex items-center px-3 py-1 rounded-md transition-all duration-200 shadow-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    <span class="font-medium">View</span>
                                                </a>
                                                <a href="{{ route('purchase-requests.timeline', $request) }}" 
                                                   class="text-white bg-purple-600 hover:bg-purple-700 flex items-center px-3 py-1 rounded-md transition-all duration-200 shadow-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                    </svg>
                                                    <span class="font-medium">Timeline</span>
                                                </a>
                                                @if(auth()->user()->isAdmin())
                                                    <a href="{{ route('purchase-requests.edit', $request) }}" 
                                                       class="text-white bg-green-600 hover:bg-green-700 flex items-center px-3 py-1 rounded-md transition-all duration-200 shadow-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                        <span class="font-medium">Edit</span>
                                                    </a>
                                                    <a href="{{ route('purchase-requests.delete-confirm', $request) }}" 
                                                       class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 flex items-center px-3 py-1 rounded-md transition-all duration-200 shadow-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        <span class="font-medium">Delete</span>
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