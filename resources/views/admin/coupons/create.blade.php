<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Buat Kupon Baru</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.coupons.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kode Kupon *</label>
                                <input type="text" name="code" value="{{ old('code') }}" required
                                    class="w-full border-gray-300 rounded-md shadow-sm @error('code') border-red-500 @enderror"
                                    placeholder="PROMO2025">
                                @error('code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Diskon *</label>
                                <select name="type" required class="w-full border-gray-300 rounded-md shadow-sm" onchange="toggleDiscountType(this)">
                                    <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                                    <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed Amount (Rp)</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Diskon *</label>
                                <input type="number" name="value" value="{{ old('value') }}" required min="0" step="0.01"
                                    class="w-full border-gray-300 rounded-md shadow-sm @error('value') border-red-500 @enderror">
                                @error('value')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                <p class="text-xs text-gray-500 mt-1" id="valueHint">Maksimal 100 untuk persentase</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Max Diskon (opsional)</label>
                                <input type="number" name="max_discount" value="{{ old('max_discount') }}" min="0" step="1000"
                                    class="w-full border-gray-300 rounded-md shadow-sm" id="maxDiscount">
                                <p class="text-xs text-gray-500 mt-1">Untuk tipe persentase</p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Minimal Pembelian (opsional)</label>
                            <input type="number" name="min_purchase" value="{{ old('min_purchase') }}" min="0" step="1000"
                                class="w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Limit Total Penggunaan</label>
                                <input type="number" name="usage_limit" value="{{ old('usage_limit') }}" min="1"
                                    class="w-full border-gray-300 rounded-md shadow-sm">
                                <p class="text-xs text-gray-500 mt-1">Kosongkan untuk unlimited</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Limit Per User *</label>
                                <input type="number" name="usage_per_user" value="{{ old('usage_per_user', 1) }}" min="1" required
                                    class="w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                                <input type="date" name="start_date" value="{{ old('start_date') }}"
                                    class="w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Berakhir</label>
                                <input type="date" name="end_date" value="{{ old('end_date') }}"
                                    class="w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                            <textarea name="description" rows="3" class="w-full border-gray-300 rounded-md shadow-sm"
                                placeholder="Deskripsi kupon untuk user...">{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                    class="rounded border-gray-300">
                                <span class="ml-2 text-sm text-gray-700">Aktifkan kupon</span>
                            </label>
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.coupons.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Batal
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan Kupon
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleDiscountType(select) {
            const maxDiscount = document.getElementById('maxDiscount');
            const valueHint = document.getElementById('valueHint');
            if (select.value === 'percentage') {
                maxDiscount.disabled = false;
                valueHint.textContent = 'Maksimal 100 untuk persentase';
            } else {
                maxDiscount.disabled = true;
                maxDiscount.value = '';
                valueHint.textContent = 'Nilai fixed dalam Rupiah';
            }
        }
    </script>
</x-app-layout>
