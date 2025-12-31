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

        /* Grid layout minimalis */
        .products-masonry {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2px;
            grid-auto-flow: dense;
        }

        @media (max-width: 768px) {
            .products-masonry {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            }
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
            transform: scale(1.02);
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
            margin-bottom: 6rem;
            padding: 3rem 2rem;
            transition: all 0.2s ease-in-out;
        }

        .category-header {
            font-size: 4rem;
            font-weight: 900;
            margin-bottom: 3rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid rgba(255, 255, 255, 0.3);
            text-transform: uppercase;
            letter-spacing: -0.05em;
            line-height: 0.9;
        }

        @media (max-width: 768px) {
            .category-header {
                font-size: 2.5rem;
            }
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header with count -->
            <div class="mb-16">
                <h1 class="text-6xl md:text-8xl font-black text-white mb-2 font-poppins uppercase tracking-tight leading-none">
                    Explore Products
                </h1>
                <p class="text-gray-400 text-sm uppercase tracking-wide">
                    <strong class="text-white font-poppins">{{ $totalProducts }}</strong> Products Available
                </p>
            </div>

            <!-- Filter Section - Compact horizontal layout -->
            <div class="mb-12 bg-[#2a2a2a] p-6">
                <form method="GET" action="{{ route('products.index') }}">
                    <div class="flex flex-wrap gap-4 items-end">
                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-sm font-semibold text-white mb-2 font-poppins">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search products..."
                                class="w-full bg-[#2a2a2a] border-0 text-white placeholder-gray-400 focus:border-purple-500 focus:ring-2 focus:ring-purple-500 py-3">
                        </div>

                        <div class="w-48">
                            <label class="block text-sm font-semibold text-white mb-2 font-poppins">Category</label>
                            <select name="category" class="w-full bg-[#2a2a2a] border-0 text-white focus:border-purple-500 focus:ring-2 focus:ring-purple-500 py-3">
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
                            <select name="sort" class="w-full bg-[#2a2a2a] border-0 text-white focus:border-purple-500 focus:ring-2 focus:ring-purple-500 py-3">
                                <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Latest</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                            </select>
                        </div>

                        <button type="submit" class="px-8 py-3 bg-purple-600 text-white hover:bg-purple-700 font-bold font-poppins transition-colors">
                            Filter
                        </button>

                        @if(request()->hasAny(['search', 'category', 'min_price', 'max_price', 'sort']))
                            <a href="{{ route('products.index') }}" class="px-6 py-3 bg-red-600 text-white hover:bg-red-700 font-semibold font-poppins">
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

                        // Array warna berbeda untuk setiap kategori
                        $colorPalette = [
                            '#8B5CF6', // Purple
                            '#EC4899', // Pink
                            '#F59E0B', // Amber
                            '#3B82F6', // Blue
                            '#EF4444', // Red
                            '#10B981', // Green
                            '#6366F1', // Indigo
                            '#F97316', // Orange
                            '#14B8A6', // Teal
                            '#8B5A3C', // Brown
                            '#06B6D4', // Cyan
                            '#A855F7', // Purple Bright
                        ];

                        // Gunakan index kategori untuk menentukan warna
                        static $categoryIndex = 0;
                        $colorIndex = $categoryIndex % count($colorPalette);
                        $defaultBg = $colorPalette[$colorIndex];
                        $categoryIndex++;

                        $bgColor = $category->background_color ?? $defaultBg;
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
                                <div class="product-card {{ $isFeatured ? 'featured' : '' }} bg-[#1a1a1a] overflow-hidden relative group"
                                    style="border: 1px solid {{ $textColor }}10;">

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
                                            <div class="product-image w-full flex items-center justify-center bg-[#2a2a2a]">
                                                <span class="text-8xl filter grayscale opacity-50">📦</span>
                                            </div>
                                        @endif

                                        <div class="absolute inset-0 bg-gradient-to-t from-[#1a1a1a]/90 via-[#1a1a1a]/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                                        @if($stock > 0 && $stock <= 10)
                                            <span class="absolute top-4 right-4 px-4 py-2 bg-yellow-400 text-black text-xs font-black font-poppins">
                                                LOW STOCK
                                            </span>
                                        @endif
                                    </a>

                                    <div class="p-4">
                                        <a href="{{ route('products.show', $product) }}">
                                            <h3 class="text-2xl font-black mb-2 hover:opacity-70 transition-opacity duration-200 font-poppins uppercase tracking-tight leading-tight line-clamp-2"
                                                style="color: {{ $textColor }};">
                                                {{ $product->name }}
                                            </h3>
                                        </a>

                                        <div class="flex items-baseline justify-between mb-3">
                                            <p class="text-3xl font-black font-poppins tracking-tighter" style="color: {{ $textColor }};">
                                                {{ number_format($product->price / 1000, 0) }}K
                                            </p>
                                            <span class="text-[10px] font-bold uppercase tracking-widest" style="color: {{ $textColor }}80;">
                                                {{ $product->category->name }}
                                            </span>
                                        </div>

                                        <div class="flex items-center gap-2 mb-3">
                                            @php
                                                $stockClass = $stock > 10 ? 'bg-green-500' : ($stock > 0 ? 'bg-yellow-500' : 'bg-red-500');
                                            @endphp
                                            <span class="w-1.5 h-1.5 {{ $stockClass }}"></span>
                                            <span class="text-[10px] uppercase tracking-wider" style="color: {{ $textColor }}80;">{{ $stock }} in stock</span>
                                        </div>

                                        @if($stock > 0)
                                            <form action="{{ route('cart.add', $product) }}" method="POST" class="w-full">
                                                @csrf
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit"
                                                    class="w-full px-4 py-2 font-black text-xs uppercase tracking-widest transition-all duration-200 hover:opacity-80"
                                                    style="background-color: {{ $textColor }}; color: {{ $bgColor }};">
                                                    Add to Cart
                                                </button>
                                            </form>
                                        @else
                                            <button class="w-full px-4 py-2 bg-gray-800 text-gray-600 cursor-not-allowed font-black text-xs uppercase tracking-widest" disabled>
                                                Sold Out
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-32 bg-[#2a2a2a]">
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
