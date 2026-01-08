{{-- CRUD Operations Module --}}

async viewDetail(id) {
    this.closeMenu();
    try {
        showLoading('Memuat detail...');
        const response = await fetch(`/activity-log/${id}`);
        const result = await response.json();
        if (result.success) {
            this.selectedLog = result.data;
            this.showDetailModal = true;
            this.$nextTick(() => { lucide.createIcons(); });
        }
    } catch (error) { console.error('Error:', error); showToast('error', 'Gagal memuat detail log'); } 
    finally { closeLoading(); }
},

closeDetailModal() {
    this.showDetailModal = false;
    this.selectedLog = null;
},

async deleteLog(id) {
    this.closeMenu();

    showConfirm(
        'Hapus Log?',
        'Log aktivitas ini akan dihapus secara permanen.',
        async () => {
            try {
                showLoading('Menghapus...');
                const response = await fetch(`/activity-log/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const result = await response.json();
                if (result.success) { this.fetchLogs(); showToast('success', result.message); } 
                else { showToast('error', result.message); }
            } catch (error) { console.error('Error:', error); showToast('error', 'Gagal menghapus log'); } 
            finally { closeLoading(); }
        },
        { icon: 'warning', confirmText: 'Ya, Hapus!' }
    );
},

async restoreLog(id) {
    this.closeMenu();
    showConfirm(
        'Pulihkan Log?',
        'Log aktivitas akan dikembalikan ke daftar aktif.',
        async () => {
            try {
                showLoading('Memulihkan...');
                const response = await fetch(`/activity-log/${id}/restore`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const result = await response.json();
                if (result.success) { this.fetchLogs(); showToast('success', result.message); } 
                else { showToast('error', result.message); }
            } catch (error) { console.error('Error:', error); showToast('error', 'Gagal memulihkan log'); } 
            finally { closeLoading(); }
        },
        { icon: 'info', confirmText: 'Ya, Pulihkan!' }
    );
},

async forceDeleteLog(id) {
    this.closeMenu();
    showConfirm(
        'Hapus Permanen?',
        'Data yang dihapus permanen TIDAK BISA dipulihkan kembali.',
        async () => {
            try {
                showLoading('Menghapus Permanen...');
                const response = await fetch(`/activity-log/${id}/force`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const result = await response.json();
                if (result.success) { this.fetchLogs(); showToast('success', result.message); } 
                else { showToast('error', result.message); }
            } catch (error) { console.error('Error:', error); showToast('error', 'Gagal menghapus log'); } 
            finally { closeLoading(); }
        },
        { icon: 'warning', confirmText: 'Hapus Permanen!', cancelText: 'Batal' }
    );
},
