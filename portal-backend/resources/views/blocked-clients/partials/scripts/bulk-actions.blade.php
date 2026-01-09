{{-- Bulk Actions Module --}}

// Select All Toggle
toggleSelectAll() {
    if (this.selectAll) {
        this.selectedIds = this.clients.map(c => c.id);
    } else {
        this.selectedIds = [];
    }
},

// Bulk Unblock
async bulkUnblock() {
    if (this.selectedIds.length === 0) return;

    showConfirm(
        `Unblock ${this.selectedIds.length} IP?`,
        'IP terpilih akan diizinkan untuk mengakses sistem kembali.',
        async () => {
            try {
                showLoading('Memproses...');
                const response = await fetch('{{ route("blocked-clients.bulk-unblock") }}', {
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
                    this.selectAll = false; 
                    this.fetchClients(); 
                    showToast('success', result.message); 
                } else { 
                    showToast('error', result.message); 
                }
            } catch (error) { 
                console.error('Error:', error); 
                showToast('error', 'Gagal unblock IP'); 
            } finally { 
                closeLoading(); 
            }
        },
        { icon: 'info', confirmText: 'Ya, Unblock!' }
    );
},

// Bulk Delete
async bulkDelete() {
    if (this.selectedIds.length === 0) return;

    showConfirm(
        `Hapus ${this.selectedIds.length} Record?`,
        'Data yang dihapus tidak dapat dikembalikan.',
        async () => {
            try {
                showLoading('Menghapus...');
                const response = await fetch('{{ route("blocked-clients.bulk-destroy") }}', {
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
                    this.selectAll = false; 
                    this.fetchClients(); 
                    showToast('success', result.message); 
                } else { 
                    showToast('error', result.message); 
                }
            } catch (error) { 
                console.error('Error:', error); 
                showToast('error', 'Gagal menghapus'); 
            } finally { 
                closeLoading(); 
            }
        },
        { icon: 'warning', confirmText: 'Ya, Hapus!' }
    );
},
