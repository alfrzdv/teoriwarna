<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Product Details: {{ $product->name }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.products.edit', $product) }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit Product
                </a>
                <a href="{{ route('admin.products.index') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to List
                </a>
            </div>
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - Product Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Basic Information</h3>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Product Name</p>
                                    <p class="font-semibold">{{ $product->name }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-600">Category</p>
                                    <p class="font-semibold">{{ $product->category->name }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-600">Price</p>
                                    <p class="font-semibold text-blue-600 text-xl">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-600">Status</p>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $product->status == 'active' ? 'bg-green-100 text-green-800' : ($product->status == 'inactive' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                </div>

                                <div class="col-span-2">
                                    <p class="text-sm text-gray-600">Description</p>
                                    <p class="text-gray-800 mt-1">{{ $product->description ?? 'No description provided' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Images -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Product Images</h3>

                            @if($product->product_images->count() > 0)
                                <div class="image-gallery">
                                    @foreach($product->product_images as $image)
                                        <div class="gallery-item {{ $image->is_primary ? 'primary' : '' }}">
                                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                                alt="{{ $product->name }}" class="gallery-image">

                                            @if($image->is_primary)
                                                <div style="position: absolute; top: 0.5rem; left: 0.5rem; background-color: #3b82f6; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600;">
                                                    Primary
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-center py-8">No images uploaded for this product.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Stock History -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Stock History</h3>

                            @if($product->product_stocks->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Note</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($product->product_stocks as $stock)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        {{ $stock->created_at->format('d M Y H:i') }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                            {{ $stock->type == 'in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                            {{ $stock->type == 'in' ? 'Stock In' : 'Stock Out' }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        {{ $stock->quantity }}
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-gray-500">
                                                        {{ $stock->note ?? '-' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-gray-500 text-center py-8">No stock history available.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column - Stock Management -->
                <div class="space-y-6">
                    <!-- Current Stock -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Current Stock</h3>

                            @php
                                $currentStock = $product->getCurrentStock();
                                $stockClass = $currentStock > 10 ? 'text-green-600' : ($currentStock > 0 ? 'text-yellow-600' : 'text-red-600');
                            @endphp

                            <div class="text-center">
                                <p class="text-5xl font-bold {{ $stockClass }}">{{ $currentStock }}</p>
                                <p class="text-gray-600 mt-2">items in stock</p>
                            </div>

                            @if($currentStock <= 10 && $currentStock > 0)
                                <div class="mt-4 bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded">
                                    <p class="text-sm">⚠️ Low stock warning!</p>
                                </div>
                            @elseif($currentStock == 0)
                                <div class="mt-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">
                                    <p class="text-sm">❌ Out of stock!</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Add Stock -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Add Stock</h3>

                            <form action="{{ route('admin.products.add-stock', $product) }}" method="POST">
                                @csrf

                                <div class="mb-4">
                                    <label for="add_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                        Quantity
                                    </label>
                                    <input type="number" name="quantity" id="add_quantity"
                                        min="1" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div class="mb-4">
                                    <label for="add_note" class="block text-sm font-medium text-gray-700 mb-2">
                                        Note (Optional)
                                    </label>
                                    <input type="text" name="note" id="add_note"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <button type="submit"
                                    class="w-full px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                                    Add Stock
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Reduce Stock -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Reduce Stock</h3>

                            <form action="{{ route('admin.products.reduce-stock', $product) }}" method="POST">
                                @csrf

                                <div class="mb-4">
                                    <label for="reduce_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                        Quantity
                                    </label>
                                    <input type="number" name="quantity" id="reduce_quantity"
                                        min="1" max="{{ $currentStock }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div class="mb-4">
                                    <label for="reduce_note" class="block text-sm font-medium text-gray-700 mb-2">
                                        Note (Optional)
                                    </label>
                                    <input type="text" name="note" id="reduce_note"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <button type="submit"
                                    class="w-full px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600"
                                    {{ $currentStock == 0 ? 'disabled' : '' }}>
                                    Reduce Stock
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
