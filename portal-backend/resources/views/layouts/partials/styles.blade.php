<style>
    [x-cloak] {
        display: none !important;
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-track {
        background: transparent;
    }

    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .dark ::-webkit-scrollbar-thumb {
        background: #3f3f46;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Hide scrollbar while keeping scroll functionality */
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    /* ============================================
       GLOBAL THEME SYSTEM - CSS Variables
       ============================================ */
    
    :root {
        /* Default: Indigo Theme */
        --theme-50: 238 242 255;
        --theme-100: 224 231 255;
        --theme-200: 199 210 254;
        --theme-300: 165 180 252;
        --theme-400: 129 140 248;
        --theme-500: 99 102 241;
        --theme-600: 79 70 229;
        --theme-700: 67 56 202;
        --theme-800: 55 48 163;
        --theme-900: 49 46 129;
        --theme-gradient-from: #6366f1;
        --theme-gradient-to: #4f46e5;
    }

    /* Indigo Theme */
    [data-theme="indigo"] {
        --theme-50: 238 242 255;
        --theme-100: 224 231 255;
        --theme-200: 199 210 254;
        --theme-300: 165 180 252;
        --theme-400: 129 140 248;
        --theme-500: 99 102 241;
        --theme-600: 79 70 229;
        --theme-700: 67 56 202;
        --theme-800: 55 48 163;
        --theme-900: 49 46 129;
        --theme-gradient-from: #6366f1;
        --theme-gradient-to: #4f46e5;
    }

    /* Emerald Theme */
    [data-theme="emerald"] {
        --theme-50: 236 253 245;
        --theme-100: 209 250 229;
        --theme-200: 167 243 208;
        --theme-300: 110 231 183;
        --theme-400: 52 211 153;
        --theme-500: 16 185 129;
        --theme-600: 5 150 105;
        --theme-700: 4 120 87;
        --theme-800: 6 95 70;
        --theme-900: 6 78 59;
        --theme-gradient-from: #10b981;
        --theme-gradient-to: #059669;
    }

    /* Rose Theme */
    [data-theme="rose"] {
        --theme-50: 255 241 242;
        --theme-100: 255 228 230;
        --theme-200: 254 205 211;
        --theme-300: 253 164 175;
        --theme-400: 251 113 133;
        --theme-500: 244 63 94;
        --theme-600: 225 29 72;
        --theme-700: 190 18 60;
        --theme-800: 159 18 57;
        --theme-900: 136 19 55;
        --theme-gradient-from: #f43f5e;
        --theme-gradient-to: #e11d48;
    }

    /* Amber Theme */
    [data-theme="amber"] {
        --theme-50: 255 251 235;
        --theme-100: 254 243 199;
        --theme-200: 253 230 138;
        --theme-300: 252 211 77;
        --theme-400: 251 191 36;
        --theme-500: 245 158 11;
        --theme-600: 217 119 6;
        --theme-700: 180 83 9;
        --theme-800: 146 64 14;
        --theme-900: 120 53 15;
        --theme-gradient-from: #f59e0b;
        --theme-gradient-to: #d97706;
    }

    /* Cyan Theme */
    [data-theme="cyan"] {
        --theme-50: 236 254 255;
        --theme-100: 207 250 254;
        --theme-200: 165 243 252;
        --theme-300: 103 232 249;
        --theme-400: 34 211 238;
        --theme-500: 6 182 212;
        --theme-600: 8 145 178;
        --theme-700: 14 116 144;
        --theme-800: 21 94 117;
        --theme-900: 22 78 99;
        --theme-gradient-from: #06b6d4;
        --theme-gradient-to: #0891b2;
    }

    /* Violet Theme */
    [data-theme="violet"] {
        --theme-50: 245 243 255;
        --theme-100: 237 233 254;
        --theme-200: 221 214 254;
        --theme-300: 196 181 253;
        --theme-400: 167 139 250;
        --theme-500: 139 92 246;
        --theme-600: 124 58 237;
        --theme-700: 109 40 217;
        --theme-800: 91 33 182;
        --theme-900: 76 29 149;
        --theme-gradient-from: #8b5cf6;
        --theme-gradient-to: #7c3aed;
    }

    /* Slate Theme */
    [data-theme="slate"] {
        --theme-50: 248 250 252;
        --theme-100: 241 245 249;
        --theme-200: 226 232 240;
        --theme-300: 203 213 225;
        --theme-400: 148 163 184;
        --theme-500: 100 116 139;
        --theme-600: 71 85 105;
        --theme-700: 51 65 85;
        --theme-800: 30 41 59;
        --theme-900: 15 23 42;
        --theme-gradient-from: #64748b;
        --theme-gradient-to: #475569;
    }

    /* Ocean Theme */
    [data-theme="ocean"] {
        --theme-50: 239 246 255;
        --theme-100: 219 234 254;
        --theme-200: 191 219 254;
        --theme-300: 147 197 253;
        --theme-400: 96 165 250;
        --theme-500: 59 130 246;
        --theme-600: 37 99 235;
        --theme-700: 29 78 216;
        --theme-800: 30 64 175;
        --theme-900: 30 58 138;
        --theme-gradient-from: #3b82f6;
        --theme-gradient-to: #0891b2;
    }

    /* Sunset Theme */
    [data-theme="sunset"] {
        --theme-50: 255 247 237;
        --theme-100: 255 237 213;
        --theme-200: 254 215 170;
        --theme-300: 253 186 116;
        --theme-400: 251 146 60;
        --theme-500: 249 115 22;
        --theme-600: 234 88 12;
        --theme-700: 194 65 12;
        --theme-800: 154 52 18;
        --theme-900: 124 45 18;
        --theme-gradient-from: #f97316;
        --theme-gradient-to: #e11d48;
    }

    /* Theme-aware utility classes */
    .bg-theme-50 { background-color: rgb(var(--theme-50)); }
    .bg-theme-100 { background-color: rgb(var(--theme-100)); }
    .bg-theme-200 { background-color: rgb(var(--theme-200)); }
    .bg-theme-300 { background-color: rgb(var(--theme-300)); }
    .bg-theme-400 { background-color: rgb(var(--theme-400)); }
    .bg-theme-500 { background-color: rgb(var(--theme-500)); }
    .bg-theme-600 { background-color: rgb(var(--theme-600)); }
    .bg-theme-700 { background-color: rgb(var(--theme-700)); }
    .bg-theme-800 { background-color: rgb(var(--theme-800)); }
    .bg-theme-900 { background-color: rgb(var(--theme-900)); }

    .text-theme-50 { color: rgb(var(--theme-50)); }
    .text-theme-100 { color: rgb(var(--theme-100)); }
    .text-theme-200 { color: rgb(var(--theme-200)); }
    .text-theme-300 { color: rgb(var(--theme-300)); }
    .text-theme-400 { color: rgb(var(--theme-400)); }
    .text-theme-500 { color: rgb(var(--theme-500)); }
    .text-theme-600 { color: rgb(var(--theme-600)); }
    .text-theme-700 { color: rgb(var(--theme-700)); }
    .text-theme-800 { color: rgb(var(--theme-800)); }
    .text-theme-900 { color: rgb(var(--theme-900)); }

    .border-theme-500 { border-color: rgb(var(--theme-500)); }
    .border-theme-600 { border-color: rgb(var(--theme-600)); }

    .ring-theme-500 { --tw-ring-color: rgb(var(--theme-500)); }
    .ring-theme-600 { --tw-ring-color: rgb(var(--theme-600)); }

    .bg-theme-gradient {
        background: linear-gradient(135deg, var(--theme-gradient-from), var(--theme-gradient-to));
    }

    .shadow-theme {
        box-shadow: 0 10px 25px -3px rgb(var(--theme-500) / 0.3);
    }

    .hover\:bg-theme-600:hover { background-color: rgb(var(--theme-600)); }
    .hover\:bg-theme-700:hover { background-color: rgb(var(--theme-700)); }

    .focus\:ring-theme-500:focus { --tw-ring-color: rgb(var(--theme-500)); }

    /* Text Selection / Highlight */
    ::selection {
        background-color: rgb(var(--theme-500));
        color: white;
    }
    
    ::-moz-selection {
        background-color: rgb(var(--theme-500));
        color: white;
    }

    /* SweetAlert2 Custom Theme */
    .swal2-popup {
        border-radius: 1.5rem !important;
        padding: 2rem !important;
    }
    
    .dark .swal2-popup {
        background: #18181b !important;
        color: #f4f4f5 !important;
    }
    
    .dark .swal2-title {
        color: #ffffff !important;
    }
    
    .dark .swal2-html-container {
        color: #a1a1aa !important;
    }
    
    .swal2-confirm {
        background: linear-gradient(135deg, var(--theme-gradient-from), var(--theme-gradient-to)) !important;
        border-radius: 0.75rem !important;
        font-weight: 600 !important;
        padding: 0.75rem 1.5rem !important;
    }
    
    .swal2-cancel {
        border-radius: 0.75rem !important;
        font-weight: 500 !important;
        padding: 0.75rem 1.5rem !important;
    }
    
    .dark .swal2-cancel {
        background: #27272a !important;
        color: #d4d4d8 !important;
    }

    /* ============================================
       PRINT / FULL PAGE SCREENSHOT STYLES
       Fixes sidebar cutoff during GoFullPage/print
       ============================================ */
    @media print {
        /* Reset body for print layout */
        body {
            overflow: visible !important;
            height: auto !important;
        }

        /* Layout wrapper should use regular flex in print */
        #app-layout-wrapper {
            display: flex !important;
            flex-direction: row !important;
            min-height: auto !important;
        }

        /* Sidebar wrapper for print - ensures full height */
        #sidebar-wrapper {
            position: relative !important;
            width: 288px !important;
            flex-shrink: 0 !important;
            height: auto !important;
            min-height: 100% !important;
        }

        /* Convert sidebar from fixed to relative for proper capture */
        #admin-sidebar {
            position: relative !important;
            width: 100% !important;
            height: auto !important;
            min-height: 100% !important;
            overflow: visible !important;
            transform: none !important;
            left: 0 !important;
            top: 0 !important;
        }

        /* Ensure main content doesn't overlap sidebar */
        #main-content {
            flex: 1 !important;
            margin-left: 0 !important;
            overflow: visible !important;
        }

        /* Ensure nav scrolls properly */
        #sidebar-nav {
            overflow: visible !important;
            max-height: none !important;
            flex: 1 !important;
        }

        /* Hide mobile backdrop */
        .fixed.inset-0.z-40 {
            display: none !important;
        }

        /* Ensure all containers are visible */
        .min-h-screen {
            min-height: auto !important;
            height: auto !important;
        }

        /* Remove backdrop blur effects that can cause issues */
        .backdrop-blur-xl,
        .backdrop-blur-sm {
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
        }

        /* Ensure visibility of all elements */
        [x-cloak] {
            display: block !important;
        }

        /* Remove fixed position from header if any */
        header {
            position: relative !important;
        }

        /* Print-friendly background */
        .dark .bg-surface-950,
        .dark .bg-surface-900\/95,
        .dark #admin-sidebar {
            background-color: #18181b !important;
            print-color-adjust: exact;
            -webkit-print-color-adjust: exact;
        }

        /* Ensure gradients render in print */
        .bg-theme-gradient {
            print-color-adjust: exact;
            -webkit-print-color-adjust: exact;
        }
    }

    /* Additional class for programmatic full-page capture mode */
    body.capture-mode #app-layout-wrapper,
    html.capture-mode #app-layout-wrapper {
        display: flex !important;
        flex-direction: row !important;
    }

    body.capture-mode #sidebar-wrapper,
    html.capture-mode #sidebar-wrapper {
        position: relative !important;
        width: 288px !important;
        flex-shrink: 0 !important;
    }

    body.capture-mode #admin-sidebar,
    html.capture-mode #admin-sidebar {
        position: relative !important;
        width: 100% !important;
        height: auto !important;
        min-height: 100% !important;
        overflow: visible !important;
        transform: none !important;
    }

    body.capture-mode #main-content,
    html.capture-mode #main-content {
        overflow: visible !important;
        margin-left: 0 !important;
        flex: 1 !important;
    }

    body.capture-mode #sidebar-nav,
    html.capture-mode #sidebar-nav {
        overflow: visible !important;
        max-height: none !important;
    }

    body.capture-mode,
    html.capture-mode {
        overflow: visible !important;
        height: auto !important;
    }
</style>
