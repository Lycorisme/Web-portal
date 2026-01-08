{{-- Form Modal Logic Module --}}

// Form Modal Logic
openCreateModal() {
    this.formMode = 'create';
    this.formData = {
        id: null,
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        role: 'author',
        phone: '',
        position: '',
        bio: '',
        location: '',
        profile_photo: null,
        profile_photo_preview: null,
    };
    this.formErrors = {};
    this.showPassword = false;
    this.showFormModal = true;
    this.$nextTick(() => lucide.createIcons());
},

openEditModal(user) {
    this.formMode = 'edit';
    this.formData = {
        id: user.id,
        name: user.name,
        email: user.email,
        password: '',
        password_confirmation: '',
        role: user.role,
        phone: user.phone || '',
        position: user.position || '',
        bio: user.bio || '',
        location: user.location || '',
        profile_photo: null,
        profile_photo_preview: user.profile_photo,
    };
    this.formErrors = {};
    this.showPassword = false;
    this.showFormModal = true;
    this.closeMenu();
    this.$nextTick(() => lucide.createIcons());
},

closeFormModal() {
    this.showFormModal = false;
    this.formData = {
        id: null,
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        role: 'author',
        phone: '',
        position: '',
        bio: '',
        location: '',
        profile_photo: null,
        profile_photo_preview: null,
    };
    this.formErrors = {};
},

handlePhotoUpload(event) {
    const file = event.target.files[0];
    if (file) {
        this.formData.profile_photo = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            this.formData.profile_photo_preview = e.target.result;
        };
        reader.readAsDataURL(file);
    }
},

async submitForm() {
    this.formLoading = true;
    this.formErrors = {};

    try {
        const url = this.formMode === 'create' ? '{{ route("users.store") }}' : `/users/${this.formData.id}`;
        
        // Use FormData for file upload
        const formData = new FormData();
        formData.append('name', this.formData.name);
        formData.append('email', this.formData.email);
        formData.append('role', this.formData.role);
        formData.append('phone', this.formData.phone || '');
        formData.append('position', this.formData.position || '');
        formData.append('bio', this.formData.bio || '');
        formData.append('location', this.formData.location || '');
        
        if (this.formData.password) {
            formData.append('password', this.formData.password);
            formData.append('password_confirmation', this.formData.password_confirmation);
        }
        
        if (this.formData.profile_photo) {
            formData.append('profile_photo', this.formData.profile_photo);
        }

        // For PUT requests, we need to use POST with _method override
        if (this.formMode === 'edit') {
            formData.append('_method', 'PUT');
        }

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: formData,
        });

        const result = await response.json();

        if (response.ok && result.success) {
            this.closeFormModal();
            this.fetchUsers();
            showToast('success', result.message);
        } else if (response.status === 422) {
            this.formErrors = result.errors || {};
            showToast('error', 'Mohon periksa kembali data yang diinput.');
        } else {
            showToast('error', result.message || 'Terjadi kesalahan');
        }
    } catch (error) {
        console.error('Error submitting form:', error);
        showToast('error', 'Gagal menyimpan user');
    } finally {
        this.formLoading = false;
    }
},
