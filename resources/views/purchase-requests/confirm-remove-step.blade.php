<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <button onclick="history.back()" 
                        class="bg-gray-500/20 hover:bg-gray-500/40 text-gray-200 hover:text-white px-4 py-2 rounded-lg font-medium transition flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back
                </button>
                <h2 class="font-semibold text-xl text-white leading-tight">
                    {{ __('Confirm Step Removal') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-card overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <!-- Warning Icon -->
                    <div class="flex justify-center mb-6">
                        <div class="w-20 h-20 rounded-full bg-red-500/20 border-2 border-red-400 flex items-center justify-center">
                            <svg class="w-10 h-10 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Confirmation Message -->
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-white glass-heading mb-4">
                            Remove Workflow Step
                        </h3>
                        <p class="text-white glass-text text-lg mb-2">
                            Are you sure you want to remove this step?
                        </p>
                        <div class="glass-card p-4 rounded-lg inline-block">
                            <p class="text-orange-200 font-semibold text-xl">
                                "{{ $stepName }}"
                            </p>
                        </div>
                        <p class="text-white/70 mt-4 text-sm">
                            This action cannot be undone. The step will be permanently removed from the workflow.
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-center space-x-4">
                        <a href="{{ route('purchase-requests.timeline', $purchaseRequest) }}" 
                           class="bg-gray-500/20 hover:bg-gray-500/40 text-gray-200 hover:text-white px-6 py-3 rounded-lg font-medium transition">
                            Cancel
                        </a>
                        
                        <form method="POST" action="{{ route('purchase-requests.workflow.remove-step', [$purchaseRequest, $stepIndex]) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-500/20 hover:bg-red-500/40 text-red-200 hover:text-red-100 px-6 py-3 rounded-lg font-medium transition">
                                Remove Step
                            </button>
                        </form>
                    </div>

                    <!-- Additional Info -->
                    <div class="mt-8 p-4 glass-card rounded-lg">
                        <h4 class="text-white glass-heading mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            What happens when you remove a step?
                        </h4>
                        <ul class="text-white/70 text-sm space-y-2">
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                The step will be permanently removed from the workflow
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Any documents uploaded for this step will remain but won't be associated with a workflow step
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                The step order will be automatically adjusted
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 