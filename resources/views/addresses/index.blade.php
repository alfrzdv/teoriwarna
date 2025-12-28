<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Alamat Saya') }}
            </h2>
            <a href="{{ route('addresses.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                + Tambah Alamat
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse ($addresses as $address)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold">{{ $address->label }}</h3>
                                    @if($address->is_primary)
                                        <span class="inline-block px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">
                                            Alamat Utama
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="space-y-2 text-sm mb-4">
                                <p class="font-semibold">{{ $address->recipient_name }}</p>
                                <p>{{ $address->phone }}</p>
                                <p class="text-gray-600">{{ $address->address }}</p>
                                <p class="text-gray-600">{{ $address->city }}, {{ $address->province }}</p>
                                <p class="text-gray-600">{{ $address->postal_code }}</p>
                            </div>

                            <div class="flex gap-2">
                                <a href="{{ route('addresses.edit', $address) }}"
                                   class="flex-1 text-center px-3 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                                    Edit
                                </a>

                                @if(!$address->is_primary)
                                    <form action="{{ route('addresses.set-primary', $address) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full px-3 py-2 bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                                            Jadikan Utama
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('addresses.destroy', $address) }}" method="POST"
                                      onsubmit="return confirm('Yakin ingin hapus alamat ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-2 bg-red-100 text-red-700 rounded hover:bg-red-200">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-center text-gray-500">
                            <p class="mb-4">Belum ada alamat tersimpan</p>
                            <a href="{{ route('addresses.create') }}" class="inline-block px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Tambah Alamat Pertama
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
