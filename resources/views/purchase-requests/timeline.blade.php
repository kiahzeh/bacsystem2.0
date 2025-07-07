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
            <div class="glass-card overflow-hidden shadow-sm sm:rounded-lg p-6">
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
                        <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium glass-badge
                            @switch($purchaseRequest->status)
                                @case('ATP') bg-purple-500/20 text-purple-200 @break
                                @case('Procurement') bg-blue-500/20 text-blue-200 @break
                                @case('Posting in PhilGEPS') bg-indigo-500/20 text-indigo-200 @break
                                @case('Pre-Bid') bg-cyan-500/20 text-cyan-200 @break
                                @case('Bid Opening') bg-teal-500/20 text-teal-200 @break
                                @case('Bid Evaluation') bg-emerald-500/20 text-emerald-200 @break
                                @case('Post Qualification') bg-green-500/20 text-green-200 @break
                                @case('Confirmation on Approval') bg-lime-500/20 text-lime-200 @break
                                @case('Issuance of Notice of Award') bg-yellow-500/20 text-yellow-200 @break
                                @case('Purchase Order') bg-amber-500/20 text-amber-200 @break
                                @case('Contract') bg-orange-500/20 text-orange-200 @break
                                @case('Notice to Proceed') bg-red-500/20 text-red-200 @break
                                    @case('Posting of Award in PhilGEPS') bg-pink-500/20 text-pink-200 @break
                                    @case('Forward Purchase or Supply') bg-rose-500/20 text-rose-200 @break
                                @default bg-gray-500/20 text-gray-200
                            @endswitch">
                            {{ $purchaseRequest->status }}
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="w-full bg-gray-500/20 rounded-full h-2.5">
                                @php
                                    $currentStep = array_search($purchaseRequest->status, $allStatuses);
                                    $totalSteps = count($allStatuses);
                                    $progress = ($totalSteps > 1) ? (($currentStep / ($totalSteps - 1)) * 100) : 100;
                                @endphp
                                <div class="bg-orange-400 h-2.5 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
                            </div>
                            <p class="text-sm text-white glass-text">Progress: {{ number_format($progress, 1) }}%</p>
                        </div>
                    </div>
                </div>

                <!-- Workflow Management Section (Admin Only) -->
                @if(auth()->user()->isAdmin())
                    <div class="mb-8 glass-card rounded-lg p-6">
                        <h3 class="text-lg font-bold text-white glass-heading mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            Customize Workflow Steps
                        </h3>
                        <p class="text-white/70 text-sm mb-4">
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
                            <div class="space-y-2">
                                @foreach($allStatuses as $index => $status)
                                    <div class="flex items-center justify-between p-3 glass-card rounded-lg">
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
                                    </div>
                                @endforeach
                            </div>
                        </div>

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
                @endif

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
                            @endphp
                            
                            <div class="relative">
                                <!-- Connection Line -->
                                @if($index < count($allStatuses) - 1)
                                    <div class="absolute left-8 top-16 w-0.5 h-12 {{ $isPastStatus ? 'bg-green-400' : 'bg-gray-400/30' }}"></div>
                                @endif
                                
                                <div class="glass-card rounded-xl p-6 {{ $isCurrentStatus ? 'ring-2 ring-orange-400/50 shadow-lg' : ($isPastStatus ? 'ring-1 ring-green-400/30' : 'ring-1 ring-gray-400/20') }}">
                                    <div class="flex items-start space-x-4">
                                        <!-- Step Indicator -->
                                        <div class="flex-shrink-0">
                                            <div class="relative">
                                                @if($isPastStatus)
                                                    <!-- Completed Step -->
                                                    <div class="w-16 h-16 rounded-full bg-green-500/20 border-2 border-green-400 flex items-center justify-center">
                                                        <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    </div>
                                                @elseif($isCurrentStatus)
                                                    <!-- Current Step -->
                                                    <div class="w-16 h-16 rounded-full bg-orange-500/20 border-2 border-orange-400 flex items-center justify-center relative">
                                                        <div class="w-8 h-8 bg-orange-400 rounded-full animate-pulse"></div>
                                                        <div class="absolute -top-1 -right-1 w-6 h-6 bg-orange-400 rounded-full flex items-center justify-center">
                                                            <span class="text-white text-xs font-bold">{{ $index + 1 }}</span>
                                                        </div>
                                                    </div>
                                                @else
                                                    <!-- Future Step -->
                                                    <div class="w-16 h-16 rounded-full bg-gray-500/20 border-2 border-gray-400/50 flex items-center justify-center">
                                                        <span class="text-gray-400 text-lg font-bold">{{ $index + 1 }}</span>
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
                                                        <p class="text-sm text-white/70 mt-1">
                                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                            Completed: {{ $statusHistory->created_at->format('M d, Y H:i') }}
                                                        </p>
                                                    @endif
                                                </div>
                                                
                                                <!-- Status Badge -->
                                                <div class="flex items-center space-x-2">
                                                    @if($isPastStatus)
                                                        <span class="px-3 py-1 bg-green-500/20 text-green-200 rounded-full text-sm font-medium">
                                                            Completed
                                                        </span>
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
                                                               class="bg-red-500/20 hover:bg-red-500/40 text-red-300 hover:text-red-200 px-2 py-1 rounded text-xs transition">
                                                                Remove
                                                            </a>
                                                            <a href="{{ route('purchase-requests.workflow.skip-step.confirm', [$purchaseRequest, $index]) }}" 
                                                               class="bg-yellow-500/20 hover:bg-yellow-500/40 text-yellow-300 hover:text-yellow-200 px-2 py-1 rounded text-xs transition">
                                                                Skip
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <!-- Document Upload Section (Only for Current Step) -->
                                            @if($isCurrentStatus)
                                                <div class="mt-6 p-4 glass-card rounded-lg border border-orange-400/30">
                                                    <!-- Workflow Actions -->
                                                    @if(auth()->user()->isAdmin())
                                                        <div class="mb-6 p-4 glass-card rounded-lg border border-blue-400/30">
                                                            <h4 class="text-sm font-medium text-white glass-heading mb-4 flex items-center">
                                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                                </svg>
                                                                Workflow Actions
                                                            </h4>
                                                            <div class="flex items-center space-x-4">
                                                                <!-- Next Step Button -->
                                                                @if($index < count($allStatuses) - 1)
                                                                    <a href="{{ route('purchase-requests.workflow.next-step.confirm', [$purchaseRequest, $index]) }}" 
                                                                       class="bg-green-500/20 hover:bg-green-500/40 text-green-200 hover:text-green-100 px-6 py-2 rounded-lg font-medium transition flex items-center">
                                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                                                        </svg>
                                                                        Next Step
                                                                    </a>
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
                                                            <div class="flex items-center justify-between p-3 glass-card rounded-lg">
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
                                                                    <li class="flex items-center justify-between text-xs glass-card p-3 rounded-lg">
                                                                        <span class="text-white glass-text">{{ $document->original_filename }}</span>
                                                                        <div class="flex items-center space-x-3">
                                                                            <span class="text-white/70">{{ $document->created_at->format('M d, Y H:i') }}</span>
                                                                            <a href="{{ route('documents.download', $document) }}" 
                                                                               class="text-blue-300 hover:text-blue-200 transition">
                                                                                Download
                                                                            </a>
                                                                            @if(auth()->user()->isAdmin())
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
                        <div class="glass-card p-4 rounded-lg">
                            <p class="text-white glass-text">{{ $purchaseRequest->remarks }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout> 