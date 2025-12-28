<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Pesanan') }} #{{ $order->order_number }}
            </h2>
            <a href="{{ route('admin.orders.index') }}" class="text-indigo-600 hover:text-indigo-900">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Messages -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Order Items -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Produk yang Dipesan</h3>
                            <div class="space-y-4">
                                @foreach ($order->order_items as $item)
                                    <div class="flex gap-4 border-b pb-4">
                                        @if($item->product->product_images->first())
                                            <img src="{{ asset('storage/' . $item->product->product_images->first()->image_path) }}"
                                                 alt="{{ $item->product->name }}"
                                                 class="w-20 h-20 object-cover rounded">
                                        @endif
                                        <div class="flex-1">
                                            <h4 class="font-semibold">{{ $item->product->name }}</h4>
                                            <p class="text-sm text-gray-600">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold">Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Total -->
                            <div class="mt-6 space-y-2">
                                <div class="flex justify-between">
                                    <span>Subtotal:</span>
                                    <span>Rp {{ number_format($order->total_amount - $order->shipping_cost, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Ongkir ({{ ucfirst($order->shipping_method ?? 'regular') }}):</span>
                                    <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-lg font-bold border-t pt-2">
                                    <span>Total:</span>
                                    <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Info -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Informasi Pengiriman</h3>
                            <div class="space-y-2">
                                <div><span class="font-semibold">Nama:</span> {{ $order->shipping_name }}</div>
                                <div><span class="font-semibold">Telepon:</span> {{ $order->shipping_phone }}</div>
                                <div><span class="font-semibold">Alamat:</span> {{ $order->shipping_address }}</div>
                                <div><span class="font-semibold">Kota:</span> {{ $order->shipping_city }}</div>
                                <div><span class="font-semibold">Kode Pos:</span> {{ $order->shipping_postal_code }}</div>
                                @if($order->notes)
                                    <div><span class="font-semibold">Catatan:</span> {{ $order->notes }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Payment Info -->
                    @if($order->payment)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold mb-4">Informasi Pembayaran</h3>
                                <div class="space-y-2">
                                    <div><span class="font-semibold">Metode:</span> {{ strtoupper($order->payment->payment_method) }}</div>
                                    <div><span class="font-semibold">Status:</span>
                                        <span class="px-2 py-1 text-xs rounded
                                            {{ $order->payment->status == 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $order->payment->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $order->payment->status == 'pending_verification' ? 'bg-orange-100 text-orange-800' : '' }}">
                                            {{ ucfirst($order->payment->status) }}
                                        </span>
                                    </div>

                                    @if($order->payment->proof_of_payment)
                                        <div>
                                            <span class="font-semibold">Bukti Pembayaran:</span>
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $order->payment->proof_of_payment) }}"
                                                     alt="Payment Proof"
                                                     class="max-w-md border rounded">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Order Status -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Status Pesanan</h3>

                            <!-- Update Status -->
                            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="mb-4">
                                @csrf
                                <label class="block text-sm font-medium mb-2">Ubah Status:</label>
                                <select name="status" class="w-full rounded-md border-gray-300 mb-2">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                    Update Status
                                </button>
                            </form>

                            <div class="text-sm text-gray-600">
                                <p>Status saat ini: <span class="font-semibold">{{ ucfirst($order->status) }}</span></p>
                                <p>Dibuat: {{ $order->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Verify Payment -->
                    @if($order->payment && $order->payment->status == 'pending_verification')
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold mb-4">Verifikasi Pembayaran</h3>
                                <form action="{{ route('admin.orders.verify-payment', $order) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                        Verifikasi Pembayaran
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- Add Tracking -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Nomor Resi</h3>
                            <form action="{{ route('admin.orders.add-tracking', $order) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-sm font-medium mb-2">Kurir:</label>
                                    <input type="text" name="shipping_courier"
                                           value="{{ $order->shipping_courier }}"
                                           placeholder="JNE, J&T, SiCepat, dll"
                                           class="w-full rounded-md border-gray-300">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Nomor Resi:</label>
                                    <input type="text" name="tracking_number"
                                           value="{{ $order->tracking_number }}"
                                           placeholder="Masukkan nomor resi"
                                           class="w-full rounded-md border-gray-300">
                                </div>
                                <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                    Simpan Resi
                                </button>
                            </form>

                            @if($order->tracking_number)
                                <div class="mt-4 p-3 bg-blue-50 rounded">
                                    <p class="text-sm"><span class="font-semibold">Kurir:</span> {{ $order->shipping_courier }}</p>
                                    <p class="text-sm"><span class="font-semibold">Resi:</span> {{ $order->tracking_number }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Customer Info -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Informasi Customer</h3>
                            <div class="space-y-2 text-sm">
                                <div><span class="font-semibold">Nama:</span> {{ $order->user->name }}</div>
                                <div><span class="font-semibold">Email:</span> {{ $order->user->email }}</div>
                                @if($order->user->phone)
                                    <div><span class="font-semibold">Telepon:</span> {{ $order->user->phone }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
