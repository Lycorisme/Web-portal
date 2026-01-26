{{-- Bulk Actions Module --}}

// Selection - hanya mempengaruhi item di halaman saat ini
toggleSelectAll() {
    const currentPageIds = this.articles.map(a => a.id);
    
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
        showToast('warning', 'Pilih berita yang ingin dihapus');
        return;
    }

    showConfirm(
        `Hapus ${this.selectedIds.length} Berita?`,
        'Semua berita yang dipilih akan dipindahkan ke tong sampah.',
        async () => {
            try {
                showLoading('Menghapus...');
                const response = await fetch('{{ route("articles.bulk-destroy") }}', {
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
                    this.selectedIds = [];
                    this.fetchArticles();
                    showToast('success', result.message);
                    window.dispatchEvent(new CustomEvent('trash-updated'));
                } else {
                    showToast('error', result.message);
                }
            } catch (error) {
                console.error('Error bulk deleting:', error);
                showToast('error', 'Gagal menghapus berita');
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
        `Pulihkan ${this.selectedIds.length} Berita?`,
        'Item terpilih akan dikembalikan ke daftar aktif.',
        async () => {
            try {
                showLoading('Memulihkan...');
                const response = await fetch('{{ route("articles.bulk-restore") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ ids: this.selectedIds }),
                });
                const result = await response.json();

                if (result.success) {
                    this.selectedIds = [];
                    this.fetchArticles();
                    showToast('success', result.message);
                    window.dispatchEvent(new CustomEvent('trash-updated'));
                } else {
                    showToast('error', result.message);
                }
            } catch (error) {
                console.error('Error bulk restoring:', error);
                showToast('error', 'Gagal memulihkan berita');
            } finally {
                closeLoading();
            }
        },
        { icon: 'info', confirmText: 'Ya, Pulihkan Semua!' }
    );
},

async bulkForceDelete() {
    if (this.selectedIds.length === 0) return;

    showConfirm(
        `Hapus Permanen ${this.selectedIds.length} Berita?`,
        'PERINGATAN: Data akan hilang selamanya dan tidak bisa dikembalikan!',
        async () => {
            try {
                showLoading('Menghapus Permanen...');
                const response = await fetch('{{ route("articles.bulk-force-delete") }}', {
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
                    this.selectedIds = [];
                    this.fetchArticles();
                    showToast('success', result.message);
                    window.dispatchEvent(new CustomEvent('trash-updated'));
                } else {
                    showToast('error', result.message);
                }
            } catch (error) {
                console.error('Error bulk force deleting:', error);
                showToast('error', 'Gagal menghapus berita');
            } finally {
                closeLoading();
            }
        },
        { icon: 'warning', confirmText: 'Ya, Hapus Permanen!' }
    );
},
