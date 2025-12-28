<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\AdminLog;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'product_images']);

        // Filter by category
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $products = $query->latest()->paginate(15);
        $categories = Category::where('is_active', true)->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,archived',
            'stock_quantity' => 'nullable|integer|min:0',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = Product::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'price' => $validated['price'],
            'description' => $validated['description'],
            'status' => $validated['status'],
        ]);

        // Add initial stock if provided
        if ($request->filled('stock_quantity') && $request->stock_quantity > 0) {
            $product->addStock($request->stock_quantity, 'Initial stock');
        }

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $index === 0, // First image is primary
                ]);
            }
        }

        AdminLog::log('create_product', "Created product: {$product->name}");

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'product_images', 'product_stocks' => function ($query) {
            $query->latest();
        }]);

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        $product->load(['category', 'product_images']);

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,archived',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'price' => $validated['price'],
            'description' => $validated['description'],
            'status' => $validated['status'],
        ]);

        // Handle new image uploads
        if ($request->hasFile('images')) {
            $existingImagesCount = $product->product_images()->count();

            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $existingImagesCount === 0 && $index === 0,
                ]);
            }
        }

        AdminLog::log('update_product', "Updated product: {$product->name}");

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diupdate.');
    }

    public function destroy(Product $product)
    {
        // Check if product has orders
        if ($product->order_items()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus produk yang sudah pernah dipesan.');
        }

        // Delete product images from storage
        foreach ($product->product_images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        $productName = $product->name;
        $product->delete();

        AdminLog::log('delete_product', "Deleted product: {$productName}");

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    public function deleteImage(ProductImage $image)
    {
        Storage::disk('public')->delete($image->image_path);

        $wasPrimary = $image->is_primary;
        $productId = $image->product_id;
        $image->delete();

        // If deleted image was primary, make the first remaining image primary
        if ($wasPrimary) {
            $firstImage = ProductImage::where('product_id', $productId)->first();
            if ($firstImage) {
                $firstImage->update(['is_primary' => true]);
            }
        }

        return back()->with('success', 'Gambar berhasil dihapus.');
    }

    public function setPrimaryImage(ProductImage $image)
    {
        // Remove primary status from all images of this product
        ProductImage::where('product_id', $image->product_id)
            ->update(['is_primary' => false]);

        // Set this image as primary
        $image->update(['is_primary' => true]);

        return back()->with('success', 'Gambar utama berhasil diubah.');
    }

    public function addStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string|max:255',
        ]);

        $product->addStock($validated['quantity'], $validated['note'] ?? 'Stock added by admin');

        AdminLog::log('add_product_stock', "Added {$validated['quantity']} stock to product: {$product->name}");

        return back()->with('success', "Stok berhasil ditambahkan: {$validated['quantity']} item.");
    }

    public function reduceStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string|max:255',
        ]);

        if (!$product->hasEnoughStock($validated['quantity'])) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        $product->reduceStock($validated['quantity'], $validated['note'] ?? 'Stock reduced by admin');

        AdminLog::log('reduce_product_stock', "Reduced {$validated['quantity']} stock from product: {$product->name}");

        return back()->with('success', "Stok berhasil dikurangi: {$validated['quantity']} item.");
    }
}
