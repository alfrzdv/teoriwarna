<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Order #{{ $order->order_number }}
            </h2>
            <a href="{{ route('orders.index') }}" class="text-blue-600 hover:text-blue-800">
                ← Back to Orders
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Order Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Order Items -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Order Items</h3>

                            @foreach($order->order_items as $item)
                                <div class="flex gap-4 py-4 border-b last:border-b-0">
                                    <div class="w-20 h-20 bg-gray-100 rounded overflow-hidden flex-shrink-0">
                                        @if($item->product->getPrimaryImage())
                                            <img src="{{ asset('storage/' . $item->product->getPrimaryImage()->image_path) }}"
                                                alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-2xl">📦</div>
                                        @endif
                                    </div>

                                    <div class="flex-1">
                                        <h4 class="font-semibold">{{ $item->product->name }}</h4>
                                        <p class="text-sm text-gray-600">Qty: {{ $item->quantity }}</p>
                                        <p class="text-sm text-gray-600">@ Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                    </div>

                                    <div class="text-right">
                                        <p class="font-bold text-blue-600">Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Shipping Information -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Shipping Information</h3>

                            <div class="space-y-2 text-sm">
                                <div><strong>Name:</strong> {{ $order->shipping_name }}</div>
                                <div><strong>Phone:</strong> {{ $order->shipping_phone }}</div>
                                <div><strong>Address:</strong> {{ $order->shipping_address }}</div>
                                <div><strong>City:</strong> {{ $order->shipping_city }}</div>
                                <div><strong>Postal Code:</strong> {{ $order->shipping_postal_code }}</div>
                                @if($order->notes)
                                    <div class="mt-3 pt-3 border-t">
                                        <strong>Notes:</strong> {{ $order->notes }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="space-y-6">
                    <!-- Status & Payment -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Order Status</h3>

                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-600">Order Status</p>
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full inline-block
                                        {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $order->status == 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $order->status == 'shipped' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $order->status == 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-600">Payment Status</p>
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full inline-block
                                        {{ $order->payment->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $order->payment->status == 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $order->payment->status == 'failed' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $order->payment->status == 'cancelled' ? 'bg-gray-100 text-gray-800' : '' }}">
                                        {{ ucfirst($order->payment->status) }}
                                    </span>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-600">Payment Method</p>
                                    <p class="font-semibold">{{ ucwords(str_replace('_', ' ', $order->payment->payment_method)) }}</p>
                                </div>

                                <div class="pt-3 border-t">
                                    <p class="text-sm text-gray-600">Order Date</p>
                                    <p class="font-semibold">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Summary -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Total Summary</h3>

                            <div class="space-y-2 text-sm">
                                @php
                                    $subtotal = $order->order_items->sum(function($item) {
                                        return $item->quantity * $item->price;
                                    });
                                    $shipping = 15000;
                                @endphp

                                <div class="flex justify-between">
                                    <span>Subtotal</span>
                                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>

                                <div class="flex justify-between">
                                    <span>Shipping</span>
                                    <span>Rp {{ number_format($shipping, 0, ',', '.') }}</span>
                                </div>

                                <div class="flex justify-between pt-3 border-t text-lg font-bold">
                                    <span>Total</span>
                                    <span class="text-blue-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Payment Proof -->
                    @if($order->payment && in_array($order->payment->payment_method, ['bank_transfer', 'e_wallet']) && $order->payment->status == 'pending')
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold mb-4">Upload Bukti Pembayaran</h3>
                                <form action="{{ route('orders.upload-payment', $order) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" name="payment_proof" accept="image/*" required
                                           class="w-full mb-3 text-sm">
                                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                        Upload Bukti Bayar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- Payment Proof -->
                    @if($order->payment && $order->payment->proof_of_payment)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold mb-4">Bukti Pembayaran</h3>
                                <img src="{{ asset('storage/' . $order->payment->proof_of_payment) }}"
                                     alt="Payment Proof" class="w-full border rounded">
                                @if($order->payment->status == 'pending_verification')
                                    <p class="text-sm text-orange-600 mt-2">Menunggu verifikasi admin</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Tracking Info -->
                    @if($order->tracking_number)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold mb-4">Informasi Pengiriman</h3>
                                <div class="space-y-2 text-sm">
                                    <div><strong>Kurir:</strong> {{ $order->shipping_courier }}</div>
                                    <div><strong>No. Resi:</strong> {{ $order->tracking_number }}</div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Complete Order -->
                    @if($order->status == 'shipped')
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <form action="{{ route('orders.complete', $order) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
                                        onclick="return confirm('Konfirmasi barang sudah diterima?')">
                                        Barang Sudah Diterima
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    @if($order->status == 'pending')
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <form action="{{ route('orders.cancel', $order) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600"
                                        onclick="return confirm('Are you sure you want to cancel this order?')">
                                        Cancel Order
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
