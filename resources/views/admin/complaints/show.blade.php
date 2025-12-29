<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Komplain #{{ $complaint->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-3 gap-6">
                <div class="col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Informasi Komplain</h3>
                                    <p class="text-sm text-gray-500">Dibuat: {{ $complaint->created_at->format('d M Y H:i') }}</p>
                                </div>
                                <span class="px-3 py-1 text-sm font-semibold rounded-full
                                    @if($complaint->status === 'open') bg-yellow-100 text-yellow-800
                                    @elseif($complaint->status === 'in_review') bg-blue-100 text-blue-800
                                    @elseif($complaint->status === 'resolved') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($complaint->status) }}
                                </span>
                            </div>

                            <div class="border-t pt-4">
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">User</p>
                                        <p class="text-gray-900">{{ $complaint->user->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $complaint->user->email }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Order</p>
                                        <p class="text-gray-900">{{ $complaint->order->order_number }}</p>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <p class="text-sm font-medium text-gray-500 mb-1">Alasan</p>
                                    <p class="text-gray-900">{{ ucfirst(str_replace('_', ' ', $complaint->reason)) }}</p>
                                </div>

                                <div class="mb-4">
                                    <p class="text-sm font-medium text-gray-500 mb-2">Deskripsi</p>
                                    <p class="text-gray-900 whitespace-pre-wrap bg-gray-50 p-4 rounded">{{ $complaint->description }}</p>
                                </div>

                                @if($complaint->hasReply())
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                        <h4 class="font-semibold text-gray-900 mb-2">Balasan Anda</h4>
                                        <p class="text-gray-700 whitespace-pre-wrap">{{ $complaint->admin_reply }}</p>
                                        <p class="text-xs text-gray-500 mt-2">Dibalas oleh: {{ $complaint->admin->name ?? 'Admin' }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Order</h3>
                            <div class="space-y-3">
                                @foreach($complaint->order->order_items as $item)
                                    <div class="flex items-center gap-4 border-b pb-3">
                                        @if($item->product->product_images->first())
                                            <img src="{{ Storage::url($item->product->product_images->first()->image_path) }}"
                                                alt="{{ $item->product->name }}"
                                                class="w-16 h-16 object-cover rounded">
                                        @endif
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-900">{{ $item->product->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                        </div>
                                        <p class="font-semibold text-gray-900">Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    @if(!$complaint->hasReply())
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Balas Komplain</h3>
                                <form action="{{ route('admin.complaints.reply', $complaint) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <textarea name="admin_reply" rows="5" required
                                            class="w-full border-gray-300 rounded-md shadow-sm"
                                            placeholder="Tulis balasan untuk user..."></textarea>
                                    </div>
                                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Kirim Balasan
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Update Status</h3>
                            <form action="{{ route('admin.complaints.update-status', $complaint) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <select name="status" required class="w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="open" {{ $complaint->status == 'open' ? 'selected' : '' }}>Open</option>
                                        <option value="in_review" {{ $complaint->status == 'in_review' ? 'selected' : '' }}>In Review</option>
                                        <option value="resolved" {{ $complaint->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                        <option value="rejected" {{ $complaint->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                                <button type="submit" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Update Status
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('admin.complaints.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</x-admin-layout>
