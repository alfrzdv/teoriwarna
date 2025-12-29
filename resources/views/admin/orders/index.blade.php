<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Pesanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Filters -->
                    <div class="mb-6">
                        <form method="GET" action="{{ route('admin.orders.index') }}" class="space-y-4">
                            <div class="flex gap-4">
                                <!-- Search -->
                                <input type="text" name="search" value="{{ request('search') }}"
                                       placeholder="Cari order number atau nama customer..."
                                       class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                    Filter
                                </button>

                                @if(request()->hasAny(['search', 'status', 'payment_status', 'payment_method']))
                                    <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                                        Clear
                                    </a>
                                @endif
                            </div>

                            <div class="grid grid-cols-3 gap-4">
                                <!-- Order Status Filter -->
                                <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="all">Order Status: All</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>

                                <!-- Payment Status Filter -->
                                <select name="payment_status" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="all">Payment Status: All</option>
                                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="pending_verification" {{ request('payment_status') == 'pending_verification' ? 'selected' : '' }}>Pending Verification</option>
                                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="cancelled" {{ request('payment_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>

                                <!-- Payment Method Filter -->
                                <select name="payment_method" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="all">Payment Method: All</option>
                                    <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="e_wallet" {{ request('payment_method') == 'e_wallet' ? 'selected' : '' }}>E-Wallet</option>
                                    <option value="cod" {{ request('payment_method') == 'cod' ? 'selected' : '' }}>COD</option>
                                </select>
                            </div>
                        </form>
                    </div>

                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Orders Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order Number</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($orders as $order)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $order->order_number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $order->user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $order->status == 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $order->status == 'shipped' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $order->status == 'processing' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $order->status == 'pending' ? 'bg-gray-100 text-gray-800' : '' }}
                                                {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($order->payment)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    {{ $order->payment->status == 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                                    {{ $order->payment->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                    {{ $order->payment->status == 'pending_verification' ? 'bg-orange-100 text-orange-800' : '' }}
                                                    {{ $order->payment->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                                    {{ ucfirst($order->payment->status) }}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $order->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            Tidak ada pesanan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
