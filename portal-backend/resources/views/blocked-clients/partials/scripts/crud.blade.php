{{-- CRUD Operations Module --}}

// Form Modal Methods
openCreateModal() {
    this.formMode = 'create';
    this.formData = {
        id: null,
        ip_address: '',
        reason: '',
        duration_type: 'temporary',
        duration_value: 60,
        duration_unit: 'minutes',
    };
    this.formErrors = {};
    this.showFormModal = true;
    this.$nextTick(() => lucide.createIcons());
},

openEditModal(client) {
    this.closeMenu();
    this.formMode = 'edit';
    this.formData = {
        id: client.id,
        ip_address: client.ip_address,
        reason: client.reason || '',
        duration_type: client.blocked_until ? 'temporary' : 'permanent',
        duration_value: 60,
        duration_unit: 'minutes',
    };
    this.formErrors = {};
    this.showFormModal = true;
    this.$nextTick(() => lucide.createIcons());
},

closeFormModal() {
    this.showFormModal = false;
    this.formErrors = {};
},

async submitForm() {
    this.formLoading = true;
    this.formErrors = {};

    try {
        const isEdit = this.formMode === 'edit';
        const url = isEdit ? `/blocked-clients/${this.formData.id}` : '/blocked-clients';
        const method = isEdit ? 'PUT' : 'POST';

        // Calculate duration in minutes
        let duration = null;
        if (this.formData.duration_type === 'temporary') {
            const multipliers = { minutes: 1, hours: 60, days: 1440, weeks: 10080 };
            duration = this.formData.duration_value * multipliers[this.formData.duration_unit];
        }

        const response = await fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                ip_address: this.formData.ip_address,
                reason: this.formData.reason,
                duration: duration,
                make_permanent: this.formData.duration_type === 'permanent',
            }),
        });

        const result = await response.json();

        if (!response.ok) {
            if (response.status === 422) {
                this.formErrors = result.errors || {};
                showToast('error', 'Validasi gagal');
            } else {
                showToast('error', result.message || 'Terjadi kesalahan');
            }
            return;
        }

        showToast('success', result.message);
        this.closeFormModal();
        this.fetchClients();
    } catch (error) {
        console.error('Error:', error);
        showToast('error', 'Gagal menyimpan data');
    } finally {
        this.formLoading = false;
    }
},

// View Detail
async viewDetail(id) {
    this.closeMenu();
    try {
        showLoading('Memuat detail...');
        const response = await fetch(`/blocked-clients/${id}`);
        const result = await response.json();
        if (result.success) {
            this.selectedClient = result.data;
            this.showDetailModal = true;
            this.$nextTick(() => lucide.createIcons());
        }
    } catch (error) { console.error('Error:', error); showToast('error', 'Gagal memuat detail'); } 
    finally { closeLoading(); }
},

closeDetailModal() {
    this.showDetailModal = false;
    this.selectedClient = null;
},

// Unblock Client
async unblockClient(id) {
    this.closeMenu();
    showConfirm(
        'Unblock IP?',
        'IP ini akan diizinkan untuk mengakses sistem kembali.',
        async () => {
            try {
                showLoading('Memproses...');
                const response = await fetch(`/blocked-clients/${id}/unblock`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const result = await response.json();
                if (result.success) { this.fetchClients(); showToast('success', result.message); } 
                else { showToast('error', result.message); }
            } catch (error) { console.error('Error:', error); showToast('error', 'Gagal unblock IP'); } 
            finally { closeLoading(); }
        },
        { icon: 'info', confirmText: 'Ya, Unblock!' }
    );
},

// Reblock Client (block again)
async reblockClient(client) {
    this.closeMenu();
    this.formMode = 'edit';
    this.formData = {
        id: client.id,
        ip_address: client.ip_address,
        reason: client.reason || 'Diblokir ulang secara manual',
        duration_type: 'temporary',
        duration_value: 60,
        duration_unit: 'minutes',
    };
    this.formErrors = {};
    this.showFormModal = true;
    this.$nextTick(() => lucide.createIcons());
},

// Delete Client
async deleteClient(id) {
    this.closeMenu();
    showConfirm(
        'Hapus Record?',
        'Data ini akan dihapus secara permanen.',
        async () => {
            try {
                showLoading('Menghapus...');
                const response = await fetch(`/blocked-clients/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const result = await response.json();
                if (result.success) { this.fetchClients(); showToast('success', result.message); } 
                else { showToast('error', result.message); }
            } catch (error) { console.error('Error:', error); showToast('error', 'Gagal menghapus'); } 
            finally { closeLoading(); }
        },
        { icon: 'warning', confirmText: 'Ya, Hapus!' }
    );
},

// Clear Expired
async clearExpired() {
    showConfirm(
        'Bersihkan Expired Blocks?',
        'Semua blokir yang sudah expired akan di-unblock secara otomatis.',
        async () => {
            try {
                showLoading('Membersihkan...');
                const response = await fetch('/blocked-clients/clear-expired', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const result = await response.json();
                if (result.success) { this.fetchClients(); showToast('success', result.message); } 
                else { showToast('error', result.message); }
            } catch (error) { console.error('Error:', error); showToast('error', 'Gagal membersihkan'); } 
            finally { closeLoading(); }
        },
        { icon: 'info', confirmText: 'Ya, Bersihkan!' }
    );
},
