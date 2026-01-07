<script>
function tagApp() {
    return {
        // State
        tags: [],
        loading: false,
        
        // Menu State
        activeMenuTag: null,
        activeMenuButton: null,
        menuPosition: { top: 0, left: 0, placement: 'bottom' },
        
        // Modal State
        selectedTag: null,
        showDetailModal: false,
        showFormModal: false,
        formMode: 'create',
        formData: { id: null, name: '', slug: '' },
        formErrors: {},
        formLoading: false,

        selectedIds: [],
        selectAll: false,
        showTrash: false,

        // Filters
        filters: { search: '' },

        // Pagination
        meta: { current_page: 1, last_page: 1, per_page: 15, total: 0, from: 0, to: 0 },

        // ========================================
        // FORM MODULE
        // ========================================
        @include('tags.partials.scripts.form')

        // ========================================
        // CRUD MODULE
        // ========================================
        @include('tags.partials.scripts.crud')

        // ========================================
        // BULK ACTIONS MODULE
        // ========================================
        @include('tags.partials.scripts.bulk-actions')

        // ========================================
        // HELPERS MODULE
        // ========================================
        @include('tags.partials.scripts.helpers')

        // ========================================
        // CORE METHODS
        // ========================================
        init() {
            this.fetchTags();
            
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.kebab-menu-container') && !e.target.closest('[x-show="activeMenuTag"]')) {
                    this.activeMenuTag = null;
                }
            });

            const updatePositionHandler = () => {
                if (this.activeMenuTag && this.activeMenuButton) { this.updateMenuPosition(); }
            };

            const scrollContainer = document.querySelector('.table-scroll-container');
            if (scrollContainer) { scrollContainer.addEventListener('scroll', updatePositionHandler); }
            window.addEventListener('scroll', updatePositionHandler, true);
            window.addEventListener('resize', updatePositionHandler);

            this.$watch('showDetailModal', value => { document.body.classList.toggle('overflow-hidden', value); });
            this.$watch('showFormModal', value => { document.body.classList.toggle('overflow-hidden', value); });
        },

        async fetchTags() {
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

                const response = await fetch(`{{ route('tags.data') }}?${params}`);
                const result = await response.json();

                if (result.success) {
                    this.tags = result.data;
                    this.meta = result.meta;
                    this.$nextTick(() => { lucide.createIcons(); });
                }
            } catch (error) {
                console.error('Error fetching tags:', error);
                showToast('error', 'Gagal memuat data tag');
            } finally { this.loading = false; }
        },

        toggleTrash() {
            this.showTrash = !this.showTrash;
            this.activeMenuTag = null;
            this.applyFilters();
        },

        // Menu Logic
        openMenu(tag, event) {
            event.preventDefault();
            event.stopPropagation();

            if (this.activeMenuTag && this.activeMenuTag.id === tag.id) {
                this.closeMenu();
                return;
            }

            this.activeMenuTag = tag;
            this.activeMenuButton = event.currentTarget;
            this.updateMenuPosition();
        },

        updateMenuPosition() {
            if (!this.activeMenuButton) return;
            const rect = this.activeMenuButton.getBoundingClientRect();
            const viewportHeight = window.innerHeight;
            const spaceBelow = viewportHeight - rect.bottom;
            const menuHeightEstimate = 180;
            
            let placement = 'bottom';
            let topPos = rect.bottom + 4;

            if (spaceBelow < menuHeightEstimate && rect.top > menuHeightEstimate) {
                placement = 'top';
                topPos = rect.top - 4;
            }

            this.menuPosition = { top: topPos, left: rect.right - 192, placement: placement };
        },

        closeMenu() { this.activeMenuTag = null; this.activeMenuButton = null; },
        applyFilters() { this.meta.current_page = 1; this.fetchTags(); },
        resetFilters() { this.filters = { search: '' }; this.applyFilters(); },
        goToPage(page) { if (page >= 1 && page <= this.meta.last_page) { this.meta.current_page = page; this.fetchTags(); } },
    }
}
</script>
