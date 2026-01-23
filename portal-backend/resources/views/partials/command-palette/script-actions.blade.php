{{-- Command Palette - JavaScript Actions (~120 lines) --}}
{{-- Contains command execution, theme, dark mode, and utility functions --}}

<script>
// Extend the commandPalette object with action methods
Object.assign(commandPalette.prototype || commandPalette, {});

// Add methods to the Alpine component after initialization
document.addEventListener('alpine:init', () => {
    // These methods will be available in the commandPalette component
});

// Command execution methods - added to window for access
window.commandPaletteActions = {
    executeCommand(component, cmd) {
        if (cmd.url) {
            window.location.href = cmd.url;
            return;
        }

        switch(cmd.action) {
            case 'toggleDarkMode':
                component.toggleDarkMode();
                break;
            case 'copyCurrentUrl':
                component.copyToClipboard(window.location.href);
                if (typeof showToast === 'function') {
                    showToast('success', 'URL Disalin', 'URL halaman berhasil disalin ke clipboard');
                }
                break;
            case 'toggleFullscreen':
                component.toggleFullscreen();
                break;
            case 'refreshPage':
                location.reload();
                break;
            case 'printPage':
                window.print();
                break;
            case 'clearLocalCache':
                component.clearLocalCache();
                break;
        }
    },

    toggleDarkMode(component) {
        component.isDarkMode = !component.isDarkMode;
        document.documentElement.classList.toggle('dark', component.isDarkMode);
        localStorage.setItem('darkMode', component.isDarkMode);
        component.$nextTick(() => lucide.createIcons());
        
        if (typeof showToast === 'function') {
            showToast('success', 'Mode Tampilan', component.isDarkMode ? 'Mode gelap diaktifkan' : 'Mode terang diaktifkan');
        }
    },

    setDarkMode(component, value) {
        component.isDarkMode = value;
        document.documentElement.classList.toggle('dark', component.isDarkMode);
        localStorage.setItem('darkMode', component.isDarkMode);
        component.$nextTick(() => lucide.createIcons());
        
        if (typeof showToast === 'function') {
            showToast('success', 'Mode Tampilan', value ? 'Mode gelap diaktifkan' : 'Mode terang diaktifkan');
        }
    },

    setTheme(component, themeId) {
        component.currentTheme = themeId;
        document.documentElement.setAttribute('data-theme', themeId);
        localStorage.setItem('theme', themeId);
        
        if (typeof showToast === 'function') {
            showToast('success', 'Tema Diubah', `Tema ${themeId} berhasil diterapkan`);
        }
        component.$nextTick(() => lucide.createIcons());
    },

    async toggleFullscreen(component) {
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
        
        component.close();
    },

    async clearLocalCache(component) {
        component.isProcessing = true;
        component.processingTitle = 'Menghapus Cache...';
        component.processingMessage = 'Mohon tunggu, sedang membersihkan data cache browser';
        component.$nextTick(() => lucide.createIcons());

        // Simulate processing time for visual feedback
        await new Promise(resolve => setTimeout(resolve, 1500));

        localStorage.clear();
        sessionStorage.clear();

        component.isProcessing = false;
        component.close();

        if (typeof showToast === 'function') {
            showToast('success', 'Cache Dihapus', 'Cache lokal browser berhasil dibersihkan');
        }

        // Reload after brief delay
        setTimeout(() => location.reload(), 500);
    },

    copyToClipboard(text) {
        navigator.clipboard.writeText(text).catch(err => {
            console.error('Failed to copy:', err);
        });
    }
};
</script>
