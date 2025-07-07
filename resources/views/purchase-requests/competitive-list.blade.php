<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Competitive Bids List') }}
            </h2>
            <div class="flex gap-4">
                <a href="{{ route('purchase-requests.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    New Purchase Request
                </a>
                <a href="{{ route('purchase-requests.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and Filter Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <form method="GET" action="{{ route('purchase-requests.competitive-list') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Search by PR number or name...">
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Statuses</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
                            <select name="department" id="department"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Departments</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ request('department') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded-md hover:bg-indigo-600">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Competitive Bids List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($purchaseRequests->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PR Number</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Funding</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($purchaseRequests as $request)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $request->pr_number }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $request->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $request->department->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-3 py-1 rounded-full text-sm font-semibold
                                                    @switch($request->status)
                                                        @case('ATP') bg-purple-100 text-purple-800 @break
                                                        @case('Procurement') bg-blue-100 text-blue-800 @break
                                                        @case('Posting in PhilGEPS') bg-indigo-100 text-indigo-800 @break
                                                        @case('Pre-Bid') bg-cyan-100 text-cyan-800 @break
                                                        @case('Bid Opening') bg-teal-100 text-teal-800 @break
                                                        @case('Bid Evaluation') bg-emerald-100 text-emerald-800 @break
                                                        @case('Post Qualification') bg-green-100 text-green-800 @break
                                                        @case('Confirmation on Approval') bg-lime-100 text-lime-800 @break
                                                        @case('Issuance of Notice of Award') bg-yellow-100 text-yellow-800 @break
                                                        @case('Purchase Order') bg-amber-100 text-amber-800 @break
                                                        @case('Contract') bg-orange-100 text-orange-800 @break
                                                        @case('Notice to Proceed') bg-red-100 text-red-800 @break
                                                        @case('Posting of Award in PhilGEPS') bg-pink-100 text-pink-800 @break
                                                        @case('Forward Purchase or Supply') bg-rose-100 text-rose-800 @break
                                                        @default bg-gray-100 text-gray-800
                                                    @endswitch">
                                                    {{ $request->status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $request->order_date->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $request->funding }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('purchase-requests.show', $request) }}" 
                                                       class="text-indigo-600 hover:text-indigo-900">View</a>
                                                    <a href="{{ route('purchase-requests.timeline', $request) }}" 
                                                       class="text-green-600 hover:text-green-900">Timeline</a>
                                                    @if(auth()->user()->isAdmin())
                                                        <a href="{{ route('purchase-requests.edit', $request) }}" 
                                                           class="text-blue-600 hover:text-blue-900">Edit</a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $purchaseRequests->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-gray-500">No competitive bids found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 