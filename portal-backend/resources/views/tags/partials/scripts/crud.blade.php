{{-- CRUD Operations Module --}}

// Delete Actions
async deleteTag(id) {
    this.closeMenu();

    showConfirm(
        'Hapus Tag?',
        'Tag ini akan dipindahkan ke tong sampah.',
        async () => {
            try {
                showLoading('Menghapus...');
                const response = await fetch(`/tags/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const result = await response.json();
                if (result.success) { this.fetchTags(); showToast('success', result.message); } 
                else { showToast('error', result.message); }
            } catch (error) { console.error('Error:', error); showToast('error', 'Gagal menghapus'); } 
            finally { closeLoading(); }
        },
        { icon: 'warning', confirmText: 'Ya, Hapus!' }
    );
},

async restoreTag(id) {
    this.closeMenu();
    showConfirm(
        'Pulihkan Tag?',
        'Tag akan dikembalikan ke daftar aktif.',
        async () => {
            try {
                showLoading('Memulihkan...');
                const response = await fetch(`/tags/${id}/restore`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const result = await response.json();
                if (result.success) { this.fetchTags(); showToast('success', result.message); } 
                else { showToast('error', result.message); }
            } catch (error) { console.error('Error:', error); showToast('error', 'Gagal memulihkan'); } 
            finally { closeLoading(); }
        },
        { icon: 'info', confirmText: 'Ya, Pulihkan!' }
    );
},

async forceDeleteTag(id) {
    this.closeMenu();
    showConfirm(
        'Hapus Permanen?',
        'Data yang dihapus permanen TIDAK BISA dipulihkan kembali.',
        async () => {
            try {
                showLoading('Menghapus Permanen...');
                const response = await fetch(`/tags/${id}/force`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const result = await response.json();
                if (result.success) { this.fetchTags(); showToast('success', result.message); } 
                else { showToast('error', result.message); }
            } catch (error) { console.error('Error:', error); showToast('error', 'Gagal menghapus'); } 
            finally { closeLoading(); }
        },
        { icon: 'warning', confirmText: 'Hapus Permanen!', cancelText: 'Batal' }
    );
},

// Detail Modal
async viewDetail(id) {
    this.closeMenu();
    try {
        showLoading('Memuat detail...');
        const response = await fetch(`/tags/${id}`);
        const result = await response.json();
        if (result.success) {
            this.selectedTag = result.data;
            this.showDetailModal = true;
            this.$nextTick(() => lucide.createIcons());
        }
    } catch (error) { console.error('Error:', error); showToast('error', 'Gagal memuat detail'); } 
    finally { closeLoading(); }
},

closeDetailModal() {
    this.showDetailModal = false;
    this.selectedTag = null;
},
