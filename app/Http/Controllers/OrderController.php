<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Notification;
use App\Models\Refund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::where('user_id', Auth::id())
            ->with(['order_items.product', 'payment'])
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

        // Search by order number
        if ($request->has('search') && $request->search) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(10)->withQueryString();

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

    public function cancel(Request $request, Order $order)
    {
        // Ensure order belongs to current user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$order->canBeCancelled()) {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan.');
        }

        DB::beginTransaction();

        try {
            // Return stock
            foreach ($order->order_items as $item) {
                $item->product->addStock($item->quantity, "Order cancelled: #{$order->order_number}");
            }

            $order->update(['status' => 'cancelled']);

            // Update payment status
            if ($order->payment) {
                $order->payment->update(['status' => 'cancelled']);
            }

            // If payment was made, create refund request automatically
            if ($order->payment && in_array($order->payment->status, ['paid', 'pending_verification'])) {
                $refund = Refund::create([
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                    'refund_number' => Refund::generateRefundNumber(),
                    'refund_amount' => $order->total_amount,
                    'refund_method' => $order->payment->payment_method === 'cod' ? 'store_credit' : $order->payment->payment_method,
                    'status' => 'pending',
                    'reason' => $request->input('cancel_reason', 'User cancelled order'),
                ]);

                // Notify admin
                Notification::create([
                    'user_id' => null,
                    'type' => 'refund_requested',
                    'title' => 'Permintaan Refund Baru',
                    'message' => "User {$order->user->name} membatalkan pesanan #{$order->order_number} dan meminta refund.",
                    'data' => json_encode([
                        'order_id' => $order->id,
                        'refund_id' => $refund->id,
                        'amount' => $order->total_amount,
                    ])
                ]);
            }

            DB::commit();

            return back()->with('success', 'Pesanan berhasil dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat membatalkan pesanan.');
        }
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

    // Request refund/return
    public function requestRefund(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$order->canRequestRefund()) {
            return back()->with('error', 'Pesanan ini tidak dapat di-refund.');
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
            'refund_method' => 'required|in:bank_transfer,e_wallet,store_credit',
            'bank_name' => 'required_if:refund_method,bank_transfer|string|max:100',
            'account_number' => 'required_if:refund_method,bank_transfer|string|max:50',
            'account_name' => 'required_if:refund_method,bank_transfer|string|max:100',
            'e_wallet_number' => 'required_if:refund_method,e_wallet|string|max:50',
        ]);

        DB::beginTransaction();

        try {
            $bankDetails = null;
            if ($validated['refund_method'] === 'bank_transfer') {
                $bankDetails = [
                    'bank_name' => $validated['bank_name'],
                    'account_number' => $validated['account_number'],
                    'account_name' => $validated['account_name'],
                ];
            } elseif ($validated['refund_method'] === 'e_wallet') {
                $bankDetails = [
                    'e_wallet_number' => $validated['e_wallet_number'],
                ];
            }

            $refund = Refund::create([
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'refund_number' => Refund::generateRefundNumber(),
                'refund_amount' => $order->total_amount,
                'refund_method' => $validated['refund_method'],
                'status' => 'pending',
                'reason' => $validated['reason'],
                'bank_details' => $bankDetails,
            ]);

            // Notify admin
            Notification::create([
                'user_id' => null,
                'type' => 'refund_requested',
                'title' => 'Permintaan Refund/Return Baru',
                'message' => "User {$order->user->name} mengajukan refund untuk pesanan #{$order->order_number}",
                'data' => json_encode([
                    'order_id' => $order->id,
                    'refund_id' => $refund->id,
                    'amount' => $order->total_amount,
                ])
            ]);

            DB::commit();

            return back()->with('success', 'Permintaan refund berhasil diajukan. Menunggu persetujuan admin.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat mengajukan refund.');
        }
    }

    // View refund status
    public function viewRefund(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$order->refund) {
            return back()->with('error', 'Tidak ada permintaan refund untuk pesanan ini.');
        }

        $refund = $order->refund;
        return view('orders.refund', compact('order', 'refund'));
    }
}
