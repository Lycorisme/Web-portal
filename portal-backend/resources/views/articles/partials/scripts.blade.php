<script>
function articleApp() {
    return {
        // State
        articles: [],
        categories: @json($categories ?? []),
        tags: @json($tags ?? []),
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
            thumbnail: null,
            thumbnail_url: '',
            category_id: '',
            tag_ids: [],
            read_time: null,
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
        detectedThreats: [],
        showSanitizePreview: false,
        sanitizedPreviewContent: '',
        auditInfo: null,

        // Selection & Trash
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

        // ========================================
        // SECURITY MODULE
        // ========================================
        @include('articles.partials.scripts.security')

        // ========================================
        // FORM MODULE
        // ========================================
        @include('articles.partials.scripts.form')

        // ========================================
        // CRUD MODULE
        // ========================================
        @include('articles.partials.scripts.crud')

        // ========================================
        // BULK ACTIONS MODULE
        // ========================================
        @include('articles.partials.scripts.bulk-actions')

        // ========================================
        // COMMENTS MODULE
        // ========================================
        @include('articles.partials.scripts.comments')

        // ========================================
        // STATISTICS MODULE
        // ========================================
        @include('articles.partials.scripts.statistics')

        // ========================================
        // HELPERS MODULE
        // ========================================
        @include('articles.partials.scripts.helpers')

        // ========================================
        // CORE METHODS
        // ========================================
        init() {
            this.fetchArticles();
            this.fetchTags();
            
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

        async fetchTags() {
            try {
                const response = await fetch(`{{ route('tags.data') }}?per_page=100&is_active=1`);
                const result = await response.json();
                if (result.success) {
                    this.tags = result.data;
                }
            } catch (error) {
                console.error('Error fetching tags:', error);
            }
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
    }
}
</script>
