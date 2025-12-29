<x-mail::message>
# Payment Verified

Hello {{ $order->customer_name }},

Great news! Your payment for order **#{{ $order->order_number }}** has been verified and confirmed.

Your order is now being processed and will be shipped soon. We will notify you once your order has been shipped.

## Order Details

**Order Number:** {{ $order->order_number }}
**Total Amount:** Rp {{ number_format($order->total_amount, 0, ',', '.') }}
**Payment Status:** Verified ✓

<x-mail::button :url="route('orders.show', $order)">
View Order Details
</x-mail::button>

Thank you for your purchase!

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
