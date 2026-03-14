<script>
function reportsPage() {
    return {
        loading: {
            users: false,
            articles: false,
            categories: false,
            galleries: false,
            interactions: false,
            activityLogs: false,
            security: false,
            statistics: false
        },
        forms: {
            users: {
                start_date: '',
                end_date: '',
                role: ''
            },
            articles: {
                start_date: '',
                end_date: '',
                status: ''
            },
            categories: {
                start_date: '',
                end_date: '',
                is_active: ''
            },
            galleries: {
                start_date: '',
                end_date: '',
                media_type: ''
            },
            interactions: {
                start_date: '',
                end_date: ''
            },
            activityLogs: {
                start_date: '',
                end_date: '',
                action: ''
            },
            security: {
                start_date: '',
                end_date: '',
                is_blocked: ''
            },
            statistics: {}
        },

        init() {
            // Reinitialize Lucide icons
            this.$nextTick(() => {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        },

        async generateReport(type) {
            // Map URL type to loading/form key
            const loadingKeys = {
                'users': 'users',
                'articles': 'articles',
                'categories': 'categories',
                'galleries': 'galleries',
                'interactions': 'interactions',
                'activity-logs': 'activityLogs',
                'security': 'security',
                'statistics': 'statistics'
            };

            const formKeys = {
                'users': 'users',
                'articles': 'articles',
                'categories': 'categories',
                'galleries': 'galleries',
                'interactions': 'interactions',
                'activity-logs': 'activityLogs',
                'security': 'security',
                'statistics': 'statistics'
            };

            const loadingKey = loadingKeys[type];
            const formKey = formKeys[type];
            
            this.loading[loadingKey] = true;

            try {
                const form = this.forms[formKey];
                const params = new URLSearchParams();

                Object.keys(form).forEach(key => {
                    if (form[key]) {
                        params.append(key, form[key]);
                    }
                });

                // Add cache buster to prevent browser from serving an old cached PDF
                params.append('_t', new Date().getTime());

                const url = `/reports/${type}?${params.toString()}`;
                
                // Open in new tab or download
                window.location.href = url;

                // Small delay to show loading state
                await new Promise(resolve => setTimeout(resolve, 1000));
            } catch (error) {
                console.error('Error generating report:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat generate laporan.',
                    confirmButtonColor: 'var(--theme-primary)',
                });
            } finally {
                this.loading[loadingKey] = false;
            }
        }
    };
}
</script>
<?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views/reports/partials/scripts.blade.php ENDPATH**/ ?>