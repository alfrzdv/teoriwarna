<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-white leading-tight font-heading">
                Order #{{ $order->order_number }}
            </h2>
            <a href="{{ route('orders.index') }}" class="text-primary-400 hover:text-primary-300 transition-colors">
                ← Back to Orders
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Order Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Order Items -->
                    <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 overflow-hidden shadow-sm rounded-xl">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-white mb-4 font-heading">Order Items</h3>

                            @foreach($order->order_items as $item)
                                <div class="flex gap-4 py-4 border-b border-gray-700/50 last:border-b-0">
                                    <div class="w-20 h-20 bg-gray-900 rounded-lg overflow-hidden flex-shrink-0">
                                        @if($item->product->getPrimaryImage())
                                            <img src="{{ asset('storage/' . $item->product->getPrimaryImage()->image_path) }}"
                                                alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-2xl">📦</div>
                                        @endif
                                    </div>

                                    <div class="flex-1">
                                        <h4 class="font-semibold text-white">{{ $item->product->name }}</h4>
                                        <p class="text-sm text-gray-400">Qty: {{ $item->quantity }}</p>
                                        <p class="text-sm text-gray-400">@ Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                    </div>

                                    <div class="text-right">
                                        <p class="font-bold text-primary-400">Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Shipping Information -->
                    <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 overflow-hidden shadow-sm rounded-xl">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-white mb-4 font-heading">Shipping Information</h3>

                            <div class="space-y-2 text-sm">
                                <div class="text-gray-300"><strong class="text-white">Name:</strong> {{ $order->shipping_name }}</div>
                                <div class="text-gray-300"><strong class="text-white">Phone:</strong> {{ $order->shipping_phone }}</div>
                                <div class="text-gray-300"><strong class="text-white">Address:</strong> {{ $order->shipping_address }}</div>
                                <div class="text-gray-300"><strong class="text-white">City:</strong> {{ $order->shipping_city }}</div>
                                <div class="text-gray-300"><strong class="text-white">Postal Code:</strong> {{ $order->shipping_postal_code }}</div>
                                @if($order->notes)
                                    <div class="mt-3 pt-3 border-t border-gray-700/50 text-gray-300">
                                        <strong class="text-white">Notes:</strong> {{ $order->notes }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="space-y-6">
                    <!-- Status & Payment -->
                    <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 overflow-hidden shadow-sm rounded-xl">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-white mb-4 font-heading">Order Status</h3>

                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-400">Order Status</p>
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full inline-block
                                        {{ $order->status == 'pending' ? 'bg-yellow-600/20 text-yellow-400 border border-yellow-600/30' : '' }}
                                        {{ $order->status == 'processing' ? 'bg-blue-600/20 text-blue-400 border border-blue-600/30' : '' }}
                                        {{ $order->status == 'shipped' ? 'bg-purple-600/20 text-purple-400 border border-purple-600/30' : '' }}
                                        {{ $order->status == 'delivered' ? 'bg-green-600/20 text-green-400 border border-green-600/30' : '' }}
                                        {{ $order->status == 'cancelled' ? 'bg-red-600/20 text-red-400 border border-red-600/30' : '' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-400">Payment Status</p>
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full inline-block
                                        {{ $order->payment->status == 'pending' ? 'bg-yellow-600/20 text-yellow-400 border border-yellow-600/30' : '' }}
                                        {{ $order->payment->status == 'paid' ? 'bg-green-600/20 text-green-400 border border-green-600/30' : '' }}
                                        {{ $order->payment->status == 'failed' ? 'bg-red-600/20 text-red-400 border border-red-600/30' : '' }}
                                        {{ $order->payment->status == 'cancelled' ? 'bg-gray-600/20 text-gray-400 border border-gray-600/30' : '' }}">
                                        {{ ucfirst($order->payment->status) }}
                                    </span>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-400">Payment Method</p>
                                    <p class="font-semibold text-white">{{ ucwords(str_replace('_', ' ', $order->payment->payment_method)) }}</p>
                                </div>

                                <div class="pt-3 border-t border-gray-700/50">
                                    <p class="text-sm text-gray-400">Order Date</p>
                                    <p class="font-semibold text-white">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Summary -->
                    <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 overflow-hidden shadow-sm rounded-xl">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-white mb-4 font-heading">Total Summary</h3>

                            <div class="space-y-2 text-sm">
                                @php
                                    $subtotal = $order->order_items->sum(function($item) {
                                        return $item->quantity * $item->price;
                                    });
                                    $shipping = 15000;
                                @endphp

                                <div class="flex justify-between text-gray-300">
                                    <span>Subtotal</span>
                                    <span class="text-white">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>

                                <div class="flex justify-between text-gray-300">
                                    <span>Shipping</span>
                                    <span class="text-white">Rp {{ number_format($shipping, 0, ',', '.') }}</span>
                                </div>

                                <div class="flex justify-between pt-3 border-t border-gray-700/50 text-lg font-bold">
                                    <span class="text-white">Total</span>
                                    <span class="text-primary-400">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tracking Info -->
                    @if($order->tracking_number)
                        <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 overflow-hidden shadow-sm rounded-xl">
                            <div class="p-6">
                                <h3 class="text-lg font-bold text-white mb-4 font-heading">Informasi Pengiriman</h3>
                                <div class="space-y-2 text-sm">
                                    <div class="text-gray-300"><strong class="text-white">Kurir:</strong> {{ $order->shipping_courier }}</div>
                                    <div class="text-gray-300"><strong class="text-white">No. Resi:</strong> {{ $order->tracking_number }}</div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Complete Order -->
                    @if($order->status == 'shipped')
                        <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 overflow-hidden shadow-sm rounded-xl">
                            <div class="p-6">
                                <form action="{{ route('orders.complete', $order) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2.5 bg-gradient-to-r from-green-600 to-green-500 hover:from-green-500 hover:to-green-400 text-white font-medium rounded-lg transition-all"
                                        onclick="return confirm('Konfirmasi barang sudah diterima?')">
                                        Barang Sudah Diterima
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- Payment Button -->
                    @if($order->status == 'pending' && $order->payment->status == 'pending')
                        <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 overflow-hidden shadow-sm rounded-xl">
                            <div class="p-6">
                                <button id="pay-button" class="w-full px-4 py-2.5 bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-500 hover:to-primary-400 text-white font-bold rounded-lg transition-all mb-3">
                                    💳 Bayar Sekarang
                                </button>
                                <p class="text-xs text-gray-400 text-center">Klik untuk membuka halaman pembayaran</p>
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    @if($order->status == 'pending' && $order->payment->status == 'pending')
                        <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 overflow-hidden shadow-sm rounded-xl">
                            <div class="p-6">
                                <form action="{{ route('orders.cancel', $order) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2.5 bg-gradient-to-r from-red-600 to-red-500 hover:from-red-500 hover:to-red-400 text-white font-medium rounded-lg transition-all"
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

    <!-- Midtrans Snap Script -->
    @if($order->status == 'pending' && $order->payment->status == 'pending')
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
        <script>
            const payButton = document.getElementById('pay-button');

            payButton.addEventListener('click', async function() {
                try {
                    payButton.disabled = true;
                    payButton.innerHTML = '⏳ Loading...';

                    // Get snap token from server
                    const response = await fetch('{{ route('payment.snap-token', $order) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    const data = await response.json();

                    if (data.error) {
                        alert(data.error);
                        payButton.disabled = false;
                        payButton.innerHTML = '💳 Bayar Sekarang';
                        return;
                    }

                    // Open Midtrans Snap payment popup
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            window.location.href = '{{ route('orders.show', $order) }}';
                        },
                        onPending: function(result) {
                            window.location.href = '{{ route('orders.show', $order) }}';
                        },
                        onError: function(result) {
                            alert('Payment failed. Please try again.');
                            payButton.disabled = false;
                            payButton.innerHTML = '💳 Bayar Sekarang';
                        },
                        onClose: function() {
                            payButton.disabled = false;
                            payButton.innerHTML = '💳 Bayar Sekarang';
                        }
                    });

                } catch (error) {
                    console.error('Error:', error);
                    alert('Failed to load payment. Please try again.');
                    payButton.disabled = false;
                    payButton.innerHTML = '💳 Bayar Sekarang';
                }
            });
        </script>
    @endif
</x-app-layout>
