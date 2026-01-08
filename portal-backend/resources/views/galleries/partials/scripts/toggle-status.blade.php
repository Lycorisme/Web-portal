// ========================================
// TOGGLE STATUS METHODS
// ========================================
async togglePublished(item) {
    try {
        const response = await fetch(`/galleries/${item.id}/toggle-published`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        });
        const result = await response.json();

        if (result.success) {
            const idx = this.galleries.findIndex(g => g.id === item.id);
            if (idx !== -1) { this.galleries[idx].is_published = result.is_published; }
            this.$nextTick(() => lucide.createIcons());
            showToast('success', result.message);
        } else { showToast('error', result.message); }
    } catch (error) { console.error('Error:', error); showToast('error', 'Gagal mengubah status'); }
},

async toggleFeatured(item) {
    try {
        const response = await fetch(`/galleries/${item.id}/toggle-featured`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        });
        const result = await response.json();

        if (result.success) {
            const idx = this.galleries.findIndex(g => g.id === item.id);
            if (idx !== -1) { this.galleries[idx].is_featured = result.is_featured; }
            this.$nextTick(() => lucide.createIcons());
            showToast('success', result.message);
        } else { showToast('error', result.message); }
    } catch (error) { console.error('Error:', error); showToast('error', 'Gagal mengubah status'); }
},
