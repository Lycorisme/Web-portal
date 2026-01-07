{{-- Form Modal Logic Module --}}

// Form Modal Logic
openCreateModal() {
    this.formMode = 'create';
    this.formData = { id: null, name: '', slug: '', is_active: true };
    this.formErrors = {};
    this.showFormModal = true;
    this.$nextTick(() => lucide.createIcons());
},

openEditModal(tag) {
    this.formMode = 'edit';
    this.formData = {
        id: tag.id, name: tag.name, slug: tag.slug, is_active: tag.is_active
    };
    this.formErrors = {};
    this.showFormModal = true;
    this.closeMenu();
    this.$nextTick(() => lucide.createIcons());
},

closeFormModal() {
    this.showFormModal = false;
    this.formData = { id: null, name: '', slug: '', is_active: true };
    this.formErrors = {};
},

async submitForm() {
    this.formLoading = true;
    this.formErrors = {};

    try {
        const url = this.formMode === 'create' ? '{{ route("tags.store") }}' : `/tags/${this.formData.id}`;
        const method = this.formMode === 'create' ? 'POST' : 'PUT';

        const response = await fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(this.formData),
        });

        const result = await response.json();

        if (response.ok && result.success) {
            this.closeFormModal();
            this.fetchTags();
            showToast('success', result.message);
        } else if (response.status === 422) {
            this.formErrors = result.errors || {};
            showToast('error', 'Mohon periksa kembali data yang diinput.');
        } else {
            showToast('error', result.message || 'Terjadi kesalahan');
        }
    } catch (error) {
        console.error('Error submitting form:', error);
        showToast('error', 'Gagal menyimpan tag');
    } finally {
        this.formLoading = false;
    }
},

// Generate slug from name
generateSlug() {
    if (this.formData.name) {
        this.formData.slug = this.formData.name.toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-').trim();
    }
},
