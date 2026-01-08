// ========================================
// ALBUM MODAL METHODS
// ========================================
async _openAlbumModalLegacy(item) {
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
