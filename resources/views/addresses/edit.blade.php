<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Alamat') }}
            </h2>
            <a href="{{ route('addresses.index') }}" class="text-indigo-600 hover:text-indigo-900">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('addresses.update', $address) }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-4">
                            <label for="label" class="block text-sm font-medium text-gray-700 mb-2">Label Alamat *</label>
                            <input type="text" name="label" id="label" value="{{ old('label', $address->label) }}"
                                   class="w-full rounded-md border-gray-300 @error('label') border-red-500 @enderror">
                            @error('label')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="recipient_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Penerima *</label>
                            <input type="text" name="recipient_name" id="recipient_name" value="{{ old('recipient_name', $address->recipient_name) }}"
                                   class="w-full rounded-md border-gray-300 @error('recipient_name') border-red-500 @enderror">
                            @error('recipient_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon *</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $address->phone) }}"
                                   class="w-full rounded-md border-gray-300 @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap *</label>
                            <textarea name="address" id="address" rows="3"
                                      class="w-full rounded-md border-gray-300 @error('address') border-red-500 @enderror">{{ old('address', $address->address) }}</textarea>
                            @error('address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-2">Kota *</label>
                                <input type="text" name="city" id="city" value="{{ old('city', $address->city) }}"
                                       class="w-full rounded-md border-gray-300 @error('city') border-red-500 @enderror">
                                @error('city')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="province" class="block text-sm font-medium text-gray-700 mb-2">Provinsi *</label>
                                <input type="text" name="province" id="province" value="{{ old('province', $address->province) }}"
                                       class="w-full rounded-md border-gray-300 @error('province') border-red-500 @enderror">
                                @error('province')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">Kode Pos *</label>
                            <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $address->postal_code) }}"
                                   class="w-full rounded-md border-gray-300 @error('postal_code') border-red-500 @enderror">
                            @error('postal_code')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_primary" value="1" {{ old('is_primary', $address->is_primary) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Jadikan alamat utama</span>
                            </label>
                        </div>

                        <div class="flex gap-4">
                            <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Simpan Perubahan
                            </button>
                            <a href="{{ route('addresses.index') }}"
                               class="flex-1 text-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
