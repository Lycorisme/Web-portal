{{-- CRUD Operations Module --}}

// Delete Actions
async deleteCategory(id) {
    this.closeMenu();

    showConfirm(
        'Hapus Kategori?',
        'Kategori ini akan dipindahkan ke tong sampah.',
        async () => {
            try {
                showLoading('Menghapus...');
                const response = await fetch(`/categories/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const result = await response.json();
                if (result.success) { this.fetchCategories(); showToast('success', result.message); } 
                else { showToast('error', result.message); }
            } catch (error) { console.error('Error:', error); showToast('error', 'Gagal menghapus'); } 
            finally { closeLoading(); }
        },
        { icon: 'warning', confirmText: 'Ya, Hapus!' }
    );
},

async restoreCategory(id) {
    this.closeMenu();
    showConfirm(
        'Pulihkan Kategori?',
        'Kategori akan dikembalikan ke daftar aktif.',
        async () => {
            try {
                showLoading('Memulihkan...');
                const response = await fetch(`/categories/${id}/restore`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const result = await response.json();
                if (result.success) { this.fetchCategories(); showToast('success', result.message); } 
                else { showToast('error', result.message); }
            } catch (error) { console.error('Error:', error); showToast('error', 'Gagal memulihkan'); } 
            finally { closeLoading(); }
        },
        { icon: 'info', confirmText: 'Ya, Pulihkan!' }
    );
},

async forceDeleteCategory(id) {
    this.closeMenu();
    showConfirm(
        'Hapus Permanen?',
        'Data yang dihapus permanen TIDAK BISA dipulihkan kembali.',
        async () => {
            try {
                showLoading('Menghapus Permanen...');
                const response = await fetch(`/categories/${id}/force`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const result = await response.json();
                if (result.success) { this.fetchCategories(); showToast('success', result.message); } 
                else { showToast('error', result.message); }
            } catch (error) { console.error('Error:', error); showToast('error', 'Gagal menghapus'); } 
            finally { closeLoading(); }
        },
        { icon: 'warning', confirmText: 'Hapus Permanen!', cancelText: 'Batal' }
    );
},

// Toggle Active Status
async toggleActive(category) {
    try {
        const response = await fetch(`/categories/${category.id}/toggle-active`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        });
        const result = await response.json();

        if (result.success) {
            const idx = this.categories.findIndex(c => c.id === category.id);
            if (idx !== -1) { this.categories[idx].is_active = result.is_active; }
            showToast('success', result.message);
        } else { showToast('error', result.message); }
    } catch (error) { console.error('Error:', error); showToast('error', 'Gagal mengubah status'); }
},

// Detail Modal
async viewDetail(id) {
    this.closeMenu();
    try {
        showLoading('Memuat detail...');
        const response = await fetch(`/categories/${id}`);
        const result = await response.json();
        if (result.success) {
            this.selectedCategory = result.data;
            this.showDetailModal = true;
            this.$nextTick(() => lucide.createIcons());
        }
    } catch (error) { console.error('Error:', error); showToast('error', 'Gagal memuat detail'); } 
    finally { closeLoading(); }
},

closeDetailModal() {
    this.showDetailModal = false;
    this.selectedCategory = null;
},
