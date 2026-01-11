{{-- Public Layout Styles --}}
<style>
    :root {
        /* Primary Colors - Vibrant Blue Gradient */
        --primary-50: #eff6ff;
        --primary-100: #dbeafe;
        --primary-200: #bfdbfe;
        --primary-300: #93c5fd;
        --primary-400: #60a5fa;
        --primary-500: #3b82f6;
        --primary-600: #2563eb;
        --primary-700: #1d4ed8;
        --primary-800: #1e40af;
        --primary-900: #1e3a8a;
        
        /* Accent Colors - Coral/Orange */
        --accent-50: #fff7ed;
        --accent-100: #ffedd5;
        --accent-200: #fed7aa;
        --accent-300: #fdba74;
        --accent-400: #fb923c;
        --accent-500: #f97316;
        --accent-600: #ea580c;
        --accent-700: #c2410c;
        
        /* Semantic Colors */
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --info: #06b6d4;
        
        /* Neutrals */
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-300: #d1d5db;
        --gray-400: #9ca3af;
        --gray-500: #6b7280;
        --gray-600: #4b5563;
        --gray-700: #374151;
        --gray-800: #1f2937;
        --gray-900: #111827;
        
        /* Gradients */
        --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --gradient-accent: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --gradient-hero: linear-gradient(135deg, #1e3a8a 0%, #7c3aed 50%, #db2777 100%);
        --gradient-card: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        
        /* Typography */
        --font-primary: 'Plus Jakarta Sans', sans-serif;
        --font-display: 'Playfair Display', serif;
        
        /* Shadows */
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        --shadow-glow: 0 0 40px rgba(59, 130, 246, 0.3);
        
        /* Transitions */
        --transition-fast: 150ms ease;
        --transition-base: 200ms ease;
        --transition-slow: 300ms ease;
        
        /* Border Radius */
        --radius-sm: 0.375rem;
        --radius-md: 0.5rem;
        --radius-lg: 0.75rem;
        --radius-xl: 1rem;
        --radius-2xl: 1.5rem;
        --radius-full: 9999px;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }
    html, body { overflow-x: hidden; width: 100%; max-width: 100vw; }
    img, video, iframe, embed, object { max-width: 100%; height: auto; }
    p, h1, h2, h3, h4, h5, h6, li, td, th, label, span, a { word-wrap: break-word; overflow-wrap: break-word; word-break: break-word; }
    pre, code { white-space: pre-wrap; word-wrap: break-word; overflow-wrap: break-word; max-width: 100%; }
    table { max-width: 100%; overflow-x: auto; display: block; }
    .flex, [class*="flex"] { min-width: 0; }
    .grid, [class*="grid"] { min-width: 0; }
    .grid > *, [class*="grid"] > * { min-width: 0; }

    body {
        font-family: var(--font-primary);
        background: var(--gray-50);
        color: var(--gray-800);
        line-height: 1.6;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* Header */
    .header { background: white; box-shadow: var(--shadow-md); position: sticky; top: 0; z-index: 100; }
    .header-top { background: var(--gradient-hero); padding: 0.5rem 0; }
    .header-top-content { max-width: 1280px; margin: 0 auto; padding: 0 1rem; display: flex; justify-content: space-between; align-items: center; font-size: 0.8125rem; color: rgba(255, 255, 255, 0.9); }
    .header-top a { color: white; text-decoration: none; transition: var(--transition-fast); }
    .header-top a:hover { color: var(--accent-300); }
    .header-top-social { display: flex; gap: 1rem; }
    .header-top-social a { font-size: 1rem; }
    .header-main { max-width: 1280px; margin: 0 auto; padding: 1rem; display: flex; justify-content: space-between; align-items: center; }

    /* Logo */
    .logo { display: flex; align-items: center; gap: 0.75rem; text-decoration: none; }
    .logo img { height: 48px; width: auto; }
    .logo-text { display: flex; flex-direction: column; }
    .logo-name { font-family: var(--font-display); font-size: 1.5rem; font-weight: 700; color: var(--primary-700); line-height: 1.2; }
    .logo-tagline { font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase; letter-spacing: 0.05em; }

    /* Navigation */
    .nav { display: flex; gap: 0.25rem; }
    .nav a { padding: 0.625rem 1rem; color: var(--gray-700); text-decoration: none; font-weight: 500; font-size: 0.9375rem; border-radius: var(--radius-lg); transition: var(--transition-base); position: relative; }
    .nav a:hover, .nav a.active { color: var(--primary-600); background: var(--primary-50); }
    .nav a.active::after { content: ''; position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); width: 24px; height: 3px; background: var(--gradient-primary); border-radius: var(--radius-full); }

    /* Header Actions */
    .header-actions { display: flex; align-items: center; gap: 0.75rem; }
    .search-toggle { width: 40px; height: 40px; border: none; background: var(--gray-100); color: var(--gray-600); border-radius: var(--radius-lg); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: var(--transition-base); }
    .search-toggle:hover { background: var(--primary-100); color: var(--primary-600); }

    /* Buttons */
    .btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1.25rem; font-size: 0.875rem; font-weight: 600; text-decoration: none; border-radius: var(--radius-lg); transition: var(--transition-base); cursor: pointer; border: none; }
    .btn-primary { background: var(--gradient-primary); color: white; box-shadow: 0 4px 14px rgba(102, 126, 234, 0.4); }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5); }
    .btn-outline { background: transparent; color: var(--primary-600); border: 2px solid var(--primary-200); }
    .btn-outline:hover { background: var(--primary-50); border-color: var(--primary-400); }
    .mobile-menu-toggle { display: none; width: 40px; height: 40px; border: none; background: var(--gray-100); color: var(--gray-600); border-radius: var(--radius-lg); cursor: pointer; align-items: center; justify-content: center; }

    /* Main Content */
    .main-content { flex: 1; }
    .container { max-width: 1280px; margin: 0 auto; padding: 0 1rem; }

    /* Footer */
    .footer { background: var(--gray-900); color: white; margin-top: auto; }
    .footer-main { padding: 3rem 0; }
    .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 2rem; }
    .footer-brand p { color: var(--gray-400); margin-top: 1rem; font-size: 0.9375rem; line-height: 1.7; }
    .footer-title { font-size: 1rem; font-weight: 600; color: white; margin-bottom: 1.25rem; }
    .footer-links { list-style: none; }
    .footer-links li { margin-bottom: 0.75rem; }
    .footer-links a { color: var(--gray-400); text-decoration: none; font-size: 0.9375rem; transition: var(--transition-fast); }
    .footer-links a:hover { color: var(--primary-400); }
    .footer-social { display: flex; gap: 0.75rem; margin-top: 1.5rem; }
    .footer-social a { width: 40px; height: 40px; background: rgba(255, 255, 255, 0.1); color: white; border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; transition: var(--transition-base); }
    .footer-social a:hover { background: var(--primary-600); transform: translateY(-2px); }
    .footer-bottom { border-top: 1px solid rgba(255, 255, 255, 0.1); padding: 1.5rem 0; text-align: center; color: var(--gray-500); font-size: 0.875rem; }

    /* Sections */
    .section { padding: 3rem 0; }
    .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
    .section-title { font-family: var(--font-display); font-size: 1.75rem; font-weight: 700; color: var(--gray-900); position: relative; padding-left: 1rem; }
    .section-title::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background: var(--gradient-primary); border-radius: var(--radius-full); }
    .view-all { display: inline-flex; align-items: center; gap: 0.5rem; color: var(--primary-600); font-weight: 600; font-size: 0.9375rem; text-decoration: none; transition: var(--transition-base); }
    .view-all:hover { color: var(--primary-700); gap: 0.75rem; }

    /* Toast */
    .toast-container { position: fixed; top: 1rem; right: 1rem; z-index: 9999; }
    .toast { background: white; padding: 1rem 1.5rem; border-radius: var(--radius-lg); box-shadow: var(--shadow-xl); display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem; animation: slideIn 0.3s ease; }
    .toast.success { border-left: 4px solid var(--success); }
    .toast.error { border-left: 4px solid var(--danger); }
    @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

    /* Modal */
    .modal-overlay { position: fixed; inset: 0; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px); z-index: 1000; display: flex; align-items: center; justify-content: center; opacity: 0; visibility: hidden; transition: var(--transition-base); }
    .modal-overlay.active { opacity: 1; visibility: visible; }
    .modal { background: white; border-radius: var(--radius-2xl); padding: 2rem; max-width: 400px; width: 90%; text-align: center; transform: scale(0.9); transition: var(--transition-base); }
    .modal-overlay.active .modal { transform: scale(1); }
    .modal-icon { width: 64px; height: 64px; background: var(--primary-100); color: var(--primary-600); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; font-size: 1.75rem; margin: 0 auto 1.25rem; }
    .modal h3 { font-size: 1.25rem; font-weight: 700; color: var(--gray-900); margin-bottom: 0.5rem; }
    .modal p { color: var(--gray-600); margin-bottom: 1.5rem; }
    .modal-actions { display: flex; gap: 0.75rem; justify-content: center; }

    /* Responsive */
    @media (max-width: 1024px) { .footer-grid { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 768px) {
        .header-top { display: none; }
        .nav { display: none; }
        .mobile-menu-toggle { display: flex; }
        .header-actions .btn { display: none; }
        .footer-grid { grid-template-columns: 1fr; text-align: center; }
        .footer-social { justify-content: center; }
        .section-header { flex-direction: column; gap: 1rem; text-align: center; }
        .section-title { padding-left: 0; }
        .section-title::before { display: none; }
    }
</style>
