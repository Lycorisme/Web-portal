<script>
function userApp() {
    return {
        // State
        users: [],
        loading: false,
        currentUserId: {{ auth()->id() }},
        currentUserIsSuperAdmin: {{ auth()->user()->isSuperAdmin() ? 'true' : 'false' }},
        
        // Menu State
        activeMenuUser: null,
        activeMenuButton: null,
        menuPosition: { top: 0, left: 0, placement: 'bottom' },
        
        // Modal State
        selectedUser: null,
        showDetailModal: false,
        showFormModal: false,
        formMode: 'create',
        formData: {
            id: null,
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
            role: 'author',
            phone: '',
            position: '',
            bio: '',
            location: '',
            profile_photo: null,
            profile_photo_preview: null,
        },
        formErrors: {},
        formLoading: false,
        showPassword: false,

        selectedIds: [],
        selectAll: false,
        showTrash: false,

        // Filters
        filters: { search: '', role: '', is_locked: '', sort_field: 'created_at' },

        // Pagination
        meta: { current_page: 1, last_page: 1, per_page: 15, total: 0, from: 0, to: 0 },

        // ========================================
        // FORM MODULE
        // ========================================
        @include('users.partials.scripts.form')

        // ========================================
        // CRUD MODULE
        // ========================================
        @include('users.partials.scripts.crud')

        // ========================================
        // BULK ACTIONS MODULE
        // ========================================
        @include('users.partials.scripts.bulk-actions')

        // ========================================
        // HELPERS MODULE
        // ========================================
        @include('users.partials.scripts.helpers')

        // ========================================
        // CORE METHODS
        // ========================================
        init() {
            this.fetchUsers();
            
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.kebab-menu-container') && !e.target.closest('[x-show="activeMenuUser"]')) {
                    this.activeMenuUser = null;
                }
            });

            const updatePositionHandler = () => {
                if (this.activeMenuUser && this.activeMenuButton) { this.updateMenuPosition(); }
            };

            const scrollContainer = document.querySelector('.table-scroll-container');
            if (scrollContainer) { scrollContainer.addEventListener('scroll', updatePositionHandler); }
            window.addEventListener('scroll', updatePositionHandler, true);
            window.addEventListener('resize', updatePositionHandler);

            this.$watch('showDetailModal', value => { document.body.classList.toggle('overflow-hidden', value); });
            this.$watch('showFormModal', value => { document.body.classList.toggle('overflow-hidden', value); });
        },

        async fetchUsers() {
            this.loading = true;
            this.selectedIds = [];
            this.selectAll = false;

            try {
                const params = new URLSearchParams({
                    page: this.meta.current_page,
                    per_page: this.meta.per_page,
                    status: this.showTrash ? 'trash' : 'active',
                    sort_field: this.filters.sort_field,
                    sort_direction: this.filters.sort_field === 'created_at' ? 'desc' : 'asc',
                    ...this.filters,
                });

                const response = await fetch(`{{ route('users.data') }}?${params}`);
                const result = await response.json();

                if (result.success) {
                    this.users = result.data;
                    this.meta = result.meta;
                    this.$nextTick(() => { lucide.createIcons(); });
                }
            } catch (error) {
                console.error('Error fetching users:', error);
                showToast('error', 'Gagal memuat data user');
            } finally { this.loading = false; }
        },

        toggleTrash() {
            this.showTrash = !this.showTrash;
            this.activeMenuUser = null;
            this.applyFilters();
        },

        // Menu Logic
        openMenu(user, event) {
            event.preventDefault();
            event.stopPropagation();

            if (this.activeMenuUser && this.activeMenuUser.id === user.id) {
                this.closeMenu();
                return;
            }

            this.activeMenuUser = user;
            this.activeMenuButton = event.currentTarget;
            this.updateMenuPosition();
        },

        updateMenuPosition() {
            if (!this.activeMenuButton) return;
            const rect = this.activeMenuButton.getBoundingClientRect();
            const viewportHeight = window.innerHeight;
            const spaceBelow = viewportHeight - rect.bottom;
            const menuHeightEstimate = 260;
            
            let placement = 'bottom';
            let topPos = rect.bottom + 4;

            if (spaceBelow < menuHeightEstimate && rect.top > menuHeightEstimate) {
                placement = 'top';
                topPos = rect.top - 4;
            }

            this.menuPosition = { top: topPos, left: rect.right - 192, placement: placement };
        },

        closeMenu() { this.activeMenuUser = null; this.activeMenuButton = null; },
        applyFilters() { this.meta.current_page = 1; this.fetchUsers(); },
        resetFilters() { this.filters = { search: '', role: '', is_locked: '', sort_field: 'created_at' }; this.applyFilters(); },
        goToPage(page) { if (page >= 1 && page <= this.meta.last_page) { this.meta.current_page = page; this.fetchUsers(); } },
    }
}
</script>
