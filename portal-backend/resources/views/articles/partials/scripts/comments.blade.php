{{-- Comments & Reply Module --}}

// Comment Reply State
replyingTo: null,
replyText: '',
replyLoading: false,

// Detail Modal Comments
detailComments: [],
detailCommentsLoading: false,

// Comment Reply Methods
openReplyForm(comment) {
    this.replyingTo = comment;
    this.replyText = '';
    this.$nextTick(() => {
        this.$refs.replyTextarea?.focus();
    });
},

cancelReply() {
    this.replyingTo = null;
    this.replyText = '';
},

async submitReply() {
    if (!this.replyingTo || !this.replyText.trim()) return;

    this.replyLoading = true;

    try {
        const response = await fetch(`/comments/${this.replyingTo.id}/reply`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ comment_text: this.replyText }),
        });
        const result = await response.json();

        if (result.success) {
            showToast('success', result.message);
            // Refresh statistics to show new reply
            if (this.statisticsData?.article_id) {
                await this.openStatisticsModal(this.statisticsData.article_id);
            }
            this.cancelReply();
        } else {
            showToast('error', result.message || 'Gagal mengirim balasan');
        }
    } catch (error) {
        console.error('Error submitting reply:', error);
        showToast('error', 'Gagal mengirim balasan');
    } finally {
        this.replyLoading = false;
    }
},

// Comment Status Methods
async hideComment(commentId) {
    try {
        const response = await fetch(`/comments/${commentId}/status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ status: 'hidden' }),
        });
        const result = await response.json();

        if (result.success) {
            showToast('success', 'Komentar disembunyikan');
            if (this.statisticsData?.article_id) {
                await this.openStatisticsModal(this.statisticsData.article_id);
            }
        } else {
            showToast('error', result.message);
        }
    } catch (error) {
        console.error('Error hiding comment:', error);
        showToast('error', 'Gagal menyembunyikan komentar');
    }
},

async showComment(commentId) {
    try {
        const response = await fetch(`/comments/${commentId}/status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ status: 'visible' }),
        });
        const result = await response.json();

        if (result.success) {
            showToast('success', 'Komentar ditampilkan');
            if (this.statisticsData?.article_id) {
                await this.openStatisticsModal(this.statisticsData.article_id);
            }
        } else {
            showToast('error', result.message);
        }
    } catch (error) {
        console.error('Error showing comment:', error);
        showToast('error', 'Gagal menampilkan komentar');
    }
},

async deleteComment(commentId) {
    showConfirm(
        'Hapus Komentar?',
        'Komentar akan disembunyikan dan dapat dipulihkan dari database.',
        async () => {
            try {
                showLoading('Menghapus...');
                const response = await fetch(`/comments/${commentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const result = await response.json();

                if (result.success) {
                    showToast('success', result.message);
                    if (this.statisticsData?.article_id) {
                        await this.openStatisticsModal(this.statisticsData.article_id);
                    }
                    // Also refresh detail modal comments if open
                    if (this.showDetailModal && this.selectedArticle) {
                        await this.fetchDetailComments(this.selectedArticle.id);
                    }
                } else {
                    showToast('error', result.message);
                }
            } catch (error) {
                console.error('Error deleting comment:', error);
                showToast('error', 'Gagal menghapus komentar');
            } finally {
                closeLoading();
            }
        },
        { icon: 'warning', confirmText: 'Ya, Hapus!' }
    );
},

// Fetch comments for detail modal
async fetchDetailComments(articleId) {
    this.detailCommentsLoading = true;
    this.detailComments = [];

    try {
        const response = await fetch(`/articles/${articleId}/comments`);
        const result = await response.json();

        if (result.success) {
            this.detailComments = result.data;
            this.$nextTick(() => lucide.createIcons());
        }
    } catch (error) {
        console.error('Error fetching comments:', error);
    } finally {
        this.detailCommentsLoading = false;
    }
},

// Comment status badge color helper
getCommentStatusColor(status) {
    const colors = {
        visible: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400',
        hidden: 'bg-surface-100 text-surface-600 dark:bg-surface-700 dark:text-surface-300',
        spam: 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400',
        reported: 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400',
    };
    return colors[status] || colors.visible;
},
