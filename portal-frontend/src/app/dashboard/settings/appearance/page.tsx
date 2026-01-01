"use client";

import { useTheme, themePresets, ThemePreset } from "@/contexts/ThemeContext";
import Link from "next/link";
import SettingsShell from "@/components/layout/SettingsShell";

// Theme Preview Card Component
function ThemePreviewCard({
    themeKey,
    themeData,
    isActive,
    onSelect,
    isDarkMode
}: {
    themeKey: string;
    themeData: ThemePreset;
    isActive: boolean;
    onSelect: () => void;
    isDarkMode: boolean;
}) {
    return (
        <button
            onClick={onSelect}
            className={`group relative rounded-2xl overflow-hidden transition-all duration-500 text-left transform ${isActive
                ? "ring-2 ring-offset-4 scale-[1.02] shadow-2xl"
                : "hover:scale-[1.02] hover:shadow-xl"
                } ${isDarkMode
                    ? isActive ? "ring-white/50 ring-offset-slate-900" : ""
                    : isActive ? "ring-slate-800/50 ring-offset-white" : ""
                }`}
        >
            {/* Gradient Preview Background */}
            <div
                className="h-32 w-full relative overflow-hidden"
                style={{
                    background: `linear-gradient(135deg, ${themeData.gradientFrom}, ${themeData.gradientVia || themeData.gradientFrom}, ${themeData.gradientTo})`,
                }}
            >
                {/* Animated shimmer effect */}
                <div className="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>

                {/* Mini Preview UI */}
                <div className="absolute inset-3 flex gap-2">
                    {/* Sidebar Preview */}
                    <div
                        className="w-12 h-full rounded-lg opacity-90"
                        style={{ backgroundColor: themeData.sidebar }}
                    >
                        <div className="p-2 space-y-1.5">
                            <div className="w-6 h-6 rounded mx-auto" style={{ background: `linear-gradient(135deg, ${themeData.gradientFrom}, ${themeData.gradientTo})` }}></div>
                            <div className="h-1.5 rounded-full bg-white/20"></div>
                            <div className="h-1.5 rounded-full bg-white/20"></div>
                            <div className="h-1.5 rounded-full" style={{ backgroundColor: themeData.accent }}></div>
                            <div className="h-1.5 rounded-full bg-white/20"></div>
                        </div>
                    </div>
                    {/* Content Preview */}
                    <div className="flex-1 bg-white/10 backdrop-blur-sm rounded-lg p-2">
                        <div className="h-2 w-16 rounded-full bg-white/40 mb-2"></div>
                        <div className="grid grid-cols-3 gap-1.5">
                            <div className="h-8 rounded bg-white/20"></div>
                            <div className="h-8 rounded bg-white/20"></div>
                            <div className="h-8 rounded bg-white/20"></div>
                        </div>
                        <div
                            className="mt-2 h-3 w-16 rounded-md ml-auto"
                            style={{ backgroundColor: themeData.accent }}
                        ></div>
                    </div>
                </div>

                {/* Active Check */}
                {isActive && (
                    <div className="absolute top-2 right-2 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-lg animate-scale-in">
                        <i className="fa-solid fa-check text-green-500 text-sm"></i>
                    </div>
                )}
            </div>

            {/* Theme Info */}
            <div
                className="p-4 transition-colors"
                style={{ backgroundColor: themeData.primary }}
            >
                <div className="flex items-center justify-between mb-2">
                    <h4 className="font-bold text-white text-sm">{themeData.name}</h4>
                    <div className="flex gap-1">
                        <div className="w-3 h-3 rounded-full" style={{ backgroundColor: themeData.gradientFrom }}></div>
                        <div className="w-3 h-3 rounded-full" style={{ backgroundColor: themeData.gradientVia || themeData.gradientFrom }}></div>
                        <div className="w-3 h-3 rounded-full" style={{ backgroundColor: themeData.gradientTo }}></div>
                    </div>
                </div>
                <p className="text-[11px] text-white/60 leading-relaxed line-clamp-2">{themeData.description}</p>
            </div>

            {/* Hover Glow Effect */}
            <div
                className="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"
                style={{
                    boxShadow: `inset 0 0 30px ${themeData.accent}30`,
                }}
            ></div>
        </button>
    );
}

export default function AppearanceSettingsPage() {
    const { currentTheme, theme, setTheme, isDarkMode } = useTheme();

    const cardStyle = isDarkMode ? {
        backgroundColor: `${theme.sidebar}D9`,
        borderColor: `${theme.accent}20`
    } : {};

    const cardClass = `rounded-2xl shadow-sm border transition-all duration-300 ${isDarkMode
        ? 'backdrop-blur-sm'
        : 'bg-white border-slate-100 shadow-lg'
        }`;

    const labelClass = `text-sm font-bold uppercase tracking-wider ${isDarkMode ? 'text-slate-400' : 'text-slate-600'}`;

    return (
        <SettingsShell>
            <div className="space-y-8">
                {/* Page Title & Back Button */}
                <div className="flex items-center gap-4 mb-8">
                    <Link
                        href="/dashboard"
                        className={`p-3 rounded-xl transition-all hover:scale-105 ${isDarkMode
                            ? 'bg-slate-800 text-slate-400 hover:text-white hover:bg-slate-700'
                            : 'bg-white shadow-sm border border-slate-200 text-slate-500 hover:text-slate-800 hover:border-slate-300'
                            }`}
                    >
                        <i className="fa-solid fa-arrow-left"></i>
                    </Link>
                    <div
                        className="w-14 h-14 rounded-2xl flex items-center justify-center shadow-lg transform transition-transform hover:scale-105"
                        style={{
                            background: `linear-gradient(135deg, ${theme.gradientFrom}, ${theme.gradientTo})`,
                            boxShadow: `0 8px 24px ${theme.accent}30`
                        }}
                    >
                        <i className="fa-solid fa-palette text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 className={`text-2xl font-bold font-[family-name:var(--font-merriweather)] ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>
                            Tampilan & Tema
                        </h2>
                        <p className={`text-sm ${isDarkMode ? 'text-slate-400' : 'text-slate-500'}`}>
                            Pilih tema gradien premium untuk seluruh aplikasi
                        </p>
                    </div>
                </div>

                {/* Theme Presets Header */}
                <div className={`${cardClass} overflow-hidden`} style={cardStyle}>
                    <div
                        className="p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 theme-gradient animate-gradient"
                        style={{ backgroundSize: "200% 200%" }}
                    >
                        <div>
                            <h3 className="text-xl font-bold text-white flex items-center gap-3">
                                <i className="fa-solid fa-wand-magic-sparkles"></i>
                                Theme Presets
                            </h3>
                            <p className="text-white/70 text-sm mt-1">
                                Perubahan akan langsung terlihat tanpa refresh!
                            </p>
                        </div>
                        <div className={`backdrop-blur-xl rounded-xl px-5 py-3 text-right ${isDarkMode
                            ? 'bg-white/10 border border-white/20'
                            : 'bg-white/20 border border-white/30'
                            }`}>
                            <span className="text-xs font-bold uppercase tracking-wider text-white/60 block mb-1">Current Active</span>
                            <p className="font-bold text-white text-lg">{theme.name}</p>
                        </div>
                    </div>

                    {/* Theme Grid */}
                    <div className={`p-6 ${isDarkMode ? 'bg-slate-900/50' : 'bg-slate-50/50'}`}>
                        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                            {Object.entries(themePresets).map(([key, themeData]) => (
                                <ThemePreviewCard
                                    key={key}
                                    themeKey={key}
                                    themeData={themeData}
                                    isActive={currentTheme === key}
                                    onSelect={() => setTheme(key)}
                                    isDarkMode={isDarkMode}
                                />
                            ))}
                        </div>
                    </div>
                </div>

                {/* Live Preview */}
                <div className={`${cardClass} p-8`} style={cardStyle}>
                    <h3 className={`text-lg font-bold mb-6 flex items-center gap-3 ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>
                        <div className="w-10 h-10 rounded-xl flex items-center justify-center" style={{ backgroundColor: `${theme.accent}15` }}>
                            <i className="fa-solid fa-eye" style={{ color: theme.accent }}></i>
                        </div>
                        Live Preview
                    </h3>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {/* Buttons Preview */}
                        <div className="space-y-4">
                            <h4 className={labelClass}>Buttons</h4>
                            <div className="flex flex-wrap gap-3">
                                <button
                                    className="px-6 py-2.5 rounded-xl font-bold text-white shadow-lg hover:shadow-xl hover:scale-105 transition-all"
                                    style={{
                                        background: `linear-gradient(135deg, ${theme.gradientFrom}, ${theme.gradientTo})`,
                                        boxShadow: `0 4px 16px ${theme.accent}40`
                                    }}
                                >
                                    Primary Button
                                </button>
                                <button
                                    className={`px-6 py-2.5 rounded-xl font-bold border-2 transition-colors hover:scale-105 ${isDarkMode
                                        ? 'hover:bg-slate-700'
                                        : 'hover:bg-slate-50'
                                        }`}
                                    style={{
                                        borderColor: theme.accent,
                                        color: theme.accent
                                    }}
                                >
                                    Outline
                                </button>
                            </div>
                        </div>

                        {/* Badge Preview */}
                        <div className="space-y-4">
                            <h4 className={labelClass}>Badges</h4>
                            <div className="flex flex-wrap gap-2">
                                <span
                                    className="px-4 py-1.5 rounded-full text-sm font-bold"
                                    style={{
                                        backgroundColor: `${theme.accent}15`,
                                        color: theme.accent,
                                        border: `1px solid ${theme.accent}30`
                                    }}
                                >
                                    Themed Badge
                                </span>
                                <span className="px-4 py-1.5 rounded-full text-sm font-bold bg-green-500/10 text-green-500 border border-green-500/30">
                                    Success
                                </span>
                                <span className="px-4 py-1.5 rounded-full text-sm font-bold bg-red-500/10 text-red-500 border border-red-500/30">
                                    Danger
                                </span>
                                <span className="px-4 py-1.5 rounded-full text-sm font-bold bg-amber-500/10 text-amber-500 border border-amber-500/30">
                                    Warning
                                </span>
                            </div>
                        </div>

                        {/* Text Selection Preview */}
                        <div className="space-y-4 md:col-span-2">
                            <h4 className={labelClass}>Text Selection</h4>
                            <p className={`p-5 rounded-xl border leading-relaxed ${isDarkMode
                                ? 'bg-slate-900/70 border-slate-700 text-slate-300'
                                : 'bg-slate-50 border-slate-200 text-slate-600'
                                }`}>
                                <strong>Coba blok teks ini!</strong> Warna selection akan mengikuti tema yang kamu pilih.
                                Ini memberikan kesan <em>integrated branding</em> yang konsisten di seluruh aplikasi.
                                Theme juga mempengaruhi warna icon, hover effects, dan elemen interaktif lainnya.
                            </p>
                        </div>

                        {/* Gradient Preview */}
                        <div className="space-y-4 md:col-span-2">
                            <h4 className={labelClass}>Gradient Variations</h4>
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div
                                    className="h-24 rounded-2xl flex items-center justify-center text-white font-bold shadow-xl"
                                    style={{
                                        background: `linear-gradient(135deg, ${theme.gradientFrom}, ${theme.gradientVia || theme.gradientFrom}, ${theme.gradientTo})`,
                                        boxShadow: `0 10px 30px ${theme.accent}30`
                                    }}
                                >
                                    <span className="drop-shadow-lg">Diagonal</span>
                                </div>
                                <div
                                    className="h-24 rounded-2xl flex items-center justify-center text-white font-bold shadow-xl"
                                    style={{
                                        background: `linear-gradient(90deg, ${theme.gradientFrom}, ${theme.gradientTo})`,
                                        boxShadow: `0 10px 30px ${theme.accent}30`
                                    }}
                                >
                                    <span className="drop-shadow-lg">Horizontal</span>
                                </div>
                                <div
                                    className="h-24 rounded-2xl flex items-center justify-center text-white font-bold shadow-xl"
                                    style={{
                                        background: `radial-gradient(circle at center, ${theme.gradientFrom}, ${theme.gradientTo})`,
                                        boxShadow: `0 10px 30px ${theme.accent}30`
                                    }}
                                >
                                    <span className="drop-shadow-lg">Radial</span>
                                </div>
                            </div>
                        </div>

                        {/* Input Focus Preview */}
                        <div className="space-y-4 md:col-span-2">
                            <h4 className={labelClass}>Input Focus State</h4>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input
                                    type="text"
                                    placeholder="Klik untuk melihat focus state..."
                                    className={`w-full rounded-xl px-4 py-3 border focus:ring-2 focus:border-transparent transition-all ${isDarkMode
                                        ? 'bg-slate-900/50 border-slate-600 text-slate-200 placeholder-slate-500'
                                        : 'bg-white border-slate-300'
                                        }`}
                                    style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                                />
                                <select
                                    className={`w-full rounded-xl px-4 py-3 border focus:ring-2 focus:border-transparent transition-all ${isDarkMode
                                        ? 'bg-slate-900/50 border-slate-600 text-slate-200'
                                        : 'bg-white border-slate-300'
                                        }`}
                                    style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                                >
                                    <option>Pilih opsi...</option>
                                    <option>Opsi 1</option>
                                    <option>Opsi 2</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Color Reference */}
                <div className={`${cardClass} p-8`} style={cardStyle}>
                    <h3 className={`text-lg font-bold mb-6 flex items-center gap-3 ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>
                        <div className="w-10 h-10 rounded-xl flex items-center justify-center" style={{ backgroundColor: `${theme.accent}15` }}>
                            <i className="fa-solid fa-droplet" style={{ color: theme.accent }}></i>
                        </div>
                        Color Reference
                    </h3>
                    <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                        {[
                            { label: "Primary", color: theme.primary },
                            { label: "Accent", color: theme.accent },
                            { label: "Gradient From", color: theme.gradientFrom },
                            { label: "Gradient Via", color: theme.gradientVia || theme.gradientFrom },
                            { label: "Gradient To", color: theme.gradientTo },
                            { label: "Icon Accent", color: theme.iconAccent },
                        ].map((item) => (
                            <div key={item.label} className="text-center group">
                                <div
                                    className={`w-full h-16 rounded-xl shadow-lg border transition-transform group-hover:scale-105 ${isDarkMode ? 'border-slate-600' : 'border-slate-200'
                                        }`}
                                    style={{ backgroundColor: item.color }}
                                ></div>
                                <p className={`text-xs font-bold mt-2 ${isDarkMode ? 'text-slate-300' : 'text-slate-600'}`}>{item.label}</p>
                                <p className="text-xs text-slate-500 font-mono">{item.color}</p>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </SettingsShell>
    );
}
