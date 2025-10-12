<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Manage Workflow Processes') }}
            </h2>
            <a href="{{ route('processes.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Add Process
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glassmorphism-card sm:rounded-lg">
                <div class="p-6 glass-effect border-b border-gray-200">
                    <div class="mb-4">
                        <p class="text-white text-lg font-semibold mb-2">Drag and drop to reorder processes:</p>
                    </div>
                    <ul id="process-list" class="space-y-4">
                        @forelse($processes as $process)
                            <li class="process-item glassmorphism-violet p-4 rounded-lg shadow flex items-center justify-between" data-id="{{ $process->id }}">
                                <span class="font-semibold">{{ $process->name }}</span>
                                <div>
                                    <a href="{{ route('processes.edit', $process) }}" class="text-white bg-indigo-600 hover:bg-indigo-700 px-3 py-1 rounded-md transition-all duration-200 shadow-sm mr-4">Edit</a>
                                    <form action="{{ route('processes.destroy', $process) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this process?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-md transition-all duration-200 shadow-sm">Delete</button>
                                    </form>
                                </div>
                            </li>
                        @empty
                            <li class="text-center py-4 text-white">
                                <div class="flex flex-col items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p class="text-lg text-white">No processes found. Please add one.</p>
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Move scripts to the very end for better loading -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        console.log('Sortable:', typeof Sortable);
        new Sortable(document.getElementById('process-list'), {
            animation: 150,
            onEnd: function (evt) {
                let order = Array.from(document.querySelectorAll('.process-item')).map(el => el.dataset.id);
                fetch('{{ route('processes.reorder') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({order: order})
                });
            }
        });
    });
    </script>
</x-app-layout> 