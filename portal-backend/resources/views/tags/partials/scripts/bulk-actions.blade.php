{{-- Bulk Actions Module --}}

// Selection
toggleSelectAll() {
    if (this.selectAll) {
        this.selectedIds = this.tags.map(t => t.id);
    } else {
        this.selectedIds = [];
    }
},

// Bulk Actions
async bulkDelete() {
    if (this.selectedIds.length === 0) {
        showToast('warning', 'Pilih tag yang ingin dihapus');
        return;
    }

    showConfirm(
        `Hapus ${this.selectedIds.length} Tag?`,
        'Semua tag yang dipilih akan dipindahkan ke tong sampah.',
        async () => {
            try {
                showLoading('Menghapus...');
                const response = await fetch('{{ route("tags.bulk-destroy") }}', {
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
                    this.fetchTags();
                    showToast('success', result.message);
                    window.dispatchEvent(new CustomEvent('trash-updated'));
                } else {
                    showToast('error', result.message);
                }
            } catch (error) {
                console.error('Error bulk deleting:', error);
                showToast('error', 'Gagal menghapus tag');
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
        `Pulihkan ${this.selectedIds.length} Tag?`,
        'Item terpilih akan dikembalikan ke daftar aktif.',
        async () => {
            try {
                showLoading('Memulihkan...');
                const response = await fetch('{{ route("tags.bulk-restore") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ ids: this.selectedIds }),
                });
                const result = await response.json();
                if (result.success) { this.fetchTags(); showToast('success', result.message); window.dispatchEvent(new CustomEvent('trash-updated')); } 
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
        `Hapus Permanen ${this.selectedIds.length} Tag?`,
        'PERINGATAN: Data akan hilang selamanya!',
        async () => {
            try {
                showLoading('Menghapus Permanen...');
                const response = await fetch('{{ route("tags.bulk-force-delete") }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ ids: this.selectedIds }),
                });
                const result = await response.json();
                if (result.success) { this.fetchTags(); showToast('success', result.message); window.dispatchEvent(new CustomEvent('trash-updated')); } 
                else { showToast('error', result.message); }
            } catch (error) { console.error('Error:', error); showToast('error', 'Gagal menghapus'); } 
            finally { closeLoading(); }
        },
        { icon: 'warning', confirmText: 'Ya, Hapus Permanen!' }
    );
},
