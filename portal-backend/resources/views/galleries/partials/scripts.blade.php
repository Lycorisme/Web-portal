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
            this.fetchAlbums();
            
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

        // ========================================
        // ALBUM MODAL METHODS
        // ========================================
        async openAlbumModal(item) {
            if (!item.is_group || item.group_count <= 1) {
                // If not a group, just open normal preview
                this.openPreview(item);
                return;
            }

            this.albumModalData = item;
            this.albumItems = [];
            this.albumCurrentIndex = 0;
            this.albumLoading = true;
            this.showAlbumModal = true;

            try {
                const params = new URLSearchParams();
                item.group_item_ids.forEach(id => params.append('ids[]', id));
                
                const response = await fetch(`{{ route('galleries.album-items') }}?${params}`);
                const result = await response.json();

                if (result.success) {
                    this.albumItems = result.data;
                }
            } catch (error) {
                console.error('Error fetching album items:', error);
                showToast('error', 'Gagal memuat gambar album');
            } finally {
                this.albumLoading = false;
                this.$nextTick(() => lucide.createIcons());
            }
        },

        closeAlbumModal() {
            this.showAlbumModal = false;
            setTimeout(() => {
                this.albumModalData = null;
                this.albumItems = [];
                this.albumCurrentIndex = 0;
            }, 300);
        },

        prevAlbumItem() {
            this.albumDirection = 'prev';
            if (this.albumCurrentIndex > 0) {
                this.albumCurrentIndex--;
            } else {
                this.albumCurrentIndex = this.albumItems.length - 1;
            }
            this.$nextTick(() => lucide.createIcons());
        },

        nextAlbumItem() {
            this.albumDirection = 'next';
            if (this.albumCurrentIndex < this.albumItems.length - 1) {
                this.albumCurrentIndex++;
            } else {
                this.albumCurrentIndex = 0;
            }
            this.$nextTick(() => lucide.createIcons());
        },

        get currentAlbumItem() {
            return this.albumItems[this.albumCurrentIndex] || null;
        },

        get albumTransition() {
            const isNext = this.albumDirection === 'next';
            return {
                ['x-transition:enter']: 'transition ease-[cubic-bezier(0.33,1,0.68,1)] duration-500',
                ['x-transition:enter-start']: isNext ? 'opacity-100 translate-x-full' : 'opacity-100 -translate-x-full',
                ['x-transition:enter-end']: 'opacity-100 translate-x-0',
                ['x-transition:leave']: 'transition ease-[cubic-bezier(0.33,1,0.68,1)] duration-500 absolute top-0 left-0 w-full z-0',
                ['x-transition:leave-start']: 'opacity-100 translate-x-0',
                ['x-transition:leave-end']: isNext ? 'opacity-100 -translate-x-full' : 'opacity-100 translate-x-full',
            };
        },

        goToAlbumItem(index) {
            if (index > this.albumCurrentIndex) {
                this.albumDirection = 'next';
            } else if (index < this.albumCurrentIndex) {
                this.albumDirection = 'prev';
            }
            this.albumCurrentIndex = index;
            this.$nextTick(() => lucide.createIcons());
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

        // Touch Swipe State
        touchStartX: 0,
        touchEndX: 0,

        onTouchStart(e) {
            this.touchStartX = e.changedTouches[0].screenX;
        },

        onTouchEnd(e) {
            this.touchEndX = e.changedTouches[0].screenX;
            this.handleSwipeGesture();
        },

        handleSwipeGesture() {
            if (this.touchEndX < this.touchStartX - 50) {
                // Swipe Left -> Next
                this.nextPreview();
            }
            if (this.touchEndX > this.touchStartX + 50) {
                // Swipe Right -> Prev
                this.prevPreview();
            }
        },

        get previewTransition() {
            const isNext = this.previewDirection === 'next';
            return {
                ['x-transition:enter']: 'transition ease-[cubic-bezier(0.33,1,0.68,1)] duration-500',
                ['x-transition:enter-start']: isNext ? 'opacity-100 translate-x-full' : 'opacity-100 -translate-x-full',
                ['x-transition:enter-end']: 'opacity-100 translate-x-0',
                ['x-transition:leave']: 'transition ease-[cubic-bezier(0.33,1,0.68,1)] duration-500 absolute top-0 left-0 w-full z-0',
                ['x-transition:leave-start']: 'opacity-100 translate-x-0',
                ['x-transition:leave-end']: isNext ? 'opacity-100 -translate-x-full' : 'opacity-100 translate-x-full',
            };
        },

        // Global Slideshow Logic
        previewList: [],

        // Modified Open Preview
        openPreview(item) {
            // 1. Construct flattened list from current view
            let flatList = [];
            
            if (this.viewMode === 'grouped') {
                this.galleries.forEach(g => {
                    if (g.is_group && g.expanded_items && g.expanded_items.length > 0) {
                        // Add all items from the group
                        flatList.push(...g.expanded_items);
                    } else {
                        // Add single item (or group with no items/fallback)
                        flatList.push(g);
                    }
                });
            } else {
                // Individual View: Copy all current items
                flatList = JSON.parse(JSON.stringify(this.galleries));
            }

            this.previewList = flatList;

            // 2. Determine start index
            let foundIndex = -1;
            
            if (item.is_group && this.viewMode === 'grouped') {
                // If clicking a group card, start at its first item
                if (item.expanded_items && item.expanded_items.length > 0) {
                    foundIndex = this.previewList.findIndex(x => x.id === item.expanded_items[0].id);
                } else if (item.group_item_ids && item.group_item_ids.length > 0) {
                     // Fallback if expanded_items missing but IDs exist (e.g. data not refreshed)
                     // In this case we might fail to find it if we didn't fetch extended data.
                     // But assuming we have refreshed data.
                     foundIndex = this.previewList.findIndex(x => x.id === item.group_item_ids[0]);
                }
            } else {
                // Single item click
                foundIndex = this.previewList.findIndex(x => x.id === item.id);
            }
            
            // Safety check
            if (foundIndex === -1) {
                // If we absolutely can't find it (e.g. pagination boundary edge case or data sync issue),
                // fall back to just showing the clicked item as a single-item list?
                // Or just start at 0.
                if (item.is_group && item.expanded_items) {
                     this.previewList = item.expanded_items; 
                     foundIndex = 0;
                } else {
                    // Try to find by loosely matching IDs if string vs int
                    foundIndex = this.previewList.findIndex(x => x.id == item.id);
                    if (foundIndex === -1) {
                         // Last resort: just put the item in
                         this.previewList = [item];
                         foundIndex = 0;
                    }
                }
            }

            this.previewCurrentIndex = foundIndex;
            this.previewItem = this.previewList[this.previewCurrentIndex];
            
            this.showPreviewModal = true;
            this.showAlbumModal = false; 
            
            this.previewDirection = 'next';
            this.$nextTick(() => lucide.createIcons());
        },

        // Override openAlbumModal to use Global Preview
        openAlbumModal(item) {
            this.openPreview(item);
        },

        // Simplified Navigation (No merging/expanding)
        prevPreview() {
            this.previewDirection = 'prev';
            if (this.previewList.length > 0) {
                this.previewCurrentIndex = (this.previewCurrentIndex - 1 + this.previewList.length) % this.previewList.length;
                this.previewItem = this.previewList[this.previewCurrentIndex];
                this.$nextTick(() => lucide.createIcons());
            }
        },

        nextPreview() {
            this.previewDirection = 'next';
            if (this.previewList.length > 0) {
                this.previewCurrentIndex = (this.previewCurrentIndex + 1) % this.previewList.length;
                this.previewItem = this.previewList[this.previewCurrentIndex];
                this.$nextTick(() => lucide.createIcons());
            }
        },

        goToPreview(index) {
            if (index > this.previewCurrentIndex) {
                this.previewDirection = 'next';
            } else if (index < this.previewCurrentIndex) {
                this.previewDirection = 'prev';
            }
            this.previewCurrentIndex = index;
            this.previewItem = this.previewList[this.previewCurrentIndex];
            this.$nextTick(() => lucide.createIcons());
        },

        closePreview() {
            this.showPreviewModal = false;
            this.showInfoModal = false;
            setTimeout(() => {
                this.previewItem = null;
                // Reset previewList to avoid memory bloat if needed, or keep it
                // this.previewList = []; 
            }, 300);
        },

        toggleInfoModal() {
            this.showInfoModal = !this.showInfoModal;
        },

        formatDateIndo(dateStr) {
            if (!dateStr) return '-';
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            
            const parts = dateStr.match(/(\d{2})\s(\w+)\s(\d{4})\s(\d{2}):(\d{2})/);
            if (!parts) return dateStr;
            
            const date = new Date(`${parts[2]} ${parts[1]}, ${parts[3]} ${parts[4]}:${parts[5]}`);
            if (isNaN(date)) return dateStr;
            
            const dayName = days[date.getDay()];
            const day = String(date.getDate()).padStart(2, '0');
            const month = months[date.getMonth()];
            const year = date.getFullYear();
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            
            return `${dayName}, ${day} ${month} ${year} ${hours}.${minutes}`;
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
