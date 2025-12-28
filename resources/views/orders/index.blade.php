<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Orders
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($orders->count() > 0)
                <div class="space-y-4">
                    @foreach($orders as $order)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold">Order #{{ $order->order_number }}</h3>
                                        <p class="text-sm text-gray-600">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                    </div>

                                    <span class="px-3 py-1 text-sm font-semibold rounded-full
                                        {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $order->status == 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $order->status == 'shipped' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $order->status == 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>

                                <div class="border-t border-gray-200 pt-4">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-sm text-gray-600">{{ $order->order_items->count() }} items</p>
                                            <p class="text-lg font-bold text-blue-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                        </div>

                                        <div class="flex gap-2">
                                            <a href="{{ route('orders.show', $order) }}"
                                                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                                View Details
                                            </a>

                                            @if($order->status == 'pending')
                                                <form action="{{ route('orders.cancel', $order) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600"
                                                        onclick="return confirm('Cancel this order?')">
                                                        Cancel Order
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <div class="text-6xl mb-4">📦</div>
                        <h3 class="text-xl font-semibold mb-2">No Orders Yet</h3>
                        <p class="text-gray-600 mb-4">You haven't placed any orders yet.</p>
                        <a href="{{ route('products.index') }}" class="px-6 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Start Shopping
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
