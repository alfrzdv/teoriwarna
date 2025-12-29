<x-mail::message>
# Order Confirmation

Hello {{ $order->customer_name }},

Thank you for your order! We have received your order and it is being processed.

## Order Details

**Order Number:** {{ $order->order_number }}
**Order Date:** {{ $order->created_at->format('d M Y, H:i') }}
**Status:** {{ ucfirst($order->status) }}

## Shipping Information

**Name:** {{ $order->shipping_name }}
**Phone:** {{ $order->shipping_phone }}
**Address:** {{ $order->shipping_address }}, {{ $order->shipping_city }}, {{ $order->shipping_postal_code }}
**Shipping Method:** {{ ucfirst(str_replace('_', ' ', $order->shipping_method)) }} - Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}

## Order Items

@foreach($order->items as $item)
- **{{ $item->product_name }}** ({{ $item->quantity }}x) - Rp {{ number_format($item->price, 0, ',', '.') }}
@endforeach

## Payment Summary

| Item | Amount |
|:-----|-------:|
| Subtotal | Rp {{ number_format($order->subtotal, 0, ',', '.') }} |
| Shipping | Rp {{ number_format($order->shipping_cost, 0, ',', '.') }} |
| **Total** | **Rp {{ number_format($order->total_amount, 0, ',', '.') }}** |

## Payment Information

**Payment Method:** {{ ucfirst(str_replace('_', ' ', $order->payment->payment_method)) }}

@if($order->payment->payment_method === 'bank_transfer')
Please complete your payment by transferring to:
- **Bank:** BCA
- **Account Number:** 1234567890
- **Account Name:** Teoriwarna Store

After payment, please upload your payment proof on the order detail page.
@elseif($order->payment->payment_method === 'cod')
You will pay cash when the order is delivered to your address.
@endif

<x-mail::button :url="route('orders.show', $order)">
View Order Details
</x-mail::button>

Thank you for shopping with us!

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
