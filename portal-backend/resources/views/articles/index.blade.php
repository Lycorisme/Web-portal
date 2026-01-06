@extends('layouts.app')

@section('title', 'Kelola Berita')

@section('content')
<div x-data="articleApp()" x-init="init()">
    {{-- Enhanced Page Header --}}
    <div class="relative mb-8 animate-fade-in group">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                {{-- Animated Icon Container --}}
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-tr from-theme-500/20 to-theme-300/20 rounded-2xl blur-lg opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative p-3.5 bg-white dark:bg-surface-800 rounded-2xl border border-surface-200/50 dark:border-surface-700/50 shadow-lg shadow-surface-100/50 dark:shadow-surface-900/50 ring-1 ring-white/50 dark:ring-surface-700/50">
                        <i data-lucide="newspaper" class="w-8 h-8 text-theme-600 dark:text-theme-400"></i>
                    </div>
                </div>
                
                {{-- Title & Subtitle --}}
                <div>
                    <h1 class="text-3xl font-bold text-surface-900 dark:text-white tracking-tight mb-2">
                        Kelola Berita
                    </h1>
                    <nav class="flex items-center gap-2 text-sm font-medium text-surface-500 dark:text-surface-400">
                        <a href="{{ route('dashboard') }}" class="hover:text-theme-600 transition-colors flex items-center gap-1.5">
                            <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                        </a>
                        <i data-lucide="chevron-right" class="w-3 h-3 text-surface-300 dark:text-surface-600"></i>
                        <span class="text-theme-600 dark:text-theme-400">Berita</span>
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
            @include('articles.partials.filter')

            {{-- Table Section --}}
            @include('articles.partials.table')

            {{-- Pagination Section --}}
            @include('articles.partials.pagination')
        </div>
    </div>

    {{-- Bulk Action Bar --}}
    @include('articles.partials.bulk-action-bar')

    {{-- Action Menu --}}
    @include('articles.partials.action-menu')

    {{-- Form Modal (Create/Edit) --}}
    @include('articles.partials.form-modal')

    {{-- Detail Modal --}}
    @include('articles.partials.detail-modal')
</div>
@endsection

@push('scripts')
<script>
function articleApp() {
    return {
        // State
        articles: [],
        categories: @json($categories ?? []),
        loading: false,
        
        // Menu State
        activeMenuArticle: null,
        activeMenuButton: null,
        menuPosition: { top: 0, left: 0, placement: 'bottom' },
        
        // Modal State
        selectedArticle: null,
        showDetailModal: false,
        showFormModal: false,
        formMode: 'create', // 'create' or 'edit'
        formData: {
            id: null,
            title: '',
            slug: '',
            excerpt: '',
            content: '',
            thumbnail: null, // Changed to null for file object
            thumbnail_url: '', // For previewing existing or new image
            category_id: '',
            read_time: null,
            status: 'draft',
            meta_title: '',
            meta_description: '',
            meta_keywords: '',
            published_at: null,
        },
        formErrors: {},
        formLoading: false,

        selectedIds: [],
        selectAll: false,
        showTrash: false,

        // Filters
        filters: {
            search: '',
            article_status: '',
            category_id: '',
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
            this.fetchArticles();
            
            // Close menu when clicking outside
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.kebab-menu-container') && !e.target.closest('[x-show="activeMenuArticle"]')) {
                    this.activeMenuArticle = null;
                }
            });

            // Update menu position on scroll
            const updatePositionHandler = () => {
                if (this.activeMenuArticle && this.activeMenuButton) {
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

        async fetchArticles() {
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

                const response = await fetch(`{{ route('articles.data') }}?${params}`);
                const result = await response.json();

                if (result.success) {
                    this.articles = result.data;
                    this.meta = result.meta;
                    this.$nextTick(() => {
                        lucide.createIcons();
                    });
                }
            } catch (error) {
                console.error('Error fetching articles:', error);
                showToast('error', 'Gagal memuat data berita');
            } finally {
                this.loading = false;
            }
        },

        toggleTrash() {
            this.showTrash = !this.showTrash;
            this.activeMenuArticle = null;
            this.applyFilters();
        },

        // Menu Logic
        openMenu(article, event) {
            event.preventDefault();
            event.stopPropagation();

            if (this.activeMenuArticle && this.activeMenuArticle.id === article.id) {
                this.closeMenu();
                return;
            }

            this.activeMenuArticle = article;
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
            this.activeMenuArticle = null;
            this.activeMenuButton = null;
        },

        applyFilters() {
            this.meta.current_page = 1;
            this.fetchArticles();
        },

        resetFilters() {
            this.filters = {
                search: '',
                article_status: '',
                category_id: '',
            };
            this.applyFilters();
        },

        goToPage(page) {
            if (page >= 1 && page <= this.meta.last_page) {
                this.meta.current_page = page;
                this.fetchArticles();
            }
        },

        // Form Modal Logic
        openCreateModal() {
            this.formMode = 'create';
            this.formData = {
                id: null,
                title: '',
                slug: '',
                excerpt: '',
                content: '',
                thumbnail: null,
                thumbnail_url: '',
                category_id: '',
                read_time: null,
                status: 'draft',
                meta_title: '',
                meta_description: '',
                meta_keywords: '',
                published_at: null,
            };
            this.formErrors = {};
            this.showFormModal = true;
            this.$nextTick(() => lucide.createIcons());
        },

        openEditModal(article) {
            this.formMode = 'edit';
            this.formData = {
                id: article.id,
                title: article.title,
                slug: article.slug,
                excerpt: article.excerpt || '',
                content: article.content || '',
                thumbnail: null, // Reset file input
                thumbnail_url: article.thumbnail || '', // Existing URL
                category_id: article.category_id || '',
                read_time: article.read_time,
                status: article.status,
                meta_title: article.meta_title || '',
                meta_description: article.meta_description || '',
                meta_keywords: article.meta_keywords || '',
                published_at: article.published_at,
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
                title: '',
                slug: '',
                excerpt: '',
                content: '',
                thumbnail: null,
                thumbnail_url: '',
                category_id: '',
                read_time: null,
                status: 'draft',
                meta_title: '',
                meta_description: '',
                meta_keywords: '',
                published_at: null,
            };
            this.formErrors = {};
        },

        async submitForm() {
            this.formLoading = true;
            this.formErrors = {};

            try {
                const url = this.formMode === 'create' 
                    ? '{{ route("articles.store") }}'
                    : `/articles/${this.formData.id}`;
                
                const method = this.formMode === 'create' ? 'POST' : 'PUT';

                const formDataStart = new FormData();
                
                // Append all fields
                formDataStart.append('title', this.formData.title);
                if (this.formData.slug) formDataStart.append('slug', this.formData.slug);
                if (this.formData.excerpt) formDataStart.append('excerpt', this.formData.excerpt);
                if (this.formData.content) formDataStart.append('content', this.formData.content);
                if (this.formData.category_id) formDataStart.append('category_id', this.formData.category_id);
                if (this.formData.read_time) formDataStart.append('read_time', this.formData.read_time);
                formDataStart.append('status', this.formData.status);
                if (this.formData.meta_title) formDataStart.append('meta_title', this.formData.meta_title);
                if (this.formData.meta_description) formDataStart.append('meta_description', this.formData.meta_description);
                if (this.formData.meta_keywords) formDataStart.append('meta_keywords', this.formData.meta_keywords);
                if (this.formData.published_at) formDataStart.append('published_at', this.formData.published_at);

                // Handle Thumbnail File
                if (this.formData.thumbnail instanceof File) {
                    formDataStart.append('thumbnail', this.formData.thumbnail);
                }

                // Method spoofing for PUT since FormData sends as multipart/form-data
                if (this.formMode === 'edit') {
                    formDataStart.append('_method', 'PUT');
                }

                const response = await fetch(url, {
                    method: 'POST', // Always POST for FormData with binary (even for updates, using _method)
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        // 'Content-Type': 'multipart/form-data', // Do NOT set this manually, let browser set boundary
                     },
                    body: formDataStart,
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    this.closeFormModal();
                    this.fetchArticles();
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
                showToast('error', 'Gagal menyimpan berita');
            } finally {
                this.formLoading = false;
            }
        },

        // Detail Modal
        async viewDetail(id) {
            this.closeMenu();
            
            try {
                showLoading('Memuat detail...');
                const response = await fetch(`/articles/${id}`);
                const result = await response.json();

                if (result.success) {
                    this.selectedArticle = result.data;
                    this.showDetailModal = true;
                    this.$nextTick(() => lucide.createIcons());
                }
            } catch (error) {
                console.error('Error fetching detail:', error);
                showToast('error', 'Gagal memuat detail berita');
            } finally {
                closeLoading();
            }
        },

        closeDetailModal() {
            this.showDetailModal = false;
            this.selectedArticle = null;
        },

        // Delete Actions
        async deleteArticle(id) {
            this.closeMenu();

            showConfirm(
                'Hapus Berita?',
                'Berita ini akan dipindahkan ke tong sampah.',
                async () => {
                    try {
                        showLoading('Menghapus...');
                        const response = await fetch(`/articles/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                        });
                        const result = await response.json();

                        if (result.success) {
                            this.fetchArticles();
                            showToast('success', result.message);
                        } else {
                            showToast('error', result.message);
                        }
                    } catch (error) {
                        console.error('Error deleting article:', error);
                        showToast('error', 'Gagal menghapus berita');
                    } finally {
                        closeLoading();
                    }
                },
                { icon: 'warning', confirmText: 'Ya, Hapus!' }
            );
        },

        async restoreArticle(id) {
            this.closeMenu();
            showConfirm(
                'Pulihkan Berita?',
                'Berita akan dikembalikan ke daftar aktif.',
                async () => {
                    try {
                        showLoading('Memulihkan...');
                        const response = await fetch(`/articles/${id}/restore`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                        });
                        const result = await response.json();

                        if (result.success) {
                            this.fetchArticles();
                            showToast('success', result.message);
                        } else {
                            showToast('error', result.message);
                        }
                    } catch (error) {
                        console.error('Error restoring article:', error);
                        showToast('error', 'Gagal memulihkan berita');
                    } finally {
                        closeLoading();
                    }
                },
                { icon: 'info', confirmText: 'Ya, Pulihkan!' }
            );
        },

        async forceDeleteArticle(id) {
            this.closeMenu();
            showConfirm(
                'Hapus Permanen?',
                'Data yang dihapus permanen TIDAK BISA dipulihkan kembali.',
                async () => {
                    try {
                        showLoading('Menghapus Permanen...');
                        const response = await fetch(`/articles/${id}/force`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                        });
                        const result = await response.json();

                        if (result.success) {
                            this.fetchArticles();
                            showToast('success', result.message);
                        } else {
                            showToast('error', result.message);
                        }
                    } catch (error) {
                        console.error('Error force deleting article:', error);
                        showToast('error', 'Gagal menghapus berita');
                    } finally {
                        closeLoading();
                    }
                },
                { icon: 'warning', confirmText: 'Hapus Permanen!', cancelText: 'Batal' }
            );
        },

        // Toggle Status
        async changeStatus(article, newStatus) {
            try {
                const response = await fetch(`/articles/${article.id}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ status: newStatus }),
                });
                const result = await response.json();

                if (result.success) {
                    // Update local state
                    const idx = this.articles.findIndex(a => a.id === article.id);
                    if (idx !== -1) {
                        this.articles[idx].status = result.status;
                    }
                    showToast('success', result.message);
                } else {
                    showToast('error', result.message);
                }
            } catch (error) {
                console.error('Error changing status:', error);
                showToast('error', 'Gagal mengubah status');
            }
        },

        // Selection
        toggleSelectAll() {
            if (this.selectAll) {
                this.selectedIds = this.articles.map(a => a.id);
            } else {
                this.selectedIds = [];
            }
        },

        // Bulk Actions
        async bulkDelete() {
            if (this.selectedIds.length === 0) {
                showToast('warning', 'Pilih berita yang ingin dihapus');
                return;
            }

            showConfirm(
                `Hapus ${this.selectedIds.length} Berita?`,
                'Semua berita yang dipilih akan dipindahkan ke tong sampah.',
                async () => {
                    try {
                        showLoading('Menghapus...');
                        const response = await fetch('{{ route("articles.bulk-destroy") }}', {
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
                            this.fetchArticles();
                            showToast('success', result.message);
                        } else {
                            showToast('error', result.message);
                        }
                    } catch (error) {
                        console.error('Error bulk deleting:', error);
                        showToast('error', 'Gagal menghapus berita');
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
                `Pulihkan ${this.selectedIds.length} Berita?`,
                'Item terpilih akan dikembalikan ke daftar aktif.',
                async () => {
                    try {
                        showLoading('Memulihkan...');
                        const response = await fetch('{{ route("articles.bulk-restore") }}', {
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
                            this.fetchArticles();
                            showToast('success', result.message);
                        } else {
                            showToast('error', result.message);
                        }
                    } catch (error) {
                        console.error('Error bulk restoring:', error);
                        showToast('error', 'Gagal memulihkan berita');
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
                `Hapus Permanen ${this.selectedIds.length} Berita?`,
                'PERINGATAN: Data akan hilang selamanya dan tidak bisa dikembalikan!',
                async () => {
                    try {
                        showLoading('Menghapus Permanen...');
                        const response = await fetch('{{ route("articles.bulk-force-delete") }}', {
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
                            this.fetchArticles();
                            showToast('success', result.message);
                        } else {
                            showToast('error', result.message);
                        }
                    } catch (error) {
                        console.error('Error bulk force deleting:', error);
                        showToast('error', 'Gagal menghapus berita');
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

        // Generate slug from title
        generateSlug() {
            if (this.formData.title) {
                this.formData.slug = this.formData.title
                    .toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .trim();
            }
        },

        // Status options
        statusOptions: [
            { value: 'draft', label: 'Draft', color: 'surface' },
            { value: 'pending', label: 'Pending', color: 'amber' },
            { value: 'published', label: 'Published', color: 'emerald' },
            { value: 'rejected', label: 'Rejected', color: 'rose' },
        ],

        getStatusLabel(status) {
            const option = this.statusOptions.find(o => o.value === status);
            return option ? option.label : status;
        },

        getStatusColor(status) {
            const colors = {
                draft: 'bg-surface-100 text-surface-600 dark:bg-surface-700 dark:text-surface-300',
                pending: 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400',
                published: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400',
                rejected: 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400',
            };
            return colors[status] || colors.draft;
        },
    }
}
</script>
@endpush
