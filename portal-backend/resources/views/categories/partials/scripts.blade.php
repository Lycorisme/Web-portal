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
        formMode: 'create',
        formData: { id: null, name: '', slug: '', description: '', color: '#6366f1', icon: 'folder', sort_order: 0, is_active: true },
        formErrors: {},
        formLoading: false,

        selectedIds: [],
        selectAll: false,
        showTrash: false,

        // Filters
        filters: { search: '', is_active: '' },

        // Pagination
        meta: { current_page: 1, last_page: 1, per_page: 15, total: 0, from: 0, to: 0 },

        // ========================================
        // FORM MODULE
        // ========================================
        @include('categories.partials.scripts.form')

        // ========================================
        // CRUD MODULE
        // ========================================
        @include('categories.partials.scripts.crud')

        // ========================================
        // BULK ACTIONS MODULE
        // ========================================
        @include('categories.partials.scripts.bulk-actions')

        // ========================================
        // HELPERS MODULE
        // ========================================
        @include('categories.partials.scripts.helpers')

        // ========================================
        // CORE METHODS
        // ========================================
        init() {
            this.fetchCategories();
            
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.kebab-menu-container') && !e.target.closest('[x-show="activeMenuCategory"]')) {
                    this.activeMenuCategory = null;
                }
            });

            const updatePositionHandler = () => {
                if (this.activeMenuCategory && this.activeMenuButton) { this.updateMenuPosition(); }
            };

            const scrollContainer = document.querySelector('.table-scroll-container');
            if (scrollContainer) { scrollContainer.addEventListener('scroll', updatePositionHandler); }
            window.addEventListener('scroll', updatePositionHandler, true);
            window.addEventListener('resize', updatePositionHandler);

            this.$watch('showDetailModal', value => { document.body.classList.toggle('overflow-hidden', value); });
            this.$watch('showFormModal', value => { document.body.classList.toggle('overflow-hidden', value); });
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
                    this.$nextTick(() => { lucide.createIcons(); });
                }
            } catch (error) {
                console.error('Error fetching categories:', error);
                showToast('error', 'Gagal memuat data kategori');
            } finally { this.loading = false; }
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

            this.menuPosition = { top: topPos, left: rect.right - 192, placement: placement };
        },

        closeMenu() { this.activeMenuCategory = null; this.activeMenuButton = null; },
        applyFilters() { this.meta.current_page = 1; this.fetchCategories(); },
        resetFilters() { this.filters = { search: '', is_active: '' }; this.applyFilters(); },
        goToPage(page) { if (page >= 1 && page <= this.meta.last_page) { this.meta.current_page = page; this.fetchCategories(); } },
    }
}
</script>
