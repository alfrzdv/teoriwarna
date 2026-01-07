<x-mail::message>
# Order Completed

Hello {{ $order->customer_name }},

Thank you for confirming receipt of your order **#{{ $order->order_number }}**.

We hope you're satisfied with your purchase! We would love to hear your feedback.

## Order Summary

**Order Number:** {{ $order->order_number }}
**Completed Date:** {{ $order->updated_at->format('d M Y, H:i') }}
**Total Amount:** Rp {{ number_format($order->total_amount, 0, ',', '.') }}

@foreach($order->items as $item)
- **{{ $item->product_name }}** ({{ $item->quantity }}x)
@endforeach

<x-mail::button :url="route('catalog.index')">
Continue Shopping
</x-mail::button>

Thank you for being our valued customer!

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
