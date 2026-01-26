// Toggle select all - hanya mempengaruhi item di halaman saat ini
toggleSelectAll() {
    const currentPageItemKeys = this.items.map(item => JSON.stringify({type: item.type, id: item.id}));
    
    if (this.selectAll) {
        // Jika semua sudah terpilih, hapus semua item di halaman ini dari selection
        this.selectedItems = this.selectedItems.filter(key => !currentPageItemKeys.includes(key));
    } else {
        // Jika belum semua terpilih, tambahkan semua item di halaman ini ke selection
        currentPageItemKeys.forEach(key => {
            if (!this.selectedItems.includes(key)) {
                this.selectedItems.push(key);
            }
        });
    }
},

// Bulk restore
async bulkRestore() {
    const items = this.selectedItems.map(item => JSON.parse(item));
    
    const confirmed = await showConfirm(
        'Pulihkan Items?',
        `Apakah Anda yakin ingin memulihkan ${items.length} item yang dipilih?`,
        'Pulihkan Semua',
        'emerald'
    );

    if (!confirmed) return;

    try {
        const response = await fetch('{{ route("trash.bulk-restore") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ items: items }),
        });
        const result = await response.json();

        if (result.success) {
            showToast('success', result.message);
            this.selectedItems = [];
            this.fetchItems();
            window.dispatchEvent(new CustomEvent('trash-updated'));
        } else { 
            showToast('error', result.message); 
        }
    } catch (error) { 
        console.error('Error:', error); 
        showToast('error', 'Gagal memulihkan items'); 
    }
},

// Bulk force delete
async bulkForceDelete() {
    const items = this.selectedItems.map(item => JSON.parse(item));
    
    const confirmed = await showConfirm(
        'Hapus Permanen?',
        `Apakah Anda yakin ingin menghapus permanen ${items.length} item yang dipilih? Tindakan ini tidak dapat dibatalkan!`,
        'Hapus Permanen',
        'rose'
    );

    if (!confirmed) return;

    try {
        const response = await fetch('{{ route("trash.bulk-force-delete") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ items: items }),
        });
        const result = await response.json();

        if (result.success) {
            showToast('success', result.message);
            this.selectedItems = [];
            this.fetchItems();
            window.dispatchEvent(new CustomEvent('trash-updated'));
        } else { 
            showToast('error', result.message); 
        }
    } catch (error) { 
        console.error('Error:', error); 
        showToast('error', 'Gagal menghapus items'); 
    }
},
