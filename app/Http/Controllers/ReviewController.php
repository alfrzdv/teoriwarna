<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use App\Models\OrderItem;
use App\Models\ReviewImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = ProductReview::with(['product', 'orderItem'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(20);

        return view('reviews.index', compact('reviews'));
    }

    public function create(OrderItem $orderItem)
    {
        // Check if user owns this order item
        if ($orderItem->order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Check if order is completed
        if ($orderItem->order->status !== 'completed') {
            return back()->with('error', 'Anda hanya bisa review produk dari order yang sudah selesai.');
        }

        // Check if already reviewed
        if ($orderItem->review) {
            return back()->with('error', 'Anda sudah memberikan review untuk produk ini.');
        }

        return view('reviews.create', compact('orderItem'));
    }

    public function store(Request $request, OrderItem $orderItem)
    {
        // Check if user owns this order item
        if ($orderItem->order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Check if order is completed
        if ($orderItem->order->status !== 'completed') {
            return back()->with('error', 'Anda hanya bisa review produk dari order yang sudah selesai.');
        }

        // Check if already reviewed
        if ($orderItem->review) {
            return back()->with('error', 'Anda sudah memberikan review untuk produk ini.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $review = ProductReview::create([
            'product_id' => $orderItem->product_id,
            'user_id' => Auth::id(),
            'order_item_id' => $orderItem->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        // Handle images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('review-images', 'public');

                ReviewImage::create([
                    'product_review_id' => $review->id,
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('orders.show', $orderItem->order_id)
            ->with('success', 'Review berhasil dikirim. Menunggu persetujuan admin.');
    }

    public function edit(ProductReview $review)
    {
        // Check if user owns this review
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('reviews.edit', compact('review'));
    }

    public function update(Request $request, ProductReview $review)
    {
        // Check if user owns this review
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'exists:review_images,id',
        ]);

        $review->update([
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        // Delete selected images
        if ($request->has('delete_images')) {
            ReviewImage::whereIn('id', $request->delete_images)
                ->where('product_review_id', $review->id)
                ->each(function ($image) {
                    $image->delete(); // Will auto-delete file via model event
                });
        }

        // Handle new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('review-images', 'public');

                ReviewImage::create([
                    'product_review_id' => $review->id,
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('reviews.index')
            ->with('success', 'Review berhasil diupdate. Menunggu persetujuan admin.');
    }

    public function destroy(ProductReview $review)
    {
        // Check if user owns this review
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $review->delete();

        return redirect()->route('reviews.index')
            ->with('success', 'Review berhasil dihapus.');
    }
}
