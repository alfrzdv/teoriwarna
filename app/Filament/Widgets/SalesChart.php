<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class SalesChart extends ChartWidget
{
    protected static ?string $heading = 'Sales Revenue (Last 7 Days)';

    protected function getData(): array
    {
        $data = $this->getSalesPerDay();

        return [
            'datasets' => [
                [
                    'label' => 'Revenue (Rp)',
                    'data' => $data['revenue'],
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => 'rgb(59, 130, 246)',
                ],
            ],
            'labels' => $data['labels'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    private function getSalesPerDay(): array
    {
        $days = 7;
        $labels = [];
        $revenue = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('M d');

            $dailyRevenue = Order::whereDate('created_at', $date)
                ->where('status', 'completed')
                ->sum('total_amount');

            $revenue[] = $dailyRevenue;
        }

        return [
            'labels' => $labels,
            'revenue' => $revenue,
        ];
    }
}
