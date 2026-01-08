        // Zoom State
        zoomScale: 1,
        zoomPercent: 100,
        panX: 0,
        panY: 0,
        isPanning: false,
        startPanX: 0,
        startPanY: 0,
        showZoomControls: false,
        isSliderDragging: false,
        videoShouldAutoplay: false,

        // Image Cache
        imageCache: new Map(),

        initZoom() {
            this.$watch('previewItem', () => {
                this.resetZoom();
                this.showZoomControls = false;
                this.videoShouldAutoplay = false; // Reset for each new item
            });

            // Add global mouse/touch event listeners for slider dragging
            document.addEventListener('mousemove', (e) => this.handleSliderDrag(e));
            document.addEventListener('mouseup', () => this.stopSliderDrag());
            document.addEventListener('touchmove', (e) => this.handleSliderDragTouch(e));
            document.addEventListener('touchend', () => this.stopSliderDrag());
        },

        // Get image with caching
        getImageWithCache(url) {
            if (!url) return '';
            
            // If already in cache, return the URL directly
            if (this.imageCache.has(url)) {
                return url;
            }
            
            // Preload and cache the image
            const img = new Image();
            img.onload = () => {
                this.imageCache.set(url, true);
            };
            img.src = url;
            
            return url;
        },

        toggleZoomControls() {
            this.showZoomControls = !this.showZoomControls;
            if (!this.showZoomControls) {
                this.resetZoom();
            }
        },

        // Zoom in with percentage steps (smooth)
        zoomIn() {
            if (this.zoomPercent < 300) {
                const newPercent = Math.min(this.zoomPercent + 25, 300);
                this.setZoomPercent(newPercent);
            }
        },

        // Zoom out with percentage steps (smooth)
        zoomOut() {
            if (this.zoomPercent > 100) {
                const newPercent = Math.max(this.zoomPercent - 25, 100);
                this.setZoomPercent(newPercent);
                if (this.zoomPercent === 100) this.resetPan();
            }
        },

        // Set zoom by percentage (100% = 1x, 200% = 2x, 300% = 3x)
        setZoomPercent(percent) {
            this.zoomPercent = Math.round(Math.max(100, Math.min(300, percent)));
            this.zoomScale = this.zoomPercent / 100;
            
            // Reset pan if at 100%
            if (this.zoomPercent === 100) {
                this.resetPan();
            }
        },

        resetZoom() {
            this.zoomPercent = 100;
            this.zoomScale = 1;
            this.resetPan();
        },

        resetPan() {
            this.panX = 0;
            this.panY = 0;
        },

        // Slider Drag Handlers (Telegram-style)
        startSliderDrag(e) {
            this.isSliderDragging = true;
            this.updateSliderFromEvent(e);
        },

        startSliderDragTouch(e) {
            this.isSliderDragging = true;
            if (e.touches && e.touches[0]) {
                this.updateSliderFromEvent(e.touches[0]);
            }
        },

        handleSliderDrag(e) {
            if (!this.isSliderDragging) return;
            e.preventDefault();
            this.updateSliderFromEvent(e);
        },

        handleSliderDragTouch(e) {
            if (!this.isSliderDragging) return;
            e.preventDefault();
            if (e.touches && e.touches[0]) {
                this.updateSliderFromEvent(e.touches[0]);
            }
        },

        stopSliderDrag() {
            this.isSliderDragging = false;
        },

        updateSliderFromEvent(e) {
            const track = this.$refs.sliderTrack;
            if (!track) return;
            
            const rect = track.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const percent = Math.max(0, Math.min(1, x / rect.width));
            
            // Map 0-1 to 100-300%
            const newZoomPercent = 100 + (percent * 200);
            this.setZoomPercent(newZoomPercent);
        },

        // Mouse wheel zoom handler
        handleMouseWheel(e) {
            if (!this.showZoomControls) return;
            
            const delta = e.deltaY > 0 ? -15 : 15;
            const newPercent = this.zoomPercent + delta;
            this.setZoomPercent(newPercent);
        },

        // Pan handlers (for dragging zoomed content)
        startPan(e) {
            if (this.zoomScale <= 1) return;
            this.isPanning = true;
            this.startPanX = e.clientX - this.panX;
            this.startPanY = e.clientY - this.panY;
        },

        handlePan(e) {
            if (!this.isPanning) return;
            e.preventDefault();
            this.panX = e.clientX - this.startPanX;
            this.panY = e.clientY - this.startPanY;
        },

        endPan() {
            this.isPanning = false;
        },
