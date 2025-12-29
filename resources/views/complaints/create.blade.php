<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Buat Komplain
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('complaints.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="order_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Order</label>
                            <select name="order_id" id="order_id" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('order_id') border-red-500 @enderror">
                                <option value="">-- Pilih Order --</option>
                                @foreach($orders as $order)
                                    <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                                        {{ $order->order_number }} - Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                        ({{ $order->created_at->format('d M Y') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('order_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Alasan Komplain</label>
                            <select name="reason" id="reason" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('reason') border-red-500 @enderror">
                                <option value="">-- Pilih Alasan --</option>
                                <option value="product_defect" {{ old('reason') == 'product_defect' ? 'selected' : '' }}>Produk Cacat/Rusak</option>
                                <option value="wrong_item" {{ old('reason') == 'wrong_item' ? 'selected' : '' }}>Barang Tidak Sesuai Pesanan</option>
                                <option value="late_delivery" {{ old('reason') == 'late_delivery' ? 'selected' : '' }}>Pengiriman Terlambat</option>
                                <option value="missing_item" {{ old('reason') == 'missing_item' ? 'selected' : '' }}>Barang Kurang/Hilang</option>
                                <option value="other" {{ old('reason') == 'other' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('reason')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Komplain</label>
                            <textarea name="description" id="description" rows="5" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                                placeholder="Jelaskan detail masalah Anda (minimal 20 karakter)">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Minimal 20 karakter, maksimal 1000 karakter</p>
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('complaints.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Batal
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Kirim Komplain
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
