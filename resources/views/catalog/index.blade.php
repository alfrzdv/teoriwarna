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
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 8px;
            grid-auto-flow: dense;
        }

        @media (max-width: 768px) {
            .products-masonry {
                grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
                gap: 6px;
            }
        }

        .product-card {
            break-inside: avoid;
            transition: opacity 0.2s ease-in-out;
            position: relative;
            overflow: hidden;
            border: 1px solid #222;
        }

        .product-card:hover {
            opacity: 0.85;
        }

        /* Uniform card heights - smaller */
        .product-card .product-image {
            height: 240px;
        }

        @media (max-width: 768px) {
            .product-card .product-image {
                height: 180px;
            }
        }

        /* Category Section Styles - Minimal */
        .category-section {
            margin-bottom: 24px;
            padding: 0;
        }

        .category-header {
            font-size: 1.5rem;
            font-weight: 900;
            margin-bottom: 8px;
            padding: 0;
            background: transparent;
            border: none;
            text-transform: uppercase;
            letter-spacing: -0.5px;
            line-height: 1;
        }

        @media (max-width: 768px) {
            .category-header {
                font-size: 1.25rem;
                margin-bottom: 6px;
            }
        }

        /* Scroll Animation - Orange Circle */
        .scroll-circle {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 100px;
            height: 100px;
            background: #F97316;
            border-radius: 50%;
            transform: translate(-50%, -50%) scale(0);
            pointer-events: none;
            z-index: 9999;
            transition: transform 0.3s ease-out;
            mix-blend-mode: multiply;
        }

        .scroll-circle.active {
            transform: translate(-50%, -50%) scale(30);
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
            <div class="mb-4 bg-black p-3 border border-[#222]">
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
                                <div class="product-card bg-black overflow-hidden relative group">

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

                                        <!-- Badges Stack -->
                                        <div class="absolute top-2 right-2 flex flex-col gap-1 items-end">
                                            @php
                                                $isNew = $product->created_at->diffInDays(now()) <= 7;
                                            @endphp

                                            @if($isNew)
                                                <span class="px-2 py-1 bg-green-400 text-black text-[8px] font-black font-poppins" style="letter-spacing: -0.4px;">
                                                    NEW
                                                </span>
                                            @endif

                                            @if($stock > 0 && $stock <= 10)
                                                <span class="px-2 py-1 bg-yellow-400 text-black text-[8px] font-black font-poppins" style="letter-spacing: -0.4px;">
                                                    {{ $stock }} LEFT
                                                </span>
                                            @endif
                                        </div>
                                    </a>

                                    <div class="p-2">
                                        <a href="{{ route('products.show', $product) }}">
                                            <h3 class="text-xs font-black mb-0.5 hover:opacity-70 transition-opacity duration-200 font-poppins uppercase line-clamp-2 text-white" style="letter-spacing: -0.5px; line-height: 1.2;">
                                                {{ $product->name }}
                                            </h3>
                                        </a>

                                        <div class="flex items-baseline justify-between mb-1">
                                            <p class="text-base font-black font-poppins text-white" style="letter-spacing: -0.5px;">
                                                {{ number_format($product->price / 1000, 0) }}K
                                            </p>
                                            <span class="text-[8px] font-bold uppercase text-gray-500" style="letter-spacing: -0.4px;">
                                                {{ $product->category->name }}
                                            </span>
                                        </div>

                                        <div class="flex items-center gap-1 mb-1.5">
                                            @php
                                                $stockClass = $stock > 10 ? 'bg-green-500' : ($stock > 0 ? 'bg-yellow-500' : 'bg-red-500');
                                            @endphp
                                            <span class="w-1 h-1 {{ $stockClass }}"></span>
                                            <span class="text-[8px] uppercase text-gray-500" style="letter-spacing: -0.4px;">{{ $stock }}</span>
                                        </div>

                                        @if($stock > 0)
                                            <form action="{{ route('cart.add', $product) }}" method="POST" class="w-full">
                                                @csrf
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit"
                                                    class="w-full px-2 py-1 font-black text-[8px] uppercase transition-opacity duration-200 hover:opacity-80 bg-white text-black" style="letter-spacing: -0.4px;">
                                                    Add
                                                </button>
                                            </form>
                                        @else
                                            <button class="w-full px-2 py-1 bg-[#111] text-gray-700 cursor-not-allowed font-black text-[8px] uppercase" style="letter-spacing: -0.4px;" disabled>
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

    <!-- Scroll Animation Circle -->
    <div class="scroll-circle"></div>

    @push('scripts')
    <script>
        // Scroll-triggered animation - Orange circle like ohira.design
        let scrollTimeout;
        const scrollCircle = document.querySelector('.scroll-circle');

        window.addEventListener('scroll', () => {
            // Clear previous timeout
            clearTimeout(scrollTimeout);

            // Show circle
            scrollCircle.classList.add('active');

            // Hide circle after scrolling stops
            scrollTimeout = setTimeout(() => {
                scrollCircle.classList.remove('active');
            }, 150);
        });

        // Smooth scroll reveal animation for product cards
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.product-card').forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = `opacity 0.4s ease ${index * 0.05}s, transform 0.4s ease ${index * 0.05}s`;
            observer.observe(card);
        });
    </script>
    @endpush
</x-app-layout>
