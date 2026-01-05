@extends('layouts.app')

@section('title', 'Activity Log')

@section('content')
<div x-data="activityLogApp()" x-init="init()">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 animate-fade-in">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-white dark:bg-surface-900 rounded-2xl border border-surface-200/50 dark:border-surface-700/50 shadow-sm shadow-surface-100/50 dark:shadow-surface-900/50">
                <div class="bg-theme-50 dark:bg-theme-500/10 p-2 rounded-xl">
                    <i data-lucide="history" class="w-6 h-6 text-theme-600 dark:text-theme-400"></i>
                </div>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-surface-900 dark:text-white tracking-tight">
                    Activity Log
                </h1>
            </div>
        </div>
        
        <nav class="hidden sm:flex items-center gap-2 text-sm font-medium bg-white dark:bg-surface-900 py-2 px-4 rounded-full border border-surface-200/50 dark:border-surface-700/50 shadow-sm">
            <a href="{{ route('dashboard') }}" class="text-surface-500 hover:text-theme-600 transition-colors">
                <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
            </a>
            <span class="text-surface-300 dark:text-surface-600">/</span>
            <span class="text-surface-900 dark:text-white">Activity Log</span>
        </nav>
    </div>

    {{-- Main Content --}}
    <div class="animate-slide-up" style="animation-delay: 0.1s;">
        <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 overflow-hidden">
            
            {{-- Filter Section --}}
            @include('activity-log.partials.filter')

            {{-- Table Section --}}
            @include('activity-log.partials.table')

            {{-- Pagination Section --}}
            @include('activity-log.partials.pagination')
        </div>
    </div>

    {{-- Bulk Action Bar --}}
    @include('activity-log.partials.bulk-action-bar')

    {{-- Detail Modal --}}
    {{-- Global Floating Action Menu --}}
    <template x-teleport="body">
        <div 
            x-show="activeMenuLog" 
            x-cloak
            @click.away="closeMenu()"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-2 scale-95"
            class="absolute z-[100] w-48 bg-white dark:bg-surface-800 rounded-xl shadow-xl border border-surface-100 dark:border-surface-700 py-1.5 overflow-hidden ring-1 ring-black/5"
            :class="menuPosition.placement === 'top' ? 'origin-bottom-right' : 'origin-top-right'"
            :style="`top: ${menuPosition.top}px; left: ${menuPosition.left}px; transform: ${menuPosition.placement === 'top' ? 'translateY(-100%)' : ''}`"
        >
            {{-- Common Actions (Only for Active Items) --}}
            <button 
                x-show="activeMenuLog && !activeMenuLog.deleted_at"
                @click="viewDetail(activeMenuLog.id); closeMenu()"
                class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-surface-700 dark:text-surface-300 hover:bg-surface-50 dark:hover:bg-surface-700/50 hover:text-theme-600 dark:hover:text-theme-400 transition-colors group"
            >
                <i data-lucide="eye" class="w-4 h-4 text-surface-400 group-hover:text-theme-500 transition-colors"></i>
                <span>Lihat Detail</span>
            </button>

            {{-- Actions for Active Items --}}
            <div x-show="activeMenuLog && !activeMenuLog.deleted_at">
                <div class="h-px bg-surface-100 dark:bg-surface-700/50 my-1 mx-2"></div>
                <button 
                    @click="deleteLog(activeMenuLog.id); closeMenu()"
                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors group"
                >
                    <i data-lucide="trash-2" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                    <span>Hapus</span>
                </button>
            </div>

            {{-- Actions for Trash Items --}}
            <div x-show="activeMenuLog && activeMenuLog.deleted_at">
                <div class="h-px bg-surface-100 dark:bg-surface-700/50 my-1 mx-2"></div>
                <button 
                    @click="restoreLog(activeMenuLog.id); closeMenu()"
                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 transition-colors group"
                >
                    <i data-lucide="rotate-ccw" class="w-4 h-4 group-hover:-rotate-180 transition-transform"></i>
                    <span>Pulihkan</span>
                </button>
                <button 
                    @click="forceDeleteLog(activeMenuLog.id); closeMenu()"
                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors group"
                >
                    <i data-lucide="trash-2" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                    <span>Hapus Permanen</span>
                </button>
            </div>
        </div>
    </template>

    @include('activity-log.partials.detail-modal')
</div>
@endsection

@push('scripts')
<script>
function activityLogApp() {
    return {
        // State
        logs: [],
        loading: false,
        
        // Kebab Menu State
        activeMenuLog: null,
        menuPosition: { top: 0, left: 0 },
        
        selectedLog: null,
        selectedLog: null,
        showDetailModal: false,

        init() {
            this.$watch('showDetailModal', value => {
                if (value) {
                    document.body.classList.add('overflow-hidden');
                } else {
                    document.body.classList.remove('overflow-hidden');
                }
            });
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

        init() {
            this.fetchLogs();
            
            // Close kebab menu when clicking outside
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.kebab-menu-container')) {
                    this.openKebabId = null;
                }
            });

            // Close kebab menu on scroll
            document.querySelector('.table-scroll-container')?.addEventListener('scroll', () => {
                this.openKebabId = null;
            });
        },

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

            // Get button position
            const button = event.currentTarget;
            const rect = button.getBoundingClientRect();
            
            // Viewport info
            const viewportHeight = window.innerHeight;
            const spaceBelow = viewportHeight - rect.bottom;
            const menuHeightEstimate = 220; // Safe estimate for menu height
            
            // Decide placement (Top or Bottom)
            let placement = 'bottom';
            let topPos = rect.bottom + window.scrollY + 4; // Default: below button

            // If not enough space below, and more space above, go UP
            if (spaceBelow < menuHeightEstimate && rect.top > menuHeightEstimate) {
                placement = 'top';
                topPos = rect.top + window.scrollY - 4; // Position at top of button, CSS translateY(-100%) will lift it
            }

            this.menuPosition = {
                top: topPos,
                left: (rect.right + window.scrollX) - 192, // Align right edge (192px = w-48)
                placement: placement
            };
        },

        closeMenu() {
            this.activeMenuLog = null;
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
@endpush
