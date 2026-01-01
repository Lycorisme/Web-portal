"use client";

import React, { createContext, useContext, useState, useEffect, useCallback, ReactNode } from "react";
import { getSettings, updateSettings, SiteSettings } from "@/lib/settings";

// ===================== THEME PRESET TYPES =====================
export interface ThemePreset {
    name: string;
    description: string;
    primary: string;
    accent: string;
    sidebar: string;
    gradientFrom: string;
    gradientVia?: string;
    gradientTo: string;
    hoverColor: string;
    selectionBg: string;
    selectionText: string;
    iconAccent: string;
    softTint: string;
}

// ===================== STUNNING GRADIENT THEME PRESETS =====================
export const themePresets: Record<string, ThemePreset> = {
    aurora: {
        name: "Aurora Borealis",
        description: "Nuansa misterius cahaya utara dengan gradien ungu-hijau yang memukau",
        primary: "#0f0f23",
        accent: "#22d3ee",
        sidebar: "#0a0a1a",
        gradientFrom: "#6366f1",
        gradientVia: "#8b5cf6",
        gradientTo: "#06b6d4",
        hoverColor: "#1e1e3f",
        selectionBg: "#8b5cf6",
        selectionText: "#ffffff",
        iconAccent: "#22d3ee",
        softTint: "#a5b4fc",
    },
    sunset: {
        name: "Sunset Paradise",
        description: "Gradien hangat seperti matahari terbenam di pantai tropis",
        primary: "#1a0a00",
        accent: "#f97316",
        sidebar: "#1c0c02",
        gradientFrom: "#f97316",
        gradientVia: "#ec4899",
        gradientTo: "#f43f5e",
        hoverColor: "#2d1a0a",
        selectionBg: "#f97316",
        selectionText: "#ffffff",
        iconAccent: "#fbbf24",
        softTint: "#fdba74",
    },
    ocean: {
        name: "Deep Ocean",
        description: "Kedalaman laut dengan gradien biru yang menenangkan dan elegan",
        primary: "#0a1628",
        accent: "#3b82f6",
        sidebar: "#061122",
        gradientFrom: "#0ea5e9",
        gradientVia: "#3b82f6",
        gradientTo: "#6366f1",
        hoverColor: "#0f2847",
        selectionBg: "#3b82f6",
        selectionText: "#ffffff",
        iconAccent: "#60a5fa",
        softTint: "#7dd3fc",
    },
    forest: {
        name: "Enchanted Forest",
        description: "Hutan mistis dengan nuansa hijau zamrud yang menyegarkan",
        primary: "#021a12",
        accent: "#10b981",
        sidebar: "#01140d",
        gradientFrom: "#059669",
        gradientVia: "#10b981",
        gradientTo: "#34d399",
        hoverColor: "#053d2a",
        selectionBg: "#10b981",
        selectionText: "#ffffff",
        iconAccent: "#6ee7b7",
        softTint: "#6ee7b7",
    },
    crimson: {
        name: "Crimson Royalty",
        description: "Kemewahan merah darah dengan sentuhan emas yang megah",
        primary: "#1a0505",
        accent: "#dc2626",
        sidebar: "#14040c",
        gradientFrom: "#dc2626",
        gradientVia: "#be123c",
        gradientTo: "#9f1239",
        hoverColor: "#3d1111",
        selectionBg: "#dc2626",
        selectionText: "#ffffff",
        iconAccent: "#fca5a5",
        softTint: "#fca5a5",
    },
    cosmic: {
        name: "Cosmic Nebula",
        description: "Galaksi jauh dengan warna kosmik yang memesona",
        primary: "#0d001a",
        accent: "#a855f7",
        sidebar: "#0a0012",
        gradientFrom: "#7c3aed",
        gradientVia: "#a855f7",
        gradientTo: "#c084fc",
        hoverColor: "#1e0a3d",
        selectionBg: "#a855f7",
        selectionText: "#ffffff",
        iconAccent: "#d8b4fe",
        softTint: "#d8b4fe",
    },
    midnight: {
        name: "Midnight Steel",
        description: "Estetika gelap premium dengan aksen metalik yang modern",
        primary: "#09090b",
        accent: "#64748b",
        sidebar: "#0a0a0c",
        gradientFrom: "#334155",
        gradientVia: "#475569",
        gradientTo: "#64748b",
        hoverColor: "#18181b",
        selectionBg: "#64748b",
        selectionText: "#ffffff",
        iconAccent: "#94a3b8",
        softTint: "#cbd5e1",
    },
    sakura: {
        name: "Sakura Bloom",
        description: "Keindahan bunga Sakura dengan gradien pink lembut yang romantis",
        primary: "#1a0a14",
        accent: "#ec4899",
        sidebar: "#140810",
        gradientFrom: "#f472b6",
        gradientVia: "#ec4899",
        gradientTo: "#db2777",
        hoverColor: "#2d1524",
        selectionBg: "#ec4899",
        selectionText: "#ffffff",
        iconAccent: "#f9a8d4",
        softTint: "#f9a8d4",
    },
    golden: {
        name: "Golden Prestige",
        description: "Kemewahan emas dengan sentuhan hangat dan elegan",
        primary: "#1a1400",
        accent: "#eab308",
        sidebar: "#141100",
        gradientFrom: "#facc15",
        gradientVia: "#eab308",
        gradientTo: "#ca8a04",
        hoverColor: "#2d2600",
        selectionBg: "#eab308",
        selectionText: "#1a1400",
        iconAccent: "#fef08a",
        softTint: "#fde047",
    },
    arctic: {
        name: "Arctic Frost",
        description: "Dinginnya Arktik dengan gradien es yang bersih dan segar",
        primary: "#0a141a",
        accent: "#06b6d4",
        sidebar: "#071014",
        gradientFrom: "#22d3ee",
        gradientVia: "#06b6d4",
        gradientTo: "#0891b2",
        hoverColor: "#0d2028",
        selectionBg: "#06b6d4",
        selectionText: "#ffffff",
        iconAccent: "#67e8f9",
        softTint: "#67e8f9",
    },
    volcanic: {
        name: "Volcanic Ember",
        description: "Panasnya lava gunung berapi dengan gradien menyala",
        primary: "#1a0800",
        accent: "#ea580c",
        sidebar: "#140500",
        gradientFrom: "#f97316",
        gradientVia: "#ea580c",
        gradientTo: "#dc2626",
        hoverColor: "#2d1200",
        selectionBg: "#ea580c",
        selectionText: "#ffffff",
        iconAccent: "#fdba74",
        softTint: "#fdba74",
    },
    neon: {
        name: "Neon Cyberpunk",
        description: "Futuristik cyberpunk dengan warna neon yang striking",
        primary: "#0a0a14",
        accent: "#a3e635",
        sidebar: "#080810",
        gradientFrom: "#84cc16",
        gradientVia: "#a3e635",
        gradientTo: "#22d3ee",
        hoverColor: "#141428",
        selectionBg: "#a3e635",
        selectionText: "#0a0a14",
        iconAccent: "#bef264",
        softTint: "#bef264",
    },
};

// ===================== CONTEXT TYPES =====================
interface ThemeContextType {
    currentTheme: string;
    theme: ThemePreset;
    settings: Partial<SiteSettings>;
    setTheme: (themeKey: string) => void;
    updateSiteSettings: (newSettings: Partial<SiteSettings>) => void;
    saveSettings: () => Promise<{ success: boolean; message: string }>;
    resetSettings: () => void;
    isLoading: boolean;
    hasChanges: boolean;
    isDarkMode: boolean;
    toggleDarkMode: () => void;
    initialSettings: Partial<SiteSettings>;
}

const ThemeContext = createContext<ThemeContextType | undefined>(undefined);

// Helper to safely get localStorage value (works only on client)
function getInitialTheme(): string {
    if (typeof window !== 'undefined') {
        return localStorage.getItem('portal_theme') || 'ocean';
    }
    return 'ocean';
}

function getInitialDarkMode(): boolean {
    if (typeof window !== 'undefined') {
        const saved = localStorage.getItem('portal_dark_mode');
        return saved === null ? true : JSON.parse(saved);
    }
    return true;
}

// ===================== THEME PROVIDER =====================
export function ThemeProvider({ children }: { children: ReactNode }) {
    // Initialize from localStorage immediately to prevent flash
    const [currentTheme, setCurrentTheme] = useState<string>(getInitialTheme);
    const [initialTheme, setInitialTheme] = useState<string>(getInitialTheme);
    const [settings, setSettings] = useState<Partial<SiteSettings>>(() => {
        if (typeof window !== 'undefined') {
            const saved = localStorage.getItem('portal_settings');
            if (saved) {
                try {
                    return JSON.parse(saved);
                } catch {
                    return {};
                }
            }
        }
        return {};
    });
    const [isLoading, setIsLoading] = useState(true);
    const [hasChanges, setHasChanges] = useState(false);
    const [initialSettings, setInitialSettings] = useState<Partial<SiteSettings>>(() => {
        if (typeof window !== 'undefined') {
            const saved = localStorage.getItem('portal_settings');
            if (saved) {
                try {
                    return JSON.parse(saved);
                } catch {
                    return {};
                }
            }
        }
        return {};
    });
    // Initialize dark mode from localStorage to prevent flash
    const [isDarkMode, setIsDarkMode] = useState<boolean>(getInitialDarkMode);

    // Get current theme object
    const theme = themePresets[currentTheme] || themePresets.ocean;

    // Apply CSS variables dynamically
    const applyThemeVariables = useCallback((themeData: ThemePreset) => {
        const root = document.documentElement;

        // Primary colors
        root.style.setProperty("--theme-primary", themeData.primary);
        root.style.setProperty("--theme-accent", themeData.accent);
        root.style.setProperty("--theme-sidebar", themeData.sidebar);

        // Gradient colors
        root.style.setProperty("--theme-gradient-from", themeData.gradientFrom);
        root.style.setProperty("--theme-gradient-via", themeData.gradientVia || themeData.gradientFrom);
        root.style.setProperty("--theme-gradient-to", themeData.gradientTo);

        // Interactive colors
        root.style.setProperty("--theme-hover", themeData.hoverColor);
        root.style.setProperty("--theme-selection-bg", themeData.selectionBg);
        root.style.setProperty("--theme-selection-text", themeData.selectionText);
        root.style.setProperty("--theme-icon-accent", themeData.iconAccent);

        // Soft Tint for backgrounds
        root.style.setProperty("--theme-soft-tint", themeData.softTint || themeData.accent);
    }, []);

    // Load settings on mount - only run once
    const hasInitialized = React.useRef(false);
    useEffect(() => {
        if (hasInitialized.current) return;
        hasInitialized.current = true;

        const loadSettings = async () => {
            try {
                // Apply theme variables immediately for current theme from localStorage
                const savedTheme = getInitialTheme();
                if (themePresets[savedTheme]) {
                    applyThemeVariables(themePresets[savedTheme]);
                }

                // Then try to load from API to get latest data
                const apiSettings = await getSettings();
                if (apiSettings) {
                    setSettings(apiSettings);
                    setInitialSettings(apiSettings);

                    if (apiSettings.current_theme && themePresets[apiSettings.current_theme]) {
                        setCurrentTheme(apiSettings.current_theme);
                        setInitialTheme(apiSettings.current_theme);
                        applyThemeVariables(themePresets[apiSettings.current_theme]);
                        localStorage.setItem("portal_theme", apiSettings.current_theme);
                    }

                    localStorage.setItem("portal_settings", JSON.stringify(apiSettings));
                }
            } catch (error) {
                console.error("Error loading settings:", error);
            } finally {
                setIsLoading(false);
            }
        };

        loadSettings();
    }, [applyThemeVariables]);

    // Apply theme when changed
    useEffect(() => {
        if (theme) {
            applyThemeVariables(theme);
        }
    }, [theme, applyThemeVariables]);

    // Helper to check if settings differ from initial
    const checkSettingsDiff = useCallback((currentSettings: Partial<SiteSettings>, currentThemeKey: string) => {
        // Check theme change
        if (currentThemeKey !== initialTheme) {
            return true;
        }

        // Check all setting keys
        const allKeys = new Set([
            ...Object.keys(currentSettings),
            ...Object.keys(initialSettings)
        ]) as Set<keyof SiteSettings>;

        for (const key of allKeys) {
            // Skip theme-related keys as we check theme separately
            if (key === 'current_theme' || key === 'theme_color' || key === 'accent_color' || key === 'sidebar_color') {
                continue;
            }

            const currentVal = currentSettings[key];
            const initialVal = initialSettings[key];

            const normalizedCurrent = currentVal === undefined || currentVal === '' ? '' : currentVal;
            const normalizedInitial = initialVal === undefined || initialVal === '' ? '' : initialVal;

            if (normalizedCurrent !== normalizedInitial) {
                return true;
            }
        }
        return false;
    }, [initialSettings, initialTheme]);

    // Set theme handler - using functional update to prevent stale closure issues
    const setTheme = useCallback((themeKey: string) => {
        if (themePresets[themeKey]) {
            setCurrentTheme(themeKey);
            const newTheme = themePresets[themeKey];

            // Use functional update to ensure we always have the latest settings
            setSettings(prevSettings => {
                const newSettings = {
                    ...prevSettings,
                    current_theme: themeKey,
                    theme_color: newTheme.primary,
                    accent_color: newTheme.accent,
                    sidebar_color: newTheme.sidebar,
                };

                // Save to localStorage for persistence
                localStorage.setItem("portal_theme", themeKey);
                localStorage.setItem("portal_settings", JSON.stringify(newSettings));

                return newSettings;
            });

            // Apply immediately without refresh
            applyThemeVariables(newTheme);

            // Check if returning to initial state (use timeout to ensure state is updated)
            setTimeout(() => {
                setSettings(currentSettings => {
                    setHasChanges(checkSettingsDiff(currentSettings, themeKey));
                    return currentSettings;
                });
            }, 0);
        }
    }, [applyThemeVariables, checkSettingsDiff]);

    // Update site settings
    const updateSiteSettings = useCallback((newSettings: Partial<SiteSettings>) => {
        const updatedSettings = { ...settings, ...newSettings };
        setSettings(updatedSettings);

        // Check if returning to initial state
        setHasChanges(checkSettingsDiff(updatedSettings, currentTheme));
    }, [settings, currentTheme, checkSettingsDiff]);

    // Save settings to API
    const saveSettings = useCallback(async () => {
        try {
            const settingsToSave = {
                ...settings,
                current_theme: currentTheme,
                theme_color: theme.primary,
                accent_color: theme.accent,
                sidebar_color: theme.sidebar,
            };

            const result = await updateSettings(settingsToSave);

            if (result.success) {
                localStorage.setItem("portal_settings", JSON.stringify(settingsToSave));
                localStorage.setItem("portal_theme", currentTheme);
                // CRITICAL: Update BOTH initialSettings AND initialTheme after successful save
                // This ensures Cancel goes back to the last saved state, not the original database state
                setInitialSettings(settingsToSave);
                setInitialTheme(currentTheme);
                setHasChanges(false);
            }

            return result;
        } catch (error) {
            return { success: false, message: "Gagal menyimpan pengaturan" };
        }
    }, [settings, currentTheme, theme]);

    // Toggle dark mode
    const toggleDarkMode = useCallback(() => {
        setIsDarkMode(prev => {
            const newValue = !prev;
            localStorage.setItem("portal_dark_mode", JSON.stringify(newValue));
            // Apply to both html and body
            if (newValue) {
                document.documentElement.classList.add('dark-mode');
                document.body.classList.add('dark-mode');
            } else {
                document.documentElement.classList.remove('dark-mode');
                document.body.classList.remove('dark-mode');
            }
            return newValue;
        });
    }, []);

    // Load dark mode preference on mount - sync with blocking script
    useEffect(() => {
        const savedDarkMode = localStorage.getItem("portal_dark_mode");
        if (savedDarkMode !== null) {
            const isDark = JSON.parse(savedDarkMode);
            setIsDarkMode(isDark);
            // Sync with document classes
            if (isDark) {
                document.documentElement.classList.add('dark-mode');
                document.body.classList.add('dark-mode');
            } else {
                document.documentElement.classList.remove('dark-mode');
                document.body.classList.remove('dark-mode');
            }
        } else {
            // Default to dark mode
            document.documentElement.classList.add('dark-mode');
            document.body.classList.add('dark-mode');
        }
    }, []);

    // Reset settings to initial
    const resetSettings = useCallback(() => {
        setSettings(initialSettings);
        if (initialTheme && themePresets[initialTheme]) {
            setCurrentTheme(initialTheme);
            applyThemeVariables(themePresets[initialTheme]);
            localStorage.setItem("portal_theme", initialTheme);
        }
        setHasChanges(false);
    }, [initialSettings, initialTheme, applyThemeVariables]);

    return (
        <ThemeContext.Provider
            value={{
                currentTheme,
                theme,
                settings,
                setTheme,
                updateSiteSettings,
                saveSettings,
                resetSettings,
                isLoading,
                hasChanges,
                isDarkMode,
                toggleDarkMode,
                initialSettings,
            }}
        >
            {children}
        </ThemeContext.Provider>
    );
}

// ===================== HOOK =====================
export function useTheme() {
    const context = useContext(ThemeContext);
    if (context === undefined) {
        throw new Error("useTheme must be used within a ThemeProvider");
    }
    return context;
}

export default ThemeContext;
