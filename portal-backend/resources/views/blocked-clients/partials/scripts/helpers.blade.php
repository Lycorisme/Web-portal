{{-- Helpers Module --}}

// Pagination helper
getVisiblePages() {
    const pages = [];
    const current = this.meta.current_page;
    const last = this.meta.last_page;
    
    if (last <= 7) {
        for (let i = 1; i <= last; i++) pages.push(i);
    } else {
        pages.push(1);
        if (current > 3) pages.push('...');
        
        const start = Math.max(2, current - 1);
        const end = Math.min(last - 1, current + 1);
        
        for (let i = start; i <= end; i++) pages.push(i);
        
        if (current < last - 2) pages.push('...');
        pages.push(last);
    }
    
    return pages;
},

// Check if block is expired
isExpired(client) {
    if (!client.blocked_until) return false;
    return new Date(client.blocked_until) < new Date();
},

// Get status label
getStatusLabel(client) {
    if (!client.is_blocked) return 'Tidak Terblokir';
    if (this.isExpired(client)) return 'Expired';
    if (!client.blocked_until) return 'Permanen';
    return 'Terblokir';
},

// Format date
formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    return `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}, ${String(date.getHours()).padStart(2, '0')}:${String(date.getMinutes()).padStart(2, '0')}`;
},

// Get time remaining
getTimeRemaining(dateString) {
    if (!dateString) return '';
    const now = new Date();
    const target = new Date(dateString);
    const diff = target - now;
    
    if (diff <= 0) return 'Expired';
    
    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
    const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    
    if (days > 0) return `${days} hari lagi`;
    if (hours > 0) return `${hours} jam lagi`;
    return `${minutes} menit lagi`;
},
