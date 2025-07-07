<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <button onclick="history.back()" 
                    class="bg-gray-500/20 hover:bg-gray-500/40 text-gray-200 hover:text-white px-4 py-2 rounded-lg font-medium transition flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </button>
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Edit Purchase Request') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glassmorphism-card overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white">
                    <form method="POST" action="{{ route('purchase-requests.update', $purchaseRequest) }}"
                        class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="name" :value="__('Name')" class="text-white" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full glassmorphism-input"
                                :value="old('name', $purchaseRequest->name)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="order_date" :value="__('Order Date')" class="text-white" />
                            <x-text-input id="order_date" name="order_date" type="date" class="mt-1 block w-full glassmorphism-input"
                                :value="old('order_date', $purchaseRequest->order_date->format('Y-m-d'))" required />
                            <x-input-error class="mt-2" :messages="$errors->get('order_date')" />
                        </div>

                        <div>
                            <x-input-label for="type" :value="__('Bid Type')" class="text-white" />
                            <div class="flex items-center space-x-4">
                                <select id="type" name="type"
                                    class="mt-1 block w-full glassmorphism-input"
                                    required>
                                    <option value="">-- Select Type --</option>
                                    <option value="alternative" {{ old('type', $purchaseRequest->type) == 'alternative' ? 'selected' : '' }}>Alternative</option>
                                    <option value="competitive" {{ old('type', $purchaseRequest->type) == 'competitive' ? 'selected' : '' }}>Competitive</option>
                                </select>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('type')" />
                        </div>

                        <div>
                            <x-input-label for="department_id" :value="__('Department')" class="text-white" />
                            <select id="department_id" name="department_id"
                                class="mt-1 block w-full glassmorphism-input"
                                required {{ !auth()->user()->isAdmin() ? 'disabled' : '' }}>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" 
                                        {{ old('department_id', $purchaseRequest->department_id) == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('department_id')" />
                            @if(!auth()->user()->isAdmin())
                                <input type="hidden" name="department_id" value="{{ auth()->user()->department_id }}">
                            @endif
                        </div>

                        <div>
                            <x-input-label for="status" :value="__('Status')" class="text-white" />
                            <select id="status" name="status"
                                class="mt-1 block w-full glassmorphism-input">
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" {{ old('status', $purchaseRequest->status) === $status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('status')" />
                        </div>

                        <div>
                            <x-input-label for="remarks" :value="__('Remarks')" class="text-white" />
                            <textarea id="remarks" name="remarks"
                                class="mt-1 block w-full glassmorphism-input"
                                rows="3">{{ old('remarks', $purchaseRequest->remarks) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('remarks')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button class="glassmorphism-button">{{ __('Update Request') }}</x-primary-button>
                            <a href="{{ route('purchase-requests.index') }}"
                                class="inline-flex items-center px-4 py-2 glassmorphism-button-secondary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                    
                    @if(auth()->user()->isAdmin())
                        <div class="mt-6 pt-6 border-t border-gray-300">
                            <h3 class="text-lg font-medium text-white mb-4">Quick Actions</h3>
                            <form method="POST" action="{{ route('purchase-requests.convert-type', $purchaseRequest) }}" 
                                  onsubmit="return confirm('Are you sure you want to convert this PR from {{ $purchaseRequest->type }} to {{ $purchaseRequest->type == 'alternative' ? 'competitive' : 'alternative' }}?');" 
                                  class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="glassmorphism-button-accent text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-200 ease-in-out transform hover:scale-105">
                                    🔄 Convert to {{ $purchaseRequest->type == 'alternative' ? 'Competitive' : 'Alternative' }}
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="mt-6 pt-6 border-t border-gray-300">
                            <p class="text-gray-300 text-sm">Quick actions are only available to administrators.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>