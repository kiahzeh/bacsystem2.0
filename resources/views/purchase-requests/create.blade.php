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

                        <!-- PR Number -->
                        <div>
                            <x-input-label for="pr_number" :value="__('PR Number')" class="text-white" />
                            <x-text-input id="pr_number" name="pr_number" type="text" class="mt-1 block w-full glassmorphism-input"
                                :value="old('pr_number')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('pr_number')" />
                        </div>

                        <!-- Project Title -->
                        <div>
                            <x-input-label for="project_title" :value="__('Project Title')" class="text-white" />
                            <x-text-input id="project_title" name="project_title" type="text" class="mt-1 block w-full glassmorphism-input"
                                :value="old('project_title')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('project_title')" />
                        </div>

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Name')" class="text-white" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full glassmorphism-input"
                                :value="old('name')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- Order Date -->
                        <div>
                            <x-input-label for="order_date" :value="__('Order Date')" class="text-white" />
                            <x-text-input id="order_date" name="order_date" type="date" class="mt-1 block w-full glassmorphism-input"
                                :value="old('order_date')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('order_date')" />
                        </div>

                        <!-- Department -->
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

                        <!-- Status -->
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

                        <!-- Mode of Procurement -->
                        <div x-data="{ modeOfProcurement: '{{ old('mode_of_procurement') }}' }">
                            <x-input-label for="mode_of_procurement" :value="__('Mode of Procurement')" class="text-white" />
                            <select id="mode_of_procurement" name="mode_of_procurement"
                                x-model="modeOfProcurement"
                                class="mt-1 block w-full glassmorphism-input"
                                required>
                                <option value="">-- Select Mode of Procurement --</option>
                                <option value="Alternative" {{ old('mode_of_procurement') == 'Alternative' ? 'selected' : '' }}>Alternative</option>
                                <option value="Competitive" {{ old('mode_of_procurement') == 'Competitive' ? 'selected' : '' }}>Competitive</option>
                                <option value="Others" {{ old('mode_of_procurement') == 'Others' ? 'selected' : '' }}>Others</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('mode_of_procurement')" />

                            <!-- Others input -->
                            <div x-show="modeOfProcurement === 'Others'" class="mt-4">
                                <x-input-label for="custom_mode_of_procurement" :value="__('Specify Other Mode of Procurement')" class="text-white" />
                                <input type="text" id="custom_mode_of_procurement" name="custom_mode_of_procurement"
                                    class="block mt-1 w-full glassmorphism-input"
                                    value="{{ old('custom_mode_of_procurement') }}" />
                                <x-input-error class="mt-2" :messages="$errors->get('custom_mode_of_procurement')" />
                            </div>
                        </div>

                        <!-- ABC/Approved Budget for Contract -->
                        <div>
                            <x-input-label for="abc_approved_budget" :value="__('ABC/Approved Budget for Contract')" class="text-white" />
                            <x-text-input id="abc_approved_budget" name="abc_approved_budget" type="number" step="0.01" min="0" class="mt-1 block w-full glassmorphism-input"
                                :value="old('abc_approved_budget')" required placeholder="0.00" />
                            <x-input-error class="mt-2" :messages="$errors->get('abc_approved_budget')" />
                        </div>

                        <!-- Category -->
                        <div x-data="{ category: '{{ old('category') }}' }">
                            <x-input-label for="category" :value="__('Category')" class="text-white" />
                            <select id="category" name="category"
                                x-model="category"
                                class="mt-1 block w-full glassmorphism-input"
                                required>
                                <option value="">-- Select Category --</option>
                                <option value="Goods" {{ old('category') == 'Goods' ? 'selected' : '' }}>Goods</option>
                                <option value="Infrastructure" {{ old('category') == 'Infrastructure' ? 'selected' : '' }}>Infrastructure</option>
                                <option value="Consulting Services" {{ old('category') == 'Consulting Services' ? 'selected' : '' }}>Consulting Services</option>
                                <option value="Non-Consulting Services" {{ old('category') == 'Non-Consulting Services' ? 'selected' : '' }}>Non-Consulting Services</option>
                                <option value="Others" {{ old('category') == 'Others' ? 'selected' : '' }}>Others</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('category')" />

                            <!-- Others input -->
                            <div x-show="category === 'Others'" class="mt-4">
                                <x-input-label for="custom_category" :value="__('Specify Other Category')" class="text-white" />
                                <input type="text" id="custom_category" name="custom_category"
                                    class="block mt-1 w-full glassmorphism-input"
                                    value="{{ old('custom_category') }}" />
                                <x-input-error class="mt-2" :messages="$errors->get('custom_category')" />
                            </div>
                        </div>

                        <!-- Purpose/Description -->
                        <div>
                            <x-input-label for="purpose_description" :value="__('Purpose/Description')" class="text-white" />
                            <textarea id="purpose_description" name="purpose_description"
                                class="mt-1 block w-full glassmorphism-input"
                                rows="4" required placeholder="Describe the purpose and details of this purchase request...">{{ old('purpose_description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('purpose_description')" />
                        </div>

                        <!-- Funding Source -->
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



                        <!-- Remarks -->
                        <div>
                            <x-input-label for="remarks" :value="__('Remarks')" class="text-white" />
                            <textarea id="remarks" name="remarks"
                                class="mt-1 block w-full glassmorphism-input"
                                rows="3" placeholder="Additional remarks or notes...">{{ old('remarks') }}</textarea>
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