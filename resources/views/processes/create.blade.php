<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight glass-heading">
            {{ __('Add New Process') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glassmorphism-card rounded-lg">
                <div class="p-6 text-white glass-text">
                    <form action="{{ route('processes.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-white">Process Name</label>
                            <input type="text" name="name" id="name" class="mt-1 block w-full glassmorphism-input" required>
                        </div>
                        <div class="mb-4">
                            <label for="order" class="block text-sm font-medium text-white">Order</label>
                            <input type="number" name="order" id="order" value="0" class="mt-1 block w-full glassmorphism-input">
                        </div>
                        <div class="mb-4">
                            <label class="inline-flex items-center text-white">
                                <input type="checkbox" name="requires_document" value="1" class="accent-violet-400/60" {{ old('requires_document') ? 'checked' : '' }}>
                                <span class="ml-2 text-white/80">Requires Document</span>
                            </label>
                        </div>
                        <div>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 text-sm font-medium rounded-md text-white glass-badge bg-indigo-500/30 hover:bg-indigo-500/40">
                                Create
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>