<?php

namespace App\Exports;

use App\Models\PurchaseRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class PurchaseRequestsMonthlyExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return PurchaseRequest::with('department')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'PR Number',
            'Name',
            'Department',
            'Status',
            'Order Date',
            'Funding',
            'Created At',
        ];
    }

    /**
     * @param PurchaseRequest $purchaseRequest
     * @return array
     */
    public function map($purchaseRequest): array
    {
        return [
            $purchaseRequest->pr_number,
            $purchaseRequest->name,
            $purchaseRequest->department->name,
            $purchaseRequest->status,
            $purchaseRequest->order_date,
            $purchaseRequest->funding,
            $purchaseRequest->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
