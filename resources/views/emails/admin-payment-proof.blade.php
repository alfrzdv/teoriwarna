<x-mail::message>
# Payment Proof Uploaded

A customer has uploaded payment proof for order **#{{ $order->order_number }}**.

## Order Details

**Order Number:** {{ $order->order_number }}
**Customer:** {{ $order->customer_name }} ({{ $order->user->email }})
**Total Amount:** Rp {{ number_format($order->total_amount, 0, ',', '.') }}
**Payment Method:** {{ ucfirst(str_replace('_', ' ', $order->payment->payment_method)) }}

## Action Required

Please review the payment proof and verify the payment.

<x-mail::button :url="route('admin.orders.show', $order)">
Review Payment Proof
</x-mail::button>

Thanks,<br>
{{ config('app.name') }} System
</x-mail::message>
