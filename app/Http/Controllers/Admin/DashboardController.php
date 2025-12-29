<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Complaint;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');

        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $shippedOrders = Order::where('status', 'shipped')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();

        $pendingPayments = Order::whereHas('payment', function($q) {
            $q->where('status', 'pending');
        })->count();

        $todayRevenue = Order::where('status', 'completed')
            ->whereDate('created_at', today())
            ->sum('total_amount');

        $monthRevenue = Order::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        $todayOrders = Order::whereDate('created_at', today())->count();
        $monthOrders = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $recentOrders = Order::with(['user', 'payment'])
            ->latest()
            ->take(10)
            ->get();

        $lowStockProducts = Product::with('product_stocks')
            ->get()
            ->filter(function ($product) {
                return $product->getCurrentStock() < 20 && $product->getCurrentStock() > 0;
            })
            ->sortBy(function ($product) {
                return $product->getCurrentStock();
            })
            ->take(10);

        $topProducts = \DB::table('order_items')
            ->select('product_id', \DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_id')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                $product = Product::find($item->product_id);
                return [
                    'product' => $product,
                    'total_sold' => $item->total_sold
                ];
            });

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalProducts',
            'totalOrders',
            'totalRevenue',
            'pendingOrders',
            'processingOrders',
            'shippedOrders',
            'completedOrders',
            'cancelledOrders',
            'pendingPayments',
            'todayRevenue',
            'monthRevenue',
            'todayOrders',
            'monthOrders',
            'recentOrders',
            'lowStockProducts',
            'topProducts'
        ));
    }
}