<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Refund;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RefundController extends Controller
{
    public function index(Request $request)
    {
        $query = Refund::with(['order', 'user'])
            ->latest();

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search by refund number or user name
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('refund_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $refunds = $query->paginate(20)->withQueryString();

        return view('admin.refunds.index', compact('refunds'));
    }

    public function show(Refund $refund)
    {
        $refund->load(['order.order_items.product', 'user', 'approver']);

        return view('admin.refunds.show', compact('refund'));
    }

    public function approve(Request $request, Refund $refund)
    {
        if (!$refund->isPending()) {
            return back()->with('error', 'Refund ini sudah diproses.');
        }

        DB::beginTransaction();

        try {
            $refund->markAsApproved(Auth::id());

            // Return stock for completed/shipped orders
            if (in_array($refund->order->status, ['completed', 'shipped', 'processing'])) {
                foreach ($refund->order->order_items as $item) {
                    $item->product->addStock($item->quantity, "Refund approved: #{$refund->refund_number}");
                }
            }

            // Update order status to refunded
            $refund->order->update(['status' => 'refunded']);

            // Notify user
            Notification::create([
                'user_id' => $refund->user_id,
                'type' => 'refund_approved',
                'title' => 'Refund Disetujui',
                'message' => "Permintaan refund #{$refund->refund_number} telah disetujui. Dana akan diproses sesuai metode yang dipilih.",
                'data' => json_encode([
                    'refund_id' => $refund->id,
                    'refund_number' => $refund->refund_number,
                    'amount' => $refund->refund_amount,
                ])
            ]);

            DB::commit();

            return back()->with('success', 'Refund berhasil disetujui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyetujui refund.');
        }
    }

    public function reject(Request $request, Refund $refund)
    {
        if (!$refund->isPending()) {
            return back()->with('error', 'Refund ini sudah diproses.');
        }

        $validated = $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $refund->markAsRejected($validated['admin_notes']);

            // Notify user
            Notification::create([
                'user_id' => $refund->user_id,
                'type' => 'refund_rejected',
                'title' => 'Refund Ditolak',
                'message' => "Permintaan refund #{$refund->refund_number} ditolak. Alasan: {$validated['admin_notes']}",
                'data' => json_encode([
                    'refund_id' => $refund->id,
                    'refund_number' => $refund->refund_number,
                ])
            ]);

            DB::commit();

            return back()->with('success', 'Refund berhasil ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menolak refund.');
        }
    }

    public function markAsProcessing(Refund $refund)
    {
        if (!$refund->isApproved()) {
            return back()->with('error', 'Refund harus disetujui terlebih dahulu.');
        }

        $refund->markAsProcessing();

        // Notify user
        Notification::create([
            'user_id' => $refund->user_id,
            'type' => 'refund_processing',
            'title' => 'Refund Sedang Diproses',
            'message' => "Refund #{$refund->refund_number} sedang diproses.",
            'data' => json_encode([
                'refund_id' => $refund->id,
                'refund_number' => $refund->refund_number,
            ])
        ]);

        return back()->with('success', 'Status refund diubah menjadi processing.');
    }

    public function markAsCompleted(Refund $refund)
    {
        if (!in_array($refund->status, ['approved', 'processing'])) {
            return back()->with('error', 'Refund belum dalam status yang tepat untuk diselesaikan.');
        }

        $refund->markAsCompleted();

        // Notify user
        Notification::create([
            'user_id' => $refund->user_id,
            'type' => 'refund_completed',
            'title' => 'Refund Selesai',
            'message' => "Refund #{$refund->refund_number} telah selesai diproses. Dana sebesar Rp " . number_format($refund->refund_amount, 0, ',', '.') . " telah dikirim.",
            'data' => json_encode([
                'refund_id' => $refund->id,
                'refund_number' => $refund->refund_number,
                'amount' => $refund->refund_amount,
            ])
        ]);

        return back()->with('success', 'Refund berhasil diselesaikan.');
    }
}
