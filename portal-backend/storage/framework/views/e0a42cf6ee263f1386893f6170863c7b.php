<script>
function galleryApp() {
    return {
        // ========================================
        // STATE DECLARATIONS
        // ========================================
        galleries: [],
        loading: false,
        
        // Menu State
        activeMenuItem: null,
        activeMenuButton: null,
        menuPosition: { top: 0, left: 0, placement: 'bottom' },
        
        // Modal State
        selectedItem: null,
        showDetailModal: false,
        showFormModal: false,
        showPreviewModal: false,
        showInfoModal: false,
        previewItem: null,
        previewCurrentIndex: 0,
        previewDirection: 'next',
        formMode: 'create',
        formData: {
            id: null,
            title: '',
            description: '',
            media_type: 'image',
            video_url: '',
            album: '',
            event_date: '',
            location: '',
            is_featured: false,
            is_published: true,
        },
        imageFiles: [],
        imagePreviews: [],
        formErrors: {},
        formLoading: false,
        activeTab: 'basic',

        // Album Autocomplete
        albums: [],
        showAlbumDropdown: false,
        albumSearch: '',

        // Album Modal State (for viewing grouped images)
        showAlbumModal: false,
        albumModalData: null,
        albumItems: [],
        albumCurrentIndex: 0,
        albumDirection: 'next',
        albumLoading: false,

        // View Mode (grouped vs individual)
        viewMode: 'grouped', // 'grouped' or 'individual'

        selectedIds: [],
        
        // Computed untuk cek apakah semua item di halaman saat ini sudah dipilih
        get selectAll() {
            if (this.galleries.length === 0) return false;
            return this.galleries.every(g => this.selectedIds.includes(g.id));
        },
        set selectAll(value) {
            // Setter diperlukan untuk x-model binding
        },
        showTrash: false,

        // Filters
        filters: { search: '', media_type: '', album: '', is_published: '', is_featured: '' },

        // Pagination
        meta: { current_page: 1, last_page: 1, per_page: 12, total: 0, from: 0, to: 0 },

        // ========================================
        // MODULE INCLUDES - FORM
        // ========================================
        <?php echo $__env->make('galleries.partials.scripts.form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        // ========================================
        // MODULE INCLUDES - CRUD
        // ========================================
        <?php echo $__env->make('galleries.partials.scripts.crud', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        // ========================================
        // MODULE INCLUDES - BULK ACTIONS
        // ========================================
        <?php echo $__env->make('galleries.partials.scripts.bulk-actions', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        // ========================================
        // MODULE INCLUDES - HELPERS
        // ========================================
        <?php echo $__env->make('galleries.partials.scripts.helpers', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        // ========================================
        // MODULE INCLUDES - ZOOM
        // ========================================
        <?php echo $__env->make('galleries.partials.scripts.zoom', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        // ========================================
        // MODULE INCLUDES - ALBUM MODAL
        // ========================================
        <?php echo $__env->make('galleries.partials.scripts.album-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        // ========================================
        // MODULE INCLUDES - PREVIEW & NAVIGATION
        // ========================================
        <?php echo $__env->make('galleries.partials.scripts.preview', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        // ========================================
        // MODULE INCLUDES - TOUCH SWIPE
        // ========================================
        <?php echo $__env->make('galleries.partials.scripts.touch-swipe', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        // ========================================
        // MODULE INCLUDES - MENU & NAVIGATION
        // ========================================
        <?php echo $__env->make('galleries.partials.scripts.menu-navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        // ========================================
        // MODULE INCLUDES - TOGGLE STATUS
        // ========================================
        <?php echo $__env->make('galleries.partials.scripts.toggle-status', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        // ========================================
        // MODULE INCLUDES - UTILITIES
        // ========================================
        <?php echo $__env->make('galleries.partials.scripts.utils', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        // ========================================
        // CORE METHODS
        // ========================================
        init() {
            this.fetchGalleries();
            this.fetchAlbums();
            this.initZoom();
            
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.gallery-card') && !e.target.closest('[x-show="activeMenuItem"]')) {
                    this.activeMenuItem = null;
                }
                // Close album dropdown when clicking outside
                if (!e.target.closest('.album-autocomplete')) {
                    this.showAlbumDropdown = false;
                }
            });

            this.$watch('showDetailModal', value => { document.body.classList.toggle('overflow-hidden', value); });
            this.$watch('showFormModal', value => { document.body.classList.toggle('overflow-hidden', value); });
            this.$watch('showPreviewModal', value => { document.body.classList.toggle('overflow-hidden', value); });
            this.$watch('showAlbumModal', value => { document.body.classList.toggle('overflow-hidden', value); });
            
            // Refresh Lucide icons when selection changes
            this.$watch('selectedIds.length', () => { this.$nextTick(() => { lucide.createIcons(); }); });

            // Keyboard shortcut: Ctrl+A to select all
            document.addEventListener('keydown', (e) => {
                // Don't trigger if typing in input, textarea, or contenteditable
                const activeEl = document.activeElement;
                const isTyping = activeEl && (
                    activeEl.tagName === 'INPUT' || 
                    activeEl.tagName === 'TEXTAREA' || 
                    activeEl.isContentEditable
                );
                
                // Don't trigger if any modal is open
                const modalOpen = this.showFormModal || this.showDetailModal || this.showPreviewModal || this.showAlbumModal;
                
                if (e.ctrlKey && e.key === 'a' && !isTyping && !modalOpen) {
                    e.preventDefault();
                    this.toggleSelectAll();
                }
            });
        },

        async fetchAlbums(search = '') {
            try {
                const params = new URLSearchParams({ search });
                const response = await fetch(`<?php echo e(route('galleries.albums')); ?>?${params}`);
                const result = await response.json();
                if (result.success) {
                    this.albums = result.data;
                }
            } catch (error) {
                console.error('Error fetching albums:', error);
            }
        },

        get filteredAlbums() {
            if (!this.formData.album) return this.albums;
            const search = this.formData.album.toLowerCase();
            return this.albums.filter(album => 
                album.toLowerCase().includes(search)
            );
        },

        selectAlbum(album) {
            this.formData.album = album;
            this.showAlbumDropdown = false;
        },

        toggleViewMode() {
            this.viewMode = this.viewMode === 'grouped' ? 'individual' : 'grouped';
            this.meta.current_page = 1;
            this.fetchGalleries();
        },

        async fetchGalleries() {
            this.loading = true;
            
            try {
                const params = new URLSearchParams({
                    page: this.meta.current_page,
                    per_page: this.meta.per_page,
                    status: this.showTrash ? 'trash' : 'active',
                    ...this.filters,
                });

                // Use grouped endpoint for grouped view mode
                const endpoint = this.viewMode === 'grouped' 
                    ? `<?php echo e(route('galleries.grouped')); ?>?${params}`
                    : `<?php echo e(route('galleries.data')); ?>?${params}`;

                const response = await fetch(endpoint);
                const result = await response.json();

                if (result.success) {
                    this.galleries = result.data;
                    this.meta = result.meta;
                    this.$nextTick(() => { lucide.createIcons(); });
                }
            } catch (error) {
                console.error('Error fetching galleries:', error);
                showToast('error', 'Gagal memuat data galeri');
            } finally { this.loading = false; }
        },
    }
}
</script>
<?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views\galleries\partials\scripts.blade.php ENDPATH**/ ?>