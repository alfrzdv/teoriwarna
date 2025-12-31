<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white leading-tight font-poppins">
            Product Catalog
        </h2>
    </x-slot>

    <style>
        .font-poppins {
            font-family: 'Poppins', sans-serif;
        }

        /* Masonry-like grid */
        .products-masonry {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            grid-auto-flow: dense;
        }

        .product-card {
            break-inside: avoid;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .product-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            opacity: 0.1;
            pointer-events: none;
            z-index: 0;
        }

        .product-card > * {
            position: relative;
            z-index: 1;
        }

        .product-card:hover {
            transform: translateY(-8px);
        }

        /* Varied heights */
        .product-card:nth-child(3n+1) .product-image {
            height: 300px;
        }

        .product-card:nth-child(3n+2) .product-image {
            height: 250px;
        }

        .product-card:nth-child(3n+3) .product-image {
            height: 350px;
        }

        /* Large featured cards */
        @media (min-width: 768px) {
            .product-card.featured {
                grid-column: span 2;
            }

            .product-card.featured .product-image {
                height: 400px;
            }
        }

        /* Category Section Styles */
        .category-section {
            margin-bottom: 4rem;
            padding: 2rem;
            border-radius: 1.5rem;
            transition: all 0.3s ease;
        }

        .category-header {
            font-size: 2.5rem;
            font-weight: 900;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 4px solid rgba(255, 255, 255, 0.2);
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header with count -->
            <div class="mb-8">
                <h1 class="text-4xl md:text-6xl font-black text-white mb-4 font-poppins">
                    Explore Products
                </h1>
                <p class="text-gray-300 text-lg">
                    Showing <strong class="text-white font-poppins">{{ $totalProducts }}</strong> products
                </p>
            </div>

            <!-- Filter Section - Compact horizontal layout -->
            <div class="mb-12 bg-purple-900/30 rounded-2xl p-6">
                <form method="GET" action="{{ route('products.index') }}">
                    <div class="flex flex-wrap gap-4 items-end">
                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-sm font-semibold text-white mb-2 font-poppins">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search products..."
                                class="w-full bg-black/40 border-0 text-white placeholder-gray-400 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-500 py-3">
                        </div>

                        <div class="w-48">
                            <label class="block text-sm font-semibold text-white mb-2 font-poppins">Category</label>
                            <select name="category" class="w-full bg-black/40 border-0 text-white rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-500 py-3">
                                <option value="">All</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-40">
                            <label class="block text-sm font-semibold text-white mb-2 font-poppins">Sort</label>
                            <select name="sort" class="w-full bg-black/40 border-0 text-white rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-500 py-3">
                                <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Latest</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                            </select>
                        </div>

                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:from-purple-700 hover:to-pink-700 font-bold font-poppins">
                            Filter
                        </button>

                        @if(request()->hasAny(['search', 'category', 'min_price', 'max_price', 'sort']))
                            <a href="{{ route('products.index') }}" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold font-poppins">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Products by Category -->
            @if(count($productsByCategory) > 0)
                @foreach($productsByCategory as $categoryData)
                    @php
                        $category = $categoryData['category'];
                        $products = $categoryData['products'];
                        $bgColor = $category->background_color ?? '#6B21A8';
                        $textColor = $category->text_color ?? '#ffffff';
                        $styleType = $category->style_type ?? 'solid';
                    @endphp

                    <div class="category-section mb-16"
                        style="background-color: {{ $bgColor }}; color: {{ $textColor }};">

                        <h2 class="category-header font-poppins" style="color: {{ $textColor }};">
                            {{ strtoupper($category->name) }}
                        </h2>

                        <div class="products-masonry">
                            @foreach($products as $index => $product)
                                @php
                                    $stock = $product->getCurrentStock();
                                    $isFeatured = $index % 5 === 0;
                                @endphp
                                <div class="product-card {{ $isFeatured ? 'featured' : '' }} bg-black/20 rounded-2xl overflow-hidden relative group"
                                    style="border: 2px solid {{ $textColor }}20;">

                                    <style>
                                        .product-card:nth-of-type({{ $index + 1 }})::before {
                                            background-color: {{ $bgColor }};
                                        }
                                    </style>

                                    <a href="{{ route('products.show', $product) }}" class="block relative overflow-hidden">
                                        @if($product->getPrimaryImage())
                                            <img src="{{ asset('storage/' . $product->getPrimaryImage()->image_path) }}"
                                                alt="{{ $product->name }}"
                                                class="product-image w-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        @else
                                            <div class="product-image w-full flex items-center justify-center bg-black/40">
                                                <span class="text-8xl filter grayscale opacity-50">📦</span>
                                            </div>
                                        @endif

                                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                                        @if($stock > 0 && $stock <= 10)
                                            <span class="absolute top-4 right-4 px-4 py-2 bg-yellow-400 text-black text-xs font-black rounded-lg font-poppins">
                                                LOW STOCK
                                            </span>
                                        @endif
                                    </a>

                                    <div class="p-6">
                                        <div class="flex items-start justify-between mb-3">
                                            <span class="text-xs font-bold uppercase tracking-wider font-poppins"
                                                style="color: {{ $textColor }};">
                                                {{ $product->category->name }}
                                            </span>
                                            <div class="flex items-center gap-2">
                                                @php
                                                    $stockClass = $stock > 10 ? 'bg-green-500' : ($stock > 0 ? 'bg-yellow-500' : 'bg-red-500');
                                                @endphp
                                                <span class="w-2 h-2 rounded-full {{ $stockClass }}"></span>
                                                <span class="text-xs" style="color: {{ $textColor }};">{{ $stock }}</span>
                                            </div>
                                        </div>

                                        <a href="{{ route('products.show', $product) }}">
                                            <h3 class="text-xl font-bold mb-3 hover:opacity-80 transition-colors font-poppins line-clamp-2"
                                                style="color: {{ $textColor }};">
                                                {{ $product->name }}
                                            </h3>
                                        </a>

                                        @if($product->description && $isFeatured)
                                            <p class="text-sm mb-4 line-clamp-2" style="color: {{ $textColor }}dd;">
                                                {{ Str::limit($product->description, 100) }}
                                            </p>
                                        @endif

                                        <div class="flex items-end justify-between mb-4">
                                            <div>
                                                <p class="text-3xl font-black font-poppins" style="color: {{ $textColor }};">
                                                    Rp {{ number_format($product->price / 1000, 0) }}K
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex gap-3">
                                            @if($stock > 0)
                                                <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-1">
                                                    @csrf
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button type="submit"
                                                        class="w-full px-4 py-3 rounded-lg font-bold font-poppins transform hover:scale-105 transition-transform"
                                                        style="background-color: {{ $textColor }}; color: {{ $bgColor }};">
                                                        Add to Cart
                                                    </button>
                                                </form>
                                            @else
                                                <button class="flex-1 px-4 py-3 bg-gray-800 text-gray-500 rounded-lg cursor-not-allowed font-semibold font-poppins" disabled>
                                                    Sold Out
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-32 bg-purple-900/20 rounded-2xl">
                    <div class="text-8xl mb-6">🔍</div>
                    <h3 class="text-3xl font-bold text-white mb-4 font-poppins">No Products Found</h3>
                    <p class="text-gray-300 text-lg">
                        @if(request('search') || request('category') || request('sort'))
                            Try adjusting your filters or search terms.
                        @else
                            There are no products available at the moment.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
