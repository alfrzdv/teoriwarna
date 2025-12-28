<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Product Catalog
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/catalog/products.css') }}">

    <div class="catalog-container">
        <!-- Filter Section -->
        <div class="filter-section">
            <form method="GET" action="{{ route('products.index') }}">
                <div class="filter-grid">
                    <div class="filter-group">
                        <label class="filter-label">Search Products</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search by name or description..." class="filter-input">
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Category</label>
                        <select name="category" class="filter-select">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Sort By</label>
                        <select name="sort" class="filter-select">
                            <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Latest</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name: A-Z</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <button type="submit" class="filter-button">Apply Filters</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Products Count -->
        <div style="margin-bottom: 1.5rem;">
            <p class="text-gray-600">
                Showing <strong>{{ $products->count() }}</strong> of <strong>{{ $products->total() }}</strong> products
            </p>
        </div>

        <!-- Products Grid -->
        @if($products->count() > 0)
            <div class="products-grid">
                @foreach($products as $product)
                    <div class="product-card">
                        <a href="{{ route('products.show', $product) }}" class="product-image-wrapper">
                            @if($product->getPrimaryImage())
                                <img src="{{ asset('storage/' . $product->getPrimaryImage()->image_path) }}"
                                    alt="{{ $product->name }}" class="product-image">
                            @else
                                <div class="product-image" style="display: flex; align-items: center; justify-content: center; background-color: #e5e7eb;">
                                    <span style="font-size: 4rem; filter: grayscale(100%);">📦</span>
                                </div>
                            @endif

                            @php
                                $stock = $product->getCurrentStock();
                            @endphp
                            @if($stock > 0 && $stock <= 10)
                                <span class="product-badge badge-sale">Low Stock!</span>
                            @endif
                        </a>

                        <div class="product-details">
                            <p class="product-category">{{ $product->category->name }}</p>

                            <a href="{{ route('products.show', $product) }}" style="text-decoration: none; color: inherit;">
                                <h3 class="product-name">{{ $product->name }}</h3>
                            </a>

                            @if($product->description)
                                <p class="product-description">{{ Str::limit($product->description, 80) }}</p>
                            @endif

                            <div class="product-footer">
                                <div>
                                    <p class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                    <div class="product-stock">
                                        @php
                                            $stockClass = $stock > 10 ? 'stock-available' : ($stock > 0 ? 'stock-low' : 'stock-out');
                                        @endphp
                                        <span class="stock-dot {{ $stockClass }}"></span>
                                        <span>{{ $stock > 0 ? $stock . ' in stock' : 'Out of stock' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="product-actions">
                                <a href="{{ route('products.show', $product) }}" class="btn-view">View Details</a>
                                @if($stock > 0)
                                    @auth
                                        <form action="{{ route('cart.add', $product) }}" method="POST" style="flex: 1;">
                                            @csrf
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn-add-cart" style="width: 100%;">
                                                Add to Cart
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}" class="btn-add-cart">Login</a>
                                    @endauth
                                @else
                                    <button class="btn-add-cart" disabled style="background-color: #9ca3af; cursor: not-allowed;">
                                        Out of Stock
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                {{ $products->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">🔍</div>
                <h3 class="empty-title">No Products Found</h3>
                <p class="empty-description">
                    @if(request('search') || request('category') || request('sort'))
                        Try adjusting your filters or search terms.
                    @else
                        There are no products available at the moment.
                    @endif
                </p>
            </div>
        @endif
    </div>
</x-app-layout>
