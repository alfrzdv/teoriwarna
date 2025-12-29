<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use App\Models\Notification;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductReview::with(['user', 'product', 'orderItem', 'images'])
            ->latest();

        // Filter by approval status
        if ($request->has('status')) {
            if ($request->status === 'pending') {
                $query->where('is_approved', false);
            } elseif ($request->status === 'approved') {
                $query->where('is_approved', true);
            }
        }

        // Filter by rating
        if ($request->has('rating') && $request->rating !== 'all') {
            $query->where('rating', $request->rating);
        }

        // Search by product name or user name
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->whereHas('product', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                })
                ->orWhereHas('user', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
            });
        }

        $reviews = $query->paginate(20)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function show(ProductReview $review)
    {
        $review->load(['user', 'product', 'orderItem.order', 'images']);

        return view('admin.reviews.show', compact('review'));
    }

    public function approve(ProductReview $review)
    {
        $review->update(['is_approved' => true]);

        // Create notification for user
        Notification::create([
            'user_id' => $review->user_id,
            'type' => 'review_approved',
            'title' => 'Review Disetujui',
            'message' => "Review Anda untuk produk {$review->product->name} telah disetujui dan dipublikasikan.",
            'data' => json_encode([
                'review_id' => $review->id,
                'product_id' => $review->product_id,
            ])
        ]);

        return back()->with('success', 'Review berhasil disetujui.');
    }

    public function reject(Request $request, ProductReview $review)
    {
        $validated = $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $review->update(['is_approved' => false]);

        // Create notification for user
        $message = "Review Anda untuk produk {$review->product->name} ditolak.";
        if (isset($validated['reason'])) {
            $message .= " Alasan: {$validated['reason']}";
        }

        Notification::create([
            'user_id' => $review->user_id,
            'type' => 'review_rejected',
            'title' => 'Review Ditolak',
            'message' => $message,
            'data' => json_encode([
                'review_id' => $review->id,
                'product_id' => $review->product_id,
                'reason' => $validated['reason'] ?? null,
            ])
        ]);

        return back()->with('success', 'Review berhasil ditolak.');
    }

    public function destroy(ProductReview $review)
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review berhasil dihapus.');
    }

    public function bulkApprove(Request $request)
    {
        $validated = $request->validate([
            'review_ids' => 'required|array',
            'review_ids.*' => 'exists:product_reviews,id',
        ]);

        $reviews = ProductReview::whereIn('id', $validated['review_ids'])->get();

        foreach ($reviews as $review) {
            $review->update(['is_approved' => true]);

            // Create notification for user
            Notification::create([
                'user_id' => $review->user_id,
                'type' => 'review_approved',
                'title' => 'Review Disetujui',
                'message' => "Review Anda untuk produk {$review->product->name} telah disetujui dan dipublikasikan.",
                'data' => json_encode([
                    'review_id' => $review->id,
                    'product_id' => $review->product_id,
                ])
            ]);
        }

        return back()->with('success', count($reviews) . ' review berhasil disetujui.');
    }

    public function bulkReject(Request $request)
    {
        $validated = $request->validate([
            'review_ids' => 'required|array',
            'review_ids.*' => 'exists:product_reviews,id',
            'reason' => 'nullable|string|max:500',
        ]);

        $reviews = ProductReview::whereIn('id', $validated['review_ids'])->get();

        foreach ($reviews as $review) {
            $review->update(['is_approved' => false]);

            // Create notification for user
            $message = "Review Anda untuk produk {$review->product->name} ditolak.";
            if (isset($validated['reason'])) {
                $message .= " Alasan: {$validated['reason']}";
            }

            Notification::create([
                'user_id' => $review->user_id,
                'type' => 'review_rejected',
                'title' => 'Review Ditolak',
                'message' => $message,
                'data' => json_encode([
                    'review_id' => $review->id,
                    'product_id' => $review->product_id,
                    'reason' => $validated['reason'] ?? null,
                ])
            ]);
        }

        return back()->with('success', count($reviews) . ' review berhasil ditolak.');
    }
}
