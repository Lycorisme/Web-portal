{{-- Helpers & Utilities Module --}}

// Status options
statusOptions: [
    { value: 'draft', label: 'Draft', color: 'surface' },
    { value: 'pending', label: 'Pending', color: 'amber' },
    { value: 'published', label: 'Published', color: 'emerald' },
    { value: 'rejected', label: 'Rejected', color: 'rose' },
],

getStatusLabel(status) {
    const option = this.statusOptions.find(o => o.value === status);
    return option ? option.label : status;
},

getStatusColor(status) {
    const colors = {
        draft: 'bg-surface-100 text-surface-600 dark:bg-surface-700 dark:text-surface-300',
        pending: 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400',
        published: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400',
        rejected: 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400',
    };
    return colors[status] || colors.draft;
},

// Pagination helpers
get paginationPages() {
    const pages = [];
    const current = this.meta.current_page;
    const last = this.meta.last_page;
    
    if (last <= 7) {
        for (let i = 1; i <= last; i++) pages.push(i);
    } else {
        if (current <= 3) {
            for (let i = 1; i <= 5; i++) pages.push(i);
            pages.push('...');
            pages.push(last);
        } else if (current >= last - 2) {
            pages.push(1);
            pages.push('...');
            for (let i = last - 4; i <= last; i++) pages.push(i);
        } else {
            pages.push(1);
            pages.push('...');
            for (let i = current - 1; i <= current + 1; i++) pages.push(i);
            pages.push('...');
            pages.push(last);
        }
    }
    return pages;
},
