{{-- Bulk Actions Module --}}

// Selection - hanya mempengaruhi item di halaman saat ini
toggleSelectAll() {
    const currentPageIds = this.categories.map(c => c.id);
    
    if (this.selectAll) {
        // Jika semua sudah terpilih, hapus dari selection
        this.selectedIds = this.selectedIds.filter(id => !currentPageIds.includes(id));
    } else {
        // Jika belum semua terpilih, tambahkan ke selection
        currentPageIds.forEach(id => {
            if (!this.selectedIds.includes(id)) {
                this.selectedIds.push(id);
            }
        });
    }
},

// Bulk Actions
async bulkDelete() {
    if (this.selectedIds.length === 0) {
        showToast('warning', 'Pilih kategori yang ingin dihapus');
        return;
    }

    showConfirm(
        `Hapus ${this.selectedIds.length} Kategori?`,
        'Semua kategori yang dipilih akan dipindahkan ke tong sampah.',
        async () => {
            try {
                showLoading('Menghapus...');
                const response = await fetch('{{ route("categories.bulk-destroy") }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ ids: this.selectedIds }),
                });
                const result = await response.json();

                if (result.success) {
                    this.fetchCategories();
                    showToast('success', result.message);
                    window.dispatchEvent(new CustomEvent('trash-updated'));
                } else {
                    showToast('error', result.message);
                }
            } catch (error) {
                console.error('Error bulk deleting:', error);
                showToast('error', 'Gagal menghapus kategori');
            } finally {
                closeLoading();
            }
        },
        { icon: 'warning', confirmText: 'Ya, Hapus Semua!' }
    );
},

async bulkRestore() {
    if (this.selectedIds.length === 0) return;

    showConfirm(
        `Pulihkan ${this.selectedIds.length} Kategori?`,
        'Item terpilih akan dikembalikan ke daftar aktif.',
        async () => {
            try {
                showLoading('Memulihkan...');
                const response = await fetch('{{ route("categories.bulk-restore") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ ids: this.selectedIds }),
                });
                const result = await response.json();
                if (result.success) { this.fetchCategories(); showToast('success', result.message); window.dispatchEvent(new CustomEvent('trash-updated')); } 
                else { showToast('error', result.message); }
            } catch (error) { console.error('Error:', error); showToast('error', 'Gagal memulihkan'); } 
            finally { closeLoading(); }
        },
        { icon: 'info', confirmText: 'Ya, Pulihkan Semua!' }
    );
},

async bulkForceDelete() {
    if (this.selectedIds.length === 0) return;

    showConfirm(
        `Hapus Permanen ${this.selectedIds.length} Kategori?`,
        'PERINGATAN: Data akan hilang selamanya!',
        async () => {
            try {
                showLoading('Menghapus Permanen...');
                const response = await fetch('{{ route("categories.bulk-force-delete") }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ ids: this.selectedIds }),
                });
                const result = await response.json();
                if (result.success) { this.fetchCategories(); showToast('success', result.message); window.dispatchEvent(new CustomEvent('trash-updated')); } 
                else { showToast('error', result.message); }
            } catch (error) { console.error('Error:', error); showToast('error', 'Gagal menghapus'); } 
            finally { closeLoading(); }
        },
        { icon: 'warning', confirmText: 'Ya, Hapus Permanen!' }
    );
},
