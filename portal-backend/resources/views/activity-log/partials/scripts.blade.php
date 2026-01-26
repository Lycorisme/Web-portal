<script>
function activityLogApp() {
    return {
        // State
        logs: [],
        loading: false,
        
        // Menu State
        activeMenuLog: null,
        activeMenuButton: null,
        menuPosition: { top: 0, left: 0, placement: 'bottom' },
        
        // Modal State
        selectedLog: null,
        showDetailModal: false,
        showAutoDeleteModal: false,
        isSavingSettings: false,
        
        // Auto Delete Settings
        autoDeleteSettings: {
            enabled: false,
            retention_days: 30,
            schedule: 'daily',
            schedule_time: '00:00',
        },

        // Selection
        selectedIds: [],
        
        // Computed untuk cek apakah semua item di halaman saat ini sudah dipilih
        get selectAll() {
            if (this.logs.length === 0) return false;
            return this.logs.every(log => this.selectedIds.includes(log.id));
        },
        set selectAll(value) {
            // Setter diperlukan untuk x-model binding
        },
        showTrash: false,

        // Filters
        filters: { search: '', action: '', level: '', user_id: '', date_from: '', date_to: '' },

        // Pagination
        meta: { current_page: 1, last_page: 1, per_page: 15, total: 0, from: 0, to: 0 },

        // ========================================
        // CRUD MODULE
        // ========================================
        @include('activity-log.partials.scripts.crud')

        // ========================================
        // BULK ACTIONS MODULE
        // ========================================
        @include('activity-log.partials.scripts.bulk-actions')

        // ========================================
        // AUTO DELETE MODULE
        // ========================================
        @include('activity-log.partials.scripts.auto-delete')

        // ========================================
        // HELPERS MODULE
        // ========================================
        @include('activity-log.partials.scripts.helpers')

        // ========================================
        // CORE METHODS
        // ========================================
        init() {
            this.fetchLogs();
            
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.kebab-menu-container') && !e.target.closest('[x-show="activeMenuLog"]')) {
                    this.activeMenuLog = null;
                }
            });

            const updatePositionHandler = () => {
                if (this.activeMenuLog && this.activeMenuButton) { this.updateMenuPosition(); }
            };

            const scrollContainer = document.querySelector('.table-scroll-container');
            if (scrollContainer) { scrollContainer.addEventListener('scroll', updatePositionHandler); }
            window.addEventListener('scroll', updatePositionHandler, true);
            window.addEventListener('resize', updatePositionHandler);

            this.$watch('showDetailModal', value => { document.body.classList.toggle('overflow-hidden', value); });
            this.$watch('showAutoDeleteModal', value => { document.body.classList.toggle('overflow-hidden', value); });
        },

        async fetchLogs() {
            this.loading = true;
            // Tidak reset selectedIds agar persist lintas pagination

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
                    this.$nextTick(() => { lucide.createIcons(); });
                }
            } catch (error) {
                console.error('Error fetching logs:', error);
                showToast('error', 'Gagal memuat data log');
            } finally { this.loading = false; }
        },

        toggleTrash() {
            this.showTrash = !this.showTrash;
            this.activeMenuLog = null;
            this.applyFilters();
        },

        // Menu Logic
        openMenu(log, event) {
            event.preventDefault();
            event.stopPropagation();

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

        closeMenu() { this.activeMenuLog = null; this.activeMenuButton = null; },
        applyFilters() { this.meta.current_page = 1; this.fetchLogs(); },
        resetFilters() { 
            this.filters = { search: '', action: '', level: '', user_id: '', date_from: '', date_to: '' }; 
            this.applyFilters(); 
        },
        goToPage(page) { if (page >= 1 && page <= this.meta.last_page) { this.meta.current_page = page; this.fetchLogs(); } },
    }
}
</script>
