<script>
function blockedClientApp() {
    return {
        // State
        clients: [],
        loading: false,
        
        // Menu State
        activeMenuClient: null,
        activeMenuButton: null,
        menuPosition: { top: 0, left: 0, placement: 'bottom' },
        
        // Modal State
        selectedClient: null,
        showDetailModal: false,
        showFormModal: false,
        formMode: 'create',
        formData: {
            id: null,
            ip_address: '',
            reason: '',
            duration_type: 'temporary',
            duration_value: 60,
            duration_unit: 'minutes',
        },
        formErrors: {},
        formLoading: false,

        selectedIds: [],
        selectAll: false,

        // Filters
        filters: { search: '', status: '', sort_field: 'created_at', sort_direction: 'desc' },

        // Pagination
        meta: { current_page: 1, last_page: 1, per_page: 15, total: 0, from: 0, to: 0 },

        // ========================================
        // CRUD MODULE
        // ========================================
        @include('blocked-clients.partials.scripts.crud')

        // ========================================
        // BULK ACTIONS MODULE
        // ========================================
        @include('blocked-clients.partials.scripts.bulk-actions')

        // ========================================
        // HELPERS MODULE
        // ========================================
        @include('blocked-clients.partials.scripts.helpers')

        // ========================================
        // CORE METHODS
        // ========================================
        init() {
            this.fetchClients();
            
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.kebab-menu-container') && !e.target.closest('[x-show="activeMenuClient"]')) {
                    this.activeMenuClient = null;
                }
            });

            const updatePositionHandler = () => {
                if (this.activeMenuClient && this.activeMenuButton) { this.updateMenuPosition(); }
            };

            const scrollContainer = document.querySelector('.table-scroll-container');
            if (scrollContainer) { scrollContainer.addEventListener('scroll', updatePositionHandler); }
            window.addEventListener('scroll', updatePositionHandler, true);
            window.addEventListener('resize', updatePositionHandler);

            this.$watch('showDetailModal', value => { document.body.classList.toggle('overflow-hidden', value); });
            this.$watch('showFormModal', value => { document.body.classList.toggle('overflow-hidden', value); });
        },

        async fetchClients() {
            this.loading = true;
            this.selectedIds = [];
            this.selectAll = false;

            try {
                const params = new URLSearchParams({
                    page: this.meta.current_page,
                    per_page: this.meta.per_page,
                    sort_field: this.filters.sort_field,
                    sort_direction: this.filters.sort_direction,
                    ...this.filters,
                });

                const response = await fetch(`{{ route('blocked-clients.data') }}?${params}`);
                const result = await response.json();

                if (result.success) {
                    this.clients = result.data;
                    this.meta = result.meta;
                    this.$nextTick(() => { lucide.createIcons(); });
                }
            } catch (error) {
                console.error('Error fetching blocked clients:', error);
                showToast('error', 'Gagal memuat data');
            } finally { this.loading = false; }
        },

        // Menu Logic
        openMenu(client, event) {
            event.preventDefault();
            event.stopPropagation();

            if (this.activeMenuClient && this.activeMenuClient.id === client.id) {
                this.closeMenu();
                return;
            }

            this.activeMenuClient = client;
            this.activeMenuButton = event.currentTarget;
            this.updateMenuPosition();
        },

        updateMenuPosition() {
            if (!this.activeMenuButton) return;
            const rect = this.activeMenuButton.getBoundingClientRect();
            const viewportHeight = window.innerHeight;
            const spaceBelow = viewportHeight - rect.bottom;
            const menuHeightEstimate = 200;
            
            let placement = 'bottom';
            let topPos = rect.bottom + 4;

            if (spaceBelow < menuHeightEstimate && rect.top > menuHeightEstimate) {
                placement = 'top';
                topPos = rect.top - 4;
            }

            this.menuPosition = { top: topPos, left: rect.right - 192, placement: placement };
        },

        closeMenu() { this.activeMenuClient = null; this.activeMenuButton = null; },
        applyFilters() { this.meta.current_page = 1; this.fetchClients(); },
        resetFilters() { this.filters = { search: '', status: '', sort_field: 'created_at', sort_direction: 'desc' }; this.applyFilters(); },
        goToPage(page) { if (page >= 1 && page <= this.meta.last_page) { this.meta.current_page = page; this.fetchClients(); } },
    }
}
</script>
