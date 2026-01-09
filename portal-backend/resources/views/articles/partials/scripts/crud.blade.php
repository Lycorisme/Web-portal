{{-- CRUD Operations Module --}}

// Delete Actions
async deleteArticle(id) {
    this.closeMenu();

    showConfirm(
        'Hapus Berita?',
        'Berita ini akan dipindahkan ke tong sampah.',
        async () => {
            try {
                showLoading('Menghapus...');
                const response = await fetch(`/articles/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const result = await response.json();

                if (result.success) {
                    this.fetchArticles();
                    showToast('success', result.message);
                    window.dispatchEvent(new CustomEvent('trash-updated'));
                } else {
                    showToast('error', result.message);
                }
            } catch (error) {
                console.error('Error deleting article:', error);
                showToast('error', 'Gagal menghapus berita');
            } finally {
                closeLoading();
            }
        },
        { icon: 'warning', confirmText: 'Ya, Hapus!' }
    );
},

async restoreArticle(id) {
    this.closeMenu();
    showConfirm(
        'Pulihkan Berita?',
        'Berita akan dikembalikan ke daftar aktif.',
        async () => {
            try {
                showLoading('Memulihkan...');
                const response = await fetch(`/articles/${id}/restore`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const result = await response.json();

                if (result.success) {
                    this.fetchArticles();
                    showToast('success', result.message);
                    window.dispatchEvent(new CustomEvent('trash-updated'));
                } else {
                    showToast('error', result.message);
                }
            } catch (error) {
                console.error('Error restoring article:', error);
                showToast('error', 'Gagal memulihkan berita');
            } finally {
                closeLoading();
            }
        },
        { icon: 'info', confirmText: 'Ya, Pulihkan!' }
    );
},

async forceDeleteArticle(id) {
    this.closeMenu();
    showConfirm(
        'Hapus Permanen?',
        'Data yang dihapus permanen TIDAK BISA dipulihkan kembali.',
        async () => {
            try {
                showLoading('Menghapus Permanen...');
                const response = await fetch(`/articles/${id}/force`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const result = await response.json();

                if (result.success) {
                    this.fetchArticles();
                    showToast('success', result.message);
                    window.dispatchEvent(new CustomEvent('trash-updated'));
                } else {
                    showToast('error', result.message);
                }
            } catch (error) {
                console.error('Error force deleting article:', error);
                showToast('error', 'Gagal menghapus berita');
            } finally {
                closeLoading();
            }
        },
        { icon: 'warning', confirmText: 'Hapus Permanen!', cancelText: 'Batal' }
    );
},

// Toggle Status
async changeStatus(article, newStatus) {
    try {
        const response = await fetch(`/articles/${article.id}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ status: newStatus }),
        });
        const result = await response.json();

        if (result.success) {
            // Update local state
            const idx = this.articles.findIndex(a => a.id === article.id);
            if (idx !== -1) {
                this.articles[idx].status = result.status;
            }
            showToast('success', result.message);
        } else {
            showToast('error', result.message);
        }
    } catch (error) {
        console.error('Error changing status:', error);
        showToast('error', 'Gagal mengubah status');
    }
},

// Detail Modal
async viewDetail(id) {
    this.closeMenu();
    
    try {
        showLoading('Memuat detail...');
        const response = await fetch(`/articles/${id}`);
        const result = await response.json();

        if (result.success) {
            this.selectedArticle = result.data;
            this.showDetailModal = true;
            this.$nextTick(() => lucide.createIcons());
            
            // Fetch comments for this article
            this.fetchDetailComments(id);
        }
    } catch (error) {
        console.error('Error fetching detail:', error);
        showToast('error', 'Gagal memuat detail berita');
    } finally {
        closeLoading();
    }
},

closeDetailModal() {
    this.showDetailModal = false;
    this.selectedArticle = null;
    this.detailComments = [];
    this.detailCommentsLoading = false;
},
