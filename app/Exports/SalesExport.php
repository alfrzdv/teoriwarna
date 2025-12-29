<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class SalesExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = Order::with(['user', 'order_items.product'])
            ->whereIn('status', ['completed', 'shipped'])
            ->whereNotNull('payment_verified_at');

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'Order Number',
            'Date',
            'Customer',
            'Items',
            'Subtotal',
            'Shipping Cost',
            'Discount',
            'Total',
            'Payment Method',
            'Status',
        ];
    }

    public function map($order): array
    {
        $items = $order->order_items->map(function($item) {
            return $item->product->name . ' (x' . $item->quantity . ')';
        })->implode(', ');

        return [
            $order->order_number,
            $order->created_at->format('d/m/Y H:i'),
            $order->user->name,
            $items,
            'Rp ' . number_format($order->subtotal ?? 0, 0, ',', '.'),
            'Rp ' . number_format($order->shipping_cost, 0, ',', '.'),
            'Rp ' . number_format($order->discount_amount ?? 0, 0, ',', '.'),
            'Rp ' . number_format($order->total_amount, 0, ',', '.'),
            ucfirst($order->payment_method),
            ucfirst($order->status),
        ];
    }

    public function title(): string
    {
        return 'Sales Report';
    }
}
