<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Manage Products
            </h2>
            <a href="{{ route('admin.products.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Add New Product
            </a>
        </div>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/admin/products.css') }}">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Error Message -->
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Filter Section -->
            <div class="filter-container">
                <form method="GET" action="{{ route('admin.products.index') }}">
                    <div class="filter-grid">
                        <div class="filter-item">
                            <label class="filter-label">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search products..." class="filter-input">
                        </div>

                        <div class="filter-item">
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

                        <div class="filter-item">
                            <label class="filter-label">Status</label>
                            <select name="status" class="filter-select">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                        </div>

                        <div class="filter-item" style="display: flex; align-items: end;">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">
                                Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($products as $product)
                    <div class="product-card">
                        <div class="product-image-container">
                            @if($product->getPrimaryImage())
                                <img src="{{ asset('storage/' . $product->getPrimaryImage()->image_path) }}"
                                    alt="{{ $product->name }}" class="product-image">
                            @else
                                <div class="product-image" style="display: flex; align-items: center; justify-content: center; background-color: #e5e7eb;">
                                    <span class="text-gray-400 text-4xl">📦</span>
                                </div>
                            @endif

                            <span class="product-badge badge-{{ $product->status }}">
                                {{ ucfirst($product->status) }}
                            </span>
                        </div>

                        <div class="product-info">
                            <h3 class="product-title">{{ $product->name }}</h3>
                            <p class="product-category">{{ $product->category->name }}</p>
                            <p class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                            <div class="product-stock">
                                @php
                                    $stock = $product->getCurrentStock();
                                    $stockClass = $stock > 10 ? 'stock-in-stock' : ($stock > 0 ? 'stock-low' : 'stock-out');
                                @endphp
                                <span class="stock-indicator {{ $stockClass }}"></span>
                                <span>Stock: {{ $stock }} items</span>
                            </div>
                        </div>

                        <div class="product-actions">
                            <a href="{{ route('admin.products.show', $product) }}" class="btn-action btn-view">View</a>
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn-action btn-edit">Edit</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" style="flex: 1;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" style="width: 100%;"
                                    onclick="return confirm('Are you sure you want to delete this product?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 mb-4">No products found.</p>
                        <a href="{{ route('admin.products.create') }}" class="text-blue-600 hover:text-blue-900">Create one now</a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
