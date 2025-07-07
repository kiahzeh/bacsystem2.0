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
                {{ __('Create Purchase Request') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glassmorphism-card overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white">
                    <form method="POST" action="{{ route('purchase-requests.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('Name')" class="text-white" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full glassmorphism-input"
                                :value="old('name')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="order_date" :value="__('Order Date')" class="text-white" />
                            <x-text-input id="order_date" name="order_date" type="date" class="mt-1 block w-full glassmorphism-input"
                                :value="old('order_date')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('order_date')" />
                        </div>

                        <div>
                            <x-input-label for="department_id" :value="__('Department')" class="text-white" />
                            <select id="department_id" name="department_id"
                                class="mt-1 block w-full glassmorphism-input"
                                required {{ !auth()->user()->isAdmin() ? 'disabled' : '' }}>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" 
                                        {{ old('department_id', auth()->user()->department_id) == $department->id ? 'selected' : '' }}>
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
                                class="mt-1 block w-full glassmorphism-input"
                                required>
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}">{{ $status }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('status')" />
                        </div>

    
                        <div>
    <x-input-label for="type" :value="__('Bid Type')" class="text-white" />
    <select id="type" name="type"
        class="mt-1 block w-full glassmorphism-input"
        required>
        <option value="">-- Select Type --</option>
        <option value="alternative" {{ old('type') == 'alternative' ? 'selected' : '' }}>Alternative</option>
        <option value="competitive" {{ old('type') == 'competitive' ? 'selected' : '' }}>Competitive</option>
    </select>
    <x-input-error class="mt-2" :messages="$errors->get('type')" />
</div>

<!-- funding -->
<div x-data="{ funding: '{{ old('funding') }}' }">
    <x-input-label for="funding" :value="__('Funding Source')" class="text-white" />
    <select id="funding" name="funding"
        x-model="funding"
        class="mt-1 block w-full glassmorphism-input"
        required>
        <option value="">-- Select Funding --</option>
        <option value="RAF-MAIN">01-RAF-Main</option>
        <option value="RAF-SC">01-RAF-SC</option>
        <option value="1G1-MAIN">05-1G1-Main</option>
        <option value="1G1-SC">05-1G1-SC</option>
        <option value="BTI">06-BTI</option>
        <option value="CF-TR">07-CF-TR</option>
        <option value="Others">Others</option>
    </select>
    <x-input-error class="mt-2" :messages="$errors->get('funding')" />

    <!-- Others input -->
    <div x-show="funding === 'Others'" class="mt-4">
        <x-input-label for="custom_funding" :value="__('Specify Other Funding Source')" class="text-white" />
        <input type="text" id="custom_funding" name="custom_funding"
            class="block mt-1 w-full glassmorphism-input"
            value="{{ old('custom_funding') }}" />
        <x-input-error class="mt-2" :messages="$errors->get('custom_funding')" />
    </div>
</div>



                        <div>
                            <x-input-label for="remarks" :value="__('Remarks')" class="text-white" />
                            <textarea id="remarks" name="remarks"
                                class="mt-1 block w-full glassmorphism-input"
                                rows="3">{{ old('remarks') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('remarks')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button class="glassmorphism-button">{{ __('Create Request') }}</x-primary-button>
                            <a href="{{ route('purchase-requests.index') }}"
                                class="inline-flex items-center px-4 py-2 glassmorphism-button-secondary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>