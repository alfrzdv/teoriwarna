<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Product: {{ $product->name }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/admin/products.css') }}">

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form id="updateProductForm" action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

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
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                            <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}"
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
                            <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}"
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
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
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
                                <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="archived" {{ old('status', $product->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Existing Images -->
                        @if($product->product_images->count() > 0)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Existing Images
                                </label>
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

                                            <div class="gallery-actions">
                                                @if(!$image->is_primary)
                                                    <button type="button" class="gallery-btn gallery-btn-primary"
                                                        onclick="setPrimaryImage('{{ route('admin.products.set-primary-image', $image) }}')">
                                                        Set Primary
                                                    </button>
                                                @endif

                                                <button type="button" class="gallery-btn gallery-btn-delete"
                                                    onclick="deleteImage('{{ route('admin.products.delete-image', $image) }}')">
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Add New Images -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Add More Images
                            </label>

                            <div x-data="productImagesEdit()" class="space-y-4">
                                <!-- Image Cropper Component -->
                                <x-image-cropper
                                    id="product-image-cropper-edit"
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
                                    <p class="text-sm font-medium text-gray-700 mb-2">New Images to Add:</p>
                                    <div class="grid grid-cols-4 gap-4">
                                        <template x-for="(img, index) in images" :key="index">
                                            <div class="relative">
                                                <img :src="img" class="w-full h-32 object-cover rounded border">
                                                <button type="button" @click="removeImage(index)"
                                                    class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600">
                                                    ×
                                                </button>
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
                            <p class="text-xs text-gray-500 mt-1">Crop and add multiple images.</p>
                        </div>

                        @push('scripts')
                        <script>
                        function productImagesEdit() {
                            return {
                                images: [],
                                addImage() {
                                    // Get the cropped image data from the image-cropper component
                                    const cropperData = document.querySelector('input[name="temp_image"]').value;
                                    if (cropperData) {
                                        this.images.push(cropperData);
                                        // Reset the cropper by clearing the input
                                        document.querySelector('input[name="temp_image"]').value = '';
                                        // Trigger reset event
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
                            <button type="submit" onclick="console.log('Submit button clicked'); return true;"
                                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                Update Product
                            </button>
                        </div>
                    </form>

                    <script>
                    // Delete image function
                    function deleteImage(url) {
                        if (!confirm('Are you sure you want to delete this image?')) {
                            return;
                        }

                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = url;

                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = csrfToken;
                        form.appendChild(csrfInput);

                        const methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'DELETE';
                        form.appendChild(methodInput);

                        document.body.appendChild(form);
                        form.submit();
                    }

                    // Set primary image function
                    function setPrimaryImage(url) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = url;

                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = csrfToken;
                        form.appendChild(csrfInput);

                        document.body.appendChild(form);
                        form.submit();
                    }

                    // Debug: Check if form submission is working
                    document.addEventListener('DOMContentLoaded', function() {
                        console.log('DOM loaded');
                        console.log('Alpine available:', typeof window.Alpine !== 'undefined');

                        const form = document.getElementById('updateProductForm');
                        if (form) {
                            console.log('Form found');
                            form.addEventListener('submit', function(e) {
                                console.log('Form submit event triggered');
                                return true; // Allow form to submit
                            });
                        } else {
                            console.error('Form not found!');
                        }
                    });

                    // Fallback: if button click doesn't trigger form submit
                    window.addEventListener('load', function() {
                        console.log('Window fully loaded');
                    });
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
