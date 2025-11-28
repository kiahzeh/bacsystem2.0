<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight glass-heading">
            {{ __('Edit Department') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glassmorphism-card rounded-lg">
                <div class="p-6 text-white glass-text">
                    <form method="POST" action="{{ route('departments.update', $department) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Name')" class="text-white" />
                            <x-text-input id="name" class="block mt-1 w-full glassmorphism-input" type="text" name="name"
                                :value="old('name', $department->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Description')" class="text-white" />
                            <textarea id="description" name="description" rows="3"
                                class="block mt-1 w-full glassmorphism-input">{{ old('description', $department->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('departments.index') }}" class="mr-3 px-3 py-1 text-xs rounded-full glass-badge bg-white/20 text-white/80 hover:text-white hover:bg-white/30 transition">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button class="glass-badge bg-blue-500/30 text-white/90 hover:bg-blue-500/40">
                                {{ __('Update Department') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>