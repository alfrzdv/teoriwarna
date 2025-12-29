<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProductsExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    public function collection()
    {
        return Product::with('category')->get();
    }

    public function headings(): array
    {
        return [
            'SKU',
            'Name',
            'Category',
            'Price',
            'Stock',
            'Status',
            'Created At',
        ];
    }

    public function map($product): array
    {
        return [
            $product->sku,
            $product->name,
            $product->category->name ?? '-',
            'Rp ' . number_format($product->price, 0, ',', '.'),
            $product->stock,
            $product->is_active ? 'Active' : 'Inactive',
            $product->created_at->format('d/m/Y'),
        ];
    }

    public function title(): string
    {
        return 'Products';
    }
}
