<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pengaturan Toko
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Informasi Dasar</h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Toko *</label>
                                    <input type="text" name="store_name" value="{{ old('store_name', $settings->store_name) }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    @error('store_name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Logo Toko</label>
                                    @if($settings->logo)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $settings->logo) }}" alt="Logo" class="h-20 w-auto">
                                        </div>
                                    @endif
                                    <input type="file" name="logo" accept="image/*"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Max: 2MB</p>
                                    @error('logo')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Toko</label>
                                    <textarea name="description" rows="3"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $settings->description) }}</textarea>
                                    @error('description')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Informasi Kontak</h3>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                    <input type="email" name="email" value="{{ old('email', $settings->email) }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    @error('email')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Telepon *</label>
                                    <input type="text" name="phone" value="{{ old('phone', $settings->phone) }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    @error('phone')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">WhatsApp</label>
                                    <input type="text" name="whatsapp" value="{{ old('whatsapp', $settings->whatsapp) }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="08xxxxxxxxxx">
                                    @error('whatsapp')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Jam Operasional</label>
                                    <input type="text" name="business_hours" value="{{ old('business_hours', $settings->business_hours) }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="Senin - Jumat, 09:00 - 17:00">
                                    @error('business_hours')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat *</label>
                                <textarea name="address" rows="2"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('address', $settings->address) }}</textarea>
                                @error('address')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Social Media -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Media Sosial</h3>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Instagram</label>
                                    <input type="text" name="instagram" value="{{ old('instagram', $settings->instagram) }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="@username atau URL">
                                    @error('instagram')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Facebook</label>
                                    <input type="text" name="facebook" value="{{ old('facebook', $settings->facebook) }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="username atau URL">
                                    @error('facebook')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Bank Information -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Informasi Bank</h3>

                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Bank</label>
                                    <input type="text" name="bank_name" value="{{ old('bank_name', $settings->bank_name) }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="BCA, Mandiri, BNI, dll">
                                    @error('bank_name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Rekening</label>
                                    <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $settings->bank_account_number) }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('bank_account_number')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pemilik Rekening</label>
                                    <input type="text" name="bank_account_name" value="{{ old('bank_account_name', $settings->bank_account_name) }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('bank_account_name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- SEO Settings -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Pengaturan SEO</h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                                    <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $settings->meta_keywords) }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="teori warna, art supplies, alat lukis">
                                    @error('meta_keywords')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                                    <textarea name="meta_description" rows="2"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="Deskripsi singkat untuk SEO">{{ old('meta_description', $settings->meta_description) }}</textarea>
                                    @error('meta_description')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Payment Gateway Settings -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Pengaturan Payment Gateway</h3>

                            <div class="space-y-4">
                                <!-- Midtrans -->
                                <div class="border rounded-lg p-4">
                                    <div class="flex items-center mb-3">
                                        <input type="checkbox" name="midtrans_enabled" id="midtrans_enabled"
                                            {{ old('midtrans_enabled', $settings->midtrans_enabled ?? false) ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                                        <label for="midtrans_enabled" class="ml-2 font-semibold text-gray-700">Aktifkan Midtrans (E-Wallet)</label>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Server Key</label>
                                            <input type="text" name="midtrans_server_key" value="{{ old('midtrans_server_key', $settings->midtrans_server_key) }}"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                placeholder="SB-Mid-server-xxxxx">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Client Key</label>
                                            <input type="text" name="midtrans_client_key" value="{{ old('midtrans_client_key', $settings->midtrans_client_key) }}"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                placeholder="SB-Mid-client-xxxxx">
                                        </div>
                                    </div>

                                    <div class="mt-3 flex items-center">
                                        <input type="checkbox" name="midtrans_is_production" id="midtrans_is_production"
                                            {{ old('midtrans_is_production', $settings->midtrans_is_production ?? false) ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                                        <label for="midtrans_is_production" class="ml-2 text-sm text-gray-700">Mode Production (uncheck untuk Sandbox)</label>
                                    </div>
                                </div>

                                <!-- Payment Methods -->
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="border rounded-lg p-4">
                                        <div class="flex items-center">
                                            <input type="checkbox" name="bank_transfer_enabled" id="bank_transfer_enabled"
                                                {{ old('bank_transfer_enabled', $settings->bank_transfer_enabled ?? true) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                                            <label for="bank_transfer_enabled" class="ml-2 font-medium text-gray-700">Bank Transfer</label>
                                        </div>
                                    </div>

                                    <div class="border rounded-lg p-4">
                                        <div class="flex items-center">
                                            <input type="checkbox" name="e_wallet_enabled" id="e_wallet_enabled"
                                                {{ old('e_wallet_enabled', $settings->e_wallet_enabled ?? false) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                                            <label for="e_wallet_enabled" class="ml-2 font-medium text-gray-700">E-Wallet</label>
                                        </div>
                                    </div>

                                    <div class="border rounded-lg p-4">
                                        <div class="flex items-center">
                                            <input type="checkbox" name="cod_enabled" id="cod_enabled"
                                                {{ old('cod_enabled', $settings->cod_enabled ?? true) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                                            <label for="cod_enabled" class="ml-2 font-medium text-gray-700">COD (Bayar di Tempat)</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Settings -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Pengaturan Pengiriman</h3>

                            <div class="space-y-4">
                                <!-- Regular Shipping -->
                                <div class="border rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-700 mb-3">Regular Shipping</h4>
                                    <div class="grid grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                                            <input type="text" name="shipping_regular_name" value="{{ old('shipping_regular_name', $settings->shipping_regular_name ?? 'Regular') }}"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Biaya (Rp)</label>
                                            <input type="number" name="shipping_regular_cost" value="{{ old('shipping_regular_cost', $settings->shipping_regular_cost ?? 15000) }}"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" min="0">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Estimasi</label>
                                            <input type="text" name="shipping_regular_estimation" value="{{ old('shipping_regular_estimation', $settings->shipping_regular_estimation ?? '3-5 hari') }}"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                    </div>
                                </div>

                                <!-- Express Shipping -->
                                <div class="border rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-700 mb-3">Express Shipping</h4>
                                    <div class="grid grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                                            <input type="text" name="shipping_express_name" value="{{ old('shipping_express_name', $settings->shipping_express_name ?? 'Express') }}"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Biaya (Rp)</label>
                                            <input type="number" name="shipping_express_cost" value="{{ old('shipping_express_cost', $settings->shipping_express_cost ?? 30000) }}"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" min="0">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Estimasi</label>
                                            <input type="text" name="shipping_express_estimation" value="{{ old('shipping_express_estimation', $settings->shipping_express_estimation ?? '1-2 hari') }}"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                    </div>
                                </div>

                                <!-- Same Day Shipping -->
                                <div class="border rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-700 mb-3">Same Day Shipping</h4>
                                    <div class="grid grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                                            <input type="text" name="shipping_sameday_name" value="{{ old('shipping_sameday_name', $settings->shipping_sameday_name ?? 'Same Day') }}"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Biaya (Rp)</label>
                                            <input type="number" name="shipping_sameday_cost" value="{{ old('shipping_sameday_cost', $settings->shipping_sameday_cost ?? 50000) }}"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" min="0">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Estimasi</label>
                                            <input type="text" name="shipping_sameday_estimation" value="{{ old('shipping_sameday_estimation', $settings->shipping_sameday_estimation ?? '1 hari') }}"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                    </div>
                                </div>

                                <!-- Free Shipping -->
                                <div class="border rounded-lg p-4 bg-green-50">
                                    <div class="flex items-center mb-3">
                                        <input type="checkbox" name="free_shipping_enabled" id="free_shipping_enabled"
                                            {{ old('free_shipping_enabled', $settings->free_shipping_enabled ?? false) ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200">
                                        <label for="free_shipping_enabled" class="ml-2 font-semibold text-gray-700">Aktifkan Gratis Ongkir</label>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Pembelian (Rp)</label>
                                        <input type="number" name="free_shipping_minimum" value="{{ old('free_shipping_minimum', $settings->free_shipping_minimum ?? 500000) }}"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" min="0">
                                        <p class="text-xs text-gray-500 mt-1">Customer akan dapat gratis ongkir jika total belanja mencapai nominal ini</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Simpan Pengaturan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
