<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $product->name }}
            </h2>
            <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-800">
                ← Back to Products
            </a>
        </div>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/catalog/products.css') }}">

    <div class="product-detail-container">
        <div class="product-detail-grid">
            <!-- Left: Images -->
            <div class="product-images-section">
                <div class="main-image-wrapper" id="mainImageWrapper">
                    @if($product->getPrimaryImage())
                        <img src="{{ asset('storage/' . $product->getPrimaryImage()->image_path) }}"
                            alt="{{ $product->name }}" class="main-product-image" id="mainImage">
                    @else
                        <div class="main-product-image" style="display: flex; align-items: center; justify-content: center; background-color: #e5e7eb;">
                            <span style="font-size: 6rem; filter: grayscale(100%);">📦</span>
                        </div>
                    @endif
                </div>

                @if($product->product_images->count() > 1)
                    <div class="thumbnail-gallery">
                        @foreach($product->product_images as $index => $image)
                            <div class="thumbnail-wrapper {{ $image->is_primary ? 'active' : '' }}"
                                onclick="changeMainImage('{{ asset('storage/' . $image->image_path) }}', this)">
                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                    alt="{{ $product->name }}" class="thumbnail-image">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Right: Product Info -->
            <div class="product-info-section">
                <p class="product-detail-category">{{ $product->category->name }}</p>
                <h1 class="product-detail-title">{{ $product->name }}</h1>
                <p class="product-detail-price">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                @php
                    $stock = $product->getCurrentStock();
                    $stockClass = $stock > 10 ? 'stock-available' : ($stock > 0 ? 'stock-low' : 'stock-out');
                    $stockText = $stock > 0 ? $stock . ' items available' : 'Out of stock';
                @endphp

                <div class="product-detail-stock">
                    <span class="stock-dot {{ $stockClass }}" style="width: 12px; height: 12px;"></span>
                    <span style="font-weight: 500;">{{ $stockText }}</span>
                </div>

                @if($product->description)
                    <div class="product-detail-description">
                        <h3 style="font-weight: 600; margin-bottom: 0.5rem; color: #111827;">Description</h3>
                        <p>{{ $product->description }}</p>
                    </div>
                @endif

                @if($stock > 0)
                    @auth
                        <form action="{{ route('cart.add', $product) }}" method="POST" id="addToCartForm">
                            @csrf
                            <div class="quantity-selector">
                                <label class="quantity-label">Quantity</label>
                                <div class="quantity-input-group">
                                    <button type="button" class="quantity-button" onclick="decrementQuantity()">−</button>
                                    <input type="number" name="quantity" id="quantityInput" value="1"
                                        min="1" max="{{ $stock }}" class="quantity-input" readonly>
                                    <button type="button" class="quantity-button" onclick="incrementQuantity({{ $stock }})">+</button>
                                </div>
                            </div>

                            <button type="submit" class="add-to-cart-button">
                                Add to Cart
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="add-to-cart-button" style="text-decoration: none;">
                            Login to Add to Cart
                        </a>
                    @endauth
                @else
                    <button class="add-to-cart-button" disabled>
                        Out of Stock
                    </button>
                @endif

                <div style="padding: 1rem; background-color: #f9fafb; border-radius: 0.5rem; margin-top: 1rem;">
                    <h4 style="font-weight: 600; margin-bottom: 0.5rem; color: #374151;">Product Information</h4>
                    <div style="display: grid; gap: 0.5rem; font-size: 0.875rem; color: #6b7280;">
                        <div style="display: flex; justify-content: space-between;">
                            <span>Category:</span>
                            <span style="font-weight: 500; color: #111827;">{{ $product->category->name }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>Stock Status:</span>
                            <span style="font-weight: 500; color: {{ $stock > 0 ? '#059669' : '#dc2626' }};">
                                {{ $stock > 0 ? 'In Stock' : 'Out of Stock' }}
                            </span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>SKU:</span>
                            <span style="font-weight: 500; color: #111827;">#{{ str_pad($product->id, 6, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
            <div class="related-products-section">
                <h2 class="related-products-title">Related Products</h2>

                <div class="products-grid">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="product-card">
                            <a href="{{ route('products.show', $relatedProduct) }}" class="product-image-wrapper">
                                @if($relatedProduct->getPrimaryImage())
                                    <img src="{{ asset('storage/' . $relatedProduct->getPrimaryImage()->image_path) }}"
                                        alt="{{ $relatedProduct->name }}" class="product-image">
                                @else
                                    <div class="product-image" style="display: flex; align-items: center; justify-content: center; background-color: #e5e7eb;">
                                        <span style="font-size: 4rem; filter: grayscale(100%);">📦</span>
                                    </div>
                                @endif
                            </a>

                            <div class="product-details">
                                <p class="product-category">{{ $relatedProduct->category->name }}</p>

                                <a href="{{ route('products.show', $relatedProduct) }}" style="text-decoration: none; color: inherit;">
                                    <h3 class="product-name">{{ $relatedProduct->name }}</h3>
                                </a>

                                <div class="product-footer">
                                    <div>
                                        <p class="product-price">Rp {{ number_format($relatedProduct->price, 0, ',', '.') }}</p>
                                        <div class="product-stock">
                                            @php
                                                $relatedStock = $relatedProduct->getCurrentStock();
                                                $relatedStockClass = $relatedStock > 10 ? 'stock-available' : ($relatedStock > 0 ? 'stock-low' : 'stock-out');
                                            @endphp
                                            <span class="stock-dot {{ $relatedStockClass }}"></span>
                                            <span>{{ $relatedStock > 0 ? $relatedStock . ' in stock' : 'Out of stock' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="product-actions">
                                    <a href="{{ route('products.show', $relatedProduct) }}" class="btn-view">View Details</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <script>
        function changeMainImage(imageSrc, thumbnailElement) {
            document.getElementById('mainImage').src = imageSrc;

            // Remove active class from all thumbnails
            document.querySelectorAll('.thumbnail-wrapper').forEach(thumb => {
                thumb.classList.remove('active');
            });

            // Add active class to clicked thumbnail
            thumbnailElement.classList.add('active');
        }

        function incrementQuantity(maxStock) {
            const input = document.getElementById('quantityInput');
            const currentValue = parseInt(input.value);
            if (currentValue < maxStock) {
                input.value = currentValue + 1;
            }
        }

        function decrementQuantity() {
            const input = document.getElementById('quantityInput');
            const currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = currentValue - 1;
            }
        }
    </script>
</x-app-layout>
