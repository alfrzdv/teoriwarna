<x-mail::message>
# Payment Proof Received

Hello {{ $order->customer_name }},

We have received your payment proof for order **#{{ $order->order_number }}**.

Our team is currently reviewing your payment. We will verify it shortly and notify you once confirmed.

## Order Details

**Order Number:** {{ $order->order_number }}
**Total Amount:** Rp {{ number_format($order->total_amount, 0, ',', '.') }}
**Payment Method:** {{ ucfirst(str_replace('_', ' ', $order->payment->payment_method)) }}

<x-mail::button :url="route('orders.show', $order)">
View Order Details
</x-mail::button>

Thank you for your patience!

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
