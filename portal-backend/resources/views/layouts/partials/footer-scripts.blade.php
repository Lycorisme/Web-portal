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

            init() {
                // Apply dark mode
                this.applyDarkMode();
                
                // Apply theme
                this.applyTheme();

                // Listen for Livewire navigation
                document.addEventListener('livewire:navigated', () => {
                    this.currentPath = window.location.pathname;
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
    // SweetAlert2 Helper Functions
    // ============================================

    // Toast notification
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        },
        customClass: {
            popup: 'rounded-xl'
        }
    });

    // Show toast notification
    function showToast(type, title, message = '') {
        const iconMap = {
            'success': 'success',
            'error': 'error',
            'warning': 'warning',
            'info': 'info'
        };
        
        Toast.fire({
            icon: iconMap[type] || 'info',
            title: title,
            text: message
        });
    }

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
    function showConfirm(title, message, callback, options = {}) {
        const isDark = document.documentElement.classList.contains('dark');
        
        Swal.fire({
            title: title,
            text: message,
            icon: options.icon || 'warning',
            showCancelButton: true,
            confirmButtonText: options.confirmText || 'Ya, Lanjutkan',
            cancelButtonText: options.cancelText || 'Batal',
            reverseButtons: true,
            customClass: {
                confirmButton: 'swal2-confirm',
                cancelButton: 'swal2-cancel'
            }
        }).then((result) => {
            if (result.isConfirmed && callback) {
                callback();
            }
        });
    }

    // Show success message
    function showSuccess(title, message = '', callback = null) {
        Swal.fire({
            icon: 'success',
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
