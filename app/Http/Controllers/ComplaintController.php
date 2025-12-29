<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    public function index()
    {
        $complaints = Complaint::where('user_id', Auth::id())
            ->with(['order'])
            ->latest()
            ->paginate(10);

        return view('complaints.index', compact('complaints'));
    }

    public function create()
    {
        // Get user's completed orders (can only complain about completed orders)
        $orders = Order::where('user_id', Auth::id())
            ->whereIn('status', ['completed', 'shipped'])
            ->latest()
            ->get();

        return view('complaints.create', compact('orders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'reason' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
        ]);

        // Ensure order belongs to user
        $order = Order::where('id', $validated['order_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        Complaint::create([
            'user_id' => Auth::id(),
            'order_id' => $validated['order_id'],
            'reason' => $validated['reason'],
            'description' => $validated['description'],
            'status' => 'open',
        ]);

        return redirect()->route('complaints.index')
            ->with('success', 'Komplain berhasil dikirim. Admin akan merespon segera.');
    }

    public function show(Complaint $complaint)
    {
        // Ensure complaint belongs to user
        if ($complaint->user_id !== Auth::id()) {
            abort(403);
        }

        $complaint->load(['order', 'admin']);

        return view('complaints.show', compact('complaint'));
    }
}
