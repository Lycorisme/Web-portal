// ========================================
// TOUCH SWIPE HANDLERS
// ========================================

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
