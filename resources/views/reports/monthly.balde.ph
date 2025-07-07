<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Monthly Report') }}
        </h2>
    </x-slot>

    <div class="py-12 px-6">
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-xl font-bold text-gray-800">Report for {{ $startOfMonth->format('F Y') }}</h3>

            <div class="mt-6">
                <p><strong>Total Number of PRs: </strong>{{ $totalPRs }}</p>
                <p><strong>Total Funding: </strong>{{ number_format($totalFunding, 2) }}</p>
            </div>

            <h4 class="text-lg font-semibold mt-4">Purchase Requests Details</h4>

            <table class="table-auto w-full mt-4 border-collapse">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">PR Number</th>
                        <th class="border px-4 py-2">Name</th>
                        <th class="border px-4 py-2">Order Date</th>
                        <th class="border px-4 py-2">Funding</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchaseRequests as $request)
                        <tr>
                            <td class="border px-4 py-2">{{ $request->pr_number }}</td>
                            <td class="border px-4 py-2">{{ $request->name }}</td>
                            <td class="border px-4 py-2">{{ $request->order_date->format('M d, Y') }}</td>
                            <td class="border px-4 py-2">{{ number_format($request->funding, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
