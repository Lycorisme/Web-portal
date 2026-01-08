{{-- CRUD Operations Module --}}

// Delete Actions
async deleteUser(id) {
    this.closeMenu();

    showConfirm(
        'Hapus User?',
        'User ini akan dipindahkan ke tong sampah.',
        async () => {
            try {
                showLoading('Menghapus...');
                const response = await fetch(`/users/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const result = await response.json();
                if (result.success) { this.fetchUsers(); showToast('success', result.message); } 
                else { showToast('error', result.message); }
            } catch (error) { console.error('Error:', error); showToast('error', 'Gagal menghapus'); } 
            finally { closeLoading(); }
        },
        { icon: 'warning', confirmText: 'Ya, Hapus!' }
    );
},

async restoreUser(id) {
    this.closeMenu();
    showConfirm(
        'Pulihkan User?',
        'User akan dikembalikan ke daftar aktif.',
        async () => {
            try {
                showLoading('Memulihkan...');
                const response = await fetch(`/users/${id}/restore`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const result = await response.json();
                if (result.success) { this.fetchUsers(); showToast('success', result.message); } 
                else { showToast('error', result.message); }
            } catch (error) { console.error('Error:', error); showToast('error', 'Gagal memulihkan'); } 
            finally { closeLoading(); }
        },
        { icon: 'info', confirmText: 'Ya, Pulihkan!' }
    );
},

async forceDeleteUser(id) {
    this.closeMenu();
    showConfirm(
        'Hapus Permanen?',
        'Data yang dihapus permanen TIDAK BISA dipulihkan kembali. Semua data user termasuk foto profil akan dihapus.',
        async () => {
            try {
                showLoading('Menghapus Permanen...');
                const response = await fetch(`/users/${id}/force`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const result = await response.json();
                if (result.success) { this.fetchUsers(); showToast('success', result.message); } 
                else { showToast('error', result.message); }
            } catch (error) { console.error('Error:', error); showToast('error', 'Gagal menghapus'); } 
            finally { closeLoading(); }
        },
        { icon: 'warning', confirmText: 'Hapus Permanen!', cancelText: 'Batal' }
    );
},

// Unlock User Account
async unlockUser(id) {
    this.closeMenu();
    showConfirm(
        'Buka Kunci Akun?',
        'Akun user akan dibuka kuncinya sehingga dapat login kembali.',
        async () => {
            try {
                showLoading('Membuka kunci...');
                const response = await fetch(`/users/${id}/unlock`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const result = await response.json();
                if (result.success) { 
                    this.fetchUsers(); 
                    showToast('success', result.message); 
                } else { 
                    showToast('error', result.message); 
                }
            } catch (error) { 
                console.error('Error:', error); 
                showToast('error', 'Gagal membuka kunci akun'); 
            } finally { 
                closeLoading(); 
            }
        },
        { icon: 'info', confirmText: 'Ya, Buka Kunci!' }
    );
},

// Detail Modal
async viewDetail(id) {
    this.closeMenu();
    try {
        showLoading('Memuat detail...');
        const response = await fetch(`/users/${id}`);
        const result = await response.json();
        if (result.success) {
            this.selectedUser = result.data;
            this.showDetailModal = true;
            this.$nextTick(() => lucide.createIcons());
        }
    } catch (error) { console.error('Error:', error); showToast('error', 'Gagal memuat detail'); } 
    finally { closeLoading(); }
},

closeDetailModal() {
    this.showDetailModal = false;
    this.selectedUser = null;
},
