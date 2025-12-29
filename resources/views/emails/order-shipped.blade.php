<x-mail::message>
# Order Shipped

Hello {{ $order->customer_name }},

Your order **#{{ $order->order_number }}** has been shipped and is on its way to you!

## Shipping Details

@if($order->tracking_number)
**Tracking Number:** {{ $order->tracking_number }}
@endif
@if($order->shipping_courier)
**Courier:** {{ $order->shipping_courier }}
@endif
**Shipping Address:** {{ $order->shipping_address }}, {{ $order->shipping_city }}, {{ $order->shipping_postal_code }}

## Order Items

@foreach($order->items as $item)
- **{{ $item->product_name }}** ({{ $item->quantity }}x)
@endforeach

<x-mail::button :url="route('orders.show', $order)">
Track Your Order
</x-mail::button>

Thank you for shopping with us!

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
