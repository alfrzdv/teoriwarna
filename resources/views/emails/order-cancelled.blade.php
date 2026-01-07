<x-mail::message>
# Order Cancelled

Hello {{ $order->customer_name }},

Your order **#{{ $order->order_number }}** has been cancelled as requested.

@if($order->payment->payment_method === 'bank_transfer' && $order->payment->status === 'paid')
Your refund will be processed within 3-5 business days.
@endif

## Cancelled Order Details

**Order Number:** {{ $order->order_number }}
**Cancelled Date:** {{ $order->updated_at->format('d M Y, H:i') }}
**Total Amount:** Rp {{ number_format($order->total_amount, 0, ',', '.') }}

## Items

@foreach($order->items as $item)
- **{{ $item->product_name }}** ({{ $item->quantity }}x) - Rp {{ number_format($item->subtotal, 0, ',', '.') }}
@endforeach

If you have any questions, please contact our customer support.

<x-mail::button :url="route('catalog.index')">
Continue Shopping
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
