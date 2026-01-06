<script>
    // Initialize dark mode from localStorage before page renders to prevent flash
    (function() {
        const darkMode = localStorage.getItem('darkMode') === 'true';
        const theme = localStorage.getItem('themePreset') || '{{ \App\Models\SiteSetting::get("current_theme", "indigo") }}';
        
        if (darkMode) {
            document.documentElement.classList.add('dark');
        }
        document.documentElement.setAttribute('data-theme', theme);
    })();
</script>

<script>
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                fontFamily: {
                    'jakarta': ['Plus Jakarta Sans', 'sans-serif'],
                    'space': ['Space Grotesk', 'sans-serif'],
                },
                colors: {
                    primary: {
                        50: '#eef2ff',
                        100: '#e0e7ff',
                        200: '#c7d2fe',
                        300: '#a5b4fc',
                        400: '#818cf8',
                        500: '#6366f1',
                        600: '#4f46e5',
                        700: '#4338ca',
                        800: '#3730a3',
                        900: '#312e81',
                    },
                    accent: {
                        cyan: '#06b6d4',
                        emerald: '#10b981',
                        amber: '#f59e0b',
                        rose: '#f43f5e',
                        violet: '#8b5cf6',
                    },
                    surface: {
                        50: '#fafafa',
                        100: '#f4f4f5',
                        200: '#e4e4e7',
                        300: '#d4d4d8',
                        400: '#a1a1aa',
                        500: '#71717a',
                        600: '#52525b',
                        700: '#3f3f46',
                        800: '#27272a',
                        900: '#18181b',
                        950: '#0f0f11',
                    }
                },
                animation: {
                    'float': 'float 6s ease-in-out infinite',
                    'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    'slide-up': 'slideUp 0.5s ease-out forwards',
                    'fade-in': 'fadeIn 0.4s ease-out forwards',
                },
                keyframes: {
                    float: {
                        '0%, 100%': { transform: 'translateY(0)' },
                        '50%': { transform: 'translateY(-10px)' },
                    },
                    slideUp: {
                        '0%': { transform: 'translateY(20px)', opacity: '0' },
                        '100%': { transform: 'translateY(0)', opacity: '1' },
                    },
                    fadeIn: {
                        '0%': { opacity: '0' },
                        '100%': { opacity: '1' },
                    },
                }
            }
        }
    }
</script>
