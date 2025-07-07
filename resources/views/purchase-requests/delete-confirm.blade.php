<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Confirm Delete Purchase Request') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-red-800">Delete Purchase Request</h3>
                        </div>
                        <p class="mt-2 text-red-700">
                            Are you sure you want to delete this purchase request? This action cannot be undone.
                        </p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Purchase Request Details</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <span class="text-gray-600">PR Number:</span>
                                <span class="ml-2 font-medium">{{ $purchaseRequest->pr_number }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Name:</span>
                                <span class="ml-2 font-medium">{{ $purchaseRequest->name }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Department:</span>
                                <span class="ml-2 font-medium">{{ $purchaseRequest->department->name }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Status:</span>
                                <span class="ml-2 font-medium">{{ $purchaseRequest->status }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Type:</span>
                                <span class="ml-2 font-medium">{{ ucfirst($purchaseRequest->type) }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Created By:</span>
                                <span class="ml-2 font-medium">{{ $purchaseRequest->user->name }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Order Date:</span>
                                <span class="ml-2 font-medium">{{ $purchaseRequest->order_date->format('F j, Y') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Created At:</span>
                                <span class="ml-2 font-medium">{{ $purchaseRequest->created_at->format('F j, Y g:i A') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex space-x-4">
                            <form method="POST" action="{{ route('purchase-requests.destroy', $purchaseRequest) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Confirm Delete
                                </button>
                            </form>
                            <a href="{{ route('purchase-requests.index') }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Cancel
                            </a>
                        </div>
                        <a href="{{ route('purchase-requests.show', $purchaseRequest) }}" 
                           class="text-blue-600 hover:text-blue-800">
                            View Purchase Request
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 