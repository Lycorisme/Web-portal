{{-- Statistics & Activity Modal Module --}}

// Statistics Modal State
showStatisticsModal: false,
statisticsData: null,
statisticsLoading: false,

// Activity Modal State
showActivityModal: false,
activityLogs: [],
activityLogLoading: false,
activityLogArticleTitle: '',

// Statistics Modal Methods
async openStatisticsModal(articleId) {
    this.showStatisticsModal = true;
    this.statisticsLoading = true;
    this.statisticsData = null;

    try {
        const response = await fetch(`/articles/${articleId}/statistics`);
        const result = await response.json();

        if (result.success) {
            this.statisticsData = result.data;
            this.$nextTick(() => lucide.createIcons());
        } else {
            showToast('error', result.message || 'Gagal memuat statistik');
        }
    } catch (error) {
        console.error('Error fetching statistics:', error);
        showToast('error', 'Gagal memuat statistik');
    } finally {
        this.statisticsLoading = false;
    }
},

closeStatisticsModal() {
    this.showStatisticsModal = false;
    this.statisticsData = null;
    this.replyingTo = null;
    this.replyText = '';
},

// Activity Modal Methods
async openActivityModal(articleId, articleTitle) {
    this.showActivityModal = true;
    this.activityLogLoading = true;
    this.activityLogs = [];
    this.activityLogArticleTitle = articleTitle;

    try {
        const response = await fetch(`/articles/${articleId}/activities`);
        const result = await response.json();

        if (result.success) {
            this.activityLogs = result.data;
            this.$nextTick(() => lucide.createIcons());
        } else {
            showToast('error', result.message || 'Gagal memuat log aktivitas');
        }
    } catch (error) {
        console.error('Error fetching activity log:', error);
        showToast('error', 'Gagal memuat log aktivitas');
    } finally {
        this.activityLogLoading = false;
    }
},

closeActivityModal() {
    this.showActivityModal = false;
    this.activityLogs = [];
},
