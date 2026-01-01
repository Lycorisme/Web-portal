"use client";

// This component injects a blocking script to prevent flash of light mode
// It runs before React hydration to set the dark-mode class immediately
export default function DarkModeScript() {
    return (
        <script
            dangerouslySetInnerHTML={{
                __html: `
                    (function() {
                        // Theme Presets - duplicated here for immediate access
                        var themePresets = {
                            aurora: { primary: "#0f0f23", accent: "#22d3ee", sidebar: "#0a0a1a", gradientFrom: "#6366f1", gradientVia: "#8b5cf6", gradientTo: "#06b6d4", hoverColor: "#1e1e3f", softTint: "#a5b4fc", iconAccent: "#22d3ee" },
                            sunset: { primary: "#1a0a00", accent: "#f97316", sidebar: "#1c0c02", gradientFrom: "#f97316", gradientVia: "#ec4899", gradientTo: "#f43f5e", hoverColor: "#2d1a0a", softTint: "#fdba74", iconAccent: "#fbbf24" },
                            ocean: { primary: "#0a1628", accent: "#3b82f6", sidebar: "#061122", gradientFrom: "#0ea5e9", gradientVia: "#3b82f6", gradientTo: "#6366f1", hoverColor: "#0f2847", softTint: "#7dd3fc", iconAccent: "#60a5fa" },
                            forest: { primary: "#021a12", accent: "#10b981", sidebar: "#01140d", gradientFrom: "#059669", gradientVia: "#10b981", gradientTo: "#34d399", hoverColor: "#053d2a", softTint: "#6ee7b7", iconAccent: "#6ee7b7" },
                            crimson: { primary: "#1a0505", accent: "#dc2626", sidebar: "#14040c", gradientFrom: "#dc2626", gradientVia: "#be123c", gradientTo: "#9f1239", hoverColor: "#3d1111", softTint: "#fca5a5", iconAccent: "#fca5a5" },
                            cosmic: { primary: "#0d001a", accent: "#a855f7", sidebar: "#0a0012", gradientFrom: "#7c3aed", gradientVia: "#a855f7", gradientTo: "#c084fc", hoverColor: "#1e0a3d", softTint: "#d8b4fe", iconAccent: "#d8b4fe" },
                            midnight: { primary: "#09090b", accent: "#64748b", sidebar: "#0a0a0c", gradientFrom: "#334155", gradientVia: "#475569", gradientTo: "#64748b", hoverColor: "#18181b", softTint: "#cbd5e1", iconAccent: "#94a3b8" },
                            sakura: { primary: "#1a0a14", accent: "#ec4899", sidebar: "#140810", gradientFrom: "#f472b6", gradientVia: "#ec4899", gradientTo: "#db2777", hoverColor: "#2d1524", softTint: "#f9a8d4", iconAccent: "#f9a8d4" },
                            golden: { primary: "#1a1400", accent: "#eab308", sidebar: "#141100", gradientFrom: "#facc15", gradientVia: "#eab308", gradientTo: "#ca8a04", hoverColor: "#2d2600", softTint: "#fde047", iconAccent: "#fef08a" },
                            arctic: { primary: "#0a141a", accent: "#06b6d4", sidebar: "#071014", gradientFrom: "#22d3ee", gradientVia: "#06b6d4", gradientTo: "#0891b2", hoverColor: "#0d2028", softTint: "#67e8f9", iconAccent: "#67e8f9" },
                            volcanic: { primary: "#1a0800", accent: "#ea580c", sidebar: "#140500", gradientFrom: "#f97316", gradientVia: "#ea580c", gradientTo: "#dc2626", hoverColor: "#2d1200", softTint: "#fdba74", iconAccent: "#fdba74" },
                            neon: { primary: "#0a0a14", accent: "#a3e635", sidebar: "#080810", gradientFrom: "#84cc16", gradientVia: "#a3e635", gradientTo: "#22d3ee", hoverColor: "#141428", softTint: "#bef264", iconAccent: "#bef264" }
                        };
                        
                        try {
                            // Handle dark mode
                            var savedDarkMode = localStorage.getItem('portal_dark_mode');
                            var isDark = savedDarkMode === null ? true : JSON.parse(savedDarkMode);
                            if (isDark) {
                                document.documentElement.classList.add('dark-mode');
                            }
                            
                            // Apply theme preset CSS variables immediately
                            var savedTheme = localStorage.getItem('portal_theme') || 'ocean';
                            var theme = themePresets[savedTheme] || themePresets.ocean;
                            var root = document.documentElement;
                            
                            root.style.setProperty('--theme-primary', theme.primary);
                            root.style.setProperty('--theme-accent', theme.accent);
                            root.style.setProperty('--theme-sidebar', theme.sidebar);
                            root.style.setProperty('--theme-gradient-from', theme.gradientFrom);
                            root.style.setProperty('--theme-gradient-via', theme.gradientVia || theme.gradientFrom);
                            root.style.setProperty('--theme-gradient-to', theme.gradientTo);
                            root.style.setProperty('--theme-hover', theme.hoverColor);
                            root.style.setProperty('--theme-soft-tint', theme.softTint || theme.accent);
                            root.style.setProperty('--theme-icon-accent', theme.iconAccent);
                            
                            // Also apply to body when DOM is ready
                            if (isDark) {
                                if (document.body) {
                                    document.body.classList.add('dark-mode');
                                } else {
                                    document.addEventListener('DOMContentLoaded', function() {
                                        document.body.classList.add('dark-mode');
                                    });
                                }
                            }
                        } catch (e) {
                            // If localStorage fails, default to dark mode with ocean theme
                            document.documentElement.classList.add('dark-mode');
                            document.addEventListener('DOMContentLoaded', function() {
                                document.body.classList.add('dark-mode');
                            });
                        }
                    })();
                `,
            }}
        />
    );
}
