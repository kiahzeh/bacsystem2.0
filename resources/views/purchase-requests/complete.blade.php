<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <h2 class="font-semibold text-xl text-white leading-tight flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Complete Purchase Request
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="glassmorphism-card overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-white mb-2">PR Details</h3>
                        <div class="bg-white/10 rounded-lg p-4">
                            <p class="text-white"><strong>PR Number:</strong> {{ $purchaseRequest->pr_number }}</p>
                            <p class="text-white"><strong>Project Title:</strong> {{ $purchaseRequest->project_title }}</p>
                            <p class="text-white"><strong>Current Status:</strong> {{ $purchaseRequest->status }}</p>
                            <p class="text-white"><strong>ABC Budget:</strong> ₱{{ number_format($purchaseRequest->abc_approved_budget, 2) }}</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('purchase-requests.complete.store', $purchaseRequest) }}" class="space-y-6">
                        @csrf

                        <!-- Completion Date -->
                        <div>
                            <x-input-label for="completion_date" :value="__('Completion Date')" class="text-white" />
                            <x-text-input id="completion_date" name="completion_date" type="date" class="mt-1 block w-full glassmorphism-input"
                                :value="old('completion_date', date('Y-m-d'))" required />
                            <x-input-error class="mt-2" :messages="$errors->get('completion_date')" />
                        </div>

                        <!-- Final Amount -->
                        <div>
                            <x-input-label for="final_amount" :value="__('Final Contract Amount')" class="text-white" />
                            <x-text-input id="final_amount" name="final_amount" type="number" step="0.01" min="0" class="mt-1 block w-full glassmorphism-input"
                                :value="old('final_amount')" required placeholder="0.00" />
                            <x-input-error class="mt-2" :messages="$errors->get('final_amount')" />
                        </div>

                        <!-- Awarded Vendor -->
                        <div>
                            <x-input-label for="awarded_vendor" :value="__('Awarded Vendor/Supplier')" class="text-white" />
                            <x-text-input id="awarded_vendor" name="awarded_vendor" type="text" class="mt-1 block w-full glassmorphism-input"
                                :value="old('awarded_vendor')" required placeholder="Enter vendor/supplier name" />
                            <x-input-error class="mt-2" :messages="$errors->get('awarded_vendor')" />
                        </div>

                        <!-- Contract Number -->
                        <div>
                            <x-input-label for="contract_number" :value="__('Contract Number (Optional)')" class="text-white" />
                            <x-text-input id="contract_number" name="contract_number" type="text" class="mt-1 block w-full glassmorphism-input"
                                :value="old('contract_number')" placeholder="Enter contract number if available" />
                            <x-input-error class="mt-2" :messages="$errors->get('contract_number')" />
                        </div>

                        <!-- Completion Notes -->
                        <div>
                            <x-input-label for="completion_notes" :value="__('Completion Notes (Optional)')" class="text-white" />
                            <textarea id="completion_notes" name="completion_notes"
                                class="mt-1 block w-full glassmorphism-input"
                                rows="4" placeholder="Add any additional notes about the completion...">{{ old('completion_notes') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('completion_notes')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded-md transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Complete Purchase Request
                            </button>
                            <a href="{{ route('purchase-requests.show', $purchaseRequest) }}"
                                class="inline-flex items-center px-4 py-2 glassmorphism-button-secondary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>