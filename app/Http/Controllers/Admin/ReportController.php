<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\SalesExport;
use App\Exports\ProductsExport;
use App\Exports\OrdersExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function sales(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $query = Order::with(['user', 'payment'])
            ->where('status', 'completed');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $orders = $query->latest()->get();

        $totalRevenue = $orders->sum('total_amount');
        $totalOrders = $orders->count();
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Group by date
        $salesByDate = $orders->groupBy(function($order) {
            return $order->created_at->format('Y-m-d');
        })->map(function($dayOrders) {
            return [
                'total' => $dayOrders->sum('total_amount'),
                'count' => $dayOrders->count()
            ];
        });

        return view('admin.reports.sales', compact(
            'orders',
            'totalRevenue',
            'totalOrders',
            'averageOrderValue',
            'salesByDate'
        ));
    }

    public function products(Request $request)
    {
        $products = Product::with(['category', 'product_stocks', 'order_items'])
            ->get()
            ->map(function($product) {
                $totalSold = $product->order_items->sum('quantity');
                $revenue = $product->order_items->sum(function($item) {
                    return $item->quantity * $item->price;
                });

                return [
                    'product' => $product,
                    'current_stock' => $product->getCurrentStock(),
                    'total_sold' => $totalSold,
                    'revenue' => $revenue
                ];
            })
            ->sortByDesc('total_sold');

        $totalProducts = Product::count();
        $activeProducts = Product::where('status', 'active')->count();
        $lowStockCount = $products->filter(function($item) {
            return $item['current_stock'] < 20 && $item['current_stock'] > 0;
        })->count();
        $outOfStockCount = $products->filter(function($item) {
            return $item['current_stock'] == 0;
        })->count();

        return view('admin.reports.products', compact(
            'products',
            'totalProducts',
            'activeProducts',
            'lowStockCount',
            'outOfStockCount'
        ));
    }

    public function transactions(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|in:all,pending,processing,shipped,completed,cancelled',
            'payment_method' => 'nullable|in:all,bank_transfer,e_wallet,cod',
        ]);

        $query = Order::with(['user', 'payment', 'order_items']);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method') && $request->payment_method !== 'all') {
            $query->whereHas('payment', function($q) use ($request) {
                $q->where('payment_method', $request->payment_method);
            });
        }

        $orders = $query->latest()->get();

        // Statistics
        $totalTransactions = $orders->count();
        $totalAmount = $orders->sum('total_amount');

        $byStatus = $orders->groupBy('status')->map(function($group) {
            return [
                'count' => $group->count(),
                'total' => $group->sum('total_amount')
            ];
        });

        $byPaymentMethod = $orders->groupBy(function($order) {
            return $order->payment ? $order->payment->payment_method : 'unknown';
        })->map(function($group) {
            return [
                'count' => $group->count(),
                'total' => $group->sum('total_amount')
            ];
        });

        return view('admin.reports.transactions', compact(
            'orders',
            'totalTransactions',
            'totalAmount',
            'byStatus',
            'byPaymentMethod'
        ));
    }

    public function users(Request $request)
    {
        $users = User::with(['orders', 'user_addresses'])
            ->where('role', 'user')
            ->get()
            ->map(function($user) {
                $completedOrders = $user->orders->where('status', 'completed');
                $totalSpent = $completedOrders->sum('total_amount');
                $totalOrders = $user->orders->count();

                return [
                    'user' => $user,
                    'total_orders' => $totalOrders,
                    'completed_orders' => $completedOrders->count(),
                    'total_spent' => $totalSpent,
                    'last_order' => $user->orders->sortByDesc('created_at')->first()
                ];
            })
            ->sortByDesc('total_spent');

        $totalUsers = User::where('role', 'user')->count();
        $activeUsers = User::where('role', 'user')
            ->whereHas('orders', function($q) {
                $q->whereDate('created_at', '>=', now()->subDays(30));
            })->count();
        $bannedUsers = User::where('role', 'user')
            ->where('is_banned', true)->count();

        return view('admin.reports.users', compact(
            'users',
            'totalUsers',
            'activeUsers',
            'bannedUsers'
        ));
    }

    // PDF Export Methods
    public function exportSalesPDF(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $query = Order::with(['user', 'payment', 'order_items.product'])
            ->where('status', 'completed');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $orders = $query->latest()->get();
        $totalRevenue = $orders->sum('total_amount');
        $totalOrders = $orders->count();
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        $pdf = Pdf::loadView('admin.reports.sales-pdf', compact(
            'orders',
            'totalRevenue',
            'totalOrders',
            'averageOrderValue'
        ));

        $filename = 'sales-report-' . now()->format('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }

    public function exportProductsPDF()
    {
        $products = Product::with(['category', 'product_stocks', 'order_items'])
            ->get()
            ->map(function($product) {
                $totalSold = $product->order_items->sum('quantity');
                $revenue = $product->order_items->sum(function($item) {
                    return $item->quantity * $item->price;
                });

                return [
                    'product' => $product,
                    'current_stock' => $product->getCurrentStock(),
                    'total_sold' => $totalSold,
                    'revenue' => $revenue
                ];
            })
            ->sortByDesc('total_sold');

        $pdf = Pdf::loadView('admin.reports.products-pdf', compact('products'));

        $filename = 'products-report-' . now()->format('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }

    public function exportTransactionsPDF(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|in:all,pending,processing,shipped,completed,cancelled',
            'payment_method' => 'nullable|in:all,bank_transfer,e_wallet,cod',
        ]);

        $query = Order::with(['user', 'payment', 'order_items']);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method') && $request->payment_method !== 'all') {
            $query->whereHas('payment', function($q) use ($request) {
                $q->where('payment_method', $request->payment_method);
            });
        }

        $orders = $query->latest()->get();
        $totalTransactions = $orders->count();
        $totalAmount = $orders->sum('total_amount');

        $pdf = Pdf::loadView('admin.reports.transactions-pdf', compact(
            'orders',
            'totalTransactions',
            'totalAmount'
        ));

        $filename = 'transactions-report-' . now()->format('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }

    // Excel Export Methods
    public function exportSalesExcel(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $filename = 'sales-report-' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(
            new SalesExport($request->start_date, $request->end_date),
            $filename
        );
    }

    public function exportProductsExcel()
    {
        $filename = 'products-report-' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(new ProductsExport(), $filename);
    }

    public function exportTransactionsExcel(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|in:all,pending,processing,shipped,completed,cancelled',
        ]);

        $filename = 'transactions-report-' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(
            new OrdersExport($request->status, $request->start_date, $request->end_date),
            $filename
        );
    }
}
