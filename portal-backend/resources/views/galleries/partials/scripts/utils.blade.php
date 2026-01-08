// ========================================
// UTILITY & FORMAT METHODS
// ========================================

formatDateIndo(dateStr) {
    if (!dateStr) return '-';
    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    
    const parts = dateStr.match(/(\d{2})\s(\w+)\s(\d{4})\s(\d{2}):(\d{2})/);
    if (!parts) return dateStr;
    
    const date = new Date(`${parts[2]} ${parts[1]}, ${parts[3]} ${parts[4]}:${parts[5]}`);
    if (isNaN(date)) return dateStr;
    
    const dayName = days[date.getDay()];
    const day = String(date.getDate()).padStart(2, '0');
    const month = months[date.getMonth()];
    const year = date.getFullYear();
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    
    return `${dayName}, ${day} ${month} ${year} ${hours}.${minutes}`;
},

getYoutubeId(url) {
    if (!url) return null;
    const match = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/);
    return match ? match[1] : null;
},
