<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Transaksi</h2>
            <a href="{{ route('admin.reports.index') }}" class="text-indigo-600 hover:text-indigo-800">← Kembali</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white p-6 shadow-sm rounded-lg">
                <form method="GET" class="grid grid-cols-4 gap-4">
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="rounded-md border-gray-300" placeholder="Start Date">
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="rounded-md border-gray-300" placeholder="End Date">
                    <select name="status" class="rounded-md border-gray-300">
                        <option value="all">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    <select name="payment_method" class="rounded-md border-gray-300">
                        <option value="all">All Payment</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="e_wallet">E-Wallet</option>
                        <option value="cod">COD</option>
                    </select>
                    <button type="submit" class="col-span-4 px-4 py-2 bg-indigo-600 text-white rounded-md">Filter</button>
                </form>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white p-6 shadow-sm rounded-lg">
                    <div class="text-sm text-gray-500">Total Transaksi</div>
                    <div class="text-2xl font-bold">{{ $totalTransactions }}</div>
                </div>
                <div class="bg-white p-6 shadow-sm rounded-lg">
                    <div class="text-sm text-gray-500">Total Amount</div>
                    <div class="text-2xl font-bold">Rp {{ number_format($totalAmount, 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($orders as $order)
                            <tr>
                                <td class="px-4 py-3 text-sm">{{ $order->order_number }}</td>
                                <td class="px-4 py-3 text-sm">{{ $order->user->name }}</td>
                                <td class="px-4 py-3 text-sm">{{ $order->created_at->format('d M Y') }}</td>
                                <td class="px-4 py-3 text-sm">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-sm">{{ ucfirst($order->status) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
