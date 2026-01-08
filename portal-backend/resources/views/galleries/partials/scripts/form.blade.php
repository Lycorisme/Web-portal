{{-- Form Modal Logic Module --}}

// Form Modal Logic
openCreateModal() {
    this.formMode = 'create';
    this.formData = {
        id: null,
        title: '',
        description: '',
        media_type: 'image',
        video_url: '',
        album: '',
        event_date: '',
        location: '',
        is_featured: false,
        is_published: true,
    };
    this.imageFiles = [];
    this.imagePreviews = [];
    this.formErrors = {};
    this.showFormModal = true;
    this.$nextTick(() => lucide.createIcons());
},

openEditModal(item) {
    this.formMode = 'edit';
    this.formData = {
        id: item.id,
        title: item.title,
        description: item.description || '',
        media_type: item.media_type,
        video_url: item.video_url || '',
        album: item.album || '',
        event_date: item.event_date_raw || '',
        location: item.location || '',
        is_featured: item.is_featured,
        is_published: item.is_published,
    };
    this.imageFiles = [];
    // For edit mode, show existing image as single preview
    this.imagePreviews = item.image_url ? [{ url: item.image_url, isExisting: true }] : [];
    this.formErrors = {};
    this.showFormModal = true;
    this.closeMenu();
    this.$nextTick(() => lucide.createIcons());
},

closeFormModal() {
    this.showFormModal = false;
    this.formData = {
        id: null,
        title: '',
        description: '',
        media_type: 'image',
        video_url: '',
        album: '',
        event_date: '',
        location: '',
        is_featured: false,
        is_published: true,
    };
    this.imageFiles = [];
    this.imagePreviews = [];
    this.formErrors = {};
    this.showAlbumDropdown = false;
},

handleMultipleImageUpload(event) {
    const files = Array.from(event.target.files);
    
    // Check max limit
    const currentCount = this.imageFiles.length;
    const maxAllowed = 20;
    
    if (currentCount + files.length > maxAllowed) {
        showToast('error', `Maksimal ${maxAllowed} gambar. Anda sudah memiliki ${currentCount} gambar.`);
        return;
    }
    
    files.forEach(file => {
        // Validate file size (max 10MB)
        if (file.size > 10 * 1024 * 1024) {
            showToast('error', `File "${file.name}" melebihi 10MB`);
            return;
        }
        
        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            showToast('error', `File "${file.name}" bukan tipe gambar yang didukung`);
            return;
        }
        
        this.imageFiles.push(file);
        
        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
            this.imagePreviews.push({
                url: e.target.result,
                name: file.name,
                isExisting: false
            });
        };
        reader.readAsDataURL(file);
    });
    
    // Reset input to allow selecting same files again
    event.target.value = '';
},

// For edit mode - single image upload
handleSingleImageUpload(event) {
    const file = event.target.files[0];
    if (file) {
        // Validate file size (max 10MB)
        if (file.size > 10 * 1024 * 1024) {
            showToast('error', 'Ukuran file maksimal 10MB');
            return;
        }
        
        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            showToast('error', 'Tipe file tidak didukung');
            return;
        }
        
        this.imageFiles = [file];
        
        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
            this.imagePreviews = [{
                url: e.target.result,
                name: file.name,
                isExisting: false
            }];
        };
        reader.readAsDataURL(file);
    }
},

removeImageAt(index) {
    // Check if it's an existing image (edit mode)
    const preview = this.imagePreviews[index];
    if (preview && preview.isExisting) {
        this.imagePreviews.splice(index, 1);
        return;
    }
    
    // Find the corresponding file index
    let fileIndex = 0;
    for (let i = 0; i < index; i++) {
        if (!this.imagePreviews[i].isExisting) {
            fileIndex++;
        }
    }
    
    this.imageFiles.splice(fileIndex, 1);
    this.imagePreviews.splice(index, 1);
},

clearAllImages() {
    this.imageFiles = [];
    this.imagePreviews = [];
},

async submitForm() {
    this.formLoading = true;
    this.formErrors = {};

    try {
        let url, formDataObj;
        
        if (this.formMode === 'create' && this.formData.media_type === 'image' && this.imageFiles.length > 1) {
            // Bulk upload for multiple images
            url = '{{ route("galleries.bulk-store") }}';
            formDataObj = new FormData();
            formDataObj.append('title', this.formData.title);
            formDataObj.append('description', this.formData.description || '');
            formDataObj.append('album', this.formData.album || '');
            formDataObj.append('event_date', this.formData.event_date || '');
            formDataObj.append('location', this.formData.location || '');
            formDataObj.append('is_featured', this.formData.is_featured ? '1' : '0');
            formDataObj.append('is_published', this.formData.is_published ? '1' : '0');
            
            // Append all images
            this.imageFiles.forEach((file, index) => {
                formDataObj.append(`images[${index}]`, file);
            });
        } else {
            // Single image upload (create with 1 image or edit mode)
            url = this.formMode === 'create' ? '{{ route("galleries.store") }}' : `/galleries/${this.formData.id}`;
            
            formDataObj = new FormData();
            formDataObj.append('title', this.formData.title);
            formDataObj.append('description', this.formData.description || '');
            formDataObj.append('media_type', this.formData.media_type);
            formDataObj.append('video_url', this.formData.video_url || '');
            formDataObj.append('album', this.formData.album || '');
            formDataObj.append('event_date', this.formData.event_date || '');
            formDataObj.append('location', this.formData.location || '');
            formDataObj.append('is_featured', this.formData.is_featured ? '1' : '0');
            formDataObj.append('is_published', this.formData.is_published ? '1' : '0');
            
            if (this.imageFiles.length > 0) {
                formDataObj.append('image', this.imageFiles[0]);
            }
            
            // For update, we need to use POST with _method override
            if (this.formMode === 'edit') {
                formDataObj.append('_method', 'PUT');
            }
        }

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: formDataObj,
        });

        const result = await response.json();

        if (response.ok && result.success) {
            this.closeFormModal();
            this.fetchGalleries();
            this.fetchAlbums(); // Refresh albums list
            showToast('success', result.message);
        } else if (response.status === 422) {
            this.formErrors = result.errors || {};
            showToast('error', 'Mohon periksa kembali data yang diinput.');
        } else {
            showToast('error', result.message || 'Terjadi kesalahan');
        }
    } catch (error) {
        console.error('Error submitting form:', error);
        showToast('error', 'Gagal menyimpan galeri');
    } finally {
        this.formLoading = false;
    }
},
