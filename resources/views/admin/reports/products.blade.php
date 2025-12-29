<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Produk</h2>
            <a href="{{ route('admin.reports.index') }}" class="text-indigo-600 hover:text-indigo-800">← Kembali</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-4 gap-4">
                <div class="bg-white p-6 shadow-sm rounded-lg">
                    <div class="text-sm text-gray-500">Total Produk</div>
                    <div class="text-2xl font-bold">{{ $totalProducts }}</div>
                </div>
                <div class="bg-white p-6 shadow-sm rounded-lg">
                    <div class="text-sm text-gray-500">Produk Aktif</div>
                    <div class="text-2xl font-bold">{{ $activeProducts }}</div>
                </div>
                <div class="bg-white p-6 shadow-sm rounded-lg">
                    <div class="text-sm text-gray-500">Stok Menipis</div>
                    <div class="text-2xl font-bold text-yellow-600">{{ $lowStockCount }}</div>
                </div>
                <div class="bg-white p-6 shadow-sm rounded-lg">
                    <div class="text-sm text-gray-500">Stok Habis</div>
                    <div class="text-2xl font-bold text-red-600">{{ $outOfStockCount }}</div>
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Performa Produk</h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Terjual</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($products as $item)
                            <tr>
                                <td class="px-4 py-3 text-sm">{{ $item['product']->name }}</td>
                                <td class="px-4 py-3 text-sm">{{ $item['product']->category->name }}</td>
                                <td class="px-4 py-3 text-sm">{{ $item['current_stock'] }}</td>
                                <td class="px-4 py-3 text-sm font-semibold">{{ $item['total_sold'] }}</td>
                                <td class="px-4 py-3 text-sm">Rp {{ number_format($item['revenue'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
