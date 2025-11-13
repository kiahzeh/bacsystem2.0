<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <h2 class="font-semibold text-xl text-white leading-tight">
                    {{ __('Confirm Reset to Default') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
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
                            Reset Workflow to Default
                        </h3>
                        <p class="text-white glass-text text-lg mb-6">
                            Are you sure you want to reset this purchase request's workflow to the default steps?
                        </p>
                        <p class="text-red-300 text-sm mb-6">
                            <strong>Warning:</strong> This action cannot be undone and will remove all custom workflow steps.
                        </p>
                    </div>

                    <!-- Current vs Default Steps Comparison -->
                    <div class="grid md:grid-cols-2 gap-6 mb-8">
                        <!-- Current Steps -->
                        <div class="glass-card p-6 rounded-lg border border-red-400/30">
                            <h4 class="text-white glass-heading mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Current Steps (Will be removed)
                            </h4>
                            @if(count($currentSteps) > 0)
                                <div class="space-y-2">
                                    @foreach($currentSteps as $index => $step)
                                        <div class="flex items-center p-2 bg-red-500/10 rounded">
                                            <span class="text-red-200 text-sm font-medium mr-2">{{ $loop->iteration }}.</span>
                                            <span class="text-white text-sm">{{ $step }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-white/70 text-sm">No custom steps defined</p>
                            @endif
                        </div>

                        <!-- Default Steps -->
                        <div class="glass-card p-6 rounded-lg border border-green-400/30">
                            <h4 class="text-white glass-heading mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Default Steps (Will be applied)
                            </h4>
                            <div class="space-y-2">
                                @foreach($defaultSteps as $index => $step)
                                    <div class="flex items-center p-2 bg-green-500/10 rounded">
                                        <span class="text-green-200 text-sm font-medium mr-2">{{ $loop->iteration }}.</span>
                                        <span class="text-white text-sm">{{ $step }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-center space-x-4">
                        <a href="{{ route('purchase-requests.timeline', $purchaseRequest) }}" 
                           class="bg-gray-500/20 hover:bg-gray-500/40 text-gray-200 hover:text-white px-6 py-3 rounded-lg font-medium transition">
                            Cancel
                        </a>
                        
                        <form method="POST" action="{{ route('purchase-requests.workflow.reset-to-default', $purchaseRequest) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="bg-red-500/20 hover:bg-red-500/40 text-red-200 hover:text-red-100 px-6 py-3 rounded-lg font-medium transition">
                                Reset to Default
                            </button>
                        </form>
                    </div>

                    <!-- Additional Info -->
                    <div class="mt-8 p-4 glass-card rounded-lg">
                        <h4 class="text-white glass-heading mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            What happens when you reset to default?
                        </h4>
                        <ul class="text-white/70 text-sm space-y-2">
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                All custom workflow steps will be permanently removed
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                The workflow will be reset to the default process steps
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                The current status will be updated to match the first default step
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                All uploaded documents and status history will be preserved
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                This action cannot be undone - please make sure this is what you want
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>