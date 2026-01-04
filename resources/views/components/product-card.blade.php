@props(['product'])

<div class="group bg-gray-800 border border-gray-700 rounded-xl overflow-hidden hover:border-primary-500 hover:shadow-lg transition-all duration-300 flex flex-col h-full">
    <a href="{{ route('products.show', $product->id) }}" class="block flex-1 flex flex-col">
        <!-- Product Image -->
        <div class="relative aspect-square bg-gray-900 overflow-hidden">
            @php
                $primaryImage = $product->getPrimaryImage();
            @endphp
            @if($primaryImage)
                <img src="{{ asset('storage/' . $primaryImage->image_path) }}"
                     alt="{{ $product->name }}"
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
            @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-800 to-gray-900">
                    <svg class="w-20 h-20 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            @endif

            <!-- Stock Badge -->
            @if($product->stock <= 0)
                <div class="absolute top-3 left-3">
                    <span class="px-3 py-1 bg-red-600/90 backdrop-blur-sm text-white text-xs font-bold rounded-full">
                        Out of Stock
                    </span>
                </div>
            @elseif($product->stock <= 10)
                <div class="absolute top-3 left-3">
                    <span class="px-3 py-1 bg-yellow-600/90 backdrop-blur-sm text-white text-xs font-bold rounded-full">
                        Low Stock
                    </span>
                </div>
            @endif

            <!-- Discount Badge -->
            @if($product->discount_percentage > 0)
                <div class="absolute top-3 right-3">
                    <span class="px-3 py-1 bg-primary-600 text-white text-xs font-bold rounded-full">
                        -{{ $product->discount_percentage }}%
                    </span>
                </div>
            @endif

            <!-- Quick View Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-4">
                <span class="text-white text-sm font-medium">Quick View</span>
            </div>
        </div>

        <!-- Product Info -->
        <div class="p-4 flex-1 flex flex-col">
            <!-- Category -->
            @if($product->category)
                <p class="text-xs font-medium text-primary-400 uppercase tracking-wide mb-1">
                    {{ $product->category->name }}
                </p>
            @endif

            <!-- Product Name -->
            <h3 class="font-heading font-semibold text-white text-base mb-2 line-clamp-2 group-hover:text-primary-400 transition-colors">
                {{ $product->name }}
            </h3>

            <!-- Rating (if available) -->
            @if($product->reviews && $product->reviews->count() > 0)
                <div class="flex items-center gap-2 mb-2">
                    <div class="flex items-center">
                        @php
                            $rating = round($product->reviews->avg('rating'));
                        @endphp
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= $rating ? 'text-yellow-400' : 'text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <span class="text-xs text-gray-400">({{ $product->reviews->count() }})</span>
                </div>
            @endif

            <!-- Price -->
            <div class="flex items-center gap-2 mt-auto">
                @if($product->discount_percentage > 0)
                    <span class="text-lg font-bold font-heading text-white">
                        Rp {{ number_format($product->final_price, 0, ',', '.') }}
                    </span>
                    <span class="text-sm text-gray-400 line-through">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </span>
                @else
                    <span class="text-lg font-bold font-heading text-white">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </span>
                @endif
            </div>
        </div>
    </a>

    <!-- Add to Cart Button -->
    @if($product->stock > 0)
        <div class="p-4 pt-0">
            <button
                onclick="addToCart({{ $product->id }})"
                class="w-full px-4 py-2.5 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-500 hover:to-primary-600 text-white text-sm font-medium rounded-lg shadow-sm hover:shadow transition-all">
                Add to Cart
            </button>
        </div>
    @else
        <div class="p-4 pt-0">
            <button
                disabled
                class="w-full px-4 py-2.5 bg-gray-700 text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed">
                Out of Stock
            </button>
        </div>
    @endif
</div>
