{{-- Form Modal Logic Module --}}

// Form Modal Logic
openCreateModal() {
    this.formMode = 'create';
    this.formData = { id: null, name: '', slug: '', description: '', color: '#6366f1', icon: 'folder', sort_order: 0, is_active: true };
    this.formErrors = {};
    this.showFormModal = true;
    this.$nextTick(() => lucide.createIcons());
},

openEditModal(category) {
    this.formMode = 'edit';
    this.formData = {
        id: category.id, name: category.name, slug: category.slug,
        description: category.description || '', color: category.color || '#6366f1',
        icon: category.icon || 'folder', sort_order: category.sort_order, is_active: category.is_active,
    };
    this.formErrors = {};
    this.showFormModal = true;
    this.closeMenu();
    this.$nextTick(() => lucide.createIcons());
},

closeFormModal() {
    this.showFormModal = false;
    this.formData = { id: null, name: '', slug: '', description: '', color: '#6366f1', icon: 'folder', sort_order: 0, is_active: true };
    this.formErrors = {};
},

async submitForm() {
    this.formLoading = true;
    this.formErrors = {};

    try {
        const url = this.formMode === 'create' ? '{{ route("categories.store") }}' : `/categories/${this.formData.id}`;
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
            this.fetchCategories();
            showToast('success', result.message);
        } else if (response.status === 422) {
            this.formErrors = result.errors || {};
            showToast('error', 'Mohon periksa kembali data yang diinput.');
        } else {
            showToast('error', result.message || 'Terjadi kesalahan');
        }
    } catch (error) {
        console.error('Error submitting form:', error);
        showToast('error', 'Gagal menyimpan kategori');
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
