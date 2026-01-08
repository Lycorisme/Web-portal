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
        selectAll: false,
        showTrash: false,

        // Filters
        filters: { search: '', media_type: '', album: '', is_published: '', is_featured: '' },

        // Pagination
        meta: { current_page: 1, last_page: 1, per_page: 12, total: 0, from: 0, to: 0 },

        // ========================================
        // MODULE INCLUDES - FORM
        // ========================================
        @include('galleries.partials.scripts.form')

        // ========================================
        // MODULE INCLUDES - CRUD
        // ========================================
        @include('galleries.partials.scripts.crud')

        // ========================================
        // MODULE INCLUDES - BULK ACTIONS
        // ========================================
        @include('galleries.partials.scripts.bulk-actions')

        // ========================================
        // MODULE INCLUDES - HELPERS
        // ========================================
        @include('galleries.partials.scripts.helpers')

        // ========================================
        // MODULE INCLUDES - ZOOM
        // ========================================
        @include('galleries.partials.scripts.zoom')

        // ========================================
        // MODULE INCLUDES - ALBUM MODAL
        // ========================================
        @include('galleries.partials.scripts.album-modal')

        // ========================================
        // MODULE INCLUDES - PREVIEW & NAVIGATION
        // ========================================
        @include('galleries.partials.scripts.preview')

        // ========================================
        // MODULE INCLUDES - TOUCH SWIPE
        // ========================================
        @include('galleries.partials.scripts.touch-swipe')

        // ========================================
        // MODULE INCLUDES - MENU & NAVIGATION
        // ========================================
        @include('galleries.partials.scripts.menu-navigation')

        // ========================================
        // MODULE INCLUDES - TOGGLE STATUS
        // ========================================
        @include('galleries.partials.scripts.toggle-status')

        // ========================================
        // MODULE INCLUDES - UTILITIES
        // ========================================
        @include('galleries.partials.scripts.utils')

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
        },

        async fetchAlbums(search = '') {
            try {
                const params = new URLSearchParams({ search });
                const response = await fetch(`{{ route('galleries.albums') }}?${params}`);
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
                    ? `{{ route('galleries.grouped') }}?${params}`
                    : `{{ route('galleries.data') }}?${params}`;

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
