<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-white leading-tight font-heading">
                {{ $product->name }}
            </h2>
            <a href="{{ route('catalog.index') }}" class="text-primary-400 hover:text-primary-300 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Products
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gray-800 border border-gray-700 rounded-xl p-6">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                    <!-- Left: Images (2 columns) -->
                    <div class="md:col-span-2 space-y-3">
                        <div class="aspect-square bg-gray-900 border border-gray-700 rounded-lg overflow-hidden">
                            @php
                                $primaryImage = $product->getPrimaryImage();
                            @endphp
                            @if($primaryImage)
                                <img src="{{ asset('storage/' . $primaryImage->image_path) }}"
                                    alt="{{ $product->name }}"
                                    class="w-full h-full object-contain"
                                    id="mainImage">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-24 h-24 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        @if($product->product_images->count() > 1)
                            <div class="grid grid-cols-5 gap-2">
                                @foreach($product->product_images as $image)
                                    <button type="button"
                                        onclick="changeMainImage('{{ asset('storage/' . $image->image_path) }}', this)"
                                        class="aspect-square bg-gray-900 border-2 rounded-md overflow-hidden transition-all {{ $image->is_primary ? 'border-primary-500' : 'border-gray-700 hover:border-gray-600' }}">
                                        <img src="{{ asset('storage/' . $image->image_path) }}"
                                            alt="{{ $product->name }}"
                                            class="w-full h-full object-contain">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Right: Product Info (3 columns) -->
                    <div class="md:col-span-3 space-y-4">
                    <div>
                        <p class="text-sm font-medium text-primary-400 uppercase tracking-wide mb-2">
                            {{ $product->category->name }}
                        </p>
                        <h1 class="text-3xl font-bold text-white font-heading mb-4">{{ $product->name }}</h1>
                        <p class="text-4xl font-bold text-primary-500">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>
                    </div>

                    @php
                        $stock = $product->stock;
                    @endphp

                    <div class="flex items-center gap-2">
                        @if($stock > 10)
                            <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                            <span class="text-sm text-gray-300 font-medium">{{ $stock }} items available</span>
                        @elseif($stock > 0)
                            <span class="w-3 h-3 bg-yellow-500 rounded-full"></span>
                            <span class="text-sm text-gray-300 font-medium">Only {{ $stock }} left in stock</span>
                        @else
                            <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                            <span class="text-sm text-gray-300 font-medium">Out of stock</span>
                        @endif
                    </div>

                    @if($product->description)
                        <div class="bg-gray-900 border border-gray-700 rounded-lg p-4">
                            <h3 class="font-semibold text-white mb-2 text-sm">Description</h3>
                            <p class="text-gray-400 text-sm leading-relaxed">{{ $product->description }}</p>
                        </div>
                    @endif

                    @if($stock > 0)
                        @auth
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Quantity</label>
                                    <div class="flex items-center gap-3">
                                        <button type="button"
                                            onclick="decrementQuantity()"
                                            class="w-10 h-10 flex items-center justify-center bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors font-bold">−</button>
                                        <input type="number"
                                            id="quantityInput"
                                            value="1"
                                            min="1"
                                            max="{{ $stock }}"
                                            class="w-16 h-10 text-center bg-gray-900 border border-gray-700 text-white rounded-lg font-medium"
                                            readonly>
                                        <button type="button"
                                            onclick="incrementQuantity({{ $stock }})"
                                            class="w-10 h-10 flex items-center justify-center bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors font-bold">+</button>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <form action="{{ route('cart.add', $product) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="quantity" id="addToCartQuantity" value="1">
                                        <button type="submit" class="w-full px-4 py-2.5 bg-gray-700 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors">
                                            Add to Cart
                                        </button>
                                    </form>

                                    <form action="{{ route('buy-now', $product) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="quantity" id="buyNowQuantity" value="1">
                                        <button type="submit" class="w-full px-4 py-2.5 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-500 hover:to-primary-600 text-white font-medium rounded-lg shadow-sm hover:shadow transition-all">
                                            Buy Now
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="block w-full px-4 py-2.5 text-center bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-500 hover:to-primary-600 text-white font-medium rounded-lg shadow-sm hover:shadow transition-all">
                                Login to Purchase
                            </a>
                        @endauth
                    @else
                        <button disabled class="w-full px-4 py-2.5 bg-gray-700 text-gray-500 font-medium rounded-lg cursor-not-allowed">
                            Out of Stock
                        </button>
                    @endif

                    <div class="bg-gray-900 border border-gray-700 rounded-lg p-4">
                        <h4 class="font-semibold text-white mb-3 text-sm">Product Information</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Category</span>
                                <span class="text-white font-medium">{{ $product->category->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Stock Status</span>
                                <span class="font-medium {{ $stock > 0 ? 'text-green-400' : 'text-red-400' }}">
                                    {{ $stock > 0 ? 'In Stock' : 'Out of Stock' }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">SKU</span>
                                <span class="text-white font-medium">#{{ str_pad($product->id, 6, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            @if($relatedProducts->count() > 0)
                <div class="mt-16">
                    <h2 class="text-2xl font-bold text-white font-heading mb-6">Related Products</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($relatedProducts as $relatedProduct)
                            <x-product-card :product="$relatedProduct" />
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function changeMainImage(imageSrc, thumbnailElement) {
            document.getElementById('mainImage').src = imageSrc;

            // Remove active class from all thumbnails
            document.querySelectorAll('button[onclick*="changeMainImage"]').forEach(thumb => {
                thumb.classList.remove('border-primary-500');
                thumb.classList.add('border-gray-700', 'hover:border-gray-600');
            });

            // Add active class to clicked thumbnail
            thumbnailElement.classList.remove('border-gray-700', 'hover:border-gray-600');
            thumbnailElement.classList.add('border-primary-500');
        }

        function incrementQuantity(maxStock) {
            const input = document.getElementById('quantityInput');
            const currentValue = parseInt(input.value);
            if (currentValue < maxStock) {
                input.value = currentValue + 1;
                syncQuantity(currentValue + 1);
            }
        }

        function decrementQuantity() {
            const input = document.getElementById('quantityInput');
            const currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = currentValue - 1;
                syncQuantity(currentValue - 1);
            }
        }

        function syncQuantity(value) {
            const addToCartQty = document.getElementById('addToCartQuantity');
            const buyNowQty = document.getElementById('buyNowQuantity');
            if (addToCartQty) addToCartQty.value = value;
            if (buyNowQty) buyNowQty.value = value;
        }
    </script>
</x-app-layout>
