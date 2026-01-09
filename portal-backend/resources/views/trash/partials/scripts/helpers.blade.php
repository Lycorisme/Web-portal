// Computed: Pagination pages array
get paginationPages() {
    const pages = [];
    const current = this.meta.current_page;
    const last = this.meta.last_page;
    
    if (last <= 7) {
        for (let i = 1; i <= last; i++) pages.push(i);
    } else {
        pages.push(1);
        
        if (current > 3) pages.push('...');
        
        let start = Math.max(2, current - 1);
        let end = Math.min(last - 1, current + 1);
        
        if (current <= 3) {
            start = 2;
            end = 4;
        }
        
        if (current >= last - 2) {
            start = last - 3;
            end = last - 1;
        }
        
        for (let i = start; i <= end; i++) pages.push(i);
        
        if (current < last - 2) pages.push('...');
        
        pages.push(last);
    }
    
    return pages;
},
