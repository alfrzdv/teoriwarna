<x-mail::message>
# New Order Received

A new order has been placed on your store!

## Order Details

**Order Number:** {{ $order->order_number }}
**Customer:** {{ $order->customer_name }} ({{ $order->user->email }})
**Order Date:** {{ $order->created_at->format('d M Y, H:i') }}
**Status:** {{ ucfirst($order->status) }}

## Items Ordered

@foreach($order->items as $item)
- **{{ $item->product_name }}** ({{ $item->quantity }}x) - Rp {{ number_format($item->subtotal, 0, ',', '.') }}
@endforeach

## Payment & Shipping

**Payment Method:** {{ ucfirst(str_replace('_', ' ', $order->payment->payment_method)) }}
**Shipping Method:** {{ ucfirst(str_replace('_', ' ', $order->shipping_method)) }}
**Total Amount:** **Rp {{ number_format($order->total_amount, 0, ',', '.') }}**

## Shipping Address

{{ $order->shipping_name }}
{{ $order->shipping_phone }}
{{ $order->shipping_address }}
{{ $order->shipping_city }}, {{ $order->shipping_postal_code }}

<x-mail::button :url="route('admin.orders.show', $order)">
View Order in Admin Panel
</x-mail::button>

Thanks,<br>
{{ config('app.name') }} System
</x-mail::message>
