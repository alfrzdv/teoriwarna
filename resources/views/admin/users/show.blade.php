<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail User') }}
            </h2>
            <a href="{{ route('admin.users.index') }}" class="text-indigo-600 hover:text-indigo-900">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- User Info -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-center mb-6">
                            @if($user->profile_picture)
                                <img src="{{ asset('storage/' . $user->profile_picture) }}"
                                     alt="{{ $user->name }}"
                                     class="w-32 h-32 rounded-full mx-auto mb-4">
                            @else
                                <div class="w-32 h-32 rounded-full bg-indigo-500 text-white flex items-center justify-center mx-auto mb-4 text-4xl">
                                    {{ $user->getInitials() }}
                                </div>
                            @endif
                            <h3 class="text-xl font-bold">{{ $user->name }}</h3>
                            <p class="text-gray-600">{{ $user->email }}</p>
                        </div>

                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="font-semibold">Role:</span>
                                <span class="px-2 py-1 text-xs rounded {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold">Status:</span>
                                <span class="px-2 py-1 text-xs rounded {{ $user->is_banned ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $user->is_banned ? 'Banned' : 'Active' }}
                                </span>
                            </div>
                            @if($user->phone)
                                <div class="flex justify-between">
                                    <span class="font-semibold">Phone:</span>
                                    <span>{{ $user->phone }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="font-semibold">Bergabung:</span>
                                <span>{{ $user->created_at->format('d M Y') }}</span>
                            </div>
                        </div>

                        <div class="mt-6 space-y-2">
                            <a href="{{ route('admin.users.edit', $user) }}"
                               class="block w-full text-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Edit User
                            </a>

                            @if($user->role != 'admin')
                                @if($user->is_banned)
                                    <form action="{{ route('admin.users.unban', $user) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                            Unban User
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.users.ban', $user) }}" method="POST"
                                          onsubmit="return confirm('Yakin ingin ban user ini?')">
                                        @csrf
                                        <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                            Ban User
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Orders & Addresses -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Recent Orders -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Riwayat Pesanan</h3>
                            @if($user->orders->count() > 0)
                                <div class="space-y-3">
                                    @foreach($user->orders->take(5) as $order)
                                        <div class="border-b pb-3">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <p class="font-semibold">{{ $order->order_number }}</p>
                                                    <p class="text-sm text-gray-600">{{ $order->created_at->format('d M Y H:i') }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="font-semibold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                                    <span class="px-2 py-1 text-xs rounded {{ $order->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @if($user->orders->count() > 5)
                                    <p class="text-sm text-gray-500 mt-3">Dan {{ $user->orders->count() - 5 }} pesanan lainnya...</p>
                                @endif
                            @else
                                <p class="text-gray-500">Belum ada pesanan</p>
                            @endif
                        </div>
                    </div>

                    <!-- Addresses -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Alamat Tersimpan</h3>
                            @if($user->user_addresses->count() > 0)
                                <div class="space-y-3">
                                    @foreach($user->user_addresses as $address)
                                        <div class="border p-3 rounded">
                                            <div class="flex justify-between items-start mb-2">
                                                <span class="font-semibold">{{ $address->label }}</span>
                                                @if($address->is_primary)
                                                    <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">Primary</span>
                                                @endif
                                            </div>
                                            <p class="text-sm">{{ $address->recipient_name }}</p>
                                            <p class="text-sm">{{ $address->phone }}</p>
                                            <p class="text-sm text-gray-600">{{ $address->address }}, {{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500">Belum ada alamat tersimpan</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
