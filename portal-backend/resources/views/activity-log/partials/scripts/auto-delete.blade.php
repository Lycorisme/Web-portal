{{-- Auto Delete Settings Module --}}

async openAutoDeleteModal() {
    try {
        showLoading('Memuat pengaturan...');
        const response = await fetch('{{ route('activity-log.settings') }}');
        const result = await response.json();

        if (result.success) {
            this.autoDeleteSettings = result.data;
            this.showAutoDeleteModal = true;
        } else {
            showToast('error', 'Gagal memuat pengaturan: ' + (result.message || 'Error Unknown'));
        }
    } catch (error) {
        console.error('Error fetching settings:', error);
        showToast('error', 'Terjadi kesalahan saat memuat pengaturan.');
    } finally {
        closeLoading();
    }
},

closeAutoDeleteModal() {
    this.showAutoDeleteModal = false;
},

async saveAutoDeleteSettings() {
    this.isSavingSettings = true;
    try {
        const response = await fetch('{{ route('activity-log.settings.update') }}', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify(this.autoDeleteSettings),
        });
        const result = await response.json();

        if (result.success) {
            showToast('success', result.message);
            this.closeAutoDeleteModal();
        } else {
            showToast('error', result.message || 'Gagal menyimpan pengaturan.');
        }
    } catch (error) {
        console.error('Error saving settings:', error);
        showToast('error', 'Terjadi kesalahan saat menyimpan pengaturan.');
    } finally {
        this.isSavingSettings = false;
    }
},
