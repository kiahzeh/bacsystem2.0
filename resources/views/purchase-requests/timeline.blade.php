<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <h2 class="font-semibold text-xl text-white leading-tight">
                    {{ __('Purchase Request Timeline') }} - {{ $purchaseRequest->name }}
                </h2>
            </div>
            <div class="flex gap-4">
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('processes.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Manage Processes
                    </a>
                    <a href="{{ route('purchase-requests.edit', $purchaseRequest) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Edit Request
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glassmorphism-card overflow-visible shadow-sm sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="bg-green-500/20 border border-green-400/30 text-green-200 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-500/20 border border-red-400/30 text-red-200 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif
                <!-- Request Details -->
                <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-white glass-heading mb-4">Request Information</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-white glass-text">PR Number:</span>
                                <span class="ml-2 font-medium text-white glass-text">{{ $purchaseRequest->pr_number }}</span>
                            </div>
                            <div>
                                <span class="text-white glass-text">Name:</span>
                                <span class="ml-2 font-medium text-white glass-text">{{ $purchaseRequest->name }}</span>
                            </div>
                            <div>
                                <span class="text-white glass-text">Department:</span>
                                <span class="ml-2 font-medium text-white glass-text">{{ $purchaseRequest->department->name }}</span>
                            </div>
                            <div>
                                <span class="text-white glass-text">Order Date:</span>
                                <span class="ml-2 font-medium text-white glass-text">{{ $purchaseRequest->order_date->format('F j, Y') }}</span>
                            </div>
                            <div>
                                <span class="text-white glass-text">Last Updated:</span>
                                <span class="ml-2 font-medium text-white glass-text">{{ $purchaseRequest->updated_at->format('F j, Y H:i:s') }}</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-white glass-heading mb-4">Current Status</h3>
                        <div class="space-y-4">
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
                            $statusClass = $statusClassMap[$purchaseRequest->status] ?? 'bg-gray-500/20 text-gray-200';
                        @endphp
                        <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium glass-badge {{ $statusClass }}">
                            {{ $purchaseRequest->status }}
                        </div>
                            
                            <!-- Progress Bar -->
                            <div class="w-full bg-gray-500/20 rounded-full h-2.5">
                                @php
                                    $currentStep = array_search($purchaseRequest->status, $allStatuses);
                                    $totalSteps = count($allStatuses);
                                    $progress = ($totalSteps > 1) ? (($currentStep / ($totalSteps - 1)) * 100) : 100;
                                @endphp
                                <div class="progress-bar bg-orange-400 h-2.5 rounded-full transition-all duration-500" data-progress="{{ number_format($progress, 2) }}"></div>
                            </div>
                            <p class="text-sm text-white glass-text">Progress: {{ number_format($progress, 1) }}%</p>
                            
                            @if($purchaseRequest->status === 'Completed')
                                @php
                                    $autoCompletedSteps = $purchaseRequest->statusHistory()->where('is_skipped', true)->where('status', '!=', 'Completed')->count();
                                    $totalSteps = count($allStatuses);
                                    $completedSteps = $purchaseRequest->statusHistory()->where('completed_at', '!=', null)->where('status', '!=', 'Completed')->count();
                                @endphp
                                @if($autoCompletedSteps > 0)
                                    <div class="mt-3 p-3 bg-yellow-500/10 border border-yellow-400/30 rounded-lg">
                                        <div class="flex items-center text-yellow-200 text-sm">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="font-medium">Workflow Summary:</span>
                                        </div>
                                        <div class="mt-2 text-xs text-yellow-300 space-y-1">
                                            <p>• {{ $completedSteps }} steps completed normally</p>
                                            <p>• {{ $autoCompletedSteps }} steps auto-completed when PR was finished</p>
                                            <p>• Total: {{ $totalSteps }} workflow steps</p>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Workflow Management Section (Admin Only) -->
                @if(auth()->user()->isAdmin())
                    <div class="mb-8 glassmorphism-card rounded-lg p-6 transition-all duration-300" 
                         x-bind:class="workflowOpen ? 'ring-2 ring-blue-400/30 bg-blue-500/5' : ''"
                         x-data="{ workflowOpen: false }"
                         x-init="workflowOpen = localStorage.getItem('workflowOpen') === 'true'"
                         x-effect="localStorage.setItem('workflowOpen', workflowOpen)">
                        <div class="flex items-center justify-between cursor-pointer hover:bg-white/5 p-2 rounded-lg transition-colors duration-200" x-on:click="workflowOpen = !workflowOpen">
                            <h3 class="text-lg font-bold text-white glass-heading flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                                Customize Workflow Steps
                            </h3>
                            <div class="flex items-center space-x-2">
                                <span class="text-white/70 text-sm" x-text="workflowOpen ? 'Click to collapse' : 'Click to expand'"></span>
                                <svg class="w-5 h-5 text-white/70 transition-transform duration-200" 
                                     x-bind:class="workflowOpen ? 'rotate-180' : ''"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        
                        <div x-show="workflowOpen" x-transition.duration.200ms.opacity.scale x-cloak>
                            <p class="text-white/70 text-sm mb-4 mt-4">
                                Add or remove workflow steps specific to this purchase request. Changes only affect this PR.
                            </p>
                        
                        <!-- Add New Step Form -->
                        <form method="POST" action="{{ route('purchase-requests.workflow.add-step', $purchaseRequest) }}" class="mb-6">
                            @csrf
                            <div class="flex items-center space-x-4">
                                <div class="flex-1">
                                    <input type="text" 
                                           name="step_name" 
                                           placeholder="Enter new step name..." 
                                           class="w-full px-4 py-2 rounded-lg glass-input focus:outline-none focus:ring-2 focus:ring-orange-400/50"
                                           required>
                                </div>
                                <button type="submit" 
                                        class="bg-orange-500/20 hover:bg-orange-500/40 text-orange-200 hover:text-orange-100 px-6 py-2 rounded-lg font-medium transition">
                                    Add Step
                                </button>
                            </div>
                        </form>

                        <!-- Current Workflow Steps -->
                        <div class="mb-4">
                            <h4 class="text-white glass-heading mb-3">Current Workflow Steps</h4>
                            <ul id="workflow-steps-list" class="space-y-2">
                                @foreach($allStatuses as $index => $status)
                                    <li class="workflow-step-item flex items-center justify-between p-3 glassmorphism-card rounded-lg" data-index="{{ $index }}">
                                        <div class="flex items-center space-x-3">
                                            <span class="text-white/70 text-sm font-mono">#{{ $index + 1 }}</span>
                                            <span class="text-white glass-text">{{ $status }}</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            @if($status === $purchaseRequest->status)
                                                <span class="px-2 py-1 bg-orange-500/20 text-orange-200 rounded text-xs">
                                                    Current
                                                </span>
                                            @endif
                                            <a href="{{ route('purchase-requests.workflow.remove-step.confirm', [$purchaseRequest, $index]) }}" 
                                               class="text-red-300 hover:text-red-200 text-xs transition">
                                                Remove
                                            </a>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- SortableJS CDN for Workflow Steps -->
                        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
                        @if(auth()->user()->isAdmin())
                        <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const list = document.getElementById('workflow-steps-list');
                            if (!list) return;
                            new Sortable(list, {
                                animation: 150,
                                onEnd: function () {
                                    const order = Array.from(document.querySelectorAll('.workflow-step-item')).map(el => el.dataset.index);
                                    const reorderUrl = "{{ route('purchase-requests.workflow.reorder-steps', $purchaseRequest) }}";
                                    fetch(reorderUrl, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'Accept': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        body: JSON.stringify({ order: order })
                                    });
                                }
                            });
                        });
                        </script>
                        @endif

                        <!-- Reset to Default -->
                        <div class="border-t border-white/20 pt-4">
                            <p class="text-white/70 text-sm mb-2">
                                Reset this PR's workflow to use the default process steps:
                            </p>
                            <a href="{{ route('purchase-requests.workflow.reset-to-default.confirm', $purchaseRequest) }}" 
                               class="bg-gray-500/20 hover:bg-gray-500/40 text-gray-200 hover:text-white px-4 py-2 rounded-lg text-sm transition inline-block">
                                Reset to Default
                            </a>
                        </div>
                        </div>
                    </div>
                @endif

                <!-- Filter & Quick-Jump -->
                <div class="mb-8 glassmorphism-card rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-semibold">Filter & Quick-Jump</h3>
                        <span class="px-2 py-1 text-xs rounded bg-orange-500/20 text-orange-200">Bro</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="statusFilter" class="block text-xs text-white/70 mb-1">Status</label>
                            <select id="statusFilter" class="w-full px-3 py-2 rounded-lg glass-input focus:outline-none focus:ring-2 focus:ring-orange-400/50">
                                <option value="">All</option>
                                <option value="completed">Completed</option>
                                <option value="skipped">Skipped</option>
                                <option value="inprogress">In Progress</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                        <div>
                            <label for="actorFilter" class="block text-xs text-white/70 mb-1">Actor</label>
                            <input id="actorFilter" type="text" placeholder="Actor name..." class="w-full px-3 py-2 rounded-lg glass-input focus:outline-none focus:ring-2 focus:ring-orange-400/50" />
                        </div>
                        <div>
                            <label for="dateFromFilter" class="block text-xs text-white/70 mb-1">From Date</label>
                            <input id="dateFromFilter" type="date" class="w-full px-3 py-2 rounded-lg glass-input focus:outline-none focus:ring-2 focus:ring-orange-400/50" />
                        </div>
                        <div>
                            <label for="quickJump" class="block text-xs text-white/70 mb-1">Quick Jump</label>
                            <div class="flex items-center space-x-2">
                                <select id="quickJump" class="flex-1 px-3 py-2 rounded-lg glass-input focus:outline-none focus:ring-2 focus:ring-orange-400/50">
                                    @foreach($allStatuses as $i => $s)
                                        <option value="step-{{ $i + 1 }}">Step {{ $i + 1 }}: {{ $s }}</option>
                                    @endforeach
                                </select>
                                <button id="quickJumpBtn" type="button" class="px-4 py-2 bg-blue-500/20 hover:bg-blue-500/40 text-blue-200 hover:text-blue-100 rounded-lg text-sm font-medium transition">Go</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Timeline -->
                <div class="mt-16">
                    <div class="space-y-6">
                        @foreach($allStatuses as $index => $status)
                            @php
                                $isCurrentStatus = $status === $purchaseRequest->status;
                                $isPastStatus = array_search($status, $allStatuses) < array_search($purchaseRequest->status, $allStatuses);
                                $isFutureStatus = array_search($status, $allStatuses) > array_search($purchaseRequest->status, $allStatuses);
                                $statusHistory = $purchaseRequest->statusHistory()->where('status', $status)->first();
                                $requiredDocuments = $purchaseRequest->getRequiredDocuments($status);
                                $uploadedDocuments = $purchaseRequest->documents()->where('status', $status)->get();
                                $pendingDocs = $purchaseRequest->getPendingDocumentsForStep($status);
                                $rejectedDocs = $purchaseRequest->getRejectedDocumentsForStep($status);
                                $docsApproved = $purchaseRequest->areDocumentsApprovedForStep($status);
                                $statusType = $isPastStatus ? (($statusHistory && $statusHistory->is_skipped) ? 'skipped' : 'completed') : ($isCurrentStatus ? 'inprogress' : 'pending');
                                $actorName = ($statusHistory && $statusHistory->user) ? $statusHistory->user->name : '';
                                $startedDate = ($statusHistory && $statusHistory->started_at) ? $statusHistory->started_at->format('Y-m-d') : '';
                                $completedDate = ($statusHistory && $statusHistory->completed_at) ? $statusHistory->completed_at->format('Y-m-d') : '';
                            @endphp
                            
                            <div id="step-{{ $index + 1 }}" class="relative"
                                 data-step-name="{{ $status }}"
                                 data-status-type="{{ $statusType }}"
                                 data-actor="{{ $actorName }}"
                                 data-started="{{ $startedDate }}"
                                 data-completed="{{ $completedDate }}">
                                <!-- Connection Line -->
                                @if($index < count($allStatuses) - 1)
                                    <div class="absolute left-8 top-16 w-0.5 h-12 {{ $isPastStatus ? 'bg-green-400' : 'bg-gray-400/30' }}"></div>
                                @endif
                                
                                <div class="glassmorphism-card rounded-xl p-6 {{ $isCurrentStatus ? 'ring-2 ring-orange-400/50 shadow-lg' : ($isPastStatus ? 'ring-1 ring-green-400/30' : 'ring-1 ring-gray-400/20') }}">
                                    <div class="flex items-start space-x-4">
                                        <!-- Step Indicator -->
                                        <div class="flex-shrink-0">
                                            <div class="relative">
                                                @if($isPastStatus)
                                                    @if($statusHistory && $statusHistory->is_skipped)
                                                        <!-- Skipped Step -->
                                                        <div class="w-16 h-16 rounded-full bg-yellow-500/20 border-2 border-yellow-400 flex items-center justify-center relative" title="Skipped">
                                                            <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                                            </svg>
                                                            <div class="absolute -top-1 -right-1 w-5 h-5 bg-yellow-400 rounded-full flex items-center justify-center">
                                                                <span class="text-yellow-900 text-xs font-bold">S</span>
                                                            </div>
                                                            @if($uploadedDocuments->count() > 0)
                                                                <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-yellow-400/80 rounded-full flex items-center justify-center" title="{{ $uploadedDocuments->count() }} document(s) uploaded">
                                                                    <svg class="w-3 h-3 text-yellow-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                    </svg>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <!-- Completed Step -->
                                                        <div class="w-16 h-16 rounded-full bg-green-500/20 border-2 border-green-400 flex items-center justify-center relative" title="Completed">
                                                            <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                            @if($docsApproved)
                                                                <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-400 rounded-full flex items-center justify-center" title="Documents approved">
                                                                    <svg class="w-3 h-3 text-green-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                    </svg>
                                                                </div>
                                                            @elseif($uploadedDocuments->count() > 0)
                                                                <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-400/70 rounded-full flex items-center justify-center" title="{{ $uploadedDocuments->count() }} document(s) uploaded">
                                                                    <svg class="w-3 h-3 text-green-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                    </svg>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endif
                                                @elseif($isCurrentStatus)
                                                    <!-- Current Step -->
                                                    <div class="w-16 h-16 rounded-full bg-orange-500/20 border-2 border-orange-400 flex items-center justify-center relative">
                                                        <div class="w-8 h-8 bg-orange-400 rounded-full animate-pulse"></div>
                                                        <div class="absolute -top-1 -right-1 w-6 h-6 bg-orange-400 rounded-full flex items-center justify-center">
                                                            <span class="text-white text-xs font-bold">{{ $index + 1 }}</span>
                                                        </div>
                                                        @if($rejectedDocs->count() > 0)
                                                            <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-red-400 rounded-full flex items-center justify-center" title="{{ $rejectedDocs->count() }} rejected document(s)">
                                                                <svg class="w-3 h-3 text-red-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </div>
                                                        @elseif($pendingDocs->count() > 0)
                                                            <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-yellow-400 rounded-full flex items-center justify-center" title="{{ $pendingDocs->count() }} pending document(s)">
                                                                <svg class="w-3 h-3 text-yellow-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                                </svg>
                                                            </div>
                                                        @elseif($docsApproved)
                                                            <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-400 rounded-full flex items-center justify-center" title="Documents approved">
                                                                <svg class="w-3 h-3 text-green-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @else
                                                    <!-- Future Step -->
                                                    <div class="w-16 h-16 rounded-full bg-gray-500/20 border-2 border-gray-400/50 flex items-center justify-center relative">
                                                        <span class="text-gray-400 text-lg font-bold">{{ $index + 1 }}</span>
                                                        @if(count($requiredDocuments) > 0)
                                                            <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-gray-400/60 rounded-full flex items-center justify-center" title="{{ count($requiredDocuments) }} document(s) required">
                                                                <svg class="w-3 h-3 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Step Content -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between mb-2">
                                                <div>
                                                    <h3 class="text-lg font-semibold {{ $isCurrentStatus ? 'text-orange-200' : ($isPastStatus ? 'text-green-200' : 'text-white glass-text') }}">
                                                        Step {{ $index + 1 }}: {{ $status }}
                                                    </h3>
                                                    @if($statusHistory)
                                                        <div class="text-sm text-white/70 mt-2 space-y-1">
                                                            @if($statusHistory->started_at)
                                                                <p class="flex items-center">
                                                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                    </svg>
                                                                    Started: {{ $statusHistory->started_at->format('M d, Y H:i') }}
                                                                </p>
                                                            @endif
                                                            @if($statusHistory->completed_at)
                                                                <p class="flex items-center">
                                                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                    </svg>
                                                                    Completed: {{ $statusHistory->completed_at->format('M d, Y H:i') }}
                                                                    @if($statusHistory && $statusHistory->user)
                                                                        <span class="ml-2 text-white/60">by {{ $statusHistory->user->name }}</span>
                                                                    @endif
                                                                </p>
                                                            @endif
                                                            @if($statusHistory->started_at && $statusHistory->completed_at)
                                                                <p class="flex items-center text-green-300">
                                                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                                    </svg>
                                                                    Duration: {{ $statusHistory->duration ?? $statusHistory->started_at->diffInHours($statusHistory->completed_at) }} hours
                                                                </p>
                                                            @endif
                                                            @if($statusHistory && $statusHistory->is_skipped)
                                                                <p class="flex items-center text-yellow-300 text-xs">
                                                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                    </svg>
                                                                    Skipped: {{ optional($statusHistory->completed_at)->format('M d, Y H:i') }}
                                                                    @if($statusHistory->user)
                                                                        <span class="ml-2 text-white/60">by {{ $statusHistory->user->name }}</span>
                                                                    @endif
                                                                </p>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                <!-- Status Badge -->
                                                <div class="flex items-center space-x-2">
                                                    @if($isPastStatus)
                                                        @if($statusHistory && $statusHistory->is_skipped)
                                                            <span class="px-3 py-1 bg-yellow-500/20 text-yellow-200 rounded-full text-sm font-medium flex items-center" title="Skipped">
                                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                                                </svg>
                                                                Skipped
                                                            </span>
                                                        @else
                                                            <span class="px-3 py-1 bg-green-500/20 text-green-200 rounded-full text-sm font-medium" title="Completed">
                                                                Completed
                                                            </span>
                                                        @endif
                                                    @elseif($isCurrentStatus)
                                                        <span class="px-3 py-1 bg-orange-500/20 text-orange-200 rounded-full text-sm font-medium animate-pulse">
                                                            In Progress
                                                        </span>
                                                    @else
                                                        <span class="px-3 py-1 bg-gray-500/20 text-gray-300 rounded-full text-sm font-medium">
                                                            Pending
                                                        </span>
                                                    @endif
                                                    
                                                    @if(auth()->user()->isAdmin())
                                                        <div class="flex space-x-1">
                                                            <a href="{{ route('purchase-requests.workflow.remove-step.confirm', [$purchaseRequest, $index]) }}" 
                                                               class="bg-red-500/20 hover:bg-red-500/40 text-red-300 hover:text-red-200 px-2 py-1 rounded text-xs transition" title="Remove this step from workflow">
                                                                Remove
                                                            </a>
                                                            <a href="{{ route('purchase-requests.workflow.skip-step.confirm', [$purchaseRequest, $index]) }}" 
                                                               class="bg-yellow-500/20 hover:bg-yellow-500/40 text-yellow-300 hover:text-yellow-200 px-2 py-1 rounded text-xs transition" title="Skip this step; advances if current">
                                                                Skip
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <!-- Document Upload Section (Only for Current Step) -->
                                            @if($isCurrentStatus)
                                                <div class="mt-6 p-4 glassmorphism-card rounded-lg border border-orange-400/30">
                                                    <!-- Workflow Actions -->
                                                    @if(auth()->user()->isAdmin())
                                                        <div class="mb-6 p-4 glassmorphism-card rounded-lg border border-blue-400/30">
                                                            <h4 class="text-sm font-medium text-white glass-heading mb-4 flex items-center">
                                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                                </svg>
                                                                Workflow Actions
                                                            </h4>
                                                            <div class="flex items-center space-x-4">
                                                                <!-- Next Step Button -->
                                                                @if($index < count($allStatuses) - 1)
                                                                    @php
                                                                        $pendingDocs = $purchaseRequest->getPendingDocumentsForStep($status);
                                                                        $rejectedDocs = $purchaseRequest->getRejectedDocumentsForStep($status);
                                                                        $canAdvance = $pendingDocs->count() === 0 && $rejectedDocs->count() === 0 && $purchaseRequest->areDocumentsApprovedForStep($status);
                                                                    @endphp
                                                                    
                                                                    @if($canAdvance)
                                                                        <a href="{{ route('purchase-requests.workflow.next-step.confirm', [$purchaseRequest, $index]) }}" 
                                                                           class="bg-green-500/20 hover:bg-green-500/40 text-green-200 hover:text-green-100 px-6 py-2 rounded-lg font-medium transition flex items-center" title="Advance to next step; marks current as complete">
                                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                                                            </svg>
                                                                            Next Step
                                                                        </a>
                                                                    @else
                                                                        <button disabled 
                                                                                class="bg-gray-500/20 text-gray-400 px-6 py-2 rounded-lg font-medium cursor-not-allowed flex items-center"
                                                                                title="Cannot advance: Documents need approval">
                                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                                            </svg>
                                                                            Next Step
                                                                        </button>
                                                                    @endif
                                                                @endif
                                                                
                                                                <!-- Skip Step Button -->
                                                                <a href="{{ route('purchase-requests.workflow.skip-step.confirm', [$purchaseRequest, $index]) }}" 
                                                                   class="bg-yellow-500/20 hover:bg-yellow-500/40 text-yellow-200 hover:text-yellow-100 px-6 py-2 rounded-lg font-medium transition flex items-center">
                                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                                                    </svg>
                                                                    Skip Step
                                                                </a>
                                                                
                                                                <!-- Current Step Info -->
                                                                <div class="ml-4 text-sm text-white/70">
                                                                    <span class="font-medium">Step {{ $index + 1 }} of {{ count($allStatuses) }}</span>
                                                                    @if($index < count($allStatuses) - 1)
                                                                        <span class="ml-2">→ Next: {{ $allStatuses[$index + 1] }}</span>
                                                                    @else
                                                                        <span class="ml-2 text-green-300">→ Final Step</span>
                                                                    @endif
                                                                    @if($statusHistory && $statusHistory->started_at)
                                                                        <div class="mt-1 text-xs text-white/60">
                                                                            Started: {{ $statusHistory->started_at->format('M d, Y H:i') }}
                                                                            @if($statusHistory && $statusHistory->user)
                                                                                <span class="ml-2">by {{ $statusHistory->user->name }}</span>
                                                                            @endif
                                                                        </div>
                                                                    @endif
                                                                    
                                                                    <!-- Document Approval Status -->
                                                                    @if($pendingDocs->count() > 0)
                                                                        <div class="mt-2 p-2 bg-yellow-500/20 border border-yellow-400/30 rounded text-xs text-yellow-200">
                                                                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                                            </svg>
                                                                            {{ $pendingDocs->count() }} document(s) pending approval
                                                                        </div>
                                                                    @endif
                                                                    
                                                                    @if($rejectedDocs->count() > 0)
                                                                        <div class="mt-2 p-2 bg-red-500/20 border border-red-400/30 rounded text-xs text-red-200">
                                                                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                            </svg>
                                                                            {{ $rejectedDocs->count() }} document(s) rejected - re-upload required
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    
                                                    <h4 class="text-sm font-medium text-white glass-heading mb-4 flex items-center">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                        Required Documents
                                                    </h4>
                                                    
                                                    <!-- Document List -->
                                                    <div class="space-y-3 mb-4">
                                                        @foreach($requiredDocuments as $doc)
                                                            <div class="flex items-center justify-between p-3 glassmorphism-card rounded-lg">
                                                                <span class="text-sm text-white glass-text">{{ $doc }}</span>
                                                                @php
                                                                    $isUploaded = $uploadedDocuments->contains('original_filename', $doc);
                                                                @endphp
                                                                <div class="flex items-center space-x-2">
                                                                    @if($isUploaded)
                                                                        <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                        </svg>
                                                                    @else
                                                                        <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                        </svg>
                                                                    @endif
                                                                    <span class="text-xs {{ $isUploaded ? 'text-green-300' : 'text-red-300' }}">
                                                                        {{ $isUploaded ? 'Uploaded' : 'Required' }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>

                                                    <!-- Upload Form -->
                                                    <form action="{{ route('purchase-requests.upload-document', $purchaseRequest) }}" 
                                                          method="POST" 
                                                          enctype="multipart/form-data" 
                                                          class="space-y-3">
                                                        @csrf
                                                        <input type="hidden" name="status" value="{{ $status }}">
                                                        <div class="flex items-center space-x-3">
                                                            <input type="file" 
                                                                   name="document" 
                                                                   class="flex-1 text-sm text-white/70 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-500/20 file:text-orange-200 hover:file:bg-orange-500/40">
                                                            <button type="submit" 
                                                                    class="bg-orange-500/20 hover:bg-orange-500/40 text-orange-200 hover:text-orange-100 px-4 py-2 rounded-lg text-sm font-medium transition">
                                                                Upload
                                                            </button>
                                                        </div>
                                                    </form>
                                                    
                                                    <!-- Uploaded Documents -->
                                                    @if($uploadedDocuments->count() > 0)
                                                        <div class="mt-4">
                                                            <h5 class="text-xs font-medium text-white/70 mb-3 flex items-center">
                                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                                </svg>
                                                                Uploaded Documents
                                                            </h5>
                                                            <ul class="space-y-2">
                                                                @foreach($uploadedDocuments as $document)
                                                                    <li class="flex items-center justify-between text-xs glassmorphism-card p-3 rounded-lg">
                                                                        <div class="flex items-center space-x-2">
                                                                            <svg class="w-4 h-4 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $document->getFileTypeIcon() }}"></path>
                                                                            </svg>
                                                                            <span class="text-white glass-text">{{ $document->original_filename }}</span>
                                                                        </div>
                                                                        <div class="flex items-center space-x-3">
                                                                            <span class="text-white/70">{{ $document->created_at->format('M d, Y H:i') }}</span>
                                                                            
                                                                            <!-- Approval Status Badge -->
                                                                            <span class="px-2 py-1 text-xs rounded-full
                                                                                @if($document->isApproved()) bg-green-500/20 text-green-300
                                                                                @elseif($document->isRejected()) bg-red-500/20 text-red-300
                                                                                @else bg-yellow-500/20 text-yellow-300 @endif">
                                                                                @if($document->isApproved())
                                                                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                                    </svg>
                                                                                    Approved
                                                                                @elseif($document->isRejected())
                                                                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                                    </svg>
                                                                                    Rejected
                                                                                @else
                                                                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                                    </svg>
                                                                                    Pending
                                                                                @endif
                                                                            </span>
                                                                            @if($document->path && $document->canBeViewedInBrowser())
                                                                                <a href="{{ route('documents.view', $document) }}" 
                                                                                   target="_blank"
                                                                                   title="View {{ $document->getExtension() }} file in new tab"
                                                                                   class="text-blue-300 hover:text-blue-200 transition flex items-center">
                                                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                                                    </svg>
                                                                                    View
                                                                                </a>
                                                                            @elseif(!$document->path)
                                                                                <span class="text-red-300 text-xs" title="File not found">
                                                                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                                                    </svg>
                                                                                    Missing
                                                                                </span>
                                                                            @endif
                                                                            @if($document->path)
                                                                                <a href="{{ route('documents.download', $document) }}" 
                                                                                   class="text-blue-300 hover:text-blue-200 transition flex items-center">
                                                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                                    </svg>
                                                                                    Download
                                                                                </a>
                                                                            @else
                                                                                <span class="text-red-300 text-xs" title="File not found">
                                                                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                                                    </svg>
                                                                                    Missing
                                                                                </span>
                                                                            @endif
                                                                            @if(auth()->user()->isAdmin())
                                                                                <!-- Approval Buttons -->
                                                                                @if($document->isPending())
                                                                                    <form action="{{ route('documents.approve', $document) }}" 
                                                                                          method="POST" 
                                                                                          class="inline">
                                                                                        @csrf
                                                                                        <button type="submit" 
                                                                                                class="text-green-300 hover:text-green-200 transition mr-2">
                                                                                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                                            </svg>
                                                                                            Approve
                                                                                        </button>
                                                                                    </form>
                                                                                    
                                                                                    <button type="button" 
                                                                                            onclick="showRejectModal('{{ $document->id }}', '{{ $document->original_filename }}')"
                                                                                            class="text-red-300 hover:text-red-200 transition mr-2">
                                                                                        <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                                        </svg>
                                                                                        Reject
                                                                                    </button>
                                                                                @endif
                                                                                
                                                                                <form action="{{ route('documents.destroy', $document) }}" 
                                                                                      method="POST" 
                                                                                      class="inline">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <button type="submit" 
                                                                                            class="text-red-300 hover:text-red-200 transition">
                                                                                        Delete
                                                                                    </button>
                                                                                </form>
                                                                            @endif
                                                                        </div>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if($purchaseRequest->remarks)
                    <div class="mt-16">
                        <h3 class="text-lg font-medium text-white glass-heading mb-2">Remarks</h3>
                        <div class="glassmorphism-card p-4 rounded-lg">
                            <p class="text-white glass-text">{{ $purchaseRequest->remarks }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Rejection Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Reject Document</h3>
            <p class="text-gray-600 mb-4">Please provide a reason for rejecting <span id="documentName" class="font-semibold"></span></p>
            
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason</label>
                    <textarea id="rejection_reason" name="rejection_reason" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Enter the reason for rejection..." required></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideRejectModal()" 
                            class="px-4 py-2 text-gray-600 bg-gray-200 rounded-md hover:bg-gray-300 transition">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                        Reject Document
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showRejectModal(documentId, documentName) {
            document.getElementById('documentName').textContent = documentName;
            document.getElementById('rejectForm').action = `/documents/${documentId}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function hideRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejection_reason').value = '';
        }

        // Close modal when clicking outside
        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideRejectModal();
            }
        });
    </script>
    <script>
    // Filtering logic (placed inside layout)
    (function() {
        const statusSelect = document.getElementById('statusFilter');
        const actorInput = document.getElementById('actorFilter');
        const dateFromInput = document.getElementById('dateFromFilter');
        const stepItems = Array.from(document.querySelectorAll('[id^="step-"]'));

        // Initialize progress bars from data-progress to avoid inline style parsing issues
        document.querySelectorAll('.progress-bar[data-progress]').forEach(el => {
            el.style.width = (el.dataset.progress || '0') + '%';
        });

        function matches(item) {
            const statusType = item.dataset.statusType || '';
            const actor = (item.dataset.actor || '').toLowerCase();
            const started = item.dataset.started || '';
            const completed = item.dataset.completed || '';
            const actorQ = (actorInput && actorInput.value || '').toLowerCase();
            const dateFrom = (dateFromInput && dateFromInput.value) || '';

            const statusOk = !statusSelect || !statusSelect.value || statusType === statusSelect.value;
            const actorOk = !actorQ || actor.includes(actorQ);
            const dateOk = !dateFrom || ((started && started >= dateFrom) || (completed && completed >= dateFrom));
            return statusOk && actorOk && dateOk;
        }

        function applyFilters() {
            stepItems.forEach(item => {
                item.style.display = matches(item) ? '' : 'none';
            });
        }

        ['input', 'change'].forEach(evt => {
            if (statusSelect) statusSelect.addEventListener(evt, applyFilters);
            if (actorInput) actorInput.addEventListener(evt, applyFilters);
            if (dateFromInput) dateFromInput.addEventListener(evt, applyFilters);
        });
        // Apply filters on initial load to reflect default selections
        applyFilters();

        // Search suggestions removed

        // Quick jump
        const quickJump = document.getElementById('quickJump');
        const quickJumpBtn = document.getElementById('quickJumpBtn');
        if (quickJump && quickJumpBtn) {
            quickJumpBtn.addEventListener('click', () => {
                const targetId = quickJump.value;
                const el = document.getElementById(targetId);
                if (el) {
                    el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    el.classList.add('ring-2', 'ring-blue-400/50');
                    setTimeout(() => el.classList.remove('ring-2', 'ring-blue-400/50'), 1200);
                }
            });
        }
    })();
    </script>
</x-app-layout>