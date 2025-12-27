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
        $totalRevenue = Order::where('status', 'completed')->sum('total_price');
        
        $pendingOrders = Order::pending()->count();
        $openComplaints = Complaint::open()->count();
        
        $recentOrders = Order::with(['user', 'payment'])
            ->latest()
            ->take(10)
            ->get();
        
        $lowStockProducts = Product::with('product_stocks')
            ->get()
            ->filter(function ($product) {
                return $product->getCurrentStock() < 20;
            })
            ->take(10);

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalProducts',
            'totalOrders',
            'totalRevenue',
            'pendingOrders',
            'openComplaints',
            'recentOrders',
            'lowStockProducts'
        ));
    }
}