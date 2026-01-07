<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductCatalogController extends Controller
{
    public function index(Request $request)
    {
        // Build product query with eager loading
        $query = Product::with(['category', 'product_images' => function($q) {
                $q->orderByRaw('is_primary DESC, id ASC');
            }])
            ->where('status', 'active');

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sort
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->latest();
        }

        // Get all products at once
        $allProducts = $query->get();

        // Group by category
        $productsByCategory = $allProducts->groupBy('category_id')
            ->map(function($products, $categoryId) {
                return [
                    'category' => $products->first()->category,
                    'products' => $products
                ];
            })
            ->values()
            ->toArray();

        // Get categories for filter
        $categories = Category::where('is_active', true)
            ->withCount('products')
            ->get();

        $totalProducts = $allProducts->count();

        return view('catalog.index', compact('productsByCategory', 'categories', 'totalProducts'));
    }

    public function show(Product $product)
    {
        if ($product->status !== 'active') {
            abort(404);
        }

        $product->load([
            'category',
            'product_images' => function($q) {
                $q->orderByRaw('is_primary DESC, id ASC');
            }
        ]);

        // Get related products from the same category
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->with(['product_images' => function($q) {
                $q->orderByRaw('is_primary DESC, id ASC');
            }])
            ->limit(4)
            ->get();

        return view('catalog.show', compact('product', 'relatedProducts'));
    }
}
