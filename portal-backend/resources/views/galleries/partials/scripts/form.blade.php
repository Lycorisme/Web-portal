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
    this.imageFile = null;
    this.imagePreview = null;
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
    this.imageFile = null;
    this.imagePreview = item.image_url || null;
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
    this.imageFile = null;
    this.imagePreview = null;
    this.formErrors = {};
},

handleImageUpload(event) {
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
        
        this.imageFile = file;
        
        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
            this.imagePreview = e.target.result;
        };
        reader.readAsDataURL(file);
    }
},

removeImage() {
    this.imageFile = null;
    this.imagePreview = null;
},

async submitForm() {
    this.formLoading = true;
    this.formErrors = {};

    try {
        const url = this.formMode === 'create' ? '{{ route("galleries.store") }}' : `/galleries/${this.formData.id}`;
        
        const formDataObj = new FormData();
        formDataObj.append('title', this.formData.title);
        formDataObj.append('description', this.formData.description || '');
        formDataObj.append('media_type', this.formData.media_type);
        formDataObj.append('video_url', this.formData.video_url || '');
        formDataObj.append('album', this.formData.album || '');
        formDataObj.append('event_date', this.formData.event_date || '');
        formDataObj.append('location', this.formData.location || '');
        formDataObj.append('is_featured', this.formData.is_featured ? '1' : '0');
        formDataObj.append('is_published', this.formData.is_published ? '1' : '0');
        
        if (this.imageFile) {
            formDataObj.append('image', this.imageFile);
        }
        
        // For update, we need to use POST with _method override
        if (this.formMode === 'edit') {
            formDataObj.append('_method', 'PUT');
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
