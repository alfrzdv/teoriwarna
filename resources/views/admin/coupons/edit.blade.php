<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Kupon: {{ $coupon->code }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kode Kupon *</label>
                                <input type="text" name="code" value="{{ old('code', $coupon->code) }}" required
                                    class="w-full border-gray-300 rounded-md shadow-sm @error('code') border-red-500 @enderror">
                                @error('code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Diskon *</label>
                                <select name="type" required class="w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="percentage" {{ old('type', $coupon->type) == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                                    <option value="fixed" {{ old('type', $coupon->type) == 'fixed' ? 'selected' : '' }}>Fixed Amount (Rp)</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Diskon *</label>
                                <input type="number" name="value" value="{{ old('value', $coupon->value) }}" required min="0" step="0.01"
                                    class="w-full border-gray-300 rounded-md shadow-sm @error('value') border-red-500 @enderror">
                                @error('value')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Max Diskon</label>
                                <input type="number" name="max_discount" value="{{ old('max_discount', $coupon->max_discount) }}" min="0" step="1000"
                                    class="w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Minimal Pembelian</label>
                            <input type="number" name="min_purchase" value="{{ old('min_purchase', $coupon->min_purchase) }}" min="0" step="1000"
                                class="w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Limit Total</label>
                                <input type="number" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}" min="1"
                                    class="w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Limit Per User *</label>
                                <input type="number" name="usage_per_user" value="{{ old('usage_per_user', $coupon->usage_per_user) }}" min="1" required
                                    class="w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                                <input type="date" name="start_date" value="{{ old('start_date', $coupon->start_date?->format('Y-m-d')) }}"
                                    class="w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Berakhir</label>
                                <input type="date" name="end_date" value="{{ old('end_date', $coupon->end_date?->format('Y-m-d')) }}"
                                    class="w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                            <textarea name="description" rows="3" class="w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $coupon->description) }}</textarea>
                        </div>

                        <div class="mb-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}
                                    class="rounded border-gray-300">
                                <span class="ml-2 text-sm text-gray-700">Aktifkan kupon</span>
                            </label>
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.coupons.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Batal
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Kupon
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
