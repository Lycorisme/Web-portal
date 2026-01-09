// Toggle select all
toggleSelectAll() {
    if (this.selectAll) {
        this.selectedItems = this.items.map(item => JSON.stringify({type: item.type, id: item.id}));
    } else {
        this.selectedItems = [];
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
            this.selectAll = false;
            this.fetchItems();
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
            this.selectAll = false;
            this.fetchItems();
        } else { 
            showToast('error', result.message); 
        }
    } catch (error) { 
        console.error('Error:', error); 
        showToast('error', 'Gagal menghapus items'); 
    }
},
