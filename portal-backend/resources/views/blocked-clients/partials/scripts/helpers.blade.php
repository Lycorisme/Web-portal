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

// Check if IP is under review (tracked but not blocked)
isUnderReview(client) {
    return !client.is_blocked && client.attempt_count > 0 && !client.user_id;
},

// Check if IP is a logged-in monitoring record
isLoggedIn(client) {
    return !client.is_blocked && client.user_id && client.last_login_at;
},

// Get status label
getStatusLabel(client) {
    // IP yang tercatat tapi belum di-block = Ditinjau
    if (!client.is_blocked && client.attempt_count > 0 && !client.user_id) return 'Ditinjau';
    if (this.isLoggedIn(client)) return 'Login Tercatat';
    if (!client.is_blocked) return 'Tidak Terblokir';
    if (this.isExpired(client)) return 'Expired';
    if (!client.blocked_until) return 'Permanen';
    return 'Terblokir';
},

// Get status icon
getStatusIcon(client) {
    if (client.is_blocked && this.isExpired(client)) return 'clock';
    if (client.is_blocked) return 'shield-ban';
    if (this.isUnderReview(client)) return 'eye';
    if (this.isLoggedIn(client)) return 'user-check';
    return 'shield-check';
},

// Get expired column text
getExpiredText(client) {
    // IP yang sedang ditinjau (belum diblokir)
    if (!client.is_blocked && client.attempt_count > 0) {
        return 'Menunggu Keputusan';
    }
    if (!client.is_blocked) return '-';
    if (!client.blocked_until) return 'Permanen';
    if (this.isExpired(client)) return 'Expired';
    return this.formatDate(client.blocked_until);
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
