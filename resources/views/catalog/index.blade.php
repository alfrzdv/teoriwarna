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

        /* Grid layout minimalis ala ohira.design */
        .products-masonry {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 1px;
            grid-auto-flow: dense;
        }

        @media (max-width: 768px) {
            .products-masonry {
                grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            }
        }

        .product-card {
            break-inside: avoid;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        /* Removed background overlay */

        .product-card:hover {
            transform: scale(1.02);
        }

        /* Uniform card heights */
        .product-card .product-image {
            height: 280px;
        }

        @media (max-width: 768px) {
            .product-card .product-image {
                height: 200px;
            }
        }

        /* Category Section Styles - Minimal */
        .category-section {
            margin-bottom: 0;
            padding: 0;
            transition: all 0.2s ease-in-out;
        }

        .category-header {
            font-size: 3rem;
            font-weight: 900;
            margin-bottom: 1px;
            padding: 2rem 1rem;
            background: #1a1a1a;
            border-bottom: 1px solid #333;
            text-transform: uppercase;
            letter-spacing: -0.05em;
            line-height: 0.9;
        }

        @media (max-width: 768px) {
            .category-header {
                font-size: 1.75rem;
                padding: 1.5rem 1rem;
            }
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header with count -->
            <div class="mb-8">
                <h1 class="text-5xl md:text-7xl font-black text-white mb-1 font-poppins uppercase tracking-tight leading-none">
                    CATALOG
                </h1>
                <p class="text-gray-600 text-[10px] uppercase tracking-widest">
                    {{ $totalProducts }} Items
                </p>
            </div>

            <!-- Filter Section - Compact horizontal layout -->
            <div class="mb-8 bg-[#0a0a0a] p-4 border border-[#222]">
                <form method="GET" action="{{ route('products.index') }}">
                    <div class="flex flex-wrap gap-4 items-end">
                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-1 font-poppins">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search..."
                                class="w-full bg-black border border-[#222] text-white placeholder-gray-600 focus:border-white focus:ring-0 py-2 px-3 text-sm">
                        </div>

                        <div class="w-48">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-1 font-poppins">Category</label>
                            <select name="category" class="w-full bg-black border border-[#222] text-white focus:border-white focus:ring-0 py-2 px-3 text-sm">
                                <option value="">All</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-40">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-1 font-poppins">Sort</label>
                            <select name="sort" class="w-full bg-black border border-[#222] text-white focus:border-white focus:ring-0 py-2 px-3 text-sm">
                                <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Latest</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                            </select>
                        </div>

                        <button type="submit" class="px-6 py-2 bg-white text-black hover:bg-gray-200 font-black text-[10px] uppercase tracking-widest transition-colors self-end">
                            Filter
                        </button>

                        @if(request()->hasAny(['search', 'category', 'min_price', 'max_price', 'sort']))
                            <a href="{{ route('products.index') }}" class="px-4 py-2 bg-black border border-white text-white hover:bg-white hover:text-black font-black text-[10px] uppercase tracking-widest transition-colors self-end">
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

                    <div class="category-section">

                        <h2 class="category-header font-poppins text-white">
                            {{ strtoupper($category->name) }}
                        </h2>

                        <div class="products-masonry">
                            @foreach($products as $index => $product)
                                @php
                                    $stock = $product->getCurrentStock();
                                @endphp
                                <div class="product-card bg-[#0a0a0a] overflow-hidden relative group"
                                    style="border: 1px solid #222;")

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

                                    <div class="p-3">
                                        <a href="{{ route('products.show', $product) }}">
                                            <h3 class="text-base font-black mb-1 hover:opacity-70 transition-opacity duration-200 font-poppins uppercase tracking-tight leading-tight line-clamp-2 text-white">
                                                {{ $product->name }}
                                            </h3>
                                        </a>

                                        <div class="flex items-baseline justify-between mb-2">
                                            <p class="text-xl font-black font-poppins tracking-tighter text-white">
                                                {{ number_format($product->price / 1000, 0) }}K
                                            </p>
                                            <span class="text-[9px] font-bold uppercase tracking-widest text-gray-500">
                                                {{ $product->category->name }}
                                            </span>
                                        </div>

                                        <div class="flex items-center gap-1.5 mb-2">
                                            @php
                                                $stockClass = $stock > 10 ? 'bg-green-500' : ($stock > 0 ? 'bg-yellow-500' : 'bg-red-500');
                                            @endphp
                                            <span class="w-1 h-1 {{ $stockClass }}"></span>
                                            <span class="text-[9px] uppercase tracking-wider text-gray-500">{{ $stock }}</span>
                                        </div>

                                        @if($stock > 0)
                                            <form action="{{ route('cart.add', $product) }}" method="POST" class="w-full">
                                                @csrf
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit"
                                                    class="w-full px-3 py-1.5 font-black text-[10px] uppercase tracking-widest transition-all duration-200 hover:opacity-80 bg-white text-black">
                                                    Add
                                                </button>
                                            </form>
                                        @else
                                            <button class="w-full px-3 py-1.5 bg-gray-900 text-gray-700 cursor-not-allowed font-black text-[10px] uppercase tracking-widest" disabled>
                                                Out
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
