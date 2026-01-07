<script>
function activityLogApp() {
    return {
        // State
        logs: [],
        loading: false,
        openKebabId: null,
        
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
        selectAll: false,
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
                if (!e.target.closest('.kebab-menu-container')) { this.openKebabId = null; }
            });

            this.$watch('showDetailModal', value => { document.body.classList.toggle('overflow-hidden', value); });
            this.$watch('showAutoDeleteModal', value => { document.body.classList.toggle('overflow-hidden', value); });
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
                    this.$nextTick(() => { lucide.createIcons(); });
                }
            } catch (error) {
                console.error('Error fetching logs:', error);
                showToast('error', 'Gagal memuat data log');
            } finally { this.loading = false; }
        },

        toggleTrash() {
            this.showTrash = !this.showTrash;
            this.openKebabId = null;
            this.applyFilters();
        },

        toggleKebab(id) {
            this.openKebabId = this.openKebabId === id ? null : id;
        },

        applyFilters() { this.meta.current_page = 1; this.fetchLogs(); },
        resetFilters() { 
            this.filters = { search: '', action: '', level: '', user_id: '', date_from: '', date_to: '' }; 
            this.applyFilters(); 
        },
        goToPage(page) { if (page >= 1 && page <= this.meta.last_page) { this.meta.current_page = page; this.fetchLogs(); } },
    }
}
</script>
