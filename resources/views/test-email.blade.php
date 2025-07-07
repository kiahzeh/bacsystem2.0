<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Test Email') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('test.email') }}" method="GET" class="space-y-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ auth()->user()->email }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <p class="mt-2 text-sm text-gray-500">Leave empty to use your account email.</p>
                        </div>

                        <div>
                            <button type="submit"
                                class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Send Test Email
                            </button>
                        </div>
                    </form>

                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900">Instructions</h3>
                        <div class="mt-4 text-sm text-gray-600">
                            <p>To view the test emails:</p>
                            <ol class="list-decimal list-inside mt-2 space-y-2">
                                <li>Make sure Mailpit is running</li>
                                <li>Visit <a href="http://localhost:8025" target="_blank"
                                        class="text-indigo-600 hover:text-indigo-900">http://localhost:8025</a> to
                                    access the Mailpit interface</li>
                                <li>Send a test email using the form above</li>
                                <li>Check the Mailpit interface to see the received email</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>