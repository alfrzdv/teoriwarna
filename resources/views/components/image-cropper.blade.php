@props([
    'id' => 'image-cropper-' . uniqid(),
    'inputName' => 'image',
    'aspectRatio' => 1, // 1:1 square by default
    'previewWidth' => 300,
    'previewHeight' => 300,
    'label' => 'Upload Image',
    'currentImage' => null
])

<div x-data="imageCropper('{{ $id }}', {{ $aspectRatio }}, {{ $previewWidth }}, {{ $previewHeight }})" x-init="init()" class="space-y-4">
    <!-- Upload Button -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">{{ $label }}</label>
        <div class="flex items-center gap-3">
            <button type="button" @click="$refs.fileInput.click()"
                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                Choose Image
            </button>
            <span x-text="fileName || 'No file chosen'" class="text-sm text-gray-500"></span>
        </div>
        <input type="file" x-ref="fileInput" @change="loadImage($event)"
            accept="image/jpeg,image/png,image/jpg,image/gif" class="hidden">
    </div>

    <!-- Cropper Modal -->
    <div x-show="showCropper" x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">

        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <!-- Modal Content -->
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="relative bg-white rounded-lg shadow-xl max-w-4xl w-full p-6">
                <h3 class="text-lg font-semibold mb-4">Crop Image</h3>

                <!-- Cropper Container -->
                <div class="mb-4">
                    <img x-ref="cropperImage" class="max-w-full" style="max-height: 500px;">
                </div>

                <!-- Controls -->
                <div class="flex items-center justify-between gap-4 mb-4">
                    <div class="flex gap-2">
                        <button type="button" @click="cropper.rotate(-90)"
                            class="px-3 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                            ↶ Rotate Left
                        </button>
                        <button type="button" @click="cropper.rotate(90)"
                            class="px-3 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                            ↷ Rotate Right
                        </button>
                        <button type="button" @click="cropper.scaleX(-1)"
                            class="px-3 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                            ⟷ Flip H
                        </button>
                        <button type="button" @click="cropper.reset()"
                            class="px-3 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                            Reset
                        </button>
                    </div>

                    <div class="flex gap-2">
                        <button type="button" @click="closeCropper"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                            Cancel
                        </button>
                        <button type="button" @click="cropImage"
                            class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            Crop & Upload
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden input with cropped image data -->
    <input type="hidden" :name="'{{ $inputName }}'" x-model="croppedImageData">
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
<script>
function imageCropper(id, aspectRatio, previewWidth, previewHeight) {
    return {
        showCropper: false,
        cropper: null,
        fileName: '',
        croppedImageUrl: '',
        croppedImageData: '',
        previewWidth: previewWidth,
        previewHeight: previewHeight,

        loadImage(event) {
            const file = event.target.files[0];
            if (!file) return;

            this.fileName = file.name;
            const reader = new FileReader();

            reader.onload = (e) => {
                this.$refs.cropperImage.src = e.target.result;
                this.showCropper = true;

                this.$nextTick(() => {
                    if (this.cropper) {
                        this.cropper.destroy();
                    }

                    this.cropper = new Cropper(this.$refs.cropperImage, {
                        aspectRatio: aspectRatio,
                        viewMode: 2,
                        dragMode: 'move',
                        autoCropArea: 1,
                        restore: false,
                        guides: true,
                        center: true,
                        highlight: false,
                        cropBoxMovable: true,
                        cropBoxResizable: true,
                        toggleDragModeOnDblclick: false,
                    });
                });
            };

            reader.readAsDataURL(file);
        },

        cropImage() {
            if (!this.cropper) return;

            const canvas = this.cropper.getCroppedCanvas({
                width: previewWidth,
                height: previewHeight,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            });

            this.croppedImageUrl = canvas.toDataURL('image/jpeg', 0.9);
            this.croppedImageData = canvas.toDataURL('image/jpeg', 0.9);

            this.closeCropper();
        },

        closeCropper() {
            this.showCropper = false;
            if (this.cropper) {
                this.cropper.destroy();
                this.cropper = null;
            }
        },

        init() {
            // Listen for reset event from parent
            document.addEventListener('reset-cropper', () => {
                this.fileName = '';
                this.croppedImageUrl = '';
                this.croppedImageData = '';
                this.$refs.fileInput.value = '';
            });
        }
    }
}
</script>
@endpush
