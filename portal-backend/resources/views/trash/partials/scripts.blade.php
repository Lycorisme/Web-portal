<script>
function trashApp() {
    return {
        // State
        items: [],
        loading: false,
        counts: @json($counts ?? []),
        currentUserIsSuperAdmin: {{ auth()->user()->isSuperAdmin() ? 'true' : 'false' }},
        
        // Menu State
        activeMenuItem: null,
        activeMenuButton: null,
        menuPosition: { top: 0, left: 0, placement: 'bottom' },
        
        // Selection
        selectedItems: [],
        selectAll: false,

        // Filters
        filters: { search: '', type: 'all' },

        // Pagination
        meta: { current_page: 1, last_page: 1, per_page: 15, total: 0, from: 0, to: 0 },

        // ========================================
        // CRUD MODULE
        // ========================================
        @include('trash.partials.scripts.crud')

        // ========================================
        // BULK ACTIONS MODULE
        // ========================================
        @include('trash.partials.scripts.bulk-actions')

        // ========================================
        // HELPERS MODULE
        // ========================================
        @include('trash.partials.scripts.helpers')

        // ========================================
        // CORE METHODS
        // ========================================
        init() {
            this.fetchItems();
            
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.kebab-menu-container') && !e.target.closest('[x-show="activeMenuItem"]')) {
                    this.activeMenuItem = null;
                }
            });

            const updatePositionHandler = () => {
                if (this.activeMenuItem && this.activeMenuButton) { this.updateMenuPosition(); }
            };

            const scrollContainer = document.querySelector('.table-scroll-container');
            if (scrollContainer) { scrollContainer.addEventListener('scroll', updatePositionHandler); }
            window.addEventListener('scroll', updatePositionHandler, true);
            window.addEventListener('resize', updatePositionHandler);
        },

        async fetchItems() {
            this.loading = true;
            this.selectedItems = [];
            this.selectAll = false;

            try {
                const params = new URLSearchParams({
                    page: this.meta.current_page,
                    per_page: this.meta.per_page,
                    type: this.filters.type,
                    search: this.filters.search,
                });

                const response = await fetch(`{{ route('trash.data') }}?${params}`);
                const result = await response.json();

                if (result.success) {
                    this.items = result.data;
                    this.meta = result.meta;
                    this.counts = result.counts;
                    this.$nextTick(() => { lucide.createIcons(); });
                }
            } catch (error) {
                console.error('Error fetching items:', error);
                showToast('error', 'Gagal memuat data');
            } finally { this.loading = false; }
        },

        // Menu Logic
        openMenu(item, event) {
            event.preventDefault();
            event.stopPropagation();

            if (this.activeMenuItem && this.activeMenuItem.id === item.id && this.activeMenuItem.type === item.type) {
                this.closeMenu();
                return;
            }

            this.activeMenuItem = item;
            this.activeMenuButton = event.currentTarget;
            this.updateMenuPosition();
        },

        updateMenuPosition() {
            if (!this.activeMenuButton) return;
            const rect = this.activeMenuButton.getBoundingClientRect();
            const viewportHeight = window.innerHeight;
            const spaceBelow = viewportHeight - rect.bottom;
            const menuHeightEstimate = 120;
            
            let placement = 'bottom';
            let topPos = rect.bottom + 4;

            if (spaceBelow < menuHeightEstimate && rect.top > menuHeightEstimate) {
                placement = 'top';
                topPos = rect.top - 4;
            }

            this.menuPosition = { top: topPos, left: rect.right - 192, placement: placement };
        },

        closeMenu() { this.activeMenuItem = null; this.activeMenuButton = null; },
        applyFilters() { this.meta.current_page = 1; this.fetchItems(); },
        goToPage(page) { if (page >= 1 && page <= this.meta.last_page) { this.meta.current_page = page; this.fetchItems(); } },
    }
}
</script>
