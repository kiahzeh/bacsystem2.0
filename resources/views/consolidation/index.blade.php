<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Consolidated Purchase Requests') }}
        </h2>
    </x-slot>

    <div class="py-12 px-6">
        <div class="flex flex-wrap gap-6">
            @foreach($consolidatedPRs as $cpr)
                <div class="w-full lg:w-1/2 glassmorphism-card p-6 rounded shadow">
                    <h3 class="text-xl font-bold mb-4 text-white glass-heading">CPR Number: {{ $cpr->cpr_number }}</h3>
                    <ul class="space-y-2">
                        @foreach ($cpr->purchaseRequests as $pr)
                            <li class="p-4 glassmorphism-card rounded">
                                <p class="text-white glass-text"><strong>PR Number:</strong> {{ $pr->pr_number }}</p>
                                <p class="text-white glass-text"><strong>Name:</strong> {{ $pr->name }}</p>
                                <p class="text-white glass-text"><strong>Order Date:</strong> {{ $pr->order_date }}</p>
                                <p class="text-white glass-text"><strong>Funding:</strong> {{ $pr->funding ?? 'N/A' }}</p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>

</x-app-layout>
