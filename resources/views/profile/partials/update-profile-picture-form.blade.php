<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Picture') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your profile picture.") }}
        </p>
    </header>

    <div class="mt-6 space-y-6">
        <!-- Current Profile Picture -->
        <div class="flex items-center space-x-6">
            <div class="shrink-0">
                @if($user->profile_picture)
                    <img src="{{ $user->getProfilePictureUrl() }}" alt="{{ $user->name }}" class="h-24 w-24 rounded-full object-cover border-2 border-gray-300">
                @else
                    <div class="h-24 w-24 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-3xl font-semibold border-2 border-gray-300">
                        {{ $user->getInitials() }}
                    </div>
                @endif
            </div>

            <div class="flex-1">
                <form id="profilePictureForm" method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('patch')

                    <x-image-cropper
                        inputName="profile_picture"
                        :aspectRatio="1"
                        :previewWidth="150"
                        :previewHeight="150"
                        label="Choose New Picture"
                        :currentImage="$user->profile_picture ? $user->getProfilePictureUrl() : null"
                    />
                    <p class="mt-1 text-sm text-gray-500">PNG, JPG, GIF up to 2MB. You can crop and rotate before uploading.</p>
                    <x-input-error class="mt-2" :messages="$errors->get('profile_picture')" />

                    <div class="flex items-center gap-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Upload') }}
                        </button>

                        @if (session('status') === 'profile-updated')
                            <p
                                x-data="{ show: true }"
                                x-show="show"
                                x-transition
                                x-init="setTimeout(() => show = false, 2000)"
                                class="text-sm text-gray-600"
                            >{{ __('Saved.') }}</p>
                        @endif
                    </div>
                </form>

                @if($user->profile_picture)
                    <form method="post" action="{{ route('profile.picture.delete') }}" class="mt-4">
                        @csrf
                        @method('delete')

                        <x-danger-button type="submit" onclick="return confirm('Are you sure you want to delete your profile picture?')">
                            {{ __('Remove Picture') }}
                        </x-danger-button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Profile form: DOM loaded');

        const form = document.getElementById('profilePictureForm');
        if (form) {
            console.log('Profile form found');
            form.addEventListener('submit', function(e) {
                console.log('Profile form submit triggered');

                // Debug: Check form data
                const formData = new FormData(form);
                console.log('Form data:');
                for (let [key, value] of formData.entries()) {
                    if (key === 'profile_picture') {
                        console.log(key + ':', value ? value.substring(0, 100) + '...' : 'EMPTY');
                    } else {
                        console.log(key + ':', value);
                    }
                }

                const profilePictureInput = form.querySelector('input[name="profile_picture"]');
                console.log('Profile picture input found:', !!profilePictureInput);
                console.log('Profile picture value length:', profilePictureInput ? profilePictureInput.value.length : 0);

                return true; // Allow submit
            });
        } else {
            console.error('Profile form NOT found!');
        }
    });
    </script>
</section>
