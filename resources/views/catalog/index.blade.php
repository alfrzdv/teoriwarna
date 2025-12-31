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

        /* Grid layout untuk kategori */
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0;
            margin-bottom: 0;
        }

        @media (max-width: 1024px) {
            .categories-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Grid layout minimalis */
        .products-masonry {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 0;
            grid-auto-flow: dense;
        }

        @media (max-width: 768px) {
            .products-masonry {
                grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
                gap: 0;
            }
        }

        .product-card {
            break-inside: avoid;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: 2px solid rgba(255, 255, 255, 0.1);
        }

        .product-card:nth-child(2n) {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .product-card:nth-child(3n) {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .product-card:nth-child(4n) {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }

        .product-card:nth-child(5n) {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }

        .product-card:nth-child(6n) {
            background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);
        }

        .product-card:hover {
            transform: scale(1.05);
            z-index: 10;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
            border-color: rgba(255, 255, 255, 0.3);
        }

        /* Image overlay */
        .product-card .product-image {
            height: 320px;
            position: relative;
        }

        .product-card .product-image::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, transparent 0%, rgba(0, 0, 0, 0.8) 100%);
        }

        @media (max-width: 768px) {
            .product-card .product-image {
                height: 240px;
            }
        }

        /* Category Section Styles */
        .category-section {
            margin-bottom: 0;
            padding: 0;
            background: transparent;
            border: none;
            position: relative;
        }

        .category-header {
            font-size: 3.5rem;
            font-weight: 900;
            margin-bottom: 0;
            padding: 3rem 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            text-transform: uppercase;
            letter-spacing: -0.08em;
            line-height: 0.85;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .category-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="50" height="50" patternUnits="userSpaceOnUse"><path d="M 50 0 L 0 0 0 50" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .category-header span {
            position: relative;
            z-index: 1;
        }

        @media (max-width: 768px) {
            .category-header {
                font-size: 2rem;
                padding: 2rem 1rem;
            }
        }

        /* Filter section styling */
        .filter-section {
            background: linear-gradient(135deg, #1e1e1e 0%, #2d2d2d 100%);
            border: 2px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
    </style>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-2 sm:px-4">
            <!-- Header with count -->
            <div class="mb-6">
                <h1 class="text-3xl md:text-5xl font-black text-white mb-0.5 font-poppins uppercase" style="letter-spacing: -0.5px; line-height: 1;">
                    CATALOG
                </h1>
                <p class="text-gray-600 text-[8px] uppercase" style="letter-spacing: -0.4px;">
                    {{ $totalProducts }} Items
                </p>
            </div>

            <!-- Filter Section - Compact horizontal layout -->
            <div class="mb-6 filter-section p-4 rounded-xl shadow-2xl">
                <form method="GET" action="{{ route('products.index') }}">
                    <div class="flex flex-wrap gap-2 items-end">
                        <div class="flex-1 min-w-[180px]">
                            <label class="block text-[8px] font-black uppercase text-gray-500 mb-0.5 font-poppins" style="letter-spacing: -0.4px;">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search..."
                                class="w-full bg-[#0a0a0a] border border-[#222] text-white placeholder-gray-600 focus:border-white focus:ring-0 py-1.5 px-2 text-xs">
                        </div>

                        <div class="w-40">
                            <label class="block text-[8px] font-black uppercase text-gray-500 mb-0.5 font-poppins" style="letter-spacing: -0.4px;">Category</label>
                            <select name="category" class="w-full bg-[#0a0a0a] border border-[#222] text-white focus:border-white focus:ring-0 py-1.5 px-2 text-xs">
                                <option value="">All</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-36">
                            <label class="block text-[8px] font-black uppercase text-gray-500 mb-0.5 font-poppins" style="letter-spacing: -0.4px;">Sort</label>
                            <select name="sort" class="w-full bg-[#0a0a0a] border border-[#222] text-white focus:border-white focus:ring-0 py-1.5 px-2 text-xs">
                                <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Latest</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                            </select>
                        </div>

                        <button type="submit" class="px-4 py-1.5 bg-white text-black hover:opacity-80 font-black text-[8px] uppercase transition-opacity self-end" style="letter-spacing: -0.4px;">
                            Filter
                        </button>

                        @if(request()->hasAny(['search', 'category', 'min_price', 'max_price', 'sort']))
                            <a href="{{ route('products.index') }}" class="px-3 py-1.5 bg-black border border-white text-white hover:bg-white hover:text-black font-black text-[8px] uppercase transition-colors self-end" style="letter-spacing: -0.4px;">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Products by Category - 2 Column Grid -->
            @if(count($productsByCategory) > 0)
                <div class="categories-grid">
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

                        <div class="category-section">

                            <h2 class="category-header font-poppins">
                                <span>{{ strtoupper($category->name) }}</span>
                            </h2>

                            <div class="products-masonry">
                                @foreach($products as $index => $product)
                                @php
                                    $stock = $product->getCurrentStock();
                                @endphp
                                <div class="product-card overflow-hidden relative group">

                                    <a href="{{ route('products.show', $product) }}" class="block relative overflow-hidden">
                                        @if($product->getPrimaryImage())
                                            <img src="{{ asset('storage/' . $product->getPrimaryImage()->image_path) }}"
                                                alt="{{ $product->name }}"
                                                class="product-image w-full object-cover">
                                        @else
                                            <div class="product-image w-full flex items-center justify-center bg-[#111]">
                                                <span class="text-6xl filter grayscale opacity-30">📦</span>
                                            </div>
                                        @endif

                                        @if($stock > 0 && $stock <= 10)
                                            <span class="absolute top-4 right-4 px-4 py-2 bg-yellow-400 text-black text-xs font-black">
                                                LOW STOCK
                                            </span>
                                        @endif
                                    </a>

                                    <div class="p-4 relative z-10">
                                        <a href="{{ route('products.show', $product) }}">
                                            <h3 class="text-sm font-black mb-2 hover:scale-105 transition-transform duration-200 font-poppins uppercase line-clamp-2 text-white drop-shadow-lg" style="letter-spacing: -0.5px; line-height: 1.1;">
                                                {{ $product->name }}
                                            </h3>
                                        </a>

                                        <div class="flex items-baseline justify-between mb-3">
                                            <p class="text-2xl font-black font-poppins text-white drop-shadow-lg" style="letter-spacing: -0.5px;">
                                                Rp {{ number_format($product->price / 1000, 0) }}K
                                            </p>
                                        </div>

                                        <div class="flex items-center gap-2 mb-3">
                                            @php
                                                $stockClass = $stock > 10 ? 'bg-green-400' : ($stock > 0 ? 'bg-yellow-400' : 'bg-red-500');
                                                $stockText = $stock > 10 ? 'In Stock' : ($stock > 0 ? 'Low Stock' : 'Out');
                                            @endphp
                                            <span class="px-2 py-1 {{ $stockClass }} text-black text-[9px] font-black uppercase rounded">
                                                {{ $stockText }}
                                            </span>
                                            <span class="text-[9px] font-bold uppercase text-white/80">{{ $stock }} pcs</span>
                                        </div>

                                        @if($stock > 0)
                                            <form action="{{ route('cart.add', $product) }}" method="POST" class="w-full">
                                                @csrf
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit"
                                                    class="w-full px-4 py-2.5 font-black text-xs uppercase transition-all duration-300 hover:scale-105 bg-white text-black rounded-lg shadow-xl hover:shadow-2xl" style="letter-spacing: -0.4px;">
                                                    Add to Cart
                                                </button>
                                            </form>
                                        @else
                                            <button class="w-full px-4 py-2.5 bg-gray-800/50 text-gray-500 cursor-not-allowed font-black text-xs uppercase rounded-lg" style="letter-spacing: -0.4px;" disabled>
                                                Sold Out
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
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
