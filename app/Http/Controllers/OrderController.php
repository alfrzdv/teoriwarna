<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['order_items.product', 'payment'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Ensure order belongs to current user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['order_items.product.product_images', 'payment']);

        return view('orders.show', compact('order'));
    }

    public function cancel(Order $order)
    {
        // Ensure order belongs to current user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Only pending orders can be cancelled
        if ($order->status !== 'pending') {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan.');
        }

        // Return stock
        foreach ($order->order_items as $item) {
            $item->product->addStock($item->quantity, "Order cancelled: #{$order->order_number}");
        }

        $order->update(['status' => 'cancelled']);

        // Update payment status
        if ($order->payment) {
            $order->payment->update(['status' => 'cancelled']);
        }

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
