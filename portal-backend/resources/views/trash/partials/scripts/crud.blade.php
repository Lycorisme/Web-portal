// Restore single item
async restoreItem(item) {
    const confirmed = await showConfirm(
        'Pulihkan Item?',
        `Apakah Anda yakin ingin memulihkan ${item.type_label}: "${item.name}"?`,
        'Pulihkan',
        'emerald'
    );

    if (!confirmed) return;

    try {
        const response = await fetch(`/trash/${item.type}/${item.id}/restore`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        });
        const result = await response.json();

        if (result.success) {
            showToast('success', result.message);
            this.fetchItems();
        } else { 
            showToast('error', result.message); 
        }
    } catch (error) { 
        console.error('Error:', error); 
        showToast('error', 'Gagal memulihkan item'); 
    }
},

// Force delete single item
async forceDeleteItem(item) {
    const confirmed = await showConfirm(
        'Hapus Permanen?',
        `Apakah Anda yakin ingin menghapus permanen ${item.type_label}: "${item.name}"? Tindakan ini tidak dapat dibatalkan!`,
        'Hapus Permanen',
        'rose'
    );

    if (!confirmed) return;

    try {
        const response = await fetch(`/trash/${item.type}/${item.id}/force`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        });
        const result = await response.json();

        if (result.success) {
            showToast('success', result.message);
            this.fetchItems();
        } else { 
            showToast('error', result.message); 
        }
    } catch (error) { 
        console.error('Error:', error); 
        showToast('error', 'Gagal menghapus item'); 
    }
},

// Empty all trash
async emptyTrash() {
    const confirmed = await showConfirm(
        'Kosongkan Tong Sampah?',
        `Apakah Anda yakin ingin menghapus permanen SEMUA ${this.counts.all} item di tong sampah? Tindakan ini tidak dapat dibatalkan!`,
        'Kosongkan',
        'rose'
    );

    if (!confirmed) return;

    try {
        const response = await fetch('{{ route("trash.empty") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ type: this.filters.type }),
        });
        const result = await response.json();

        if (result.success) {
            showToast('success', result.message);
            this.fetchItems();
        } else { 
            showToast('error', result.message); 
        }
    } catch (error) { 
        console.error('Error:', error); 
        showToast('error', 'Gagal mengosongkan tong sampah'); 
    }
},
