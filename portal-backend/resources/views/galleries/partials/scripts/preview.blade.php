// ========================================
// PREVIEW & NAVIGATION METHODS
// ========================================

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
    this.videoShouldAutoplay = false; // Reset autoplay state
    setTimeout(() => {
        this.previewItem = null;
        // Reset previewList to avoid memory bloat if needed, or keep it
        // this.previewList = []; 
    }, 300);
},

// Open preview with video autoplay (triggered from action menu "Putar Video")
openPreviewWithAutoplay(item) {
    this.videoShouldAutoplay = true;
    this.openPreview(item);
},

toggleInfoModal() {
    this.showInfoModal = !this.showInfoModal;
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
