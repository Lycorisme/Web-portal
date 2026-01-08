<script>
function galleryApp() {
    return {
        // State
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
        imageFile: null,
        imagePreview: null,
        formErrors: {},
        formLoading: false,

        selectedIds: [],
        selectAll: false,
        showTrash: false,

        // Filters
        filters: { search: '', media_type: '', album: '', is_published: '', is_featured: '' },

        // Pagination
        meta: { current_page: 1, last_page: 1, per_page: 12, total: 0, from: 0, to: 0 },

        // ========================================
        // FORM MODULE
        // ========================================
        @include('galleries.partials.scripts.form')

        // ========================================
        // CRUD MODULE
        // ========================================
        @include('galleries.partials.scripts.crud')

        // ========================================
        // BULK ACTIONS MODULE
        // ========================================
        @include('galleries.partials.scripts.bulk-actions')

        // ========================================
        // HELPERS MODULE
        // ========================================
        @include('galleries.partials.scripts.helpers')

        // ========================================
        // CORE METHODS
        // ========================================
        init() {
            this.fetchGalleries();
            
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.gallery-card') && !e.target.closest('[x-show="activeMenuItem"]')) {
                    this.activeMenuItem = null;
                }
            });

            this.$watch('showDetailModal', value => { document.body.classList.toggle('overflow-hidden', value); });
            this.$watch('showFormModal', value => { document.body.classList.toggle('overflow-hidden', value); });
            this.$watch('showPreviewModal', value => { document.body.classList.toggle('overflow-hidden', value); });
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

                const response = await fetch(`{{ route('galleries.data') }}?${params}`);
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

        toggleTrash() {
            this.showTrash = !this.showTrash;
            this.activeMenuItem = null;
            this.applyFilters();
        },

        toggleSelection(id) {
            if (this.selectedIds.includes(id)) {
                this.selectedIds = this.selectedIds.filter(itemId => itemId !== id);
                this.selectAll = false;
            } else {
                this.selectedIds.push(id);
                // Check if all items are selected
                const shownIds = this.galleries.map(g => g.id);
                const allSelected = shownIds.every(sid => this.selectedIds.includes(sid));
                if (allSelected && shownIds.length > 0) {
                    this.selectAll = true;
                }
            }
        },

        // Menu Logic
        openMenu(item, event) {
            event.preventDefault();
            event.stopPropagation();

            if (this.activeMenuItem && this.activeMenuItem.id === item.id) {
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
            const menuHeightEstimate = 250;
            
            let placement = 'bottom';
            let topPos = rect.bottom + 4;

            if (spaceBelow < menuHeightEstimate && rect.top > menuHeightEstimate) {
                placement = 'top';
                topPos = rect.top - 4;
            }

            this.menuPosition = { top: topPos, left: rect.left - 150, placement: placement };
        },

        closeMenu() { this.activeMenuItem = null; this.activeMenuButton = null; },
        applyFilters() { this.meta.current_page = 1; this.fetchGalleries(); },
        resetFilters() { this.filters = { search: '', media_type: '', album: '', is_published: '', is_featured: '' }; this.applyFilters(); },
        goToPage(page) { if (page >= 1 && page <= this.meta.last_page) { this.meta.current_page = page; this.fetchGalleries(); } },

        // Preview
        openPreview(item) {
            this.previewItem = item;
            this.previewCurrentIndex = this.galleries.findIndex(g => g.id === item.id);
            this.previewDirection = 'next';
            this.showPreviewModal = true;
            this.$nextTick(() => lucide.createIcons());
        },

        closePreview() {
            this.showPreviewModal = false;
            setTimeout(() => {
                this.previewItem = null;
            }, 300);
        },

        prevPreview() {
            this.previewDirection = 'prev';
            if (this.previewCurrentIndex > 0) {
                this.previewCurrentIndex--;
            } else {
                this.previewCurrentIndex = this.galleries.length - 1;
            }
            // Trigger reactivity by setting previewItem after direction is set
            this.previewItem = null; 
            this.$nextTick(() => {
                this.previewItem = this.galleries[this.previewCurrentIndex];
                this.$nextTick(() => lucide.createIcons());
            });
        },

        nextPreview() {
            this.previewDirection = 'next';
            if (this.previewCurrentIndex < this.galleries.length - 1) {
                this.previewCurrentIndex++;
            } else {
                this.previewCurrentIndex = 0;
            }
             // Trigger reactivity by setting previewItem after direction is set
            this.previewItem = null;
            this.$nextTick(() => {
                this.previewItem = this.galleries[this.previewCurrentIndex];
                this.$nextTick(() => lucide.createIcons());
            });
        },

        getYoutubeId(url) {
            if (!url) return null;
            const match = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/);
            return match ? match[1] : null;
        },

        // Toggle Status
        async togglePublished(item) {
            try {
                const response = await fetch(`/galleries/${item.id}/toggle-published`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const result = await response.json();

                if (result.success) {
                    const idx = this.galleries.findIndex(g => g.id === item.id);
                    if (idx !== -1) { this.galleries[idx].is_published = result.is_published; }
                    this.$nextTick(() => lucide.createIcons());
                    showToast('success', result.message);
                } else { showToast('error', result.message); }
            } catch (error) { console.error('Error:', error); showToast('error', 'Gagal mengubah status'); }
        },

        async toggleFeatured(item) {
            try {
                const response = await fetch(`/galleries/${item.id}/toggle-featured`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const result = await response.json();

                if (result.success) {
                    const idx = this.galleries.findIndex(g => g.id === item.id);
                    if (idx !== -1) { this.galleries[idx].is_featured = result.is_featured; }
                    this.$nextTick(() => lucide.createIcons());
                    showToast('success', result.message);
                } else { showToast('error', result.message); }
            } catch (error) { console.error('Error:', error); showToast('error', 'Gagal mengubah status'); }
        },
    }
}
</script>
