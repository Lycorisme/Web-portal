<script>
function reportsPage() {
    return {
        loading: {
            articles: false,
            categories: false,
            users: false,
            activityLogs: false,
            blockedClients: false,
            galleries: false
        },
        forms: {
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
            users: {
                start_date: '',
                end_date: '',
                role: ''
            },
            activityLogs: {
                start_date: '',
                end_date: '',
                action: ''
            },
            blockedClients: {
                start_date: '',
                end_date: '',
                is_blocked: ''
            },
            galleries: {
                start_date: '',
                end_date: '',
                media_type: ''
            }
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
            // Map form key to loading key
            const loadingKeys = {
                'articles': 'articles',
                'categories': 'categories',
                'users': 'users',
                'activity-logs': 'activityLogs',
                'blocked-clients': 'blockedClients',
                'galleries': 'galleries'
            };

            const formKeys = {
                'articles': 'articles',
                'categories': 'categories',
                'users': 'users',
                'activity-logs': 'activityLogs',
                'blocked-clients': 'blockedClients',
                'galleries': 'galleries'
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
