        zoomScale: 1,
        zoomLevel: 100,
        panX: 0,
        panY: 0,
        isPanning: false,
        startPanX: 0,
        startPanY: 0,
        showZoomControls: false,

        initZoom() {
            this.$watch('previewItem', () => {
                this.resetZoom();
                this.showZoomControls = false;
            });
        },

        toggleZoomControls() {
            this.showZoomControls = !this.showZoomControls;
            if (!this.showZoomControls) {
                this.resetZoom();
            }
        },

        zoomIn() {
            if (this.zoomScale < 3) {
                this.zoomScale = Math.min(this.zoomScale + 0.5, 3);
                this.updateZoomLevel();
            }
        },

        zoomOut() {
            if (this.zoomScale > 1) {
                this.zoomScale = Math.max(this.zoomScale - 0.5, 1);
                this.updateZoomLevel();
                if (this.zoomScale === 1) this.resetPan();
            }
        },

        resetZoom() {
            this.zoomScale = 1;
            this.updateZoomLevel();
            this.resetPan();
        },

        updateZoomLevel() {
            this.zoomLevel = Math.round(this.zoomScale * 100);
        },

        resetPan() {
            this.panX = 0;
            this.panY = 0;
        },

        // Pan handlers (optional, for drag)
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
