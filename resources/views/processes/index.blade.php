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
            <div class="glassmorphism-card overflow-hidden sm:rounded-lg">
                <div class="p-6 glass-effect border-b border-gray-200">
                    <table class="min-w-full bg-transparent">
                        <thead class="bg-gray-50/60">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider glass-table-heading">Order</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider glass-table-heading">Name</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider glass-table-heading">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-white">
                            @forelse($processes as $process)
                            <tr class="hover:bg-gray-50/60 transition-all duration-150">
                                <td class="px-6 py-4 whitespace-nowrap glass-table-text text-white">{{ $process->order }}</td>
                                <td class="px-6 py-4 whitespace-nowrap glass-table-text text-white">{{ $process->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <a href="{{ route('processes.edit', $process) }}" class="text-white bg-indigo-600 hover:bg-indigo-700 px-3 py-1 rounded-md transition-all duration-200 shadow-sm mr-4">Edit</a>
                                    <form action="{{ route('processes.destroy', $process) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this process?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-md transition-all duration-200 shadow-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-white">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <p class="text-lg text-white">No processes found. Please add one.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 