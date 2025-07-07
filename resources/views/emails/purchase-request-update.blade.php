<x-mail::message>
# {{ $title }}

Hello {{ $notifiable->name }},

{{ $message }}

---

**Purchase Request Details:**
- **Name:** {{ $purchaseRequest->name }}
- **Department:** {{ $purchaseRequest->department->name }}
- **Order Date:** {{ $purchaseRequest->order_date->format('F j, Y') }}
- **Previous Status:** {{ $oldStatus }}
- **New Status:** {{ $purchaseRequest->status }}
@if($purchaseRequest->remarks)
- **Remarks:** {{ $purchaseRequest->remarks }}
@endif
- **Last Updated By:** {{ $updater }}
- **Last Updated At:** {{ $updatedAt }}

<x-mail::button :url="route('purchase-requests.show', $purchaseRequest)">
View Purchase Request
</x-mail::button>

---

This is an automated message from the BAC System. Please do not reply to this email.

Thanks,<br>
BAC System
</x-mail::message>
