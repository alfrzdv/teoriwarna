<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Moderasi Review Produk</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" class="flex gap-4">
                        <div class="flex-1">
                            <input type="text" name="search" placeholder="Cari produk atau nama user..."
                                value="{{ request('search') }}"
                                class="w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <select name="status" class="border-gray-300 rounded-md shadow-sm">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        </select>
                        <select name="rating" class="border-gray-300 rounded-md shadow-sm">
                            <option value="all">Semua Rating</option>
                            <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>★★★★★</option>
                            <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>★★★★☆</option>
                            <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>★★★☆☆</option>
                            <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>★★☆☆☆</option>
                            <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>★☆☆☆☆</option>
                        </select>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Filter
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($reviews->count() > 0)
                        <div class="space-y-4">
                            @foreach($reviews as $review)
                                <div class="border rounded-lg p-4 {{ !$review->is_approved ? 'bg-yellow-50' : '' }}">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <h4 class="font-semibold text-gray-900">{{ $review->user->name }}</h4>
                                                @if($review->is_verified_purchase)
                                                    <span class="px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">
                                                        Verified Purchase
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-600">{{ $review->product->name }}</p>
                                            <div class="flex items-center gap-2 mt-1">
                                                <div class="text-yellow-400">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->rating)
                                                            ★
                                                        @else
                                                            ☆
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span class="text-xs text-gray-500">{{ $review->created_at->format('d M Y') }}</span>
                                            </div>
                                        </div>
                                        <div class="flex gap-2">
                                            @if(!$review->is_approved)
                                                <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white text-sm font-bold py-1 px-3 rounded">
                                                        Approve
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.reviews.reject', $review) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white text-sm font-bold py-1 px-3 rounded">
                                                        Reject
                                                    </button>
                                                </form>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">
                                                    Approved
                                                </span>
                                            @endif
                                            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
                                                onsubmit="return confirm('Hapus review ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <p class="text-gray-700 mb-2">{{ $review->review }}</p>

                                    @if($review->images->count() > 0)
                                        <div class="flex gap-2 mt-2">
                                            @foreach($review->images as $image)
                                                <img src="{{ Storage::url($image->image_path) }}" alt="Review image"
                                                    class="w-20 h-20 object-cover rounded">
                                            @endforeach
                                        </div>
                                    @endif

                                    @if($review->helpful_count > 0)
                                        <p class="text-xs text-gray-500 mt-2">{{ $review->helpful_count }} orang menganggap ini membantu</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            {{ $reviews->links() }}
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">Tidak ada review.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
