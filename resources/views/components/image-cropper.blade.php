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
        <label class="block text-sm font-medium text-dark-300 mb-2">{{ $label }}</label>
        <div class="flex items-center gap-3">
            <button type="button" @click="$refs.fileInput.click()"
                class="px-4 py-2 bg-gradient-to-r from-brand-600 to-purple-600 hover:from-brand-500 hover:to-purple-500 text-white text-sm font-medium rounded-lg shadow-glow-sm hover:shadow-glow transition-all">
                Choose Image
            </button>
            <span x-text="fileName || 'No file chosen'" class="text-sm text-dark-400"></span>
        </div>
        <input type="file" x-ref="fileInput" @change="loadImage($event)"
            accept="image/jpeg,image/png,image/jpg,image/gif" class="hidden">
    </div>

    <!-- Cropper Modal -->
    <div x-show="showCropper" x-cloak
        class="fixed inset-0 z-[9999] overflow-y-auto"
        aria-labelledby="modal-title" role="dialog" aria-modal="true"
        @click.self="closeCropper">

        <!-- Backdrop -->
        <div class="fixed inset-0 bg-dark-950/90 backdrop-blur-sm transition-opacity"></div>

        <!-- Modal Content -->
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="relative bg-dark-800 border border-dark-700 rounded-xl shadow-2xl max-w-4xl w-full p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-white font-heading">Crop Image</h3>
                    <button type="button" @click="closeCropper" class="text-dark-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Cropper Container -->
                <div class="mb-4 bg-dark-900 rounded-lg p-4">
                    <img x-ref="cropperImage" class="max-w-full" style="max-height: 500px;">
                </div>

                <!-- Controls -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex flex-wrap gap-2">
                        <button type="button" @click="cropper.rotate(-90)"
                            class="px-3 py-2 bg-dark-700 hover:bg-dark-600 text-white text-sm font-medium rounded-lg transition-colors">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M3 12l6.414 6.414a2 2 0 001.414.586H19a2 2 0 002-2V7a2 2 0 00-2-2h-8.172a2 2 0 00-1.414.586L3 12z"/>
                                </svg>
                                Rotate Left
                            </span>
                        </button>
                        <button type="button" @click="cropper.rotate(90)"
                            class="px-3 py-2 bg-dark-700 hover:bg-dark-600 text-white text-sm font-medium rounded-lg transition-colors">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M3 12l6.414 6.414a2 2 0 001.414.586H19a2 2 0 002-2V7a2 2 0 00-2-2h-8.172a2 2 0 00-1.414.586L3 12z"/>
                                </svg>
                                Rotate Right
                            </span>
                        </button>
                        <button type="button" @click="cropper.scaleX(cropper.getData().scaleX === -1 ? 1 : -1)"
                            class="px-3 py-2 bg-dark-700 hover:bg-dark-600 text-white text-sm font-medium rounded-lg transition-colors">
                            Flip Horizontal
                        </button>
                        <button type="button" @click="cropper.reset()"
                            class="px-3 py-2 bg-dark-700 hover:bg-dark-600 text-white text-sm font-medium rounded-lg transition-colors">
                            Reset
                        </button>
                    </div>

                    <div class="flex gap-2">
                        <button type="button" @click="closeCropper"
                            class="px-4 py-2 bg-dark-700 hover:bg-dark-600 text-white text-sm font-medium rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button type="button" @click="cropImage"
                            class="px-4 py-2 bg-gradient-to-r from-brand-600 to-purple-600 hover:from-brand-500 hover:to-purple-500 text-white text-sm font-medium rounded-lg shadow-glow-sm hover:shadow-glow transition-all">
                            Crop & Save
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
