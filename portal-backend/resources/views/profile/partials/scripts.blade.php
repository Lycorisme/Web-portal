<script>
function profilePage() {
    return {
        // UI State
        editMode: false,
        showPhotoModal: false,
        showPasswordForm: false,
        showCurrentPassword: false,
        showNewPassword: false,
        showConfirmPassword: false,
        
        // Photo Upload State
        dragging: false,
        selectedFile: null,
        selectedFileName: '',
        selectedFileSize: '',
        previewUrl: null,
        
        // Form Data
        formData: {
            name: '{{ $user->name ?? "" }}',
            email: '{{ $user->email ?? "" }}',
            phone: '{{ $user->phone ?? "" }}',
            position: '{{ $user->position ?? "" }}',
            bio: `{{ $user->bio ?? "" }}`,
            location: '{{ $user->location ?? "" }}',
        },
        
        // Original Form Data for Reset
        originalFormData: null,
        
        // Password Data
        passwordData: {
            current_password: '',
            password: '',
            password_confirmation: '',
        },
        
        init() {
            // Store original values for reset
            this.originalFormData = { ...this.formData };
            
            // Re-initialize Lucide icons when modals open
            this.$watch('showPhotoModal', () => {
                this.$nextTick(() => lucide.createIcons());
            });
            this.$watch('showPasswordForm', () => {
                this.$nextTick(() => lucide.createIcons());
            });
            this.$watch('editMode', () => {
                this.$nextTick(() => lucide.createIcons());
            });
        },
        
        // Handle file drop
        handleDrop(event) {
            this.dragging = false;
            const file = event.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                this.processFile(file);
            }
        },
        
        // Handle file selection
        handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                this.processFile(file);
            }
        },
        
        // Process selected file
        processFile(file) {
            // Validate file size (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                showError('File Terlalu Besar', 'Ukuran file maksimal adalah 5MB');
                return;
            }
            
            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                showError('Format Tidak Didukung', 'Gunakan format JPG, PNG, GIF, atau WebP');
                return;
            }
            
            this.selectedFile = file;
            this.selectedFileName = file.name;
            this.selectedFileSize = this.formatFileSize(file.size);
            
            // Create preview
            const reader = new FileReader();
            reader.onload = (e) => {
                this.previewUrl = e.target.result;
            };
            reader.readAsDataURL(file);
        },
        
        // Format file size
        formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },
        
        // Clear preview
        clearPreview() {
            this.selectedFile = null;
            this.selectedFileName = '';
            this.selectedFileSize = '';
            this.previewUrl = null;
            document.getElementById('photoInput').value = '';
        },
        
        // Upload photo
        async uploadPhoto() {
            if (!this.selectedFile) return;
            
            showLoading('Mengupload foto...');
            
            const formData = new FormData();
            formData.append('profile_photo', this.selectedFile);
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
                    // Update preview on page
                    const photoPreview = document.getElementById('profilePhotoPreview');
                    if (photoPreview) {
                        if (photoPreview.tagName === 'IMG') {
                            photoPreview.src = data.photo_url;
                        } else {
                            photoPreview.innerHTML = `<img src="${data.photo_url}" alt="Profile" class="w-full h-full object-cover">`;
                        }
                    }
                    
                    this.showPhotoModal = false;
                    this.clearPreview();
                    
                    showSuccess('Berhasil!', data.message);
                    
                    // Refresh page after delay to show updated UI
                    setTimeout(() => location.reload(), 1500);
                } else {
                    throw new Error(data.message || 'Gagal upload foto');
                }
            } catch (error) {
                console.error('Error:', error);
                showError('Gagal!', error.message || 'Terjadi kesalahan saat upload foto');
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
        
        // Save profile info
        async saveProfileInfo() {
            showLoading('Menyimpan perubahan...');
            
            try {
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
                    this.editMode = false;
                    showSuccess('Berhasil!', data.message);
                    
                    // Refresh to show updated data
                    setTimeout(() => location.reload(), 1500);
                } else {
                    throw new Error(data.message || 'Gagal menyimpan profil');
                }
            } catch (error) {
                console.error('Error:', error);
                showError('Gagal!', error.message || 'Terjadi kesalahan saat menyimpan profil');
            }
        },
        
        // Reset form
        resetForm() {
            this.formData = { ...this.originalFormData };
            lucide.createIcons();
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
        }
    };
}
</script>
