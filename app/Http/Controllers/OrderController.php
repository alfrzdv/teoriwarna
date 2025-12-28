<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

    // Upload payment proof
    public function uploadPaymentProof(Request $request, Order $order)
    {
        // Ensure order belongs to current user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if (!$order->payment) {
            return back()->with('error', 'Payment record not found.');
        }

        // Delete old payment proof if exists
        if ($order->payment->proof_of_payment) {
            Storage::disk('public')->delete($order->payment->proof_of_payment);
        }

        // Upload new payment proof
        $path = $request->file('payment_proof')->store('payment-proofs', 'public');

        $order->payment->update([
            'proof_of_payment' => $path,
            'status' => 'pending_verification'
        ]);

        // Create notification for admin
        Notification::create([
            'user_id' => null, // For all admins
            'type' => 'payment_uploaded',
            'title' => 'Bukti Pembayaran Diunggah',
            'message' => "Pengguna {$order->user->name} telah mengunggah bukti pembayaran untuk pesanan #{$order->order_number}",
            'data' => json_encode([
                'order_id' => $order->id,
                'order_number' => $order->order_number
            ])
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.');
    }

    // Complete order (confirm received)
    public function complete(Order $order)
    {
        // Ensure order belongs to current user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Only shipped orders can be completed
        if ($order->status !== 'shipped') {
            return back()->with('error', 'Pesanan belum dikirim.');
        }

        $order->update(['status' => 'completed']);

        if ($order->payment) {
            $order->payment->update(['status' => 'paid']);
        }

        return back()->with('success', 'Pesanan telah diselesaikan. Terima kasih!');
    }
}
