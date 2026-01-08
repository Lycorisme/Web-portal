// ========================================
// MENU & NAVIGATION METHODS
// ========================================

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
