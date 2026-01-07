{{-- Security & Content Safety Module --}}

// Expanded dangerous keywords for comprehensive protection
dangerousKeywords: [
    // XSS & Script Injection
    '<' + 'script', '<' + '/script>', 'javascript:', 'vbscript:', 'onclick', 'onerror', 'onload', 'onmouseover',
    'onfocus', 'onblur', 'onsubmit', 'onchange', 'ondblclick', 'onkeydown', 'onkeyup', 'onkeypress',
    'onmousedown', 'onmouseup', 'onmousemove', 'onmouseout', 'onselect', 'onreset', 'onabort',
    'eval(', 'document.cookie', 'document.write', 'window.location', 'innerHTML',
    'expression(', 'url(javascript', 'behavior:', '-moz-binding',
    
    // Iframe & Embed Injection
    '<' + 'iframe', '<' + '/iframe>', '<' + 'embed', '<' + 'object', '<' + 'applet', '<' + 'frame', '<' + 'frameset',
    
    // SQL Injection Patterns
    'union select', 'drop table', 'insert into', 'delete from', '1=1', "1'='1", 'or 1=1',
    
    // PHP/Server Injection
    '<' + '?php', '?' + '>', '<' + '%', '%' + '>', 'system(', 'exec(', 'shell_exec', 'passthru(',
    
    // Gambling/Judi Online Keywords (Indonesian)
    'slot gacor', 'slot online', 'judi online', 'judi slot', 'situs judi', 'agen judi',
    'bandar togel', 'togel online', 'poker online', 'casino online', 'taruhan bola',
    'zeus', 'pragmatic', 'pragmatic play', 'pg soft', 'habanero', 'spadegaming',
    'bet88', 'bet365', 'sbobet', 'maxbet', 'ibcbet', 'nova88', 'm88',
    'judol', 'judionline', 'slotgacor', 'bonanza', 'gates of olympus', 'sweet bonanza',
    'mahjong ways', 'starlight princess', 'wild west gold', 'aztec gems',
    'rtp live', 'rtp slot', 'bocoran slot', 'pola slot', 'jp slot', 'jackpot slot',
    'deposit pulsa', 'slot pulsa', 'slot dana', 'slot ovo', 'slot gopay',
    'link alternatif', 'daftar slot', 'login slot', 'situs slot',
    
    // Gambling Keywords (English)
    'online gambling', 'sports betting', 'online casino', 'poker room',
],

// Threat categories for better reporting
threatCategories: {
    xss: ['<' + 'script', '<' + '/script>', 'javascript:', 'vbscript:', 'onclick', 'onerror', 'onload', 'onmouseover', 'onfocus', 'onblur', 'onsubmit', 'eval(', 'document.cookie', 'document.write', 'expression('],
    iframe: ['<' + 'iframe', '<' + '/iframe>', '<' + 'embed', '<' + 'object', '<' + 'applet', '<' + 'frame'],
    sql: ['union select', 'drop table', 'insert into', 'delete from', '1=1', "1'='1", 'or 1=1'],
    php: ['<' + '?php', '?' + '>', '<' + '%', '%' + '>', 'system(', 'exec(', 'shell_exec', 'passthru('],
    gambling: ['slot gacor', 'slot online', 'judi online', 'judi slot', 'situs judi', 'agen judi', 'bandar togel', 'togel online', 'poker online', 'casino online', 'zeus', 'pragmatic', 'bet88', 'sbobet', 'judol', 'bonanza', 'gates of olympus', 'rtp slot', 'bocoran slot']
},

checkContentSafety(content) {
    if (!content) {
        this.injectionDetected = false;
        this.detectedThreats = [];
        return;
    }
    
    const lowerContent = content.toLowerCase();
    const detected = [];
    
    // Check each keyword and categorize threats
    this.dangerousKeywords.forEach(keyword => {
        if (lowerContent.includes(keyword.toLowerCase())) {
            let category = 'unknown';
            let severity = 'medium';
            let description = 'Konten mencurigakan terdeteksi';
            
            // Determine category and severity
            if (this.threatCategories.xss.some(k => keyword.toLowerCase().includes(k.toLowerCase()))) {
                category = 'XSS Attack';
                severity = 'critical';
                description = 'Potensi serangan Cross-Site Scripting (XSS)';
            } else if (this.threatCategories.iframe.some(k => keyword.toLowerCase().includes(k.toLowerCase()))) {
                category = 'Iframe Injection';
                severity = 'high';
                description = 'Potensi injeksi iframe/embed berbahaya';
            } else if (this.threatCategories.sql.some(k => keyword.toLowerCase().includes(k.toLowerCase()))) {
                category = 'SQL Injection';
                severity = 'critical';
                description = 'Potensi serangan SQL Injection';
            } else if (this.threatCategories.php.some(k => keyword.toLowerCase().includes(k.toLowerCase()))) {
                category = 'Code Injection';
                severity = 'critical';
                description = 'Potensi injeksi kode server-side';
            } else if (this.threatCategories.gambling.some(k => keyword.toLowerCase().includes(k.toLowerCase()))) {
                category = 'Judi Online';
                severity = 'high';
                description = 'Konten promosi judi online terdeteksi';
            }
            
            // Avoid duplicates
            if (!detected.find(t => t.keyword === keyword)) {
                detected.push({
                    keyword: keyword,
                    category: category,
                    severity: severity,
                    description: description
                });
            }
        }
    });
    
    this.detectedThreats = detected;
    this.injectionDetected = detected.length > 0;
},

// Get sanitized preview without modifying actual content
previewSanitization() {
    let content = this.formData.content;
    if (!content) {
        showToast('warning', 'Tidak ada konten untuk di-preview');
        return;
    }
    
    // Create sanitized version
    this.dangerousKeywords.forEach(keyword => {
        const regex = new RegExp(keyword.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'gi');
        content = content.replace(regex, '<span class="bg-rose-200 dark:bg-rose-800 line-through text-rose-600 dark:text-rose-300">[REMOVED]</span>');
    });
    
    this.sanitizedPreviewContent = content;
    this.showSanitizePreview = true;
    this.$nextTick(() => lucide.createIcons());
},

// Apply sanitization to actual content
applySanitization() {
    let content = this.formData.content;
    if (!content) return;
    
    const removedCount = this.detectedThreats.length;
    
    this.dangerousKeywords.forEach(keyword => {
        const regex = new RegExp(keyword.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'gi');
        content = content.replace(regex, '');
    });
    
    // Clean up empty tags and extra whitespace
    content = content.replace(/<(\w+)[^>]*>\s*<\/\1>/g, '');
    content = content.replace(/\s{2,}/g, ' ');
    
    this.formData.content = content;
    
    // Update Trix Editor
    const element = document.querySelector('trix-editor');
    if (element && element.editor) {
        element.editor.loadHTML(content);
    }
    
    this.checkContentSafety(content);
    this.showSanitizePreview = false;
    showToast('success', `${removedCount} ancaman berhasil dibersihkan dari konten.`);
},

closeSanitizePreview() {
    this.showSanitizePreview = false;
    this.sanitizedPreviewContent = '';
},

getSeverityColor(severity) {
    const colors = {
        critical: 'bg-rose-500 text-white',
        high: 'bg-orange-500 text-white',
        medium: 'bg-amber-500 text-white',
        low: 'bg-yellow-400 text-yellow-900'
    };
    return colors[severity] || colors.medium;
},

getSeverityBorderColor(severity) {
    const colors = {
        critical: 'border-rose-500 bg-rose-50 dark:bg-rose-900/20',
        high: 'border-orange-500 bg-orange-50 dark:bg-orange-900/20',
        medium: 'border-amber-500 bg-amber-50 dark:bg-amber-900/20',
        low: 'border-yellow-400 bg-yellow-50 dark:bg-yellow-900/20'
    };
    return colors[severity] || colors.medium;
},
