<script>
    // App State Management
    function appState() {
        return {
            sidebarOpen: window.innerWidth >= 1024, // Closed on mobile, open on desktop
            darkMode: localStorage.getItem('darkMode') === 'true',
            themePreset: localStorage.getItem('themePreset') || '{{ \App\Models\SiteSetting::get("current_theme", "indigo") }}',
            showNotification: false,
            showProfile: false,
            currentPath: window.location.pathname,
            captureMode: false,

            init() {
                // Apply dark mode
                this.applyDarkMode();
                
                // Apply theme
                this.applyTheme();

                // Initialize sidebar height sync for full page screenshots
                this.syncSidebarHeight();

                // Listen for Livewire navigation
                document.addEventListener('livewire:navigated', () => {
                    this.currentPath = window.location.pathname;
                    this.syncSidebarHeight();
                });

                // Watch for dark mode changes
                this.$watch('darkMode', (value) => {
                    localStorage.setItem('darkMode', value);
                    this.applyDarkMode();
                });

                // Watch for theme changes
                this.$watch('themePreset', (value) => {
                    localStorage.setItem('themePreset', value);
                    this.applyTheme();
                });

                // Watch for capture mode
                this.$watch('captureMode', (value) => {
                    this.toggleCaptureMode(value);
                });

                // Keyboard shortcut for capture mode (Ctrl+Shift+P)
                document.addEventListener('keydown', (e) => {
                    if (e.ctrlKey && e.shiftKey && e.key === 'P') {
                        e.preventDefault();
                        this.captureMode = !this.captureMode;
                    }
                });

                // Sync sidebar height on resize and content changes
                window.addEventListener('resize', () => this.syncSidebarHeight());

                // Create a MutationObserver to watch for DOM changes
                const observer = new MutationObserver(() => this.syncSidebarHeight());
                observer.observe(document.body, { childList: true, subtree: true });
            },

            applyDarkMode() {
                if (this.darkMode) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            },

            applyTheme() {
                document.documentElement.setAttribute('data-theme', this.themePreset);
            },

            toggleDarkMode() {
                this.darkMode = !this.darkMode;
            },

            // Sync sidebar height with document height for full page screenshots
            syncSidebarHeight() {
                requestAnimationFrame(() => {
                    const sidebar = document.getElementById('admin-sidebar');
                    const mainContent = document.getElementById('main-content');
                    const sidebarWrapper = document.getElementById('sidebar-wrapper');
                    
                    if (sidebar && mainContent && sidebarWrapper) {
                        const docHeight = Math.max(
                            document.body.scrollHeight,
                            document.documentElement.scrollHeight,
                            mainContent.scrollHeight
                        );
                        
                        // Set minimum height on sidebar wrapper to match content
                        sidebarWrapper.style.minHeight = docHeight + 'px';
                    }
                });
            },

            // Toggle capture mode for full page screenshots
            toggleCaptureMode(enabled) {
                if (enabled) {
                    document.body.classList.add('capture-mode');
                    document.documentElement.classList.add('capture-mode');
                    
                    // Force sidebar to show full height
                    const sidebar = document.getElementById('admin-sidebar');
                    const sidebarWrapper = document.getElementById('sidebar-wrapper');
                    const mainContent = document.getElementById('main-content');
                    
                    if (sidebar && sidebarWrapper && mainContent) {
                        const docHeight = Math.max(
                            document.body.scrollHeight,
                            document.documentElement.scrollHeight,
                            mainContent.scrollHeight
                        );
                        
                        sidebarWrapper.style.minHeight = docHeight + 'px';
                        sidebar.style.position = 'relative';
                        sidebar.style.height = 'auto';
                        sidebar.style.minHeight = '100%';
                    }
                    
                    console.log('📷 Capture mode ENABLED - Ready for full page screenshot');
                } else {
                    document.body.classList.remove('capture-mode');
                    document.documentElement.classList.remove('capture-mode');
                    
                    // Reset sidebar styles
                    const sidebar = document.getElementById('admin-sidebar');
                    const sidebarWrapper = document.getElementById('sidebar-wrapper');
                    
                    if (sidebar && sidebarWrapper) {
                        sidebarWrapper.style.minHeight = '';
                        sidebar.style.position = '';
                        sidebar.style.height = '';
                        sidebar.style.minHeight = '';
                    }
                    
                    console.log('📷 Capture mode DISABLED');
                }
            }
        }
    }

    // Initialize Lucide Icons
    document.addEventListener('DOMContentLoaded', function () {
        lucide.createIcons();
    });

    // Re-initialize icons after Alpine.js updates
    document.addEventListener('alpine:initialized', function () {
        setInterval(function () {
            lucide.createIcons();
        }, 500);
    });

    // ============================================
    // Toast Helper Functions (Using Custom Toast System)
    // ============================================

    // Note: Custom Toast is loaded from layouts.partials.custom-toast
    // showToast, toastSuccess, toastError, toastWarning, toastInfo are available globally

    // Show alert dialog
    function showAlert(type, title, message, callback = null) {
        const iconMap = {
            'success': 'success',
            'error': 'error',
            'warning': 'warning',
            'info': 'info',
            'danger': 'error',
            'question': 'question'
        };

        Swal.fire({
            icon: iconMap[type] || 'info',
            title: title,
            text: message,
            confirmButtonText: 'OK',
            customClass: {
                confirmButton: 'swal2-confirm'
            }
        }).then((result) => {
            if (result.isConfirmed && callback) {
                callback();
            }
        });
    }

    // Show confirm dialog
    // Show confirm dialog
    function showConfirm(title, message, callbackOrConfirmText, optionsOrColor = {}) {
        let callback = null;
        let options = {};
        
        // Check if called with (title, message, confirmText, color) - Promise style
        if (typeof callbackOrConfirmText === 'string') {
            options = {
                confirmText: callbackOrConfirmText,
                color: optionsOrColor
            };
        } 
        // Check if called with (title, message, callback, options) - Callback style
        else if (typeof callbackOrConfirmText === 'function') {
            callback = callbackOrConfirmText;
            options = optionsOrColor || {};
        } else {
             // Fallback or just options
             options = callbackOrConfirmText || {};
        }

        const isDark = document.documentElement.classList.contains('dark');
        
        // Determine button style
        let confirmClass = 'swal2-confirm';
        if (options.color === 'rose' || options.color === 'red') {
             // We append custom color classes. Ensure Tailwind classes work with Swal
             confirmClass += ' swal-danger'; 
        } else if (options.color === 'emerald' || options.color === 'green') {
             confirmClass += ' swal-success';
        }
        
        return Swal.fire({
            title: title,
            text: message,
            icon: options.icon || 'warning',
            showCancelButton: true,
            confirmButtonText: options.confirmText || 'Ya, Lanjutkan',
            cancelButtonText: options.cancelText || 'Batal',
            reverseButtons: true,
            customClass: {
                confirmButton: confirmClass,
                cancelButton: 'swal2-cancel'
            }
        }).then((result) => {
            if (result.isConfirmed && callback) {
                callback();
            }
            return result.isConfirmed;
        });
    }

    // Show success message - now uses custom toast to avoid double confirmation
    function showSuccess(title, message = '', callback = null) {
        // Mark that a Swal action just completed (prevents loading screen on page reload)
        sessionStorage.setItem('swalActionCompleted', Date.now().toString());
        
        // Use custom toast instead of SweetAlert popup
        showToast('success', title, message);
        
        // Execute callback immediately if provided
        if (callback) {
            callback();
        }
    }

    // Show error message
    function showError(title, message = '') {
        Swal.fire({
            icon: 'error',
            title: title,
            text: message,
            confirmButtonText: 'OK',
            customClass: {
                confirmButton: 'swal2-confirm'
            }
        });
    }

    // Show loading
    function showLoading(title = 'Memproses...') {
        // Hide global loading screen if it's still visible
        if (typeof window.hideGlobalLoadingScreen === 'function') {
            window.hideGlobalLoadingScreen();
        }
        
        Swal.fire({
            title: title,
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }

    // Close loading
    function closeLoading() {
        // Mark that a Swal action just completed (prevents loading screen on page reload)
        sessionStorage.setItem('swalActionCompleted', Date.now().toString());
        Swal.close();
    }

    // Show delete confirmation
    function showDeleteConfirm(itemName, callback) {
        Swal.fire({
            title: 'Hapus ' + itemName + '?',
            text: 'Data yang dihapus tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#f43f5e',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed && callback) {
                callback();
            }
        });
    }

    // ============================================
    // Flash Messages Handler
    // ============================================
    @if(session('success'))
        showToast('success', '{{ session('success') }}');
    @endif

    @if(session('error'))
        showToast('error', '{{ session('error') }}');
    @endif

    @if(session('warning'))
        showToast('warning', '{{ session('warning') }}');
    @endif

    @if(session('info'))
        showToast('info', '{{ session('info') }}');
    @endif
</script>
