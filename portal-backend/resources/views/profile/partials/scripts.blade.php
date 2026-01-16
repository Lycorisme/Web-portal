<script>
function profilePage() {
    return {
        // UI State
        showPasswordForm: false,
        showCurrentPassword: false,
        showNewPassword: false,
        showConfirmPassword: false,
        
        // Logout All Devices State
        showLogoutAllModal: false,
        showLogoutPassword: false,
        logoutAllPassword: '',
        isLoggingOutAll: false,
        
        // Photo Upload State
        isUploading: false,
        pendingPhotoFile: null,      // File yang dipilih tapi belum diupload
        pendingPhotoPreview: null,   // URL preview lokal
        originalPhotoSrc: null,      // URL foto original untuk reset
        hasPhoto: {{ $user->profile_photo ? 'true' : 'false' }},
        
        // Cropper State
        showCropModal: false,
        cropper: null,
        cropZoom: 1,
        originalFileForCrop: null,
        
        // Change Detection
        hasChanges: false,
        
        // Form Data
        formData: {
            name: '{{ $user->name ?? "" }}',
            email: '{{ $user->email ?? "" }}',
            phone: '{{ $user->phone ?? "" }}',
            position: '{{ $user->position ?? "" }}',
            bio: `{{ $user->bio ?? "" }}`,
            location: '{{ $user->location ?? "" }}',
        },
        
        // Original Form Data for Reset and Change Detection
        originalFormData: null,
        
        // Password Data
        passwordData: {
            current_password: '',
            password: '',
            password_confirmation: '',
        },
        
        init() {
            // Store original values for reset and change detection
            this.originalFormData = { ...this.formData };
            
            // Store original photo source for reset
            const photoPreview = document.getElementById('profilePhotoPreview');
            if (photoPreview) {
                this.originalPhotoSrc = photoPreview.tagName === 'IMG' ? photoPreview.src : null;
            }
            
            // Re-initialize Lucide icons when needed
            this.$watch('showPasswordForm', () => {
                this.$nextTick(() => lucide.createIcons());
            });
            this.$watch('hasChanges', () => {
                this.$nextTick(() => lucide.createIcons());
            });
            this.$watch('pendingPhotoFile', () => {
                this.$nextTick(() => lucide.createIcons());
            });
            this.$watch('showCropModal', (value) => {
                this.$nextTick(() => lucide.createIcons());
                if (!value && this.cropper) {
                    this.cropper.destroy();
                    this.cropper = null;
                }
            });
        },
        
        // Check if form has changes compared to original
        checkForChanges() {
            if (!this.originalFormData) {
                this.hasChanges = false;
                return;
            }
            
            // Compare each field
            const formChanged = 
                this.formData.name !== this.originalFormData.name ||
                this.formData.email !== this.originalFormData.email ||
                this.formData.phone !== this.originalFormData.phone ||
                this.formData.position !== this.originalFormData.position ||
                this.formData.bio !== this.originalFormData.bio ||
                this.formData.location !== this.originalFormData.location;
            
            // Check if photo has changed
            const photoChanged = this.pendingPhotoFile !== null;
            
            this.hasChanges = formChanged || photoChanged;
        },
        
        // Cancel changes - reset form to original values
        cancelChanges() {
            this.formData = { ...this.originalFormData };
            
            // Reset photo preview
            if (this.pendingPhotoFile) {
                this.pendingPhotoFile = null;
                if (this.pendingPhotoPreview) {
                    URL.revokeObjectURL(this.pendingPhotoPreview);
                    this.pendingPhotoPreview = null;
                }
                
                // Restore original photo
                const photoPreview = document.getElementById('profilePhotoPreview');
                if (photoPreview && this.originalPhotoSrc) {
                    if (photoPreview.tagName === 'IMG') {
                        photoPreview.src = this.originalPhotoSrc;
                    }
                } else if (photoPreview && !this.originalPhotoSrc) {
                    // Restore initial placeholder
                    location.reload();
                    return;
                }
            }
            
            // Clear file input
            const fileInput = document.getElementById('photoInput');
            if (fileInput) fileInput.value = '';
            
            this.hasChanges = false;
            lucide.createIcons();
        },
        
        // Handle file selection - open crop modal
        handleFileSelect(event) {
            const file = event.target.files[0];
            if (!file) return;
            
            // Validate file size (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                showError('File Terlalu Besar', 'Ukuran file maksimal adalah 5MB');
                event.target.value = '';
                return;
            }
            
            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                showError('Format Tidak Didukung', 'Gunakan format JPG, PNG, GIF, atau WebP');
                event.target.value = '';
                return;
            }
            
            // Store file for cropping
            this.originalFileForCrop = file;
            
            // Open crop modal with the file
            this.openCropModalWithFile(file);
        },
        
        // Open crop modal with file
        openCropModalWithFile(file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const imgSrc = e.target.result;
                this.showCropModal = true;
                
                this.$nextTick(() => {
                    const image = document.getElementById('profileCropperImage');
                    if (image) {
                        image.src = imgSrc;
                        this.initCropper(image);
                    }
                    lucide.createIcons();
                });
            };
            reader.readAsDataURL(file);
        },
        
        // Open crop modal for existing photo
        openCropModal() {
            const photoPreview = document.getElementById('profilePhotoPreview');
            if (!photoPreview) return;
            
            let imgSrc;
            if (this.pendingPhotoPreview) {
                imgSrc = this.pendingPhotoPreview;
            } else if (photoPreview.tagName === 'IMG') {
                imgSrc = photoPreview.src;
            } else {
                // No photo to crop
                this.$refs.photoInput.click();
                return;
            }
            
            this.showCropModal = true;
            
            this.$nextTick(() => {
                const image = document.getElementById('profileCropperImage');
                if (image) {
                    image.src = imgSrc;
                    this.initCropper(image);
                }
                lucide.createIcons();
            });
        },
        
        // Initialize Cropper.js
        initCropper(image) {
            if (this.cropper) {
                this.cropper.destroy();
            }
            
            this.cropper = new Cropper(image, {
                aspectRatio: 1, // 1:1 for profile photo
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 0.9,
                restore: false,
                guides: true,
                center: true,
                highlight: true,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
                ready: () => {
                    this.cropZoom = 1;
                }
            });
        },
        
        // Close crop modal
        closeCropModal() {
            this.showCropModal = false;
            if (this.cropper) {
                this.cropper.destroy();
                this.cropper = null;
            }
        },
        
        // Zoom cropper
        zoomCropper(delta) {
            if (this.cropper) {
                this.cropper.zoom(delta);
                const containerData = this.cropper.getContainerData();
                const imageData = this.cropper.getImageData();
                this.cropZoom = Math.max(0.1, Math.min(3, imageData.width / imageData.naturalWidth));
            }
        },
        
        // Set cropper zoom
        setCropperZoom(value) {
            if (this.cropper) {
                const currentZoom = this.cropper.getImageData().width / this.cropper.getImageData().naturalWidth;
                const targetZoom = parseFloat(value);
                this.cropper.zoom(targetZoom / currentZoom - 1);
            }
        },
        
        // Rotate cropper
        rotateCropper(degrees) {
            if (this.cropper) {
                this.cropper.rotate(degrees);
            }
        },
        
        // Reset cropper
        resetCropper() {
            if (this.cropper) {
                this.cropper.reset();
                this.cropZoom = 1;
            }
        },
        
        // Apply crop
        applyCrop() {
            if (!this.cropper) return;
            
            const canvas = this.cropper.getCroppedCanvas({
                width: 500,
                height: 500,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high'
            });
            
            canvas.toBlob((blob) => {
                // Create file from blob
                const fileName = this.originalFileForCrop ? this.originalFileForCrop.name : 'cropped_profile.jpg';
                const file = new File([blob], fileName, { type: 'image/jpeg' });
                
                // Revoke old preview URL if exists
                if (this.pendingPhotoPreview) {
                    URL.revokeObjectURL(this.pendingPhotoPreview);
                }
                
                // Store cropped file
                this.pendingPhotoFile = file;
                
                // Create preview URL
                this.pendingPhotoPreview = URL.createObjectURL(blob);
                
                // Update preview on page
                const photoPreview = document.getElementById('profilePhotoPreview');
                if (photoPreview) {
                    if (photoPreview.tagName === 'IMG') {
                        photoPreview.src = this.pendingPhotoPreview;
                    } else {
                        // Replace initials div with image
                        photoPreview.outerHTML = `<img id="profilePhotoPreview" src="${this.pendingPhotoPreview}" alt="Profile" class="w-full h-full object-cover">`;
                    }
                }
                
                // Update hasPhoto state
                this.hasPhoto = true;
                
                // Mark as changed
                this.hasChanges = true;
                
                // Close modal
                this.closeCropModal();
                
                showToast('success', 'Foto di-crop', 'Klik "Simpan" untuk menyimpan perubahan');
                
                this.$nextTick(() => lucide.createIcons());
            }, 'image/jpeg', 0.9);
        },
        
        // Upload photo to server (called when saving)
        async uploadPhotoToServer() {
            if (!this.pendingPhotoFile) return true; // No photo to upload
            
            const formData = new FormData();
            formData.append('profile_photo', this.pendingPhotoFile);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            
            try {
                const response = await fetch('{{ route("profile.photo.update") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Update header profile photo if exists
                    const headerPhoto = document.getElementById('headerProfilePhoto');
                    if (headerPhoto) {
                        headerPhoto.src = data.photo_url + '?t=' + Date.now();
                    }
                    
                    // Update header initial if it was showing initials
                    const headerInitials = document.getElementById('headerProfileInitials');
                    if (headerInitials && data.photo_url) {
                        headerInitials.outerHTML = `<img id="headerProfilePhoto" src="${data.photo_url}?t=${Date.now()}" alt="Profile" class="w-full h-full object-cover rounded-lg">`;
                    }
                    
                    // Clean up
                    if (this.pendingPhotoPreview) {
                        URL.revokeObjectURL(this.pendingPhotoPreview);
                    }
                    this.pendingPhotoFile = null;
                    this.pendingPhotoPreview = null;
                    this.hasPhoto = true;
                    
                    return true;
                } else {
                    throw new Error(data.message || 'Gagal upload foto');
                }
            } catch (error) {
                console.error('Photo upload error:', error);
                throw error;
            }
        },
        
        // Delete photo
        async deletePhoto() {
            showConfirm(
                'Hapus Foto Profil?',
                'Foto profil akan dihapus secara permanen.',
                async () => {
                    showLoading('Menghapus foto...');
                    
                    try {
                        const response = await fetch('{{ route("profile.photo.delete") }}', {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            showSuccess('Berhasil!', data.message);
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            throw new Error(data.message || 'Gagal menghapus foto');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showError('Gagal!', error.message || 'Terjadi kesalahan saat menghapus foto');
                    }
                },
                { icon: 'warning', confirmText: 'Ya, Hapus!' }
            );
        },
        
        // Save profile info (and photo if changed)
        async saveProfileInfo() {
            showLoading('Menyimpan perubahan...');
            
            try {
                // Upload photo first if there's a pending photo
                if (this.pendingPhotoFile) {
                    await this.uploadPhotoToServer();
                }
                
                // Then update profile info
                const response = await fetch('{{ route("profile.info.update") }}', {
                    method: 'PUT',
                    body: JSON.stringify(this.formData),
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.originalFormData = { ...this.formData };
                    this.hasChanges = false;
                    
                    // Clear file input
                    const fileInput = document.getElementById('photoInput');
                    if (fileInput) fileInput.value = '';
                    
                    showSuccess('Berhasil!', 'Semua perubahan berhasil disimpan!');
                    
                    // Refresh to show updated data
                    setTimeout(() => location.reload(), 1500);
                } else {
                    throw new Error(data.message || 'Gagal menyimpan profil');
                }
            } catch (error) {
                console.error('Error:', error);
                showError('Gagal!', error.message || 'Terjadi kesalahan saat menyimpan perubahan');
            }
        },
        
        // Update password
        async updatePassword() {
            // Validate
            if (!this.passwordData.current_password || !this.passwordData.password || !this.passwordData.password_confirmation) {
                showError('Validasi Gagal', 'Semua field password harus diisi');
                return;
            }
            
            if (this.passwordData.password !== this.passwordData.password_confirmation) {
                showError('Validasi Gagal', 'Konfirmasi password tidak sama');
                return;
            }
            
            if (this.passwordData.password.length < 8) {
                showError('Validasi Gagal', 'Password minimal 8 karakter');
                return;
            }
            
            showLoading('Mengubah password...');
            
            try {
                const response = await fetch('{{ route("profile.password.update") }}', {
                    method: 'PUT',
                    body: JSON.stringify(this.passwordData),
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showPasswordForm = false;
                    this.resetPasswordForm();
                    showSuccess('Berhasil!', data.message);
                } else {
                    throw new Error(data.message || 'Gagal mengubah password');
                }
            } catch (error) {
                console.error('Error:', error);
                showError('Gagal!', error.message || 'Terjadi kesalahan saat mengubah password');
            }
        },
        
        // Reset password form
        resetPasswordForm() {
            this.passwordData = {
                current_password: '',
                password: '',
                password_confirmation: '',
            };
            this.showCurrentPassword = false;
            this.showNewPassword = false;
            this.showConfirmPassword = false;
        },
        
        // Logout from all devices
        async logoutAllDevices() {
            if (!this.logoutAllPassword) {
                showError('Validasi Gagal', 'Password harus diisi untuk konfirmasi');
                return;
            }
            
            this.isLoggingOutAll = true;
            
            try {
                const response = await fetch('{{ route("profile.logout-all-devices") }}', {
                    method: 'POST',
                    body: JSON.stringify({
                        current_password: this.logoutAllPassword
                    }),
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showLogoutAllModal = false;
                    this.logoutAllPassword = '';
                    showSuccess('Berhasil!', data.message);
                    
                    // Refresh icons
                    this.$nextTick(() => lucide.createIcons());
                } else {
                    throw new Error(data.message || 'Gagal keluar dari semua perangkat');
                }
            } catch (error) {
                console.error('Error:', error);
                showError('Gagal!', error.message || 'Terjadi kesalahan');
            } finally {
                this.isLoggingOutAll = false;
            }
        }
    };
}
</script>
