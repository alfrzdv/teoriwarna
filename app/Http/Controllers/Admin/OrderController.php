<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Notification;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // View all orders
    public function index(Request $request)
    {
        $query = Order::with(['user', 'order_items.product', 'payment'])
            ->latest();

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status !== 'all') {
            $query->whereHas('payment', function($q) use ($request) {
                $q->where('status', $request->payment_status);
            });
        }

        // Filter by payment method
        if ($request->has('payment_method') && $request->payment_method !== 'all') {
            $query->whereHas('payment', function($q) use ($request) {
                $q->where('payment_method', $request->payment_method);
            });
        }

        // Search by order number or user name
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $orders = $query->paginate(20)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    // View order detail
    public function show(Order $order)
    {
        $order->load(['user.user_addresses', 'order_items.product.product_images', 'payment']);

        return view('admin.orders.show', compact('order'));
    }

    // Update order status
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled'
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        // Create notification for user
        Notification::create([
            'user_id' => $order->user_id,
            'type' => 'order_status',
            'title' => 'Status Pesanan Diupdate',
            'message' => "Pesanan #{$order->order_number} telah diupdate dari {$oldStatus} menjadi {$request->status}",
            'data' => json_encode([
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'old_status' => $oldStatus,
                'new_status' => $request->status
            ])
        ]);

        return back()->with('success', 'Status pesanan berhasil diupdate.');
    }

    // Add tracking number
    public function addTracking(Request $request, Order $order)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:255',
            'shipping_courier' => 'nullable|string|max:100'
        ]);

        $order->update([
            'tracking_number' => $request->tracking_number,
            'shipping_courier' => $request->shipping_courier,
            'status' => 'shipped' // Auto update status to shipped when tracking added
        ]);

        // Create notification for user
        Notification::create([
            'user_id' => $order->user_id,
            'type' => 'order_shipped',
            'title' => 'Pesanan Dikirim',
            'message' => "Pesanan #{$order->order_number} telah dikirim dengan nomor resi: {$request->tracking_number}",
            'data' => json_encode([
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'tracking_number' => $request->tracking_number,
                'shipping_courier' => $request->shipping_courier
            ])
        ]);

        return back()->with('success', 'Nomor resi berhasil ditambahkan.');
    }

    // Verify payment
    public function verifyPayment(Order $order)
    {
        if (!$order->payment) {
            return back()->with('error', 'Pembayaran tidak ditemukan.');
        }

        $order->payment->update(['status' => 'paid']);
        $order->update(['status' => 'processing']);

        // Create notification for user
        Notification::create([
            'user_id' => $order->user_id,
            'type' => 'payment_verified',
            'title' => 'Pembayaran Terverifikasi',
            'message' => "Pembayaran untuk pesanan #{$order->order_number} telah terverifikasi.",
            'data' => json_encode([
                'order_id' => $order->id,
                'order_number' => $order->order_number
            ])
        ]);

        return back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    // Reject payment
    public function rejectPayment(Request $request, Order $order)
    {
        if (!$order->payment) {
            return back()->with('error', 'Pembayaran tidak ditemukan.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $order->payment->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason']
        ]);

        // Create notification for user
        Notification::create([
            'user_id' => $order->user_id,
            'type' => 'payment_rejected',
            'title' => 'Pembayaran Ditolak',
            'message' => "Bukti pembayaran untuk pesanan #{$order->order_number} ditolak. Alasan: {$validated['rejection_reason']}. Silakan upload ulang bukti pembayaran yang benar.",
            'data' => json_encode([
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'reason' => $validated['rejection_reason']
            ])
        ]);

        return back()->with('success', 'Pembayaran berhasil ditolak.');
    }

    // Bulk update order status
    public function bulkUpdateStatus(Request $request)
    {
        $validated = $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
            'status' => 'required|in:pending,processing,shipped,completed,cancelled'
        ]);

        $updated = 0;
        foreach ($validated['order_ids'] as $orderId) {
            $order = Order::find($orderId);
            if ($order) {
                $oldStatus = $order->status;
                $order->update(['status' => $validated['status']]);

                // Create notification for user
                Notification::create([
                    'user_id' => $order->user_id,
                    'type' => 'order_status',
                    'title' => 'Status Pesanan Diupdate',
                    'message' => "Pesanan #{$order->order_number} telah diupdate dari {$oldStatus} menjadi {$validated['status']}",
                    'data' => json_encode([
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'old_status' => $oldStatus,
                        'new_status' => $validated['status']
                    ])
                ]);

                $updated++;
            }
        }

        return back()->with('success', "{$updated} pesanan berhasil diupdate.");
    }
}
