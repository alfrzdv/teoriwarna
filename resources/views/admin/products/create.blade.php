<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add New Product
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/admin/products.css') }}">

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Category -->
                        <div class="mb-4">
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <select name="category_id" id="category_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('category_id') border-red-500 @enderror"
                                required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Product Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                                required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div class="mb-4">
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                Price (Rp) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="price" id="price" value="{{ old('price') }}"
                                min="0" step="0.01"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('price') border-red-500 @enderror"
                                required>
                            @error('price')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea name="description" id="description" rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror"
                                required>
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Initial Stock -->
                        <div class="mb-4">
                            <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                Initial Stock Quantity
                            </label>
                            <input type="number" name="stock_quantity" id="stock_quantity" value="{{ old('stock_quantity', 0) }}"
                                min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('stock_quantity') border-red-500 @enderror">
                            @error('stock_quantity')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Leave as 0 if you want to add stock later</p>
                        </div>

                        <!-- Product Images -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Product Images
                            </label>

                            <div x-data="productImages()" class="space-y-4">
                                <!-- Image Cropper Component -->
                                <x-image-cropper
                                    id="product-image-cropper"
                                    inputName="temp_image"
                                    :aspectRatio="1"
                                    :previewWidth="400"
                                    :previewHeight="400"
                                    label="Add Product Image"
                                />

                                <!-- Add Image Button -->
                                <button type="button" @click="addImage()"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                    Add This Image
                                </button>

                                <!-- Preview of Added Images -->
                                <div x-show="images.length > 0" class="mt-4">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Added Images:</p>
                                    <div class="grid grid-cols-4 gap-4">
                                        <template x-for="(img, index) in images" :key="index">
                                            <div class="relative">
                                                <img :src="img" class="w-full h-32 object-cover rounded border">
                                                <button type="button" @click="removeImage(index)"
                                                    class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600">
                                                    ×
                                                </button>
                                                <span x-show="index === 0" class="absolute bottom-1 left-1 bg-blue-500 text-white text-xs px-2 py-1 rounded">
                                                    Primary
                                                </span>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <!-- Hidden inputs for each image -->
                                <template x-for="(img, index) in images" :key="index">
                                    <input type="hidden" :name="'images[' + index + ']'" :value="img">
                                </template>
                            </div>

                            @error('images.*')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Crop and add multiple images. First image will be the primary image.</p>
                        </div>

                        @push('scripts')
                        <script>
                        function productImages() {
                            return {
                                images: [],
                                addImage() {
                                    // Get the cropped image data from the image-cropper component
                                    const cropperData = document.querySelector('input[name="temp_image"]').value;
                                    if (cropperData) {
                                        this.images.push(cropperData);
                                        // Reset the cropper by clearing the input
                                        document.querySelector('input[name="temp_image"]').value = '';
                                        // Trigger click on file input to allow adding another image
                                        const event = new Event('reset-cropper');
                                        document.dispatchEvent(event);
                                    } else {
                                        alert('Please crop an image first');
                                    }
                                },
                                removeImage(index) {
                                    this.images.splice(index, 1);
                                }
                            }
                        }
                        </script>
                        @endpush

                        <!-- Buttons -->
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('admin.products.index') }}"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                Cancel
                            </a>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                Create Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
