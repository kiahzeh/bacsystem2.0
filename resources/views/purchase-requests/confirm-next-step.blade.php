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
                    {{ __('Confirm Next Step') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-card overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <!-- Success Icon -->
                    <div class="flex justify-center mb-6">
                        <div class="w-20 h-20 rounded-full bg-green-500/20 border-2 border-green-400 flex items-center justify-center">
                            <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Confirmation Message -->
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-white glass-heading mb-4">
                            Advance Workflow Step
                        </h3>
                        <p class="text-white glass-text text-lg mb-2">
                            Are you sure you want to advance to the next step?
                        </p>
                        <div class="glass-card p-4 rounded-lg inline-block">
                            <div class="flex items-center space-x-4">
                                <div class="text-center">
                                    <p class="text-red-200 font-semibold text-lg">Current</p>
                                    <p class="text-white font-semibold text-xl">"{{ $currentStep }}"</p>
                                </div>
                                <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                                <div class="text-center">
                                    <p class="text-green-200 font-semibold text-lg">Next</p>
                                    <p class="text-white font-semibold text-xl">"{{ $nextStep }}"</p>
                                </div>
                            </div>
                        </div>
                        <p class="text-white/70 mt-4 text-sm">
                            This will mark the current step as completed and advance the workflow.
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-center space-x-4">
                        <a href="{{ route('purchase-requests.timeline', $purchaseRequest) }}" 
                           class="bg-gray-500/20 hover:bg-gray-500/40 text-gray-200 hover:text-white px-6 py-3 rounded-lg font-medium transition">
                            Cancel
                        </a>
                        
                        <form method="POST" action="{{ route('purchase-requests.workflow.next-step', [$purchaseRequest, $stepIndex]) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="bg-green-500/20 hover:bg-green-500/40 text-green-200 hover:text-green-100 px-6 py-3 rounded-lg font-medium transition">
                                Advance Step
                            </button>
                        </form>
                    </div>

                    <!-- Additional Info -->
                    <div class="mt-8 p-4 glass-card rounded-lg">
                        <h4 class="text-white glass-heading mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            What happens when you advance a step?
                        </h4>
                        <ul class="text-white/70 text-sm space-y-2">
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                The current step will be marked as completed
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                The purchase request status will be updated to the next step
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                A notification will be sent to the PR owner about the status change
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                The workflow will proceed to the next step automatically
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 