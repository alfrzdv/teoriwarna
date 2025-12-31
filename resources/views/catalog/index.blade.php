<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Product Catalog
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter Section -->
            <div class="mb-8 bg-gradient-to-br from-purple-900/20 to-pink-900/20 backdrop-blur-sm rounded-2xl p-6">
                <form method="GET" action="{{ route('products.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-200 mb-2">Search Products</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search by name or description..."
                                class="w-full bg-white/10 border-white/20 text-white placeholder-gray-400 rounded-md focus:border-purple-500 focus:ring-purple-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-200 mb-2">Category</label>
                            <select name="category" class="w-full bg-white/10 border-white/20 text-white rounded-md focus:border-purple-500 focus:ring-purple-500">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-200 mb-2">Min Price</label>
                            <input type="number" name="min_price" value="{{ request('min_price') }}"
                                placeholder="Min price..."
                                class="w-full bg-white/10 border-white/20 text-white placeholder-gray-400 rounded-md focus:border-purple-500 focus:ring-purple-500"
                                min="0" step="1000">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-200 mb-2">Max Price</label>
                            <input type="number" name="max_price" value="{{ request('max_price') }}"
                                placeholder="Max price..."
                                class="w-full bg-white/10 border-white/20 text-white placeholder-gray-400 rounded-md focus:border-purple-500 focus:ring-purple-500"
                                min="0" step="1000">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-200 mb-2">Sort By</label>
                            <select name="sort" class="w-full bg-white/10 border-white/20 text-white rounded-md focus:border-purple-500 focus:ring-purple-500">
                                <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Latest</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name: A-Z</option>
                            </select>
                        </div>

                        <div class="flex items-end gap-2">
                            <button type="submit" class="flex-1 px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-md hover:from-purple-700 hover:to-pink-700 font-semibold">
                                Apply Filters
                            </button>
                            @if(request()->hasAny(['search', 'category', 'min_price', 'max_price', 'sort']))
                                <a href="{{ route('products.index') }}" class="px-4 py-2 bg-white/10 text-white rounded-md hover:bg-white/20 text-center font-semibold">
                                    Clear
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <!-- Products Count -->
            <div class="mb-6">
                <p class="text-gray-300">
                    Showing <strong class="text-white">{{ $products->count() }}</strong> of <strong class="text-white">{{ $products->total() }}</strong> products
                </p>
            </div>

            <!-- Products Grid -->
            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($products as $product)
                        <div class="bg-gradient-to-br from-purple-900/20 to-pink-900/20 backdrop-blur-sm rounded-2xl overflow-hidden hover:scale-105 transition-transform duration-200">
                            <a href="{{ route('products.show', $product) }}" class="block">
                                @if($product->getPrimaryImage())
                                    <img src="{{ asset('storage/' . $product->getPrimaryImage()->image_path) }}"
                                        alt="{{ $product->name }}"
                                        class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 flex items-center justify-center bg-white/5">
                                        <span class="text-6xl filter grayscale">📦</span>
                                    </div>
                                @endif

                                @php
                                    $stock = $product->getCurrentStock();
                                @endphp
                                @if($stock > 0 && $stock <= 10)
                                    <span class="absolute top-2 right-2 px-3 py-1 bg-yellow-500 text-black text-xs font-bold rounded-full">Low Stock!</span>
                                @endif
                            </a>

                            <div class="p-4">
                                <p class="text-sm text-pink-400 font-semibold mb-1">{{ $product->category->name }}</p>

                                <a href="{{ route('products.show', $product) }}" class="block">
                                    <h3 class="text-lg font-bold text-white mb-2 hover:text-purple-300">{{ $product->name }}</h3>
                                </a>

                                @if($product->description)
                                    <p class="text-sm text-gray-300 mb-3">{{ Str::limit($product->description, 80) }}</p>
                                @endif

                                <div class="mb-3">
                                    <p class="text-2xl font-black text-white">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        @php
                                            $stockClass = $stock > 10 ? 'bg-green-500' : ($stock > 0 ? 'bg-yellow-500' : 'bg-red-500');
                                        @endphp
                                        <span class="w-2 h-2 rounded-full {{ $stockClass }}"></span>
                                        <span class="text-sm text-gray-300">{{ $stock > 0 ? $stock . ' in stock' : 'Out of stock' }}</span>
                                    </div>
                                </div>

                                <div class="flex gap-2">
                                    <a href="{{ route('products.show', $product) }}" class="flex-1 text-center px-3 py-2 bg-white/10 text-white rounded-md hover:bg-white/20 text-sm font-semibold">
                                        View Details
                                    </a>
                                    @if($stock > 0)
                                        <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-1">
                                            @csrf
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="w-full px-3 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-md hover:from-purple-700 hover:to-pink-700 text-sm font-semibold">
                                                Add to Cart
                                            </button>
                                        </form>
                                    @else
                                        <button class="flex-1 px-3 py-2 bg-gray-600 text-gray-400 rounded-md cursor-not-allowed text-sm font-semibold" disabled>
                                            Out of Stock
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-16 bg-gradient-to-br from-purple-900/20 to-pink-900/20 backdrop-blur-sm rounded-2xl">
                    <div class="text-6xl mb-4">🔍</div>
                    <h3 class="text-2xl font-bold text-white mb-2">No Products Found</h3>
                    <p class="text-gray-300">
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
