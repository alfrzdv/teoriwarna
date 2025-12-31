<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white leading-tight font-heading">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-dark-800/50 backdrop-blur-sm border border-dark-700/50 shadow-glow-sm rounded-xl">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-picture-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-dark-800/50 backdrop-blur-sm border border-dark-700/50 shadow-glow-sm rounded-xl">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-dark-800/50 backdrop-blur-sm border border-dark-700/50 shadow-glow-sm rounded-xl">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-dark-800/50 backdrop-blur-sm border border-dark-700/50 shadow-glow-sm rounded-xl">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
