<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class OrdersExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $status;
    protected $startDate;
    protected $endDate;

    public function __construct($status = null, $startDate = null, $endDate = null)
    {
        $this->status = $status;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = Order::with(['user', 'order_items']);

        if ($this->status && $this->status !== 'all') {
            $query->where('status', $this->status);
        }

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
            'Email',
            'Phone',
            'Total Items',
            'Total Amount',
            'Payment Method',
            'Payment Status',
            'Order Status',
        ];
    }

    public function map($order): array
    {
        return [
            $order->order_number,
            $order->created_at->format('d/m/Y H:i'),
            $order->user->name,
            $order->user->email,
            $order->phone,
            $order->order_items->sum('quantity'),
            'Rp ' . number_format($order->total_amount, 0, ',', '.'),
            ucfirst($order->payment_method),
            $order->payment_verified_at ? 'Verified' : 'Pending',
            ucfirst($order->status),
        ];
    }

    public function title(): string
    {
        return 'Orders';
    }
}
