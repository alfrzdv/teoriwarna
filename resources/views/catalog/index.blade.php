<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white leading-tight font-heading">
            Shop All Products
        </h2>
    </x-slot>

    @push('scripts')
    <script>
        function addToCart(productId) {
            // You can implement this function or use a form submit
            fetch('/cart/add/' + productId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ quantity: 1 })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    alert('Product added to cart!');
                }
            });
        }
    </script>
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header & Filters -->
            <div class="mb-10">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-8">
                    <div>
                        <x-section-header title="Discover Products" subtitle="Shop">
                            <p class="text-dark-400 text-sm">
                                Browse our collection of {{ $totalProducts }} amazing products
                            </p>
                        </x-section-header>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="bg-dark-800/30 backdrop-blur-sm border border-dark-700/50 rounded-xl p-6">
                    <form method="GET" action="{{ route('products.index') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Search -->
                            <div class="lg:col-span-2">
                                <label class="block text-sm font-medium text-dark-300 mb-2">Search Products</label>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Search by name or description..."
                                    class="w-full bg-dark-900 border border-dark-700 text-white placeholder-dark-500 focus:border-brand-500 focus:ring-brand-500 rounded-lg py-2.5 px-4 text-sm">
                            </div>

                            <!-- Category -->
                            <div>
                                <label class="block text-sm font-medium text-dark-300 mb-2">Category</label>
                                <select name="category" class="w-full bg-dark-900 border border-dark-700 text-white focus:border-brand-500 focus:ring-brand-500 rounded-lg py-2.5 px-4 text-sm">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Sort -->
                            <div>
                                <label class="block text-sm font-medium text-dark-300 mb-2">Sort By</label>
                                <select name="sort" class="w-full bg-dark-900 border border-dark-700 text-white focus:border-brand-500 focus:ring-brand-500 rounded-lg py-2.5 px-4 text-sm">
                                    <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Latest First</option>
                                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name (A-Z)</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-brand-600 to-purple-600 hover:from-brand-500 hover:to-purple-500 text-white text-sm font-medium rounded-lg shadow-glow-sm hover:shadow-glow transition-all">
                                Apply Filters
                            </button>

                            @if(request()->hasAny(['search', 'category', 'min_price', 'max_price', 'sort']))
                                <a href="{{ route('products.index') }}" class="px-6 py-2.5 bg-dark-700 hover:bg-dark-600 text-white text-sm font-medium rounded-lg transition-colors">
                                    Clear All
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Products by Category -->
            @if(count($productsByCategory) > 0)
                @foreach($productsByCategory as $categoryData)
                    @php
                        $category = $categoryData['category'];
                        $products = $categoryData['products'];
                    @endphp

                    <div class="mb-16">
                        <!-- Category Header -->
                        <div class="mb-6 pb-4 border-b border-dark-800/50">
                            <h2 class="text-2xl md:text-3xl font-black font-heading text-white">
                                {{ $category->name }}
                            </h2>
                            <p class="text-dark-400 text-sm mt-1">{{ $products->count() }} products</p>
                        </div>

                        <!-- Products Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach($products as $product)
                                <x-product-card :product="$product" />
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Empty State -->
                <div class="text-center py-20">
                    <div class="max-w-md mx-auto">
                        <div class="w-20 h-20 bg-dark-800 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-dark-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold font-heading text-white mb-3">No Products Found</h3>
                        <p class="text-dark-400 mb-6">
                            @if(request('search') || request('category') || request('sort'))
                                We couldn't find any products matching your filters. Try adjusting your search criteria.
                            @else
                                There are no products available at the moment. Please check back later.
                            @endif
                        </p>
                        @if(request()->hasAny(['search', 'category', 'min_price', 'max_price', 'sort']))
                            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-brand-600 to-purple-600 hover:from-brand-500 hover:to-purple-500 text-white font-medium rounded-lg shadow-glow-sm hover:shadow-glow transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Reset Filters
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

</x-app-layout>
