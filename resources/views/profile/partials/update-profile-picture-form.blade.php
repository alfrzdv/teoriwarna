<section>
    <header>
        <h2 class="text-lg font-semibold text-white font-heading">
            {{ __('Foto Profil') }}
        </h2>

        <p class="mt-1 text-sm text-dark-400">
            {{ __("Perbarui foto profil Anda.") }}
        </p>
    </header>

    <div class="mt-6">
        <div class="flex flex-col sm:flex-row items-start gap-6">
            <!-- Current Profile Picture -->
            <div class="shrink-0">
                @if($user->profile_picture)
                    <img id="profilePreview" src="{{ $user->getProfilePictureUrl() }}" alt="{{ $user->name }}"
                        class="h-32 w-32 rounded-full object-cover border-4 border-brand-500/30 shadow-xl">
                @else
                    <div id="profilePreview" class="h-32 w-32 rounded-full bg-gradient-to-br from-brand-500 to-purple-600 flex items-center justify-center text-white text-4xl font-bold border-4 border-brand-500/30 shadow-xl">
                        {{ $user->getInitials() }}
                    </div>
                @endif
            </div>

            <!-- Upload Form -->
            <div class="flex-1 space-y-4">
                <form id="profilePictureForm" method="post" action="{{ route('profile.picture.update') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Pilih Foto Baru</label>
                        <div class="flex items-center gap-3">
                            <input type="file"
                                   id="profilePictureInput"
                                   name="profile_picture"
                                   accept="image/jpeg,image/png,image/jpg,image/gif"
                                   class="hidden"
                                   onchange="previewProfilePicture(event)">
                            <button type="button"
                                    onclick="document.getElementById('profilePictureInput').click()"
                                    class="px-4 py-2 bg-gradient-to-r from-brand-600 to-purple-600 hover:from-brand-500 hover:to-purple-500 text-white text-sm font-medium rounded-lg shadow-glow-sm hover:shadow-glow transition-all">
                                Pilih Gambar
                            </button>
                            <span id="fileName" class="text-sm text-dark-400">Belum ada file dipilih</span>
                        </div>
                        <p class="mt-2 text-xs text-dark-500">
                            PNG, JPG, atau GIF maksimal 2MB
                        </p>
                        <x-input-error class="mt-2" :messages="$errors->get('profile_picture')" />
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-brand-600 to-purple-600 hover:from-brand-500 hover:to-purple-500 text-white text-sm font-medium rounded-lg shadow-glow-sm hover:shadow-glow transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            {{ __('Unggah Foto') }}
                        </button>

                        @if (session('status') === 'profile-picture-updated')
                            <p
                                x-data="{ show: true }"
                                x-show="show"
                                x-transition
                                x-init="setTimeout(() => show = false, 3000)"
                                class="text-sm font-medium text-green-400 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ __('Foto profil berhasil diperbarui!') }}
                            </p>
                        @endif
                    </div>
                </form>

                @if($user->profile_picture)
                    <form method="post" action="{{ route('profile.picture.delete') }}">
                        @csrf
                        @method('delete')

                        <button type="submit"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus foto profil Anda?')"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-dark-700 hover:bg-red-600/20 text-red-400 border border-red-500/30 hover:border-red-500/50 text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            {{ __('Hapus Foto') }}
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <script>
        function previewProfilePicture(event) {
            const file = event.target.files[0];
            const fileNameSpan = document.getElementById('fileName');
            const preview = document.getElementById('profilePreview');

            if (file) {
                fileNameSpan.textContent = file.name;

                const reader = new FileReader();
                reader.onload = function(e) {
                    // Replace preview with image
                    preview.outerHTML = `<img id="profilePreview" src="${e.target.result}" alt="Preview" class="h-32 w-32 rounded-full object-cover border-4 border-brand-500/30 shadow-xl">`;
                }
                reader.readAsDataURL(file);
            } else {
                fileNameSpan.textContent = 'Belum ada file dipilih';
            }
        }
    </script>
</section>
