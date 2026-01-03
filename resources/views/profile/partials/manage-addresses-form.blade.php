<section x-data="{ showAddForm: false, editingId: null, showEditForm: false }">
    <header>
        <h2 class="text-lg font-semibold text-white font-heading">
            {{ __('Alamat Pengiriman') }}
        </h2>

        <p class="mt-1 text-sm text-dark-400">
            {{ __("Kelola alamat pengiriman Anda untuk proses checkout yang lebih cepat.") }}
        </p>
    </header>

    <div class="mt-6 space-y-4">
        <!-- Add Address Button -->
        <button @click="showAddForm = !showAddForm" type="button"
            class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-brand-600 to-purple-600 hover:from-brand-500 hover:to-purple-500 text-white text-sm font-medium rounded-lg shadow-glow-sm hover:shadow-glow transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span x-text="showAddForm ? 'Batal' : 'Tambah Alamat Baru'"></span>
        </button>

        <!-- Add Address Form -->
        <div x-show="showAddForm" x-collapse class="bg-dark-700/30 border border-dark-600/50 rounded-lg p-6">
            <form method="POST" action="{{ route('addresses.store') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Nama Penerima</label>
                        <input type="text" name="recipient_name" required
                            class="w-full bg-dark-900 border border-dark-700 text-white placeholder-dark-500 focus:border-brand-500 focus:ring-brand-500 rounded-lg py-2.5 px-4 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Nomor Telepon</label>
                        <input type="text" name="phone" required
                            class="w-full bg-dark-900 border border-dark-700 text-white placeholder-dark-500 focus:border-brand-500 focus:ring-brand-500 rounded-lg py-2.5 px-4 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Alamat Lengkap</label>
                    <textarea name="address" rows="3" required
                        class="w-full bg-dark-900 border border-dark-700 text-white placeholder-dark-500 focus:border-brand-500 focus:ring-brand-500 rounded-lg py-2.5 px-4 text-sm"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Kota</label>
                        <input type="text" name="city" required
                            class="w-full bg-dark-900 border border-dark-700 text-white placeholder-dark-500 focus:border-brand-500 focus:ring-brand-500 rounded-lg py-2.5 px-4 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Provinsi</label>
                        <input type="text" name="province" required
                            class="w-full bg-dark-900 border border-dark-700 text-white placeholder-dark-500 focus:border-brand-500 focus:ring-brand-500 rounded-lg py-2.5 px-4 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Kode Pos</label>
                        <input type="text" name="postal_code" required
                            class="w-full bg-dark-900 border border-dark-700 text-white placeholder-dark-500 focus:border-brand-500 focus:ring-brand-500 rounded-lg py-2.5 px-4 text-sm">
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_default" id="is_default_new" value="1"
                        class="w-4 h-4 text-brand-600 bg-dark-900 border-dark-700 rounded focus:ring-brand-500 focus:ring-2">
                    <label for="is_default_new" class="ml-2 text-sm text-dark-300">Jadikan alamat utama</label>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-brand-600 to-purple-600 hover:from-brand-500 hover:to-purple-500 text-white text-sm font-medium rounded-lg shadow-glow-sm hover:shadow-glow transition-all">
                        Simpan Alamat
                    </button>
                    <button type="button" @click="showAddForm = false"
                        class="px-6 py-2.5 bg-dark-700 hover:bg-dark-600 text-white text-sm font-medium rounded-lg transition-colors">
                        Batal
                    </button>
                </div>
            </form>
        </div>

        <!-- Address List -->
        @if($addresses->count() > 0)
            <div class="space-y-3">
                @foreach($addresses as $address)
                    <div class="bg-dark-700/30 border border-dark-600/50 rounded-lg p-6" x-data="{ editing: false }">
                        <!-- View Mode -->
                        <div x-show="!editing">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <h3 class="text-white font-semibold">{{ $address->recipient_name }}</h3>
                                        @if($address->is_default)
                                            <span class="px-2 py-1 text-xs font-semibold bg-brand-600/20 text-brand-400 border border-brand-600/30 rounded">
                                                Utama
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-dark-300 text-sm mb-1">{{ $address->phone }}</p>
                                    <p class="text-dark-400 text-sm">{{ $address->getFullAddress() }}</p>
                                </div>

                                <div class="flex gap-2">
                                    @if(!$address->is_default)
                                        <form method="POST" action="{{ route('addresses.set-default', $address) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="p-2 text-dark-400 hover:text-brand-400 hover:bg-dark-600/50 rounded-lg transition-all"
                                                title="Jadikan Alamat Utama">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                    <button @click="editing = true" type="button"
                                        class="p-2 text-dark-400 hover:text-blue-400 hover:bg-dark-600/50 rounded-lg transition-all"
                                        title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    <form method="POST" action="{{ route('addresses.destroy', $address) }}" class="inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus alamat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 text-dark-400 hover:text-red-400 hover:bg-dark-600/50 rounded-lg transition-all"
                                            title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Mode -->
                        <div x-show="editing" x-collapse>
                            <form method="POST" action="{{ route('addresses.update', $address) }}" class="space-y-4">
                                @csrf
                                @method('PUT')

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-dark-300 mb-2">Nama Penerima</label>
                                        <input type="text" name="recipient_name" value="{{ $address->recipient_name }}" required
                                            class="w-full bg-dark-900 border border-dark-700 text-white placeholder-dark-500 focus:border-brand-500 focus:ring-brand-500 rounded-lg py-2.5 px-4 text-sm">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-dark-300 mb-2">Nomor Telepon</label>
                                        <input type="text" name="phone" value="{{ $address->phone }}" required
                                            class="w-full bg-dark-900 border border-dark-700 text-white placeholder-dark-500 focus:border-brand-500 focus:ring-brand-500 rounded-lg py-2.5 px-4 text-sm">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-dark-300 mb-2">Alamat Lengkap</label>
                                    <textarea name="address" rows="3" required
                                        class="w-full bg-dark-900 border border-dark-700 text-white placeholder-dark-500 focus:border-brand-500 focus:ring-brand-500 rounded-lg py-2.5 px-4 text-sm">{{ $address->address }}</textarea>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-dark-300 mb-2">Kota</label>
                                        <input type="text" name="city" value="{{ $address->city }}" required
                                            class="w-full bg-dark-900 border border-dark-700 text-white placeholder-dark-500 focus:border-brand-500 focus:ring-brand-500 rounded-lg py-2.5 px-4 text-sm">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-dark-300 mb-2">Provinsi</label>
                                        <input type="text" name="province" value="{{ $address->province }}" required
                                            class="w-full bg-dark-900 border border-dark-700 text-white placeholder-dark-500 focus:border-brand-500 focus:ring-brand-500 rounded-lg py-2.5 px-4 text-sm">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-dark-300 mb-2">Kode Pos</label>
                                        <input type="text" name="postal_code" value="{{ $address->postal_code }}" required
                                            class="w-full bg-dark-900 border border-dark-700 text-white placeholder-dark-500 focus:border-brand-500 focus:ring-brand-500 rounded-lg py-2.5 px-4 text-sm">
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" name="is_default" id="is_default_{{ $address->id }}" value="1"
                                        {{ $address->is_default ? 'checked' : '' }}
                                        class="w-4 h-4 text-brand-600 bg-dark-900 border-dark-700 rounded focus:ring-brand-500 focus:ring-2">
                                    <label for="is_default_{{ $address->id }}" class="ml-2 text-sm text-dark-300">Jadikan alamat utama</label>
                                </div>

                                <div class="flex gap-3">
                                    <button type="submit"
                                        class="px-6 py-2.5 bg-gradient-to-r from-brand-600 to-purple-600 hover:from-brand-500 hover:to-purple-500 text-white text-sm font-medium rounded-lg shadow-glow-sm hover:shadow-glow transition-all">
                                        Simpan Perubahan
                                    </button>
                                    <button type="button" @click="editing = false"
                                        class="px-6 py-2.5 bg-dark-700 hover:bg-dark-600 text-white text-sm font-medium rounded-lg transition-colors">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 bg-dark-700/30 border border-dark-600/50 rounded-lg">
                <svg class="w-12 h-12 mx-auto text-dark-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <p class="text-dark-400 text-sm">Belum ada alamat tersimpan</p>
            </div>
        @endif

        @if (session('success'))
            <div x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 3000)"
                class="flex items-center gap-2 p-4 bg-green-600/20 border border-green-600/30 text-green-400 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif
    </div>
</section>
