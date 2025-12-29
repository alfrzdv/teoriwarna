<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $query = Complaint::with(['user', 'order', 'admin'])
            ->latest();

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search by order number or user name
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->whereHas('order', function($q) use ($request) {
                    $q->where('order_number', 'like', '%' . $request->search . '%');
                })
                ->orWhereHas('user', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
            });
        }

        $complaints = $query->paginate(20)->withQueryString();

        return view('admin.complaints.index', compact('complaints'));
    }

    public function show(Complaint $complaint)
    {
        $complaint->load(['user', 'order.order_items.product', 'admin']);

        return view('admin.complaints.show', compact('complaint'));
    }

    public function reply(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'admin_reply' => 'required|string|max:1000',
        ]);

        $complaint->replyByAdmin(Auth::id(), $validated['admin_reply']);

        // Create notification for user
        Notification::create([
            'user_id' => $complaint->user_id,
            'type' => 'complaint_reply',
            'title' => 'Komplain Dibalas Admin',
            'message' => "Admin telah membalas komplain Anda untuk order #{$complaint->order->order_number}",
            'data' => json_encode([
                'complaint_id' => $complaint->id,
                'order_id' => $complaint->order_id,
            ])
        ]);

        return back()->with('success', 'Balasan berhasil dikirim.');
    }

    public function updateStatus(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,in_review,resolved,rejected',
        ]);

        $complaint->update(['status' => $validated['status']]);

        if ($validated['status'] === 'resolved') {
            $complaint->update(['resolved_at' => now()]);
        }

        // Create notification for user
        Notification::create([
            'user_id' => $complaint->user_id,
            'type' => 'complaint_status',
            'title' => 'Status Komplain Diupdate',
            'message' => "Status komplain untuk order #{$complaint->order->order_number} telah diupdate menjadi {$validated['status']}",
            'data' => json_encode([
                'complaint_id' => $complaint->id,
                'status' => $validated['status'],
            ])
        ]);

        return back()->with('success', 'Status komplain berhasil diupdate.');
    }
}
