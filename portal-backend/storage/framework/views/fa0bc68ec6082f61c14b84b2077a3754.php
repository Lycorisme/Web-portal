<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <div 
        class="space-y-6 animate-fade-in"
        x-data="dashboardHandler()"
        x-init="init()"
    >
        
        <?php echo $__env->make('dashboard.partials.welcome-banner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        
        <?php echo $__env->make('dashboard.partials.stats-grid', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            
            <div class="lg:col-span-8 flex flex-col gap-6">
                
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <?php echo $__env->make('dashboard.partials.visitor-chart', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                    
                    <?php echo $__env->make('dashboard.partials.category-distribution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>

                
                <?php echo $__env->make('dashboard.partials.recent-news', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

            
            <div class="lg:col-span-4 flex flex-col gap-6">
                
                
                <?php echo $__env->make('dashboard.partials.quick-actions', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($stats['is_admin'] ?? false): ?>
                    <?php echo $__env->make('dashboard.partials.security-widget', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                
                <?php echo $__env->make('dashboard.partials.activity-log', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>

        
        <?php echo $__env->make('dashboard.partials.article-detail-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        
        <?php echo $__env->make('dashboard.partials.activity-detail-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Pre-load article data from controller
const articlesData = <?php echo json_encode($articlesForModal, 15, 512) ?>;

// Pre-load activity logs data from controller
const activityLogsData = <?php echo json_encode($activityLogsForModal, 15, 512) ?>;

function dashboardHandler() {
    return {
        // Article Modal
        showArticleModal: false,
        selectedArticle: null,
        
        // Activity Modal
        showActivityModal: false,
        selectedLog: null,
        
        init() {
            // Initialize Lucide icons after modal content loads
            this.$watch('showArticleModal', (value) => {
                if (value) {
                    this.$nextTick(() => {
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                    });
                }
            });
            this.$watch('showActivityModal', (value) => {
                if (value) {
                    this.$nextTick(() => {
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                    });
                }
            });
        },
        
        // Article functions
        openArticleModal(articleId) {
            const article = articlesData[articleId];
            if (article) {
                this.selectedArticle = article;
                this.showArticleModal = true;
            } else {
                console.warn('Article not found:', articleId);
            }
        },
        
        closeArticleModal() {
            this.showArticleModal = false;
            setTimeout(() => {
                this.selectedArticle = null;
            }, 200);
        },
        
        getStatusColor(status) {
            const colors = {
                'published': 'text-emerald-600 ring-emerald-500/20 bg-emerald-50 dark:bg-emerald-500/10 dark:text-emerald-400',
                'draft': 'text-amber-600 ring-amber-500/20 bg-amber-50 dark:bg-amber-500/10 dark:text-amber-400',
                'pending': 'text-blue-600 ring-blue-500/20 bg-blue-50 dark:bg-blue-500/10 dark:text-blue-400',
            };
            return colors[status] || 'text-surface-600 ring-surface-500/20';
        },
        
        getStatusLabel(status) {
            const labels = {
                'published': 'Published',
                'draft': 'Draft',
                'pending': 'Review',
            };
            return labels[status] || status;
        },
        
        // Activity functions
        openActivityModal(logId) {
            const log = activityLogsData[logId];
            if (log) {
                this.selectedLog = log;
                this.showActivityModal = true;
            } else {
                console.warn('Activity log not found:', logId);
            }
        },
        
        closeActivityModal() {
            this.showActivityModal = false;
            setTimeout(() => {
                this.selectedLog = null;
            }, 200);
        },
        
        getActionBadgeClass(action) {
            const classes = {
                'CREATE': 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400',
                'UPDATE': 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400',
                'DELETE': 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400',
                'LOGIN': 'bg-violet-100 text-violet-700 dark:bg-violet-500/20 dark:text-violet-400',
                'LOGOUT': 'bg-slate-100 text-slate-700 dark:bg-slate-500/20 dark:text-slate-400',
                'LOGIN_FAILED': 'bg-orange-100 text-orange-700 dark:bg-orange-500/20 dark:text-orange-400',
            };
            return classes[action] || 'bg-surface-100 text-surface-700 dark:bg-surface-500/20 dark:text-surface-400';
        },
        
        getLevelBadgeClass(level) {
            const classes = {
                'info': 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400',
                'warning': 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400',
                'error': 'bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400',
                'critical': 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400',
            };
            return classes[level] || 'bg-surface-50 text-surface-600 dark:bg-surface-500/10 dark:text-surface-400';
        },
        
        getAllKeys(oldValues, newValues) {
            const keys = new Set();
            if (oldValues) Object.keys(oldValues).forEach(k => keys.add(k));
            if (newValues) Object.keys(newValues).forEach(k => keys.add(k));
            return Array.from(keys);
        },
        
        formatKey(key) {
            return key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        },
        
        formatValue(value) {
            if (value === null || value === undefined) return '—';
            if (typeof value === 'object') return JSON.stringify(value, null, 2);
            return String(value);
        }
    }
}
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views\dashboard.blade.php ENDPATH**/ ?>