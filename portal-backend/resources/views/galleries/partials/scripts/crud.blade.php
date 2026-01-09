{{-- CRUD Operations Module --}}

// Delete Actions
async deleteItem(id) {
    this.closeMenu();

    showConfirm(
        'Hapus Item Galeri?',
        'Item ini akan dipindahkan ke tong sampah.',
        async () => {
            try {
                showLoading('Menghapus...');
                const response = await fetch(`/galleries/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const result = await response.json();
                if (result.success) { this.fetchGalleries(); showToast('success', result.message); window.dispatchEvent(new CustomEvent('trash-updated')); } 
                else { showToast('error', result.message); }
            } catch (error) { console.error('Error:', error); showToast('error', 'Gagal menghapus'); } 
            finally { closeLoading(); }
        },
        { icon: 'warning', confirmText: 'Ya, Hapus!' }
    );
},

async restoreItem(id) {
    this.closeMenu();
    showConfirm(
        'Pulihkan Item Galeri?',
        'Item akan dikembalikan ke daftar aktif.',
        async () => {
            try {
                showLoading('Memulihkan...');
                const response = await fetch(`/galleries/${id}/restore`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const result = await response.json();
                if (result.success) { this.fetchGalleries(); showToast('success', result.message); window.dispatchEvent(new CustomEvent('trash-updated')); } 
                else { showToast('error', result.message); }
            } catch (error) { console.error('Error:', error); showToast('error', 'Gagal memulihkan'); } 
            finally { closeLoading(); }
        },
        { icon: 'info', confirmText: 'Ya, Pulihkan!' }
    );
},

async forceDeleteItem(id) {
    this.closeMenu();
    showConfirm(
        'Hapus Permanen?',
        'Data yang dihapus permanen TIDAK BISA dipulihkan kembali. File gambar juga akan dihapus.',
        async () => {
            try {
                showLoading('Menghapus Permanen...');
                const response = await fetch(`/galleries/${id}/force`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const result = await response.json();
                if (result.success) { this.fetchGalleries(); showToast('success', result.message); window.dispatchEvent(new CustomEvent('trash-updated')); } 
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
        const response = await fetch(`/galleries/${id}`);
        const result = await response.json();
        if (result.success) {
            this.selectedItem = result.data;
            this.showDetailModal = true;
            this.$nextTick(() => lucide.createIcons());
        }
    } catch (error) { console.error('Error:', error); showToast('error', 'Gagal memuat detail'); } 
    finally { closeLoading(); }
},

closeDetailModal() {
    this.showDetailModal = false;
    this.selectedItem = null;
},
