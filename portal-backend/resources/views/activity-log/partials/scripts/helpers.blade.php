{{-- Helpers & Utilities Module --}}

getLevelBadgeClass(level) {
    const classes = {
        'info': 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400',
        'warning': 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400',
        'danger': 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400',
        'critical': 'bg-red-600 text-white',
    };
    return classes[level] || 'bg-surface-100 text-surface-700';
},

getActionBadgeClass(action) {
    const actionColors = {
        'CREATE': 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400',
        'UPDATE': 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400',
        'DELETE': 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400',
        'LOGIN': 'bg-violet-100 text-violet-700 dark:bg-violet-500/20 dark:text-violet-400',
        'LOGOUT': 'bg-slate-100 text-slate-700 dark:bg-slate-500/20 dark:text-slate-400',
        'LOGIN_FAILED': 'bg-orange-100 text-orange-700 dark:bg-orange-500/20 dark:text-orange-400',
    };
    return actionColors[action] || 'bg-surface-100 text-surface-700 dark:bg-surface-700 dark:text-surface-300';
},

formatJson(data) {
    if (!data) return '-';
    try { return JSON.stringify(data, null, 2); } 
    catch { return String(data); }
},

getAllKeys(oldVal, newVal) {
    const keys = new Set([...Object.keys(oldVal || {}), ...Object.keys(newVal || {})]);
    return Array.from(keys);
},

formatKey(key) {
    return key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
},

formatValue(value) {
    if (value === null || value === undefined || value === '') return '-';
    if (typeof value === 'boolean') return value ? 'Ya' : 'Tidak';
    if (Array.isArray(value)) return value.join(', ');
    if (typeof value === 'object') return JSON.stringify(value);
    return value;
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
