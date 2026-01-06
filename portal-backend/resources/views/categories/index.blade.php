@extends('layouts.app')

@section('title', 'Kelola Kategori')

@section('content')
<div x-data="categoryApp()" x-init="init()">
    {{-- Enhanced Page Header --}}
    <div class="relative mb-8 animate-fade-in group">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                {{-- Animated Icon Container --}}
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-tr from-theme-500/20 to-theme-300/20 rounded-2xl blur-lg opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative p-3.5 bg-white dark:bg-surface-800 rounded-2xl border border-surface-200/50 dark:border-surface-700/50 shadow-lg shadow-surface-100/50 dark:shadow-surface-900/50 ring-1 ring-white/50 dark:ring-surface-700/50">
                        <i data-lucide="folder-tree" class="w-8 h-8 text-theme-600 dark:text-theme-400"></i>
                    </div>
                </div>
                
                {{-- Title & Subtitle --}}
                <div>
                    <h1 class="text-3xl font-bold text-surface-900 dark:text-white tracking-tight mb-2">
                        Kelola Kategori
                    </h1>
                    <nav class="flex items-center gap-2 text-sm font-medium text-surface-500 dark:text-surface-400">
                        <a href="{{ route('dashboard') }}" class="hover:text-theme-600 transition-colors flex items-center gap-1.5">
                            <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                        </a>
                        <i data-lucide="chevron-right" class="w-3 h-3 text-surface-300 dark:text-surface-600"></i>
                        <span class="text-theme-600 dark:text-theme-400">Kategori</span>
                    </nav>
                </div>
            </div>

            {{-- Modern Server Time Widget --}}
            <div class="hidden lg:flex items-center gap-4 px-5 py-2.5 bg-white/50 dark:bg-surface-800/50 backdrop-blur-md border border-surface-200/60 dark:border-surface-700/60 rounded-2xl shadow-lg shadow-surface-200/10 dark:shadow-surface-900/10 hover:shadow-xl hover:scale-[1.02] hover:bg-white dark:hover:bg-surface-800 hover:border-theme-500/20 dark:hover:border-theme-500/20 transition-all duration-300 group/clock"
                 x-data="{
                    serverOffset: {{ now()->timestamp * 1000 }} - Date.now(),
                    hours: '00',
                    minutes: '00',
                    seconds: '00',
                    dayName: '',
                    fullDate: '',
                    init() {
                        this.update();
                        setInterval(() => {
                            this.update();
                        }, 1000);
                    },
                    getServerTime() {
                        return Date.now() + this.serverOffset;
                    },
                    update() {
                        const date = new Date(this.getServerTime());
                        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                        
                        this.dayName = days[date.getDay()];
                        this.fullDate = `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
                        this.hours = String(date.getHours()).padStart(2, '0');
                        this.minutes = String(date.getMinutes()).padStart(2, '0');
                        this.seconds = String(date.getSeconds()).padStart(2, '0');
                    }
                 }"
                 x-cloak>
                
                {{-- Animated Icon --}}
                <div class="relative">
                    <div class="absolute inset-0 bg-theme-500 rounded-full blur opacity-0 group-hover/clock:opacity-20 transition-opacity duration-500"></div>
                    <div class="relative p-2.5 bg-gradient-to-br from-theme-50 to-theme-100 dark:from-theme-900/40 dark:to-theme-800/40 rounded-xl text-theme-600 dark:text-theme-400 group-hover/clock:rotate-12 transition-transform duration-500">
                        <i data-lucide="clock" class="w-5 h-5"></i>
                    </div>
                </div>

                {{-- Divider --}}
                <div class="h-8 w-px bg-surface-200 dark:bg-surface-700/50"></div>

                {{-- Time Display --}}
                <div class="flex flex-col">
                    <div class="flex items-baseline gap-0.5">
                         <span class="text-xl font-bold font-space text-surface-900 dark:text-white tracking-tight" x-text="hours"></span>
                         <span class="text-theme-500 font-bold animate-pulse px-0.5">:</span>
                         <span class="text-xl font-bold font-space text-surface-900 dark:text-white tracking-tight" x-text="minutes"></span>
                         <span class="text-surface-400 font-bold px-0.5">:</span>
                         <span class="text-base font-medium font-space text-surface-500 dark:text-surface-400" x-text="seconds"></span>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs font-medium text-surface-500 dark:text-surface-400">
                        <span x-text="dayName" class="text-theme-600 dark:text-theme-400"></span>
                        <span class="w-1 h-1 rounded-full bg-surface-300 dark:bg-surface-600"></span>
                        <span x-text="fullDate"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="animate-slide-up" style="animation-delay: 0.1s;">
        <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 overflow-hidden">
            
            {{-- Filter Section --}}
            @include('categories.partials.filter')

            {{-- Table Section --}}
            @include('categories.partials.table')

            {{-- Pagination Section --}}
            @include('categories.partials.pagination')
        </div>
    </div>

    {{-- Bulk Action Bar --}}
    @include('categories.partials.bulk-action-bar')

    {{-- Action Menu --}}
    @include('categories.partials.action-menu')

    {{-- Form Modal (Create/Edit) --}}
    @include('categories.partials.form-modal')

    {{-- Detail Modal --}}
    @include('categories.partials.detail-modal')
</div>
@endsection

@push('scripts')
<script>
function categoryApp() {
    return {
        // State
        categories: [],
        loading: false,
        
        // Menu State
        activeMenuCategory: null,
        activeMenuButton: null,
        menuPosition: { top: 0, left: 0, placement: 'bottom' },
        
        // Modal State
        selectedCategory: null,
        showDetailModal: false,
        showFormModal: false,
        formMode: 'create', // 'create' or 'edit'
        formData: {
            id: null,
            name: '',
            slug: '',
            description: '',
            color: '#6366f1',
            icon: 'folder',
            sort_order: 0,
            is_active: true,
        },
        formErrors: {},
        formLoading: false,

        selectedIds: [],
        selectAll: false,
        showTrash: false,

        // Filters
        filters: {
            search: '',
            is_active: '',
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

        init() {
            this.fetchCategories();
            
            // Close menu when clicking outside
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.kebab-menu-container') && !e.target.closest('[x-show="activeMenuCategory"]')) {
                    this.activeMenuCategory = null;
                }
            });

            // Update menu position on scroll
            const updatePositionHandler = () => {
                if (this.activeMenuCategory && this.activeMenuButton) {
                    this.updateMenuPosition();
                }
            };

            const scrollContainer = document.querySelector('.table-scroll-container');
            if (scrollContainer) {
                scrollContainer.addEventListener('scroll', updatePositionHandler);
            }
            window.addEventListener('scroll', updatePositionHandler, true);
            window.addEventListener('resize', updatePositionHandler);

            // Watch for modal body scroll lock
            this.$watch('showDetailModal', value => {
                document.body.classList.toggle('overflow-hidden', value);
            });
            this.$watch('showFormModal', value => {
                document.body.classList.toggle('overflow-hidden', value);
            });
        },

        async fetchCategories() {
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

                const response = await fetch(`{{ route('categories.data') }}?${params}`);
                const result = await response.json();

                if (result.success) {
                    this.categories = result.data;
                    this.meta = result.meta;
                    this.$nextTick(() => {
                        lucide.createIcons();
                    });
                }
            } catch (error) {
                console.error('Error fetching categories:', error);
                showToast('error', 'Gagal memuat data kategori');
            } finally {
                this.loading = false;
            }
        },

        toggleTrash() {
            this.showTrash = !this.showTrash;
            this.activeMenuCategory = null;
            this.applyFilters();
        },

        // Menu Logic
        openMenu(category, event) {
            event.preventDefault();
            event.stopPropagation();

            if (this.activeMenuCategory && this.activeMenuCategory.id === category.id) {
                this.closeMenu();
                return;
            }

            this.activeMenuCategory = category;
            this.activeMenuButton = event.currentTarget;
            this.updateMenuPosition();
        },

        updateMenuPosition() {
            if (!this.activeMenuButton) return;

            const rect = this.activeMenuButton.getBoundingClientRect();
            const viewportHeight = window.innerHeight;
            const spaceBelow = viewportHeight - rect.bottom;
            const menuHeightEstimate = 220;
            
            let placement = 'bottom';
            let topPos = rect.bottom + 4;

            if (spaceBelow < menuHeightEstimate && rect.top > menuHeightEstimate) {
                placement = 'top';
                topPos = rect.top - 4;
            }

            this.menuPosition = {
                top: topPos,
                left: rect.right - 192,
                placement: placement
            };
        },

        closeMenu() {
            this.activeMenuCategory = null;
            this.activeMenuButton = null;
        },

        applyFilters() {
            this.meta.current_page = 1;
            this.fetchCategories();
        },

        resetFilters() {
            this.filters = {
                search: '',
                is_active: '',
            };
            this.applyFilters();
        },

        goToPage(page) {
            if (page >= 1 && page <= this.meta.last_page) {
                this.meta.current_page = page;
                this.fetchCategories();
            }
        },

        // Form Modal Logic
        openCreateModal() {
            this.formMode = 'create';
            this.formData = {
                id: null,
                name: '',
                slug: '',
                description: '',
                color: '#6366f1',
                icon: 'folder',
                sort_order: 0,
                is_active: true,
            };
            this.formErrors = {};
            this.showFormModal = true;
            this.$nextTick(() => lucide.createIcons());
        },

        openEditModal(category) {
            this.formMode = 'edit';
            this.formData = {
                id: category.id,
                name: category.name,
                slug: category.slug,
                description: category.description || '',
                color: category.color || '#6366f1',
                icon: category.icon || 'folder',
                sort_order: category.sort_order,
                is_active: category.is_active,
            };
            this.formErrors = {};
            this.showFormModal = true;
            this.closeMenu();
            this.$nextTick(() => lucide.createIcons());
        },

        closeFormModal() {
            this.showFormModal = false;
            this.formData = {
                id: null,
                name: '',
                slug: '',
                description: '',
                color: '#6366f1',
                icon: 'folder',
                sort_order: 0,
                is_active: true,
            };
            this.formErrors = {};
        },

        async submitForm() {
            this.formLoading = true;
            this.formErrors = {};

            try {
                const url = this.formMode === 'create' 
                    ? '{{ route("categories.store") }}'
                    : `/categories/${this.formData.id}`;
                
                const method = this.formMode === 'create' ? 'POST' : 'PUT';

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(this.formData),
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    this.closeFormModal();
                    this.fetchCategories();
                    showToast('success', result.message);
                } else if (response.status === 422) {
                    // Validation errors
                    this.formErrors = result.errors || {};
                    showToast('error', 'Mohon periksa kembali data yang diinput.');
                } else {
                    showToast('error', result.message || 'Terjadi kesalahan');
                }
            } catch (error) {
                console.error('Error submitting form:', error);
                showToast('error', 'Gagal menyimpan kategori');
            } finally {
                this.formLoading = false;
            }
        },

        // Detail Modal
        async viewDetail(id) {
            this.closeMenu();
            
            try {
                showLoading('Memuat detail...');
                const response = await fetch(`/categories/${id}`);
                const result = await response.json();

                if (result.success) {
                    this.selectedCategory = result.data;
                    this.showDetailModal = true;
                    this.$nextTick(() => lucide.createIcons());
                }
            } catch (error) {
                console.error('Error fetching detail:', error);
                showToast('error', 'Gagal memuat detail kategori');
            } finally {
                closeLoading();
            }
        },

        closeDetailModal() {
            this.showDetailModal = false;
            this.selectedCategory = null;
        },

        // Delete Actions
        async deleteCategory(id) {
            this.closeMenu();

            showConfirm(
                'Hapus Kategori?',
                'Kategori ini akan dipindahkan ke tong sampah.',
                async () => {
                    try {
                        showLoading('Menghapus...');
                        const response = await fetch(`/categories/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                        });
                        const result = await response.json();

                        if (result.success) {
                            this.fetchCategories();
                            showToast('success', result.message);
                        } else {
                            showToast('error', result.message);
                        }
                    } catch (error) {
                        console.error('Error deleting category:', error);
                        showToast('error', 'Gagal menghapus kategori');
                    } finally {
                        closeLoading();
                    }
                },
                { icon: 'warning', confirmText: 'Ya, Hapus!' }
            );
        },

        async restoreCategory(id) {
            this.closeMenu();
            showConfirm(
                'Pulihkan Kategori?',
                'Kategori akan dikembalikan ke daftar aktif.',
                async () => {
                    try {
                        showLoading('Memulihkan...');
                        const response = await fetch(`/categories/${id}/restore`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                        });
                        const result = await response.json();

                        if (result.success) {
                            this.fetchCategories();
                            showToast('success', result.message);
                        } else {
                            showToast('error', result.message);
                        }
                    } catch (error) {
                        console.error('Error restoring category:', error);
                        showToast('error', 'Gagal memulihkan kategori');
                    } finally {
                        closeLoading();
                    }
                },
                { icon: 'info', confirmText: 'Ya, Pulihkan!' }
            );
        },

        async forceDeleteCategory(id) {
            this.closeMenu();
            showConfirm(
                'Hapus Permanen?',
                'Data yang dihapus permanen TIDAK BISA dipulihkan kembali.',
                async () => {
                    try {
                        showLoading('Menghapus Permanen...');
                        const response = await fetch(`/categories/${id}/force`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                        });
                        const result = await response.json();

                        if (result.success) {
                            this.fetchCategories();
                            showToast('success', result.message);
                        } else {
                            showToast('error', result.message);
                        }
                    } catch (error) {
                        console.error('Error force deleting category:', error);
                        showToast('error', 'Gagal menghapus kategori');
                    } finally {
                        closeLoading();
                    }
                },
                { icon: 'warning', confirmText: 'Hapus Permanen!', cancelText: 'Batal' }
            );
        },

        // Toggle Active Status
        async toggleActive(category) {
            try {
                const response = await fetch(`/categories/${category.id}/toggle-active`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const result = await response.json();

                if (result.success) {
                    // Update local state
                    const idx = this.categories.findIndex(c => c.id === category.id);
                    if (idx !== -1) {
                        this.categories[idx].is_active = result.is_active;
                    }
                    showToast('success', result.message);
                } else {
                    showToast('error', result.message);
                }
            } catch (error) {
                console.error('Error toggling active:', error);
                showToast('error', 'Gagal mengubah status');
            }
        },

        // Selection
        toggleSelectAll() {
            if (this.selectAll) {
                this.selectedIds = this.categories.map(c => c.id);
            } else {
                this.selectedIds = [];
            }
        },

        // Bulk Actions
        async bulkDelete() {
            if (this.selectedIds.length === 0) {
                showToast('warning', 'Pilih kategori yang ingin dihapus');
                return;
            }

            showConfirm(
                `Hapus ${this.selectedIds.length} Kategori?`,
                'Semua kategori yang dipilih akan dipindahkan ke tong sampah.',
                async () => {
                    try {
                        showLoading('Menghapus...');
                        const response = await fetch('{{ route("categories.bulk-destroy") }}', {
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
                            this.fetchCategories();
                            showToast('success', result.message);
                        } else {
                            showToast('error', result.message);
                        }
                    } catch (error) {
                        console.error('Error bulk deleting:', error);
                        showToast('error', 'Gagal menghapus kategori');
                    } finally {
                        closeLoading();
                    }
                },
                { icon: 'warning', confirmText: 'Ya, Hapus Semua!' }
            );
        },

        async bulkRestore() {
            if (this.selectedIds.length === 0) return;

            showConfirm(
                `Pulihkan ${this.selectedIds.length} Kategori?`,
                'Item terpilih akan dikembalikan ke daftar aktif.',
                async () => {
                    try {
                        showLoading('Memulihkan...');
                        const response = await fetch('{{ route("categories.bulk-restore") }}', {
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
                            this.fetchCategories();
                            showToast('success', result.message);
                        } else {
                            showToast('error', result.message);
                        }
                    } catch (error) {
                        console.error('Error bulk restoring:', error);
                        showToast('error', 'Gagal memulihkan kategori');
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
                `Hapus Permanen ${this.selectedIds.length} Kategori?`,
                'PERINGATAN: Data akan hilang selamanya dan tidak bisa dikembalikan!',
                async () => {
                    try {
                        showLoading('Menghapus Permanen...');
                        const response = await fetch('{{ route("categories.bulk-force-delete") }}', {
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
                            this.fetchCategories();
                            showToast('success', result.message);
                        } else {
                            showToast('error', result.message);
                        }
                    } catch (error) {
                        console.error('Error bulk force deleting:', error);
                        showToast('error', 'Gagal menghapus kategori');
                    } finally {
                        closeLoading();
                    }
                },
                { icon: 'warning', confirmText: 'Ya, Hapus Permanen!' }
            );
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

        // Generate slug from name
        generateSlug() {
            if (this.formData.name) {
                this.formData.slug = this.formData.name
                    .toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .trim();
            }
        },

        // Predefined colors
        colorOptions: [
            '#6366f1', // Indigo
            '#8b5cf6', // Violet
            '#ec4899', // Pink
            '#ef4444', // Red
            '#f97316', // Orange
            '#eab308', // Yellow
            '#22c55e', // Green
            '#14b8a6', // Teal
            '#06b6d4', // Cyan
            '#3b82f6', // Blue
        ],

        // Predefined icons
        iconOptions: [
            'folder', 'tag', 'bookmark', 'star', 'heart', 'flag',
            'newspaper', 'megaphone', 'bell', 'info', 'alert-circle',
            'file-text', 'image', 'video', 'music', 'calendar',
        ],
    }
}
</script>
@endpush
