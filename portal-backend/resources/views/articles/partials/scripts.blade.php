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
            status: 'draft',
            meta_title: '',
            meta_description: '',
            meta_keywords: '',
            published_at: null,
            is_pinned: false,
            is_headline: false,
        },
        formErrors: {},
        formLoading: false,
        
        // Form UI State
        activeTab: 'content',
        injectionDetected: false,
        dangerousKeywords: ['<script', 'javascript:', 'onclick', 'onload', 'iframe', 'slot gacor', 'zeus', 'pragmatic', 'bet88', 'judol'],
        auditInfo: null, // { created_by: '', created_at: '', updated_by: '', updated_at: '' }

        // Statistics Modal State
        showStatisticsModal: false,
        statisticsData: null,
        statisticsLoading: false,
        
        statisticsLoading: false,
        
        checkContentSafety(content) {
            if (!content) {
                this.injectionDetected = false;
                return;
            }
            const lowerContent = content.toLowerCase();
            this.injectionDetected = this.dangerousKeywords.some(keyword => lowerContent.includes(keyword));
        },

        sanitizeContent() {
             let content = this.formData.content;
             if (!content) return;
             
             this.dangerousKeywords.forEach(keyword => {
                 const regex = new RegExp(keyword.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'gi');
                 content = content.replace(regex, '');
             });
             
             this.formData.content = content;
             
             // Update Trix Editor
             const element = document.querySelector('trix-editor');
             if(element && element.editor) {
                 element.editor.loadHTML(content);
             }
             
             this.checkContentSafety(content);
             showToast('success', 'Konten telah dibersihkan otomatis.');
        },

        // Activity Modal State
        showActivityModal: false,
        activityLogs: [],
        activityLogLoading: false,
        activityLogArticleTitle: '',
        
        // Comment Reply State
        replyingTo: null,
        replyText: '',
        replyLoading: false,

        // Detail Modal Comments
        detailComments: [],
        detailCommentsLoading: false,

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
            this.$watch('showStatisticsModal', value => {
                document.body.classList.toggle('overflow-hidden', value);
                if (!value) {
                    this.replyingTo = null;
                    this.replyText = '';
                }
            });
            this.$watch('showActivityModal', value => {
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
                is_pinned: false,
                is_headline: false,
            };
            this.activeTab = 'content';
            this.injectionDetected = false;
            this.auditInfo = null;
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
                is_pinned: article.is_pinned || false,
                is_headline: article.is_headline || false,
            };
            this.activeTab = 'content';
            this.auditInfo = {
                created_by: article.user ? article.user.name : 'Unknown',
                created_at: article.created_at,
                updated_by: 'Unknown', // Ideally backend sends this
                updated_at: article.updated_at
            };
            this.checkContentSafety(this.formData.content);
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
                is_pinned: false,
                is_headline: false,
            };
            this.activeTab = 'content';
            this.auditInfo = null;
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
                if (this.formData.meta_keywords) formDataStart.append('meta_keywords', this.formData.meta_keywords);
                if (this.formData.published_at) formDataStart.append('published_at', this.formData.published_at);
                formDataStart.append('is_pinned', this.formData.is_pinned ? 1 : 0);
                formDataStart.append('is_headline', this.formData.is_headline ? 1 : 0);

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
                    
                    // Fetch comments for this article
                    this.fetchDetailComments(id);
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
            this.detailComments = [];
            this.detailCommentsLoading = false;
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

        // Statistics Modal Methods
        async openStatisticsModal(articleId) {
            this.showStatisticsModal = true;
            this.statisticsLoading = true;
            this.statisticsData = null;

            try {
                const response = await fetch(`/articles/${articleId}/statistics`);
                const result = await response.json();

                if (result.success) {
                    this.statisticsData = result.data;
                    this.$nextTick(() => lucide.createIcons());
                } else {
                    showToast('error', result.message || 'Gagal memuat statistik');
                }
            } catch (error) {
                console.error('Error fetching statistics:', error);
                showToast('error', 'Gagal memuat statistik');
            } finally {
                this.statisticsLoading = false;
            }
        },


        closeStatisticsModal() {
            this.showStatisticsModal = false;
            this.statisticsData = null;
            this.replyingTo = null;
            this.replyText = '';
        },

        // Activity Modal Methods
        async openActivityModal(articleId, articleTitle) {
            this.showActivityModal = true;
            this.activityLogLoading = true;
            this.activityLogs = [];
            this.activityLogArticleTitle = articleTitle;

            try {
                const response = await fetch(`/articles/${articleId}/activities`);
                const result = await response.json();

                if (result.success) {
                    this.activityLogs = result.data;
                    this.$nextTick(() => lucide.createIcons());
                } else {
                    showToast('error', result.message || 'Gagal memuat log aktivitas');
                }
            } catch (error) {
                console.error('Error fetching activity log:', error);
                showToast('error', 'Gagal memuat log aktivitas');
            } finally {
                this.activityLogLoading = false;
            }
        },

        closeActivityModal() {
            this.showActivityModal = false;
            this.activityLogs = [];
        },

        // Comment Reply Methods
        openReplyForm(comment) {
            this.replyingTo = comment;
            this.replyText = '';
            this.$nextTick(() => {
                this.$refs.replyTextarea?.focus();
            });
        },

        cancelReply() {
            this.replyingTo = null;
            this.replyText = '';
        },

        async submitReply() {
            if (!this.replyingTo || !this.replyText.trim()) return;

            this.replyLoading = true;

            try {
                const response = await fetch(`/comments/${this.replyingTo.id}/reply`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ comment_text: this.replyText }),
                });
                const result = await response.json();

                if (result.success) {
                    showToast('success', result.message);
                    // Refresh statistics to show new reply
                    if (this.statisticsData?.article_id) {
                        await this.openStatisticsModal(this.statisticsData.article_id);
                    }
                    this.cancelReply();
                } else {
                    showToast('error', result.message || 'Gagal mengirim balasan');
                }
            } catch (error) {
                console.error('Error submitting reply:', error);
                showToast('error', 'Gagal mengirim balasan');
            } finally {
                this.replyLoading = false;
            }
        },

        // Comment Status Methods
        async hideComment(commentId) {
            try {
                const response = await fetch(`/comments/${commentId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ status: 'hidden' }),
                });
                const result = await response.json();

                if (result.success) {
                    showToast('success', 'Komentar disembunyikan');
                    if (this.statisticsData?.article_id) {
                        await this.openStatisticsModal(this.statisticsData.article_id);
                    }
                } else {
                    showToast('error', result.message);
                }
            } catch (error) {
                console.error('Error hiding comment:', error);
                showToast('error', 'Gagal menyembunyikan komentar');
            }
        },

        async showComment(commentId) {
            try {
                const response = await fetch(`/comments/${commentId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ status: 'visible' }),
                });
                const result = await response.json();

                if (result.success) {
                    showToast('success', 'Komentar ditampilkan');
                    if (this.statisticsData?.article_id) {
                        await this.openStatisticsModal(this.statisticsData.article_id);
                    }
                } else {
                    showToast('error', result.message);
                }
            } catch (error) {
                console.error('Error showing comment:', error);
                showToast('error', 'Gagal menampilkan komentar');
            }
        },

        async deleteComment(commentId) {
            showConfirm(
                'Hapus Komentar?',
                'Komentar akan disembunyikan dan dapat dipulihkan dari database.',
                async () => {
                    try {
                        showLoading('Menghapus...');
                        const response = await fetch(`/comments/${commentId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                        });
                        const result = await response.json();

                        if (result.success) {
                            showToast('success', result.message);
                            if (this.statisticsData?.article_id) {
                                await this.openStatisticsModal(this.statisticsData.article_id);
                            }
                            // Also refresh detail modal comments if open
                            if (this.showDetailModal && this.selectedArticle) {
                                await this.fetchDetailComments(this.selectedArticle.id);
                            }
                        } else {
                            showToast('error', result.message);
                        }
                    } catch (error) {
                        console.error('Error deleting comment:', error);
                        showToast('error', 'Gagal menghapus komentar');
                    } finally {
                        closeLoading();
                    }
                },
                { icon: 'warning', confirmText: 'Ya, Hapus!' }
            );
        },

        // Fetch comments for detail modal
        async fetchDetailComments(articleId) {
            this.detailCommentsLoading = true;
            this.detailComments = [];

            try {
                const response = await fetch(`/articles/${articleId}/comments`);
                const result = await response.json();

                if (result.success) {
                    this.detailComments = result.data;
                    this.$nextTick(() => lucide.createIcons());
                }
            } catch (error) {
                console.error('Error fetching comments:', error);
            } finally {
                this.detailCommentsLoading = false;
            }
        },

        // Comment status badge color helper
        getCommentStatusColor(status) {
            const colors = {
                visible: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400',
                hidden: 'bg-surface-100 text-surface-600 dark:bg-surface-700 dark:text-surface-300',
                spam: 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400',
                reported: 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400',
            };
            return colors[status] || colors.visible;
        },
    }
}
</script>
