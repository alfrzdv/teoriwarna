<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductCatalogController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('is_active', true)
            ->withCount('products')
            ->get();

        // Group products by category
        $productsByCategory = [];

        foreach ($categories as $category) {
            $query = Product::with(['category', 'product_images'])
                ->where('status', 'active')
                ->where('category_id', $category->id);

            // Search
            if ($request->filled('search')) {
                $query->search($request->search);
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

            $products = $query->get();

            if ($products->count() > 0) {
                $productsByCategory[] = [
                    'category' => $category,
                    'products' => $products
                ];
            }
        }

        // If category filter is selected, show only that category
        if ($request->filled('category')) {
            $selectedCategoryId = $request->category;
            $productsByCategory = array_filter($productsByCategory, function($item) use ($selectedCategoryId) {
                return $item['category']->id == $selectedCategoryId;
            });
        }

        // Calculate total products
        $totalProducts = collect($productsByCategory)->sum(function($item) {
            return $item['products']->count();
        });

        return view('catalog.index', compact('productsByCategory', 'categories', 'totalProducts'));
    }

    public function show(Product $product)
    {
        if ($product->status !== 'active') {
            abort(404);
        }

        $product->load(['category', 'product_images']);

        // Get related products from the same category
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->with(['product_images'])
            ->limit(4)
            ->get();

        return view('catalog.show', compact('product', 'relatedProducts'));
    }
}
