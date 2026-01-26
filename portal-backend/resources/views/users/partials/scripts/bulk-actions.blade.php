{{-- Bulk Actions Module --}}

// Select All Toggle - hanya mempengaruhi item di halaman saat ini
toggleSelectAll() {
    const selectableUsers = this.users.filter(u => u.id !== this.currentUserId);
    const currentPageIds = selectableUsers.map(u => u.id);
    
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

// Bulk Delete
async bulkDelete() {
    if (this.selectedIds.length === 0) return;

    showConfirm(
        `Hapus ${this.selectedIds.length} User?`,
        'User terpilih akan dipindahkan ke tong sampah.',
        async () => {
            try {
                showLoading('Menghapus...');
                const response = await fetch('{{ route("users.bulk-destroy") }}', {
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
                    this.fetchUsers(); 
                    showToast('success', result.message); 
                } else { 
                    showToast('error', result.message); 
                }
            } catch (error) { 
                console.error('Error:', error); 
                showToast('error', 'Gagal menghapus user'); 
            } finally { 
                closeLoading(); 
            }
        },
        { icon: 'warning', confirmText: 'Ya, Hapus!' }
    );
},

// Bulk Restore
async bulkRestore() {
    if (this.selectedIds.length === 0) return;

    showConfirm(
        `Pulihkan ${this.selectedIds.length} User?`,
        'User terpilih akan dikembalikan ke daftar aktif.',
        async () => {
            try {
                showLoading('Memulihkan...');
                const response = await fetch('{{ route("users.bulk-restore") }}', {
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
                    this.fetchUsers(); 
                    showToast('success', result.message); 
                } else { 
                    showToast('error', result.message); 
                }
            } catch (error) { 
                console.error('Error:', error); 
                showToast('error', 'Gagal memulihkan user'); 
            } finally { 
                closeLoading(); 
            }
        },
        { icon: 'info', confirmText: 'Ya, Pulihkan!' }
    );
},

// Bulk Force Delete
async bulkForceDelete() {
    if (this.selectedIds.length === 0) return;

    showConfirm(
        `Hapus Permanen ${this.selectedIds.length} User?`,
        'Data yang dihapus permanen TIDAK BISA dipulihkan kembali.',
        async () => {
            try {
                showLoading('Menghapus Permanen...');
                const response = await fetch('{{ route("users.bulk-force-delete") }}', {
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
                    this.fetchUsers(); 
                    showToast('success', result.message); 
                } else { 
                    showToast('error', result.message); 
                }
            } catch (error) { 
                console.error('Error:', error); 
                showToast('error', 'Gagal menghapus permanen user'); 
            } finally { 
                closeLoading(); 
            }
        },
        { icon: 'warning', confirmText: 'Hapus Permanen!', cancelText: 'Batal' }
    );
},
