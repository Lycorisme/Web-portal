{{-- Cropper Module - Image Cropping Logic --}}

// Open Crop Modal
openCropModal(file) {
    this.originalImageFile = file;
    this.showCropModal = true;
    
    // Wait for modal to render
    this.$nextTick(() => {
        const image = document.getElementById('cropperImage');
        if (image) {
            // Create object URL for the file
            const objectUrl = URL.createObjectURL(file);
            image.src = objectUrl;
            
            // Wait for image to load before initializing cropper
            image.onload = () => {
                this.initCropper(image);
                lucide.createIcons();
            };
        }
    });
},

// Open Crop Modal from URL (for existing images)
async openCropModalFromUrl() {
    // If we have the original file, use it directly
    if (this.originalImageFile) {
        this.openCropModal(this.originalImageFile);
        return;
    }
    
    // Otherwise, fetch the image from URL
    const thumbnailUrl = this.formData.thumbnail_url;
    if (!thumbnailUrl) {
        showToast('error', 'Tidak ada gambar untuk di-crop');
        return;
    }
    
    try {
        showToast('info', 'Memuat gambar untuk crop...');
        
        // Fetch image from URL
        const response = await fetch(thumbnailUrl);
        if (!response.ok) throw new Error('Failed to fetch image');
        
        const blob = await response.blob();
        const fileName = thumbnailUrl.split('/').pop() || 'thumbnail.jpg';
        const file = new File([blob], fileName, { type: blob.type });
        
        // Store as original file and open crop modal
        this.originalImageFile = file;
        this.openCropModal(file);
    } catch (error) {
        console.error('Error loading image for crop:', error);
        showToast('error', 'Gagal memuat gambar untuk crop');
    }
},

// Initialize Cropper Instance
initCropper(imageElement) {
    // Destroy existing instance if any
    if (this.cropperInstance) {
        this.cropperInstance.destroy();
        this.cropperInstance = null;
    }
    
    this.cropZoom = 1;
    
    // Initialize Cropper.js with 16:9 aspect ratio
    this.cropperInstance = new Cropper(imageElement, {
        aspectRatio: 16 / 9,
        viewMode: 1,
        dragMode: 'move',
        autoCropArea: 0.9,
        responsive: true,
        restore: false,
        guides: true,
        center: true,
        highlight: true,
        cropBoxMovable: true,
        cropBoxResizable: true,
        toggleDragModeOnDblclick: false,
        minContainerWidth: 300,
        minContainerHeight: 200,
        background: true,
        ready: () => {
            // Cropper is ready
            console.log('Cropper initialized');
        }
    });
},

// Close Crop Modal
closeCropModal() {
    if (this.cropperInstance) {
        this.cropperInstance.destroy();
        this.cropperInstance = null;
    }
    this.showCropModal = false;
    this.cropZoom = 1;
},

// Apply Crop and Convert to Blob
applyCrop() {
    if (!this.cropperInstance) return;
    
    // Get cropped canvas with optimal dimensions
    const canvas = this.cropperInstance.getCroppedCanvas({
        width: 1280,
        height: 720,
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'high',
    });
    
    if (!canvas) {
        showToast('error', 'Gagal memproses gambar');
        return;
    }
    
    // Convert canvas to blob
    canvas.toBlob((blob) => {
        if (blob) {
            // Create a File from the Blob
            const fileName = this.originalImageFile ? this.originalImageFile.name : 'cropped-thumbnail.jpg';
            const croppedFile = new File([blob], fileName, {
                type: 'image/jpeg',
                lastModified: Date.now()
            });
            
            // Set the cropped file to formData
            this.formData.thumbnail = croppedFile;
            this.formData.thumbnail_url = URL.createObjectURL(blob);
            
            // Close modal
            this.closeCropModal();
            
            showToast('success', 'Gambar berhasil di-crop');
            
            // Refresh icons
            this.$nextTick(() => {
                lucide.createIcons();
            });
        } else {
            showToast('error', 'Gagal mengkonversi gambar');
        }
    }, 'image/jpeg', 0.92); // JPEG with 92% quality for good balance
},

// Zoom Controls
zoomCropper(delta) {
    if (this.cropperInstance) {
        this.cropperInstance.zoom(delta);
        // Update zoom slider (approximate)
        const containerData = this.cropperInstance.getContainerData();
        const imageData = this.cropperInstance.getImageData();
        this.cropZoom = Math.min(3, Math.max(0.1, imageData.width / imageData.naturalWidth));
    }
},

setCropperZoom(value) {
    if (this.cropperInstance) {
        const currentZoom = this.cropperInstance.getImageData().width / this.cropperInstance.getImageData().naturalWidth;
        const targetZoom = parseFloat(value);
        const delta = targetZoom / currentZoom;
        this.cropperInstance.zoomTo(targetZoom);
    }
},

// Rotate Controls
rotateCropper(degree) {
    if (this.cropperInstance) {
        this.cropperInstance.rotate(degree);
    }
},

// Reset Cropper
resetCropper() {
    if (this.cropperInstance) {
        this.cropperInstance.reset();
        this.cropZoom = 1;
    }
},
