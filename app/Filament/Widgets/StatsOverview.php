<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Total Revenue (dari orders yang completed)
        $totalRevenue = Order::where('status', 'completed')
            ->sum('total_amount');

        // Revenue bulan ini
        $monthlyRevenue = Order::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        // Total Orders
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();

        // Total Products
        $totalProducts = Product::count();
        $lowStockProducts = Product::where('stock', '<=', 10)
            ->where('stock', '>', 0)
            ->count();

        // Total Customers (users with role 'user')
        $totalCustomers = User::where('role', 'user')->count();
        $newCustomersThisMonth = User::where('role', 'user')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return [
            Stat::make('Total Revenue', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->description('Rp ' . number_format($monthlyRevenue, 0, ',', '.') . ' this month')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Total Orders', $totalOrders)
                ->description($pendingOrders . ' pending orders')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('warning'),

            Stat::make('Total Products', $totalProducts)
                ->description($lowStockProducts . ' low stock items')
                ->descriptionIcon('heroicon-m-cube')
                ->color('info'),

            Stat::make('Total Customers', $totalCustomers)
                ->description($newCustomersThisMonth . ' new this month')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
        ];
    }
}
