<script>
function activityLogApp() {
    return {
        // State
        logs: [],
        loading: false,
        
        // Kebab Menu State
        activeMenuLog: null,
        activeMenuButton: null,
        menuPosition: { top: 0, left: 0, placement: 'bottom' },
        
        selectedLog: null,
        showDetailModal: false,
        showAutoDeleteModal: false,
        autoDeleteSettings: {
            enabled: false,
            retention_days: 30,
            schedule: 'daily',
            time: '00:00'
        },
        isSavingSettings: false,

        init() {
            this.$watch('showDetailModal', value => {
                if (value) {
                    document.body.classList.add('overflow-hidden');
                } else {
                    document.body.classList.remove('overflow-hidden');
                }
            });

            this.fetchLogs();
            
            // Close kebab menu when clicking outside
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.kebab-menu-container')) {
                    this.openKebabId = null;
                }
            });

            // Update menu position on scroll to make it "stick"
            const updatePositionHandler = () => {
                if (this.activeMenuLog && this.activeMenuButton) {
                    this.updateMenuPosition();
                }
            };

            const scrollContainer = document.querySelector('.table-scroll-container');
            if (scrollContainer) {
                scrollContainer.addEventListener('scroll', updatePositionHandler);
            }
            // Listen for window scroll and resize to keep menu attached
            window.addEventListener('scroll', updatePositionHandler, true);
            window.addEventListener('resize', updatePositionHandler);
        },

        selectedIds: [],
        selectAll: false,
        showTrash: false,

        // Filters
        filters: {
            search: '',
            user_id: '',
            action: '',
            level: '',
            date_from: '',
            date_to: '',
        },

        // Pagination
        meta: {
            current_page: 1,
            last_page: 1,
            per_page: 15,
            total: 0,
            from: 0,
            to: 0,
        },

        // Kebab menu
        openKebabId: null,

        async fetchLogs() {
            this.loading = true;
            this.selectedIds = [];
            this.selectAll = false;

            try {
                const params = new URLSearchParams({
                    page: this.meta.current_page,
                    per_page: this.meta.per_page,
                    status: this.showTrash ? 'trash' : 'active',
                    ...this.filters,
                });

                const response = await fetch(`{{ route('activity-log.data') }}?${params}`);
                const result = await response.json();

                if (result.success) {
                    this.logs = result.data;
                    this.meta = result.meta;
                    // Re-initialize icons after DOM update
                    this.$nextTick(() => {
                        lucide.createIcons();
                    });
                }
            } catch (error) {
                console.error('Error fetching logs:', error);
                showToast('error', 'Gagal memuat data log aktivitas');
            } finally {
                this.loading = false;
            }
        },

        toggleTrash() {
            this.showTrash = !this.showTrash;
            this.activeMenuLog = null; // Close menu on toggle
            this.applyFilters();
        },

        // Floating Menu Logic
        openMenu(log, event) {
            // Prevent event from bubbling up
            event.preventDefault();
            event.stopPropagation();

            // If clicking the same button, close the menu
            if (this.activeMenuLog && this.activeMenuLog.id === log.id) {
                this.closeMenu();
                return;
            }

            this.activeMenuLog = log;
            this.activeMenuButton = event.currentTarget;
            this.updateMenuPosition();
        },

        updateMenuPosition() {
            if (!this.activeMenuButton) return;

            const rect = this.activeMenuButton.getBoundingClientRect();
            
            // Viewport info
            const viewportHeight = window.innerHeight;
            const spaceBelow = viewportHeight - rect.bottom;
            const menuHeightEstimate = 220; // Safe estimate for menu height
            
            // Decide placement (Top or Bottom)
            let placement = 'bottom';
            // Fixed positioning: rect.bottom is relative to viewport top
            let topPos = rect.bottom + 4; 

            // If not enough space below, and more space above, go UP
            if (spaceBelow < menuHeightEstimate && rect.top > menuHeightEstimate) {
                placement = 'top';
                // Fixed positioning: rect.top is relative to viewport top
                topPos = rect.top - 4; // Position at top of button, CSS translateY(-100%) will lift it
            }

            this.menuPosition = {
                top: topPos,
                // Fixed positioning: rect.right is relative to viewport left
                left: rect.right - 192, // Align right edge (192px = w-48)
                placement: placement
            };
        },

        closeMenu() {
            this.activeMenuLog = null;
            this.activeMenuButton = null;
        },

        applyFilters() {
            this.meta.current_page = 1;
            this.fetchLogs();
        },

        resetFilters() {
            this.filters = {
                search: '',
                user_id: '',
                action: '',
                level: '',
                date_from: '',
                date_to: '',
            };
            this.applyFilters();
        },

        goToPage(page) {
            if (page >= 1 && page <= this.meta.last_page) {
                this.meta.current_page = page;
                this.fetchLogs();
            }
        },

        toggleKebab(id) {
            this.openKebabId = this.openKebabId === id ? null : id;
        },

        async viewDetail(id) {
            this.openKebabId = null;
            
            try {
                showLoading('Memuat detail...');
                const response = await fetch(`/activity-log/${id}`);
                const result = await response.json();

                if (result.success) {
                    this.selectedLog = result.data;
                    this.showDetailModal = true;
                    // Re-initialize icons in modal
                    this.$nextTick(() => {
                        lucide.createIcons();
                    });
                }
            } catch (error) {
                console.error('Error fetching detail:', error);
                showToast('error', 'Gagal memuat detail log');
            } finally {
                closeLoading();
            }
        },

        closeDetailModal() {
            this.showDetailModal = false;
            this.selectedLog = null;
        },

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

        async deleteLog(id) {
            this.openKebabId = null;

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

                        if (result.success) {
                            this.fetchLogs();
                            showToast('success', result.message);
                        } else {
                            showToast('error', result.message);
                        }
                    } catch (error) {
                        console.error('Error deleting log:', error);
                        showToast('error', 'Gagal menghapus log');
                    } finally {
                        closeLoading();
                    }
                },
                { icon: 'warning', confirmText: 'Ya, Hapus!' }
            );
        },

        toggleSelectAll() {
            if (this.selectAll) {
                this.selectedIds = this.logs.map(log => log.id);
            } else {
                this.selectedIds = [];
            }
        },

        async bulkDelete() {
            if (this.selectedIds.length === 0) {
                showToast('warning', 'Pilih log yang ingin dihapus');
                return;
            }

            showConfirm(
                `Hapus ${this.selectedIds.length} Log?`,
                'Semua log yang dipilih akan dihapus secara permanen.',
                async () => {
                    try {
                        showLoading('Menghapus...');
                        const response = await fetch('{{ route('activity-log.bulk-destroy') }}', {
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
                            this.fetchLogs();
                            showToast('success', result.message);
                        } else {
                            showToast('error', result.message);
                        }
                    } catch (error) {
                        console.error('Error bulk deleting:', error);
                        showToast('error', 'Gagal menghapus log');
                    } finally {
                        closeLoading();
                    }
                },
                { icon: 'warning', confirmText: 'Ya, Hapus Semua!' }
            );
        },

        getLevelBadgeClass(level) {
            const classes = {
                'info': 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400',
                'warning': 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400',
                'danger': 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400',
                'critical': 'bg-red-600 text-white',
            };
            return classes[level] || 'bg-surface-100 text-surface-700';
        },

        getActionBadgeClass(action) {
            const actionColors = {
                'CREATE': 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400',
                'UPDATE': 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400',
                'DELETE': 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400',
                'LOGIN': 'bg-violet-100 text-violet-700 dark:bg-violet-500/20 dark:text-violet-400',
                'LOGOUT': 'bg-slate-100 text-slate-700 dark:bg-slate-500/20 dark:text-slate-400',
                'LOGIN_FAILED': 'bg-orange-100 text-orange-700 dark:bg-orange-500/20 dark:text-orange-400',
            };
            return actionColors[action] || 'bg-surface-100 text-surface-700 dark:bg-surface-700 dark:text-surface-300';
        },

        formatJson(data) {
            if (!data) return '-';
            try {
                return JSON.stringify(data, null, 2);
            } catch {
                return String(data);
            }
        },

        getAllKeys(oldVal, newVal) {
            const keys = new Set([
                ...Object.keys(oldVal || {}), 
                ...Object.keys(newVal || {})
            ]);
            return Array.from(keys);
        },

        formatKey(key) {
            // "first_name" -> "First Name"
            return key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        },

        formatValue(value) {
            if (value === null || value === undefined || value === '') return '-';
            if (typeof value === 'boolean') return value ? 'Ya' : 'Tidak';
            if (Array.isArray(value)) return value.join(', ');
            if (typeof value === 'object') return JSON.stringify(value);
            return value;
        },

        // Pagination helpers
        get paginationPages() {
            const pages = [];
            const current = this.meta.current_page;
            const last = this.meta.last_page;
            
            if (last <= 7) {
                for (let i = 1; i <= last; i++) pages.push(i);
            } else {
                if (current <= 3) {
                    for (let i = 1; i <= 5; i++) pages.push(i);
                    pages.push('...');
                    pages.push(last);
                } else if (current >= last - 2) {
                    pages.push(1);
                    pages.push('...');
                    for (let i = last - 4; i <= last; i++) pages.push(i);
                } else {
                    pages.push(1);
                    pages.push('...');
                    for (let i = current - 1; i <= current + 1; i++) pages.push(i);
                    pages.push('...');
                    pages.push(last);
                }
            }
            return pages;
        },

        // Soft Delete & Restore Functions
        async restoreLog(id) {
            this.openKebabId = null;
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

                        if (result.success) {
                            this.fetchLogs();
                            showToast('success', result.message);
                        } else {
                            showToast('error', result.message);
                        }
                    } catch (error) {
                        console.error('Error restoring log:', error);
                        showToast('error', 'Gagal memulihkan log');
                    } finally {
                        closeLoading();
                    }
                },
                { icon: 'info', confirmText: 'Ya, Pulihkan!' }
            );
        },

        async forceDeleteLog(id) {
            this.openKebabId = null;
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

                        if (result.success) {
                            this.fetchLogs();
                            showToast('success', result.message);
                        } else {
                            showToast('error', result.message);
                        }
                    } catch (error) {
                        console.error('Error force deleting log:', error);
                        showToast('error', 'Gagal menghapus log');
                    } finally {
                        closeLoading();
                    }
                },
                { icon: 'warning', confirmText: 'Hapus Permanen!', cancelText: 'Batal' }
            );
        },

        async bulkRestore() {
            if (this.selectedIds.length === 0) return;

            showConfirm(
                `Pulihkan ${this.selectedIds.length} Log?`,
                'Item terpilih akan dikembalikan ke daftar aktif.',
                async () => {
                    try {
                        showLoading('Memulihkan...');
                        const response = await fetch('{{ route('activity-log.bulk-restore') }}', {
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
                            this.fetchLogs();
                            showToast('success', result.message);
                        } else {
                            showToast('error', result.message);
                        }
                    } catch (error) {
                        console.error('Error bulk restoring:', error);
                        showToast('error', 'Gagal memulihkan log');
                    } finally {
                        closeLoading();
                    }
                },
                { icon: 'info', confirmText: 'Ya, Pulihkan Semua!' }
            );
        },

        async bulkForceDelete() {
            if (this.selectedIds.length === 0) return;

            showConfirm(
                `Hapus Permanen ${this.selectedIds.length} Log?`,
                'PERINGATAN: Data akan hilang selamanya dan tidak bisa dikembalikan!',
                async () => {
                    try {
                        showLoading('Menghapus Permanen...');
                        const response = await fetch('{{ route('activity-log.bulk-force-delete') }}', {
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
                            this.fetchLogs();
                            showToast('success', result.message);
                        } else {
                            showToast('error', result.message);
                        }
                    } catch (error) {
                        console.error('Error bulk force deleting:', error);
                        showToast('error', 'Gagal menghapus log');
                    } finally {
                        closeLoading();
                    }
                },
                { icon: 'warning', confirmText: 'Ya, Hapus Permanen!' }
            );
        }
    }
}
</script>
