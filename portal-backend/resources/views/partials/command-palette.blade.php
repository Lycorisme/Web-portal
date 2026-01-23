{{-- Command Palette / Global Search - Enhanced Edition --}}
{{-- Refactored into modular components for better maintainability --}}
{{-- Main entry point (~50 lines) --}}

{{-- Template (HTML Structure) --}}
@include('partials.command-palette.template')

{{-- Configuration Data --}}
@include('partials.command-palette.config')

{{-- Main Script Logic --}}
<script>
function commandPalette() {
    return {
        isOpen: false,
        isProcessing: false,
        processingTitle: '',
        processingMessage: '',
        query: '',
        results: [],
        totalResults: 0,
        isLoading: false,
        hasSearched: false,
        selectedIndex: 0,
        mode: 'navigate',
        calculatorResult: null,

        currentTheme: document.documentElement.getAttribute('data-theme') || 'emerald',
        recentSearches: JSON.parse(localStorage.getItem('recentSearches') || '[]'),
        recentPages: JSON.parse(localStorage.getItem('recentPages') || '[]'),

        // Use config data
        modes: commandPaletteConfig.modes,
        themes: commandPaletteConfig.themes,
        quickActions: commandPaletteConfig.quickActions,
        createActions: commandPaletteConfig.createActions,
        systemCommands: commandPaletteConfig.systemCommands,

        init() {
            window.addEventListener('open-command-palette', () => this.open());
            this.trackCurrentPage();
            
            // Check if cache was cleared (show toast after refresh)
            this.checkCacheClearedToast();
            
            document.addEventListener('keydown', (e) => {
                if (this.isOpen) return;

            });
        },
        
        checkCacheClearedToast() {
            const cacheCleared = sessionStorage.getItem('cache_cleared_toast');
            if (cacheCleared) {
                sessionStorage.removeItem('cache_cleared_toast');
                setTimeout(() => {
                    if (typeof showToast === 'function') {
                        showToast('success', 'Cache Dihapus', 'Cache lokal browser berhasil dibersihkan');
                    }
                }, 500);
            }
        },

        getPlaceholder() {
            const placeholders = {
                'navigate': 'Cari halaman atau ketik untuk mencari...',
                'create': 'Pilih item untuk dibuat...',
                'commands': 'Ketik perintah atau gunakan mouse...',
                'theme': 'Pilih tema favorit Anda...'
            };
            return placeholders[this.mode] || 'Ketik sesuatu...';
        },

        trackCurrentPage() {
            const currentPage = {
                url: window.location.href,
                title: document.title.replace(' - Portal Berita', ''),
                time: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })
            };
            
            if (this.recentPages.length > 0 && this.recentPages[0].url === currentPage.url) return;
            
            this.recentPages = [currentPage, ...this.recentPages.filter(p => p.url !== currentPage.url)].slice(0, 10);
            localStorage.setItem('recentPages', JSON.stringify(this.recentPages));
        },

        toggle() { this.isOpen ? this.close() : this.open(); },

        open() {
            this.isOpen = true;
            this.query = '';
            this.results = [];
            this.selectedIndex = 0;
            this.hasSearched = false;
            this.calculatorResult = null;
            this.mode = 'navigate';
            this.$nextTick(() => {
                this.$refs.searchInput?.focus();
                lucide.createIcons();
            });
        },

        close() {
            this.isOpen = false;
            this.query = '';
            this.results = [];
            this.calculatorResult = null;
        },

        switchMode(modeId) {
            this.mode = modeId;
            this.selectedIndex = 0;
            this.query = '';
            this.results = [];
            this.calculatorResult = null;
            this.$nextTick(() => {
                this.$refs.searchInput?.focus();
                lucide.createIcons();
            });
        },

        nextMode() {
            const currentIndex = this.modes.findIndex(m => m.id === this.mode);
            const nextIndex = (currentIndex + 1) % this.modes.length;
            this.switchMode(this.modes[nextIndex].id);
        },

        handleInput() {
            this.calculatorResult = this.tryCalculate(this.query);
            
            if (this.query.length >= 2 && this.calculatorResult === null) {
                this.search();
            } else if (this.calculatorResult === null) {
                this.results = [];
                this.totalResults = 0;
                this.hasSearched = false;
            }
        },

        tryCalculate(expr) {
            if (!/^[\d\s+\-*/().%^]+$/.test(expr) || expr.length < 2) return null;
            
            try {
                const sanitized = expr.replace(/\^/g, '**');
                const result = Function('"use strict"; return (' + sanitized + ')')();
                
                if (typeof result === 'number' && !isNaN(result) && isFinite(result)) {
                    return result.toLocaleString('id-ID', { maximumFractionDigits: 10 });
                }
            } catch (e) {}
            return null;
        },

        async search() {
            if (this.query.length < 2) {
                this.results = [];
                this.totalResults = 0;
                this.hasSearched = false;
                return;
            }

            this.isLoading = true;
            this.hasSearched = false;

            try {
                const response = await fetch(`${commandPaletteConfig.searchUrl}?q=${encodeURIComponent(this.query)}`);
                const data = await response.json();

                this.results = data.results;
                this.totalResults = data.total;
                this.selectedIndex = 0;
                this.hasSearched = true;

                if (this.query.length >= 2 && data.total > 0) {
                    this.addToRecentSearches(this.query);
                }

                this.$nextTick(() => lucide.createIcons());
            } catch (error) {
                console.error('Search error:', error);
                this.results = [];
                this.totalResults = 0;
            } finally {
                this.isLoading = false;
            }
        },

        navigateDown() {
            const maxIndex = this.getMaxIndex();
            this.selectedIndex = Math.min(this.selectedIndex + 1, maxIndex);
        },

        navigateUp() {
            this.selectedIndex = Math.max(this.selectedIndex - 1, 0);
        },

        getMaxIndex() {
            if (this.query && this.results.length > 0) return this.getFlatItemsCount() - 1;
            
            const counts = {
                'navigate': this.quickActions.length - 1,
                'create': this.createActions.length - 1,
                'commands': this.systemCommands.length - 1,
                'theme': this.themes.length - 1
            };
            return counts[this.mode] || 0;
        },

        selectCurrent() {
            if (this.query && this.results.length > 0) {
                const item = this.getFlatItemByIndex(this.selectedIndex);
                if (item) window.location.href = item.url;
                return;
            }

            const actions = {
                'navigate': this.quickActions,
                'create': this.createActions,
                'commands': this.systemCommands,
                'theme': this.themes
            };

            const items = actions[this.mode];
            if (items?.[this.selectedIndex]) {
                if (this.mode === 'commands') {
                    this.executeCommand(items[this.selectedIndex]);
                } else if (this.mode === 'theme') {
                    this.setTheme(items[this.selectedIndex].id);
                } else {
                    window.location.href = items[this.selectedIndex].url;
                }
            }
        },

        executeCommand(cmd) {
            if (cmd.url) {
                window.location.href = cmd.url;
                return;
            }

            switch(cmd.action) {

                case 'copyCurrentUrl':
                    this.copyToClipboard(window.location.href);
                    if (typeof showToast === 'function') {
                        showToast('success', 'URL Disalin', 'URL halaman berhasil disalin ke clipboard');
                    }
                    break;
                case 'toggleFullscreen':
                    this.toggleFullscreen();
                    break;
                case 'refreshPage':
                    location.reload();
                    break;
                case 'printPage':
                    window.print();
                    break;
                case 'clearLocalCache':
                    this.clearLocalCache();
                    break;
                case 'logout':
                    this.confirmLogout();
                    break;
            }
        },
        
        confirmLogout() {
            this.close();
            Swal.fire({
                title: 'Konfirmasi Logout',
                text: 'Apakah Anda yakin ingin keluar dari sistem?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: 'var(--color-theme-500)',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-lg px-6',
                    cancelButton: 'rounded-lg px-6'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create and submit logout form with POST method
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("logout") }}';
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = document.querySelector('meta[name="csrf-token"]')?.content || '';
                    form.appendChild(csrfToken);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        },



        setTheme(themeId) {
            this.currentTheme = themeId;
            document.documentElement.setAttribute('data-theme', themeId);
            localStorage.setItem('theme', themeId);
            
            if (typeof showToast === 'function') {
                showToast('success', 'Tema Diubah', `Tema ${themeId} berhasil diterapkan`);
            }
            this.$nextTick(() => lucide.createIcons());
        },

        async toggleFullscreen() {
            const overlay = document.getElementById('fullscreen-overlay');
            
            // Show transition overlay
            if (overlay) {
                overlay.style.display = 'block';
                requestAnimationFrame(() => {
                    overlay.style.opacity = '1';
                });
            }

            try {
                if (!document.fullscreenElement) {
                    await document.documentElement.requestFullscreen();
                    if (typeof showToast === 'function') {
                        showToast('info', 'Layar Penuh', 'Mode layar penuh aktif. Tekan ESC untuk keluar.');
                    }
                } else {
                    await document.exitFullscreen();
                    if (typeof showToast === 'function') {
                        showToast('info', 'Layar Penuh', 'Mode layar penuh dinonaktifkan');
                    }
                }
            } catch (err) {
                console.error('Fullscreen error:', err);
            }

            // Hide transition overlay
            setTimeout(() => {
                if (overlay) {
                    overlay.style.opacity = '0';
                    setTimeout(() => {
                        overlay.style.display = 'none';
                    }, 300);
                }
            }, 100);
            
            this.close();
        },

        async clearLocalCache() {
            this.isProcessing = true;
            this.processingTitle = 'Menghapus Cache...';
            this.processingMessage = 'Mohon tunggu, sedang membersihkan data cache browser';
            this.$nextTick(() => lucide.createIcons());

            // Simulate processing time for visual feedback
            await new Promise(resolve => setTimeout(resolve, 1500));

            // Set flag to show toast after reload (before clearing storage)
            sessionStorage.setItem('cache_cleared_toast', 'true');
            
            // Clear localStorage only (keep sessionStorage flag)
            localStorage.clear();

            this.isProcessing = false;
            this.close();

            // Reload after brief delay
            setTimeout(() => location.reload(), 300);
        },

        copyToClipboard(text) {
            navigator.clipboard.writeText(text).catch(err => {
                console.error('Failed to copy:', err);
            });
        },

        getFlatItemsCount() {
            return this.results.reduce((count, group) => count + group.items.length, 0);
        },

        getFlatItemByIndex(flatIndex) {
            let currentIndex = 0;
            for (const group of this.results) {
                for (const item of group.items) {
                    if (currentIndex === flatIndex) return item;
                    currentIndex++;
                }
            }
            return null;
        },

        setSelectedByFlatIndex(groupIndex, itemIndex) {
            let flatIndex = 0;
            for (let i = 0; i < groupIndex; i++) {
                flatIndex += this.results[i].items.length;
            }
            this.selectedIndex = flatIndex + itemIndex;
        },

        isItemSelected(groupIndex, itemIndex) {
            let flatIndex = 0;
            for (let i = 0; i < groupIndex; i++) {
                flatIndex += this.results[i].items.length;
            }
            return this.selectedIndex === (flatIndex + itemIndex);
        },

        addToRecentSearches(query) {
            this.recentSearches = this.recentSearches.filter(s => s.toLowerCase() !== query.toLowerCase());
            this.recentSearches.unshift(query);
            this.recentSearches = this.recentSearches.slice(0, 10);
            localStorage.setItem('recentSearches', JSON.stringify(this.recentSearches));
        },

        clearRecentSearches() {
            this.recentSearches = [];
            localStorage.removeItem('recentSearches');
        },

        clearRecentPages() {
            this.recentPages = [];
            localStorage.removeItem('recentPages');
        }
    }
}
</script>
