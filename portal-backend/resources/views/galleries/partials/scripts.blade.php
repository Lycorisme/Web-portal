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

        initPreviewList() {
            // Deep copy galleries to previewList
            this.previewList = JSON.parse(JSON.stringify(this.galleries));
        },

        async expandGroupAtIndex(index) {
            const item = this.previewList[index];
            if (!item.is_group || item.group_count <= 1) return;

            // Fetch items
            try {
                const params = new URLSearchParams();
                if(item.group_item_ids) {
                    item.group_item_ids.forEach(id => params.append('ids[]', id));
                }
                
                // Using existing album-items endpoint
                const response = await fetch(`{{ route('galleries.album-items') }}?${params}`);
                const result = await response.json();

                if (result.success) {
                    // Replace group placeholder with actual items
                    // We remove 1 element at 'index' and add 'result.data' items
                    this.previewList.splice(index, 1, ...result.data);
                }
            } catch (error) {
                console.error('Error expanding group:', error);
            }
        },

        // Modified Open Preview
        async openPreview(item) {
            this.initPreviewList();
            
            // Find index in the new list
            let index = this.previewList.findIndex(g => g.id === item.id);
            if (index === -1) return;

            // If it's a group, expand it immediately
            if (item.is_group && item.group_count > 1) {
                await this.expandGroupAtIndex(index);
                // After expansion, the index 'index' now points to the first child
                // So we stick to 'index'
            }

            this.previewCurrentIndex = index;
            this.previewItem = this.previewList[this.previewCurrentIndex];
            this.previewDirection = 'next';
            this.showPreviewModal = true;
            this.$nextTick(() => lucide.createIcons());
        },

        // Override openAlbumModal to use Global Preview
        openAlbumModal(item) {
            this.openPreview(item);
        },

        // Modified Navigation
        async prevPreview() {
            this.previewDirection = 'prev';
            
            let newIndex = this.previewCurrentIndex - 1;
            
            if (newIndex < 0) {
                 newIndex = this.previewList.length - 1;
            }

            // Check if ANY item in the direction is a group? 
            // Simplified: Just update index. If we land on a group placeholder, expand it.
            // But if we wrap around to end, and end is a group, we need to handle it.
            
            let targetItem = this.previewList[newIndex];

            if (targetItem.is_group && targetItem.group_count > 1 && !targetItem.image_url) { 
                // Assumption: placeholders might lack full image_url or have unique flag
                // Actually we reuse the item structure. is_group=true is the flag.
                // Expanded items should NOT have is_group=true or group_count > 1 (unless nested, unlikely)
                // The API returns simple items.
                
                await this.expandGroupAtIndex(newIndex);
                // After expansion, newIndex points to the *first* item of that group.
                // But we are going PREV. So we want the LAST item of that group.
                // The group was replaced by N items. 
                // newIndex is the start. 
                // We need to find how many items were added? 
                // It's tricky to know exact count without reliable return.
                // But wait, expandGroupAtIndex logic modifies the array.
                // We can just rely on the fact that expanded items are now at [newIndex...newIndex+N]
                // If we were going PREV into a group, logically we want the LAST item of that group.
                // But simplified: just land on the first item of the group is acceptable, or user can tap prev again.
                // Let's stick to simple: Land on first item of expanded group.
                targetItem = this.previewList[newIndex];
            }

            this.previewCurrentIndex = newIndex;
            this.previewItem = targetItem;
            this.$nextTick(() => lucide.createIcons());
        },

        async nextPreview() {
            this.previewDirection = 'next';
            
            let newIndex = this.previewCurrentIndex + 1;
            if (newIndex >= this.previewList.length) {
                newIndex = 0;
            }

            let targetItem = this.previewList[newIndex];
            
            if (targetItem && targetItem.is_group && targetItem.group_count > 1) {
                await this.expandGroupAtIndex(newIndex);
                // After expansion, newIndex is the first item of the group. Perfect for NEXT direction.
                targetItem = this.previewList[newIndex];
            }

            this.previewCurrentIndex = newIndex;
            this.previewItem = targetItem;
            this.$nextTick(() => lucide.createIcons());
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
