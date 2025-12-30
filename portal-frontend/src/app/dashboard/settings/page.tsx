"use client";

import { useState, useEffect, useCallback, useRef } from "react";
import { useRouter } from "next/navigation";
import { getUser, isLoggedIn, User, logout } from "@/lib/auth";
import Sidebar from "@/components/layout/Sidebar";
import Header from "@/components/layout/Header";
import Toast, { ToastData } from "@/components/ui/Toast";

// Declare SweetAlert2 type for TypeScript
declare const Swal: any;
import { useTheme, themePresets, ThemePreset } from "@/contexts/ThemeContext";
import { SiteSettings } from "@/lib/settings";

// ===================== TYPES =====================
interface Notification {
    id: number;
    type: "success" | "error" | "warning" | "info";
    title: string;
    message: string;
    time: string;
    read: boolean;
}

// ===================== THEME PREVIEW CARD =====================
function ThemePreviewCard({
    themeKey,
    themeData,
    isActive,
    onSelect,
}: {
    themeKey: string;
    themeData: ThemePreset;
    isActive: boolean;
    onSelect: () => void;
}) {
    return (
        <button
            onClick={onSelect}
            className={`group relative rounded-2xl overflow-hidden transition-all duration-500 text-left transform ${isActive
                ? "ring-2 ring-white/50 ring-offset-4 ring-offset-slate-900 scale-[1.02] shadow-2xl"
                : "hover:scale-[1.02] hover:shadow-xl"
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

// ===================== TAB CONTENT COMPONENTS =====================
function GeneralSettingsTab({
    localSettings,
    onChange
}: {
    localSettings: Partial<SiteSettings>;
    onChange: (key: keyof SiteSettings, value: any) => void;
}) {
    const { theme, isDarkMode } = useTheme();

    const cardClass = `rounded-2xl shadow-sm border p-8 transition-colors ${isDarkMode ? 'bg-slate-800/50 border-slate-700/50' : 'bg-white border-slate-100'
        }`;

    const inputClass = `w-full rounded-lg border focus:ring-2 focus:border-transparent transition-all ${isDarkMode
        ? 'bg-slate-900 border-slate-700 text-slate-200 placeholder-slate-500 focus:ring-slate-600'
        : 'bg-white border-slate-300 text-slate-800 placeholder-slate-400'
        }`;

    const labelClass = `block text-sm font-bold mb-2 ${isDarkMode ? 'text-slate-300' : 'text-slate-700'
        }`;

    return (
        <div className="space-y-6">
            <div className={cardClass}>
                <h3 className={`text-lg font-bold mb-6 flex items-center gap-2 ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>
                    <div className="w-8 h-8 rounded-lg flex items-center justify-center" style={{ backgroundColor: `${theme.accent}15` }}>
                        <i className="fa-solid fa-globe" style={{ color: theme.accent }}></i>
                    </div>
                    Informasi Dasar Website
                </h3>
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label className={labelClass}>Nama Portal</label>
                        <input
                            type="text"
                            value={localSettings.site_name || ""}
                            onChange={(e) => onChange("site_name", e.target.value)}
                            className={inputClass}
                            placeholder="Masukkan nama portal"
                            style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                        />
                    </div>
                    <div>
                        <label className={labelClass}>Tagline</label>
                        <input
                            type="text"
                            value={localSettings.site_tagline || ""}
                            onChange={(e) => onChange("site_tagline", e.target.value)}
                            className={inputClass}
                            placeholder="Slogan atau tagline website"
                            style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                        />
                    </div>
                    <div>
                        <label className={labelClass}>Email Redaksi</label>
                        <input
                            type="email"
                            value={localSettings.site_email || ""}
                            onChange={(e) => onChange("site_email", e.target.value)}
                            className={inputClass}
                            placeholder="email@portal.id"
                            style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                        />
                    </div>
                    <div>
                        <label className={labelClass}>Nomor Telepon</label>
                        <input
                            type="tel"
                            value={localSettings.site_phone || ""}
                            onChange={(e) => onChange("site_phone", e.target.value)}
                            className={inputClass}
                            placeholder="+62 xxx xxxx xxxx"
                            style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                        />
                    </div>
                    <div className="md:col-span-2">
                        <label className={labelClass}>Alamat Redaksi</label>
                        <textarea
                            value={localSettings.site_address || ""}
                            onChange={(e) => onChange("site_address", e.target.value)}
                            rows={2}
                            className={inputClass}
                            placeholder="Alamat lengkap kantor redaksi"
                            style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                        />
                    </div>
                    <div className="md:col-span-2">
                        <label className={labelClass}>Deskripsi Website</label>
                        <textarea
                            value={localSettings.site_description || ""}
                            onChange={(e) => onChange("site_description", e.target.value)}
                            rows={3}
                            className={inputClass}
                            placeholder="Deskripsi singkat tentang portal berita Anda"
                            style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                        />
                    </div>
                </div>
            </div>

            {/* SEO Settings */}
            <div className={cardClass}>
                <h3 className={`text-lg font-bold mb-6 flex items-center gap-2 ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>
                    <div className="w-8 h-8 rounded-lg flex items-center justify-center bg-green-500/10">
                        <i className="fa-solid fa-magnifying-glass text-green-500"></i>
                    </div>
                    Pengaturan SEO
                </h3>
                <div className="grid grid-cols-1 gap-6">
                    <div>
                        <label className={labelClass}>Meta Title</label>
                        <input
                            type="text"
                            value={localSettings.meta_title || ""}
                            onChange={(e) => onChange("meta_title", e.target.value)}
                            className={inputClass}
                            placeholder="Judul yang muncul di hasil pencarian"
                            style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                        />
                        <p className={`text-xs mt-1 ${isDarkMode ? 'text-slate-500' : 'text-slate-400'}`}>Rekomendasi: 50-60 karakter</p>
                    </div>
                    <div>
                        <label className={labelClass}>Meta Description</label>
                        <textarea
                            value={localSettings.meta_description || ""}
                            onChange={(e) => onChange("meta_description", e.target.value)}
                            rows={3}
                            className={inputClass}
                            placeholder="Deskripsi yang muncul di hasil pencarian Google"
                            style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                        />
                        <p className={`text-xs mt-1 ${isDarkMode ? 'text-slate-500' : 'text-slate-400'}`}>Rekomendasi: 150-160 karakter</p>
                    </div>
                    <div>
                        <label className={labelClass}>Meta Keywords</label>
                        <input
                            type="text"
                            value={localSettings.meta_keywords || ""}
                            onChange={(e) => onChange("meta_keywords", e.target.value)}
                            className={inputClass}
                            placeholder="berita, portal, indonesia, terkini"
                            style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                        />
                        <p className={`text-xs mt-1 ${isDarkMode ? 'text-slate-500' : 'text-slate-400'}`}>Pisahkan dengan koma</p>
                    </div>
                    <div>
                        <label className={labelClass}>Google Analytics ID</label>
                        <input
                            type="text"
                            value={localSettings.google_analytics_id || ""}
                            onChange={(e) => onChange("google_analytics_id", e.target.value)}
                            className={inputClass}
                            placeholder="UA-XXXXXXXXX-X atau G-XXXXXXXXXX"
                            style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                        />
                    </div>
                </div>
            </div>

            {/* Social Media */}
            <div className={cardClass}>
                <h3 className={`text-lg font-bold mb-6 flex items-center gap-2 ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>
                    <div className="w-8 h-8 rounded-lg flex items-center justify-center bg-purple-500/10">
                        <i className="fa-solid fa-share-nodes text-purple-500"></i>
                    </div>
                    Media Sosial
                </h3>
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label className={labelClass}>
                            <i className="fa-brands fa-facebook text-blue-600 mr-2"></i>Facebook
                        </label>
                        <input
                            type="url"
                            value={localSettings.facebook_url || ""}
                            onChange={(e) => onChange("facebook_url", e.target.value)}
                            className={inputClass}
                            placeholder="https://facebook.com/yourpage"
                            style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                        />
                    </div>
                    <div>
                        <label className={labelClass}>
                            <i className="fa-brands fa-twitter text-sky-500 mr-2"></i>Twitter / X
                        </label>
                        <input
                            type="url"
                            value={localSettings.twitter_url || ""}
                            onChange={(e) => onChange("twitter_url", e.target.value)}
                            className={inputClass}
                            placeholder="https://twitter.com/yourhandle"
                            style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                        />
                    </div>
                    <div>
                        <label className={labelClass}>
                            <i className="fa-brands fa-instagram text-pink-500 mr-2"></i>Instagram
                        </label>
                        <input
                            type="url"
                            value={localSettings.instagram_url || ""}
                            onChange={(e) => onChange("instagram_url", e.target.value)}
                            className={inputClass}
                            placeholder="https://instagram.com/yourprofile"
                            style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                        />
                    </div>
                    <div>
                        <label className={labelClass}>
                            <i className="fa-brands fa-youtube text-red-600 mr-2"></i>YouTube
                        </label>
                        <input
                            type="url"
                            value={localSettings.youtube_url || ""}
                            onChange={(e) => onChange("youtube_url", e.target.value)}
                            className={inputClass}
                            placeholder="https://youtube.com/c/yourchannel"
                            style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                        />
                    </div>
                    <div className="md:col-span-2">
                        <label className={labelClass}>
                            <i className="fa-brands fa-linkedin text-blue-700 mr-2"></i>LinkedIn
                        </label>
                        <input
                            type="url"
                            value={localSettings.linkedin_url || ""}
                            onChange={(e) => onChange("linkedin_url", e.target.value)}
                            className={inputClass}
                            placeholder="https://linkedin.com/company/yourcompany"
                            style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                        />
                    </div>
                </div>
            </div>
        </div>
    );
}

function AppearanceTab() {
    const { currentTheme, theme, setTheme, isDarkMode } = useTheme();

    const cardClass = `rounded-2xl shadow-sm border transition-colors ${isDarkMode ? 'bg-slate-800/50 border-slate-700/50' : 'bg-white border-slate-100'
        }`;

    const labelClass = `text-sm font-bold uppercase tracking-wider ${isDarkMode ? 'text-slate-400' : 'text-slate-600'
        }`;

    return (
        <div className="space-y-8">
            {/* Theme Presets Header */}
            <div className={`${cardClass} overflow-hidden`}>
                <div
                    className="p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 theme-gradient animate-gradient"
                    style={{ backgroundSize: "200% 200%" }}
                >
                    <div>
                        <h3 className="text-xl font-bold text-white flex items-center gap-3">
                            <i className="fa-solid fa-palette"></i>
                            Theme Presets
                        </h3>
                        <p className="text-white/70 text-sm mt-1">
                            Pilih tema dengan gradien premium untuk seluruh aplikasi. Perubahan akan langsung terlihat tanpa refresh!
                        </p>
                    </div>
                    <div className="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-3 text-right">
                        <span className="text-xs font-bold uppercase tracking-wider text-white/60 block mb-1">Current Active</span>
                        <p className="font-bold text-white">{theme.name}</p>
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
                            />
                        ))}
                    </div>
                </div>
            </div>

            {/* Live Preview */}
            <div className={`${cardClass} p-8`}>
                <h3 className={`text-lg font-bold mb-6 flex items-center gap-2 ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>
                    <i className="fa-solid fa-eye" style={{ color: theme.accent }}></i>
                    Live Preview
                </h3>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {/* Buttons Preview */}
                    <div className="space-y-4">
                        <h4 className={labelClass}>Buttons</h4>
                        <div className="flex flex-wrap gap-3">
                            <button className="btn-themed px-6 py-2.5 rounded-lg font-bold">
                                Primary Button
                            </button>
                            <button className="btn-themed-outline px-6 py-2.5 rounded-lg font-bold">
                                Outline
                            </button>
                        </div>
                    </div>

                    {/* Badge Preview */}
                    <div className="space-y-4">
                        <h4 className={labelClass}>Badges</h4>
                        <div className="flex flex-wrap gap-2">
                            <span className="badge-themed">Themed Badge</span>
                            <span className="badge-success">Success</span>
                            <span className="badge-danger">Danger</span>
                            <span className="badge-warning">Warning</span>
                        </div>
                    </div>

                    {/* Text Selection Preview */}
                    <div className="space-y-4 md:col-span-2">
                        <h4 className={labelClass}>Text Selection</h4>
                        <p className={`p-4 rounded-lg border leading-relaxed ${isDarkMode
                                ? 'bg-slate-900/50 border-slate-700 text-slate-300'
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
                            <div className="h-20 rounded-xl theme-gradient flex items-center justify-center text-white font-bold shadow-lg">
                                Diagonal
                            </div>
                            <div className="h-20 rounded-xl theme-gradient-horizontal flex items-center justify-center text-white font-bold shadow-lg">
                                Horizontal
                            </div>
                            <div className="h-20 rounded-xl theme-gradient-radial flex items-center justify-center text-white font-bold shadow-lg">
                                Radial
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
                                className={`w-full rounded-lg border input-themed ${isDarkMode
                                        ? 'bg-slate-900 border-slate-700 text-slate-200 placeholder-slate-500'
                                        : 'bg-white border-slate-300'
                                    }`}
                            />
                            <select className={`w-full rounded-lg border input-themed ${isDarkMode
                                    ? 'bg-slate-900 border-slate-700 text-slate-200'
                                    : 'bg-white border-slate-300'
                                }`}>
                                <option>Pilih opsi...</option>
                                <option>Opsi 1</option>
                                <option>Opsi 2</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {/* Color Reference */}
            <div className={`${cardClass} p-8`}>
                <h3 className={`text-lg font-bold mb-6 flex items-center gap-2 ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>
                    <i className="fa-solid fa-droplet" style={{ color: theme.accent }}></i>
                    Color Reference
                </h3>
                <div className="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    <div className="text-center">
                        <div
                            className={`w-full h-16 rounded-lg shadow-sm border ${isDarkMode ? 'border-slate-600' : 'border-slate-200'}`}
                            style={{ backgroundColor: theme.primary }}
                        ></div>
                        <p className={`text-xs font-bold mt-2 ${isDarkMode ? 'text-slate-400' : 'text-slate-600'}`}>Primary</p>
                        <p className="text-xs text-slate-500 font-mono">{theme.primary}</p>
                    </div>
                    <div className="text-center">
                        <div
                            className="w-full h-16 rounded-lg shadow-sm"
                            style={{ backgroundColor: theme.accent }}
                        ></div>
                        <p className={`text-xs font-bold mt-2 ${isDarkMode ? 'text-slate-400' : 'text-slate-600'}`}>Accent</p>
                        <p className="text-xs text-slate-500 font-mono">{theme.accent}</p>
                    </div>
                    <div className="text-center">
                        <div
                            className="w-full h-16 rounded-lg shadow-sm"
                            style={{ backgroundColor: theme.gradientFrom }}
                        ></div>
                        <p className={`text-xs font-bold mt-2 ${isDarkMode ? 'text-slate-400' : 'text-slate-600'}`}>Gradient From</p>
                        <p className="text-xs text-slate-500 font-mono">{theme.gradientFrom}</p>
                    </div>
                    <div className="text-center">
                        <div
                            className="w-full h-16 rounded-lg shadow-sm"
                            style={{ backgroundColor: theme.gradientVia || theme.gradientFrom }}
                        ></div>
                        <p className={`text-xs font-bold mt-2 ${isDarkMode ? 'text-slate-400' : 'text-slate-600'}`}>Gradient Via</p>
                        <p className="text-xs text-slate-500 font-mono">{theme.gradientVia || theme.gradientFrom}</p>
                    </div>
                    <div className="text-center">
                        <div
                            className="w-full h-16 rounded-lg shadow-sm"
                            style={{ backgroundColor: theme.gradientTo }}
                        ></div>
                        <p className={`text-xs font-bold mt-2 ${isDarkMode ? 'text-slate-400' : 'text-slate-600'}`}>Gradient To</p>
                        <p className="text-xs text-slate-500 font-mono">{theme.gradientTo}</p>
                    </div>
                    <div className="text-center">
                        <div
                            className="w-full h-16 rounded-lg shadow-sm"
                            style={{ backgroundColor: theme.iconAccent }}
                        ></div>
                        <p className={`text-xs font-bold mt-2 ${isDarkMode ? 'text-slate-400' : 'text-slate-600'}`}>Icon Accent</p>
                        <p className="text-xs text-slate-500 font-mono">{theme.iconAccent}</p>
                    </div>
                </div>
            </div>
        </div>
    );
}

function MediaTab({
    localSettings,
    onChange
}: {
    localSettings: Partial<SiteSettings>;
    onChange: (key: keyof SiteSettings, value: any) => void;
}) {
    const { theme, isDarkMode } = useTheme();
    const signatureInputRef = useRef<HTMLInputElement>(null);
    const stampInputRef = useRef<HTMLInputElement>(null);
    const faviconInputRef = useRef<HTMLInputElement>(null);
    const logoInputRef = useRef<HTMLInputElement>(null);

    const orgTypes = [
        { value: 'government', label: 'Pemerintah', icon: 'fa-landmark' },
        { value: 'private', label: 'Swasta', icon: 'fa-building' },
        { value: 'other', label: 'Lainnya', icon: 'fa-ellipsis' },
    ];

    const cardClass = `rounded-2xl shadow-sm border transition-colors overflow-hidden ${isDarkMode ? 'bg-slate-800/50 border-slate-700/50' : 'bg-white border-slate-100'
        }`;

    const inputClass = `w-full rounded-lg border focus:ring-2 focus:border-transparent transition-all ${isDarkMode
        ? 'bg-slate-900 border-slate-700 text-slate-200 placeholder-slate-500 focus:ring-slate-600'
        : 'bg-white border-slate-300 text-slate-800 placeholder-slate-400'
        }`;

    const labelClass = `block text-sm font-bold mb-2 ${isDarkMode ? 'text-slate-300' : 'text-slate-700'
        }`;

    // Handle file upload and convert to base64
    const handleFileUpload = (key: keyof SiteSettings) => (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0];
        if (!file) return;

        // Validate file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert("Ukuran file maksimal 2MB");
            return;
        }

        const reader = new FileReader();
        reader.onload = (event) => {
            const base64 = event.target?.result as string;
            onChange(key, base64);
        };
        reader.readAsDataURL(file);
    };

    return (
        <div className="space-y-6">
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {/* Favicon */}
                <div className={`${cardClass} p-6 flex flex-col items-center text-center`}>
                    <div
                        className={`h-16 w-16 rounded-xl mb-4 flex items-center justify-center border-2 border-dashed overflow-hidden ${isDarkMode ? 'bg-slate-900/50' : 'bg-slate-50'
                            }`}
                        style={{ borderColor: theme.accent }}
                    >
                        {localSettings.favicon_url ? (
                            <img src={localSettings.favicon_url} className="h-10 w-10 object-contain" alt="Favicon" />
                        ) : (
                            <i className="fa-solid fa-image text-2xl" style={{ color: theme.accent }}></i>
                        )}
                    </div>
                    <h4 className={`font-bold ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>Favicon Website</h4>
                    <p className={`text-xs mb-4 ${isDarkMode ? 'text-slate-400' : 'text-slate-500'}`}>ICO/PNG (Max 512px)</p>
                    <label className="w-full cursor-pointer group">
                        <div
                            className={`w-full py-2 px-4 rounded-lg border-2 border-dashed transition-all text-sm font-medium ${isDarkMode ? 'hover:bg-slate-700' : 'hover:bg-slate-50'
                                }`}
                            style={{
                                borderColor: theme.accent,
                                color: theme.accent,
                            }}
                        >
                            <i className="fa-solid fa-upload mr-2"></i>Upload
                        </div>
                        <input
                            ref={faviconInputRef}
                            type="file"
                            className="hidden"
                            accept=".ico,.png"
                            onChange={handleFileUpload('favicon_url')}
                        />
                    </label>
                </div>

                {/* Logo */}
                <div className={`${cardClass} p-6 flex flex-col items-center text-center lg:col-span-2`}>
                    <label
                        className={`w-full h-32 rounded-xl flex flex-col items-center justify-center cursor-pointer mb-2 border-2 border-dashed transition-all group ${isDarkMode ? 'bg-slate-900/50 hover:bg-slate-800' : 'hover:bg-slate-50'
                            }`}
                        style={{ borderColor: theme.accent }}
                    >
                        {localSettings.logo_url ? (
                            <img src={localSettings.logo_url} className="h-20 object-contain" alt="Logo" />
                        ) : (
                            <>
                                <i
                                    className="fa-regular fa-image text-3xl mb-2 transition-colors"
                                    style={{ color: theme.accent }}
                                ></i>
                                <span className={`text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-600'}`}>Klik untuk upload Logo Utama</span>
                                <span className={`text-xs ${isDarkMode ? 'text-slate-500' : 'text-slate-400'}`}>Format PNG Transparan disarankan</span>
                            </>
                        )}
                        <input
                            ref={logoInputRef}
                            type="file"
                            className="hidden"
                            accept=".png,.jpg,.jpeg,.svg"
                            onChange={handleFileUpload('logo_url')}
                        />
                    </label>
                </div>
            </div>

            {/* Organization & Leader Info */}
            <div className={cardClass}>
                <div className={`p-6 border-b theme-gradient ${isDarkMode ? 'border-slate-700' : 'border-slate-100'}`}>
                    <h3 className="text-lg font-bold text-white flex items-center gap-2">
                        <i className="fa-solid fa-id-card-clip"></i>
                        Informasi Organisasi & Pimpinan
                    </h3>
                    <p className="text-sm text-white/70">Data ini digunakan untuk dokumen resmi, surat tugas, dan laporan.</p>
                </div>
                <div className="p-6 space-y-6">
                    {/* Organization Type */}
                    <div>
                        <label className={`${labelClass} mb-3`}>Tipe Organisasi</label>
                        <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
                            {orgTypes.map((org) => (
                                <button
                                    key={org.value}
                                    type="button"
                                    onClick={() => onChange('organization_type', org.value as any)}
                                    className={`p-4 rounded-xl border-2 transition-all text-center ${localSettings.organization_type === org.value
                                        ? 'border-current shadow-sm'
                                        : isDarkMode
                                            ? 'border-slate-700 hover:border-slate-600 bg-slate-900/50'
                                            : 'border-slate-200 hover:border-slate-300'
                                        }`}
                                    style={localSettings.organization_type === org.value ? {
                                        borderColor: theme.accent,
                                        backgroundColor: `${theme.accent}10`,
                                        color: theme.accent
                                    } : undefined}
                                >
                                    <i className={`fa-solid ${org.icon} text-xl mb-2 ${localSettings.organization_type !== org.value
                                        ? isDarkMode ? 'text-slate-500' : 'text-slate-400'
                                        : ''
                                        }`}></i>
                                    <p className={`text-xs font-medium ${localSettings.organization_type !== org.value
                                        ? isDarkMode ? 'text-slate-400' : 'text-slate-600'
                                        : ''
                                        }`}>
                                        {org.label}
                                    </p>
                                </button>
                            ))}
                        </div>
                    </div>

                    {/* Leader Info */}
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label className={labelClass}>Nama Pimpinan</label>
                            <input
                                type="text"
                                value={localSettings.leader_name || ""}
                                onChange={(e) => onChange("leader_name", e.target.value)}
                                className={inputClass}
                                placeholder="Nama lengkap pimpinan"
                            />
                        </div>
                        <div>
                            <label className={labelClass}>Jabatan</label>
                            <input
                                type="text"
                                value={localSettings.leader_title || ""}
                                onChange={(e) => onChange("leader_title", e.target.value)}
                                className={inputClass}
                                placeholder={localSettings.organization_type === 'government' ? 'Kepala Dinas / Direktur' : 'CEO / Direktur Utama'}
                            />
                        </div>
                    </div>

                    {/* Dynamic ID fields based on org type */}
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {localSettings.organization_type === 'government' ? (
                            <div>
                                <label className={labelClass}>
                                    <i className="fa-solid fa-id-badge mr-2 text-slate-400"></i>NIP (Nomor Induk Pegawai)
                                </label>
                                <input
                                    type="text"
                                    value={localSettings.leader_nip || ""}
                                    onChange={(e) => onChange("leader_nip", e.target.value)}
                                    className={inputClass}
                                    placeholder="19XXXXXXXXXXXXXX"
                                />
                            </div>
                        ) : (
                            <div>
                                <label className={labelClass}>
                                    <i className="fa-solid fa-id-card mr-2 text-slate-400"></i>NIK (Nomor Induk Kependudukan)
                                </label>
                                <input
                                    type="text"
                                    value={localSettings.leader_nik || ""}
                                    onChange={(e) => onChange("leader_nik", e.target.value)}
                                    className={inputClass}
                                    placeholder="3XXXXXXXXXXXXXXX"
                                />
                            </div>
                        )}
                        <div>
                            <div className="flex items-center gap-2 mb-2">
                                <input
                                    type="text"
                                    value={localSettings.leader_custom_id_label || ""}
                                    onChange={(e) => onChange("leader_custom_id_label", e.target.value)}
                                    className={`text-sm font-bold bg-transparent border-none p-0 focus:ring-0 w-auto ${isDarkMode ? 'text-slate-300' : 'text-slate-700'}`}
                                    placeholder="ID Lainnya (klik untuk edit)"
                                    style={{ minWidth: '180px' }}
                                />
                                <span className={`text-xs ${isDarkMode ? 'text-slate-500' : 'text-slate-400'}`}>(opsional)</span>
                            </div>
                            <input
                                type="text"
                                value={localSettings.leader_custom_id || ""}
                                onChange={(e) => onChange("leader_custom_id", e.target.value)}
                                className={inputClass}
                                placeholder="Nomor identitas lainnya"
                            />
                        </div>
                    </div>

                    {/* Contact Info */}
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label className={labelClass}>
                                <i className="fa-solid fa-phone mr-2 text-slate-400"></i>No. Telepon
                            </label>
                            <input
                                type="tel"
                                value={localSettings.leader_phone || ""}
                                onChange={(e) => onChange("leader_phone", e.target.value)}
                                className={inputClass}
                                placeholder="+62 8XX XXXX XXXX"
                            />
                        </div>
                        <div>
                            <label className={labelClass}>
                                <i className="fa-solid fa-envelope mr-2 text-slate-400"></i>Email
                            </label>
                            <input
                                type="email"
                                value={localSettings.leader_email || ""}
                                onChange={(e) => onChange("leader_email", e.target.value)}
                                className={inputClass}
                                placeholder="pimpinan@organisasi.id"
                            />
                        </div>
                    </div>
                </div>
            </div>

            {/* Official Documents */}
            <div className={cardClass}>
                <div className={`p-6 border-b theme-gradient ${isDarkMode ? 'border-slate-700' : 'border-slate-100'}`}>
                    <h3 className="text-lg font-bold text-white">Aset Persuratan (Official)</h3>
                    <p className="text-sm text-white/70">Aset ini akan digunakan otomatis pada fitur "Generate Surat Tugas" & Laporan.</p>
                </div>
                <div className="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                    {/* Letterhead */}
                    <div>
                        <label className={labelClass}>Header Kop Surat (Image)</label>
                        <div
                            className={`h-40 rounded-xl flex flex-col items-center justify-center cursor-pointer relative group border-2 border-dashed transition-all ${isDarkMode ? 'bg-slate-900/50 hover:bg-slate-800' : 'hover:bg-slate-50'
                                }`}
                            style={{ borderColor: theme.accent }}
                        >
                            <div className="text-center group-hover:scale-105 transition-transform">
                                <i className="fa-solid fa-file-invoice text-3xl mb-2" style={{ color: theme.accent }}></i>
                                <p className={`text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-600'}`}>Upload Kop Surat</p>
                                <p className={`text-xs ${isDarkMode ? 'text-slate-500' : 'text-slate-400'}`}>Lebar: 1920px (High Res)</p>
                            </div>
                            <input type="file" className="absolute inset-0 opacity-0 cursor-pointer" />
                        </div>
                    </div>

                    {/* Signature & Stamp */}
                    <div className="space-y-6">
                        <div>
                            <label className={labelClass}>Tanda Tangan Digital</label>
                            <div className="flex items-center gap-4">
                                <div
                                    className={`h-20 w-32 border-2 border-dashed rounded-lg flex items-center justify-center overflow-hidden ${isDarkMode ? 'bg-slate-900/50' : 'bg-slate-50'
                                        }`}
                                    style={{ borderColor: theme.accent }}
                                >
                                    {localSettings.signature_url ? (
                                        <img src={localSettings.signature_url} className="h-full object-contain" alt="Signature" />
                                    ) : (
                                        <div className="text-center">
                                            <i className="fa-solid fa-signature text-xl mb-1" style={{ color: theme.accent }}></i>
                                            <p className="text-[10px] text-slate-400">Preview</p>
                                        </div>
                                    )}
                                </div>
                                <div className="flex flex-col gap-2">
                                    <label className="cursor-pointer">
                                        <span
                                            className="px-4 py-2 text-sm font-medium rounded-lg transition-colors btn-themed-outline inline-block"
                                        >
                                            <i className="fa-solid fa-upload mr-2"></i>Upload TTD
                                        </span>
                                        <input
                                            ref={signatureInputRef}
                                            type="file"
                                            className="hidden"
                                            accept=".png,.jpg,.jpeg,.gif"
                                            onChange={handleFileUpload('signature_url')}
                                        />
                                    </label>
                                    {localSettings.signature_url && (
                                        <button
                                            type="button"
                                            onClick={() => onChange('signature_url', '')}
                                            className="text-xs text-red-500 hover:text-red-600"
                                        >
                                            <i className="fa-solid fa-trash mr-1"></i>Hapus
                                        </button>
                                    )}
                                </div>
                            </div>
                            <p className={`text-xs mt-2 ${isDarkMode ? 'text-slate-500' : 'text-slate-400'}`}>PNG transparan disarankan (maks 2MB)</p>
                        </div>
                        <div>
                            <label className={labelClass}>Stempel Perusahaan (Cap Basah)</label>
                            <div className="flex items-center gap-4">
                                <div
                                    className={`h-20 w-20 border-2 border-dashed rounded-full flex items-center justify-center overflow-hidden ${isDarkMode ? 'bg-slate-900/50' : 'bg-slate-50'
                                        }`}
                                    style={{ borderColor: theme.accent }}
                                >
                                    {localSettings.stamp_url ? (
                                        <img src={localSettings.stamp_url} className="h-full w-full object-cover rounded-full" alt="Stamp" />
                                    ) : (
                                        <i className="fa-solid fa-stamp text-xl" style={{ color: theme.accent }}></i>
                                    )}
                                </div>
                                <div className="flex flex-col gap-2">
                                    <label className="cursor-pointer">
                                        <span
                                            className="px-4 py-2 text-sm font-medium rounded-lg transition-colors btn-themed-outline inline-block"
                                        >
                                            <i className="fa-solid fa-upload mr-2"></i>Upload Cap
                                        </span>
                                        <input
                                            ref={stampInputRef}
                                            type="file"
                                            className="hidden"
                                            accept=".png,.jpg,.jpeg,.gif"
                                            onChange={handleFileUpload('stamp_url')}
                                        />
                                    </label>
                                    {localSettings.stamp_url && (
                                        <button
                                            type="button"
                                            onClick={() => onChange('stamp_url', '')}
                                            className="text-xs text-red-500 hover:text-red-600"
                                        >
                                            <i className="fa-solid fa-trash mr-1"></i>Hapus
                                        </button>
                                    )}
                                </div>
                            </div>
                            <p className={`text-xs mt-2 ${isDarkMode ? 'text-slate-500' : 'text-slate-400'}`}>PNG/JPG (maks 2MB)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

function SecurityTab({
    localSettings,
    onChange
}: {
    localSettings: Partial<SiteSettings>;
    onChange: (key: keyof SiteSettings, value: any) => void;
}) {
    const { theme, isDarkMode } = useTheme();

    const cardClass = `rounded-2xl shadow-sm border transition-colors ${isDarkMode ? 'bg-slate-800/50 border-slate-700/50' : 'bg-white border-slate-100'
        }`;

    return (
        <div className="space-y-6">
            {/* Warning Banner */}
            <div className={`border-l-4 border-red-500 p-6 rounded-r-xl shadow-sm ${isDarkMode ? 'bg-red-900/10' : 'bg-red-50'
                }`}>
                <div className="flex">
                    <div className="flex-shrink-0">
                        <i className="fa-solid fa-triangle-exclamation text-red-500 text-xl"></i>
                    </div>
                    <div className="ml-4">
                        <h3 className={`text-lg font-bold ${isDarkMode ? 'text-red-400' : 'text-red-800'}`}>Zona Bahaya</h3>
                        <p className={`text-sm mt-1 ${isDarkMode ? 'text-red-200' : 'text-red-700'}`}>
                            Pengaturan di bawah ini berhubungan langsung dengan firewall dan proteksi data.
                            Perubahan akan dicatat dalam Audit Log.
                        </p>
                    </div>
                </div>
            </div>

            {/* Rate Limiting */}
            <div className={`${cardClass} p-8`}>
                <h3 className={`text-lg font-bold mb-6 flex items-center gap-2 ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>
                    <i className="fa-solid fa-shield-cat" style={{ color: theme.accent }}></i>
                    Parameter Rate Limiting
                </h3>
                <div className="space-y-6">
                    <div className={`flex items-center justify-between py-4 border-b ${isDarkMode ? 'border-slate-700' : 'border-slate-50'}`}>
                        <div>
                            <h4 className={`font-bold ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>API Threshold</h4>
                            <p className={`text-sm ${isDarkMode ? 'text-slate-400' : 'text-slate-500'}`}>Batas request per menit untuk endpoint publik.</p>
                        </div>
                        <div className={`flex items-center rounded-lg p-1 ${isDarkMode ? 'bg-slate-900' : 'bg-slate-100'}`}>
                            <input
                                type="number"
                                value={localSettings.rate_limit_per_minute || 60}
                                onChange={(e) => onChange("rate_limit_per_minute", parseInt(e.target.value))}
                                className={`w-20 bg-transparent border-none text-center font-bold focus:ring-0 ${isDarkMode ? 'text-white' : 'text-slate-700'}`}
                            />
                            <span className={`text-xs font-bold pr-3 ${isDarkMode ? 'text-slate-400' : 'text-slate-500'}`}>REQ/MIN</span>
                        </div>
                    </div>
                    <div className={`flex items-center justify-between py-4 border-b ${isDarkMode ? 'border-slate-700' : 'border-slate-50'}`}>
                        <div>
                            <h4 className={`font-bold ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>Auto-Ban IP</h4>
                            <p className={`text-sm ${isDarkMode ? 'text-slate-400' : 'text-slate-500'}`}>Blokir permanen IP yang melakukan spam 1000x/jam.</p>
                        </div>
                        <label className="relative inline-flex items-center cursor-pointer">
                            <input
                                type="checkbox"
                                checked={localSettings.auto_ban_enabled ?? true}
                                onChange={(e) => onChange("auto_ban_enabled", e.target.checked)}
                                className="sr-only peer"
                            />
                            <div
                                className={`w-11 h-6 peer-focus:outline-none peer-focus:ring-4 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all ${isDarkMode ? 'bg-slate-700 border-slate-600' : 'bg-slate-200'
                                    }`}
                                style={{
                                    backgroundColor: localSettings.auto_ban_enabled ? theme.accent : undefined,
                                    "--tw-ring-color": `${theme.accent}40`,
                                } as React.CSSProperties}
                            ></div>
                        </label>
                    </div>
                    <div className="flex items-center justify-between py-4">
                        <div>
                            <h4 className={`font-bold ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>Maintenance Mode</h4>
                            <p className={`text-sm ${isDarkMode ? 'text-slate-400' : 'text-slate-500'}`}>Aktifkan mode pemeliharaan untuk pengunjung.</p>
                        </div>
                        <label className="relative inline-flex items-center cursor-pointer">
                            <input
                                type="checkbox"
                                checked={localSettings.maintenance_mode ?? false}
                                onChange={(e) => onChange("maintenance_mode", e.target.checked)}
                                className="sr-only peer"
                            />
                            <div className={`w-11 h-6 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600 ${isDarkMode ? 'bg-slate-700' : 'bg-slate-200'
                                }`}></div>
                        </label>
                    </div>
                </div>
            </div>

            {/* Security Actions */}
            <div className={`${cardClass} p-8`}>
                <h3 className={`text-lg font-bold mb-6 flex items-center gap-2 ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>
                    <i className="fa-solid fa-key text-amber-500"></i>
                    Aksi Keamanan
                </h3>
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <button className={`p-4 border rounded-xl transition-colors text-left group hover-glow ${isDarkMode
                        ? 'border-slate-700 hover:bg-slate-700/50'
                        : 'border-slate-200 hover:bg-slate-50'
                        }`}>
                        <div className="flex items-center gap-3">
                            <div
                                className="w-10 h-10 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform"
                                style={{ backgroundColor: `${theme.accent}20`, color: theme.accent }}
                            >
                                <i className="fa-solid fa-rotate"></i>
                            </div>
                            <div>
                                <p className="font-bold text-slate-800">Regenerate API Keys</p>
                                <p className="text-xs text-slate-500">Buat ulang semua API key</p>
                            </div>
                        </div>
                    </button>
                    <button className="p-4 border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors text-left group hover-glow">
                        <div className="flex items-center gap-3">
                            <div className="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center text-green-600 group-hover:scale-110 transition-transform">
                                <i className="fa-solid fa-database"></i>
                            </div>
                            <div>
                                <p className="font-bold text-slate-800">Clear Cache</p>
                                <p className="text-xs text-slate-500">Bersihkan semua cache sistem</p>
                            </div>
                        </div>
                    </button>
                    <button className="p-4 border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors text-left group hover-glow">
                        <div className="flex items-center gap-3">
                            <div className="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center text-purple-600 group-hover:scale-110 transition-transform">
                                <i className="fa-solid fa-user-lock"></i>
                            </div>
                            <div>
                                <p className="font-bold text-slate-800">Force Logout All</p>
                                <p className="text-xs text-slate-500">Paksa logout semua sesi aktif</p>
                            </div>
                        </div>
                    </button>
                    <button className="p-4 border border-red-200 rounded-xl hover:bg-red-50 transition-colors text-left group">
                        <div className="flex items-center gap-3">
                            <div className="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center text-red-600 group-hover:scale-110 transition-transform">
                                <i className="fa-solid fa-trash-can"></i>
                            </div>
                            <div>
                                <p className="font-bold text-red-800">Reset All Settings</p>
                                <p className="text-xs text-red-500">Kembalikan ke pengaturan default</p>
                            </div>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    );
}

// ===================== MAIN SETTINGS PAGE =====================
export default function SettingsPage() {
    const router = useRouter();
    const { theme, settings: globalSettings, updateSiteSettings, saveSettings, resetSettings, hasChanges: globalHasChanges, initialSettings } = useTheme();

    // States
    const [user, setUserState] = useState<User | null>(null);
    const [sidebarOpen, setSidebarOpen] = useState(false);
    const [activeTab, setActiveTab] = useState("general");
    const [isSaving, setIsSaving] = useState(false);
    const [localHasChanges, setLocalHasChanges] = useState(false);

    // Local settings state for form inputs
    const [localSettings, setLocalSettings] = useState<Partial<SiteSettings>>({});

    // Toast state
    const [toast, setToast] = useState<ToastData>({ show: false, message: "", type: "success" });

    // Notifications
    const [notifications, setNotifications] = useState<Notification[]>([]);

    // Floating bar animation state
    const [floatingBarVisible, setFloatingBarVisible] = useState(false);
    const [isFloatingBarExiting, setIsFloatingBarExiting] = useState(false);

    // Toast handler
    const showToast = useCallback((message: string, type: ToastData["type"]) => {
        setToast({ show: true, message, type });
        setTimeout(() => setToast(prev => ({ ...prev, show: false })), 4000);
    }, []);

    // SweetAlert2 confirm dialog
    const showConfirm = useCallback(async (
        type: "warning" | "danger" | "info",
        title: string,
        message: string,
        confirmText: string = "Ya, Lanjutkan"
    ): Promise<boolean> => {
        const iconMap = {
            warning: "warning",
            danger: "error",
            info: "question"
        };
        const colorMap = {
            warning: "#f59e0b",
            danger: "#ef4444",
            info: theme.accent
        };

        const result = await Swal.fire({
            title: title,
            text: message,
            icon: iconMap[type],
            showCancelButton: true,
            confirmButtonColor: colorMap[type],
            cancelButtonColor: "#64748b",
            confirmButtonText: confirmText,
            cancelButtonText: "Batal",
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-lg font-bold',
                cancelButton: 'rounded-lg font-bold'
            }
        });

        return result.isConfirmed;
    }, [theme.accent]);

    // Auth check
    useEffect(() => {
        if (!isLoggedIn()) {
            router.push("/");
            return;
        }
        const userData = getUser();
        if (userData) {
            setUserState(userData);
        }
    }, [router]);

    // Sync global settings to local
    useEffect(() => {
        setLocalSettings(globalSettings);
    }, [globalSettings]);

    // Sync localHasChanges with globalHasChanges - when global says no changes, local should also be false
    useEffect(() => {
        if (!globalHasChanges) {
            setLocalHasChanges(false);
        }
    }, [globalHasChanges]);

    // Helper function to check if settings have changed from initial with deep comparison
    const checkHasChanges = useCallback((newSettings: Partial<SiteSettings>) => {
        // Compare each key that exists in newSettings or initialSettings
        const allKeys = new Set([
            ...Object.keys(newSettings),
            ...Object.keys(initialSettings)
        ]) as Set<keyof SiteSettings>;

        for (const key of allKeys) {
            // Skip theme-related keys that are handled by globalHasChanges
            if (key === 'current_theme' || key === 'theme_color' || key === 'accent_color' || key === 'sidebar_color') {
                continue;
            }

            const newVal = newSettings[key];
            const initialVal = initialSettings[key];

            // Handle undefined/empty string/null equivalence
            const normalizedNew = (newVal === undefined || newVal === '' || newVal === null) ? '' : newVal;
            const normalizedInitial = (initialVal === undefined || initialVal === '' || initialVal === null) ? '' : initialVal;

            // Handle boolean comparison
            if (typeof normalizedNew === 'boolean' || typeof normalizedInitial === 'boolean') {
                if (Boolean(normalizedNew) !== Boolean(normalizedInitial)) {
                    return true;
                }
                continue;
            }

            // Handle number comparison
            if (typeof normalizedNew === 'number' || typeof normalizedInitial === 'number') {
                if (Number(normalizedNew) !== Number(normalizedInitial)) {
                    return true;
                }
                continue;
            }

            if (normalizedNew !== normalizedInitial) {
                return true;
            }
        }
        return false;
    }, [initialSettings]);

    // Handle local settings change
    const handleLocalSettingsChange = useCallback((key: keyof SiteSettings, value: any) => {
        const newSettings = { ...localSettings, [key]: value };
        setLocalSettings(newSettings);
        updateSiteSettings({ [key]: value });

        // Check if any changes exist compared to initial settings (for non-theme settings)
        const hasAnyChange = checkHasChanges(newSettings);
        setLocalHasChanges(hasAnyChange);
    }, [updateSiteSettings, localSettings, checkHasChanges]);

    // Cancel changes handler (without page reload)
    const handleCancelChanges = useCallback(() => {
        resetSettings();
        setLocalSettings(initialSettings);
        setLocalHasChanges(false);
    }, [resetSettings, initialSettings]);

    // Calculate hasAnyChanges BEFORE conditional returns (hooks must be called consistently)
    const hasAnyChanges = globalHasChanges || localHasChanges;

    // Control floating bar visibility with exit animation (must be before conditional return)
    useEffect(() => {
        if (hasAnyChanges) {
            // Show immediately when there are changes
            setIsFloatingBarExiting(false);
            setFloatingBarVisible(true);
        } else if (floatingBarVisible) {
            // Trigger exit animation first, then hide
            setIsFloatingBarExiting(true);
            const timer = setTimeout(() => {
                setFloatingBarVisible(false);
                setIsFloatingBarExiting(false);
            }, 350); // Match animation duration
            return () => clearTimeout(timer);
        }
    }, [hasAnyChanges, floatingBarVisible]);

    // Handlers
    const handleLogout = async () => {
        const confirmed = await showConfirm(
            "warning",
            "Konfirmasi Logout",
            "Apakah Anda yakin ingin keluar dari sistem?",
            "Ya, Logout"
        );
        if (confirmed) {
            await logout();
            showToast("Logout berhasil!", "success");
            setTimeout(() => router.push("/"), 500);
        }
    };

    const handleMarkAllRead = () => {
        setNotifications(prev => prev.map(n => ({ ...n, read: true })));
        showToast("Semua notifikasi ditandai dibaca", "success");
    };

    const handleClearNotifications = async () => {
        const confirmed = await showConfirm(
            "danger",
            "Hapus Semua Notifikasi",
            "Apakah Anda yakin ingin menghapus semua notifikasi?",
            "Ya, Hapus Semua"
        );
        if (confirmed) {
            setNotifications([]);
            showToast("Semua notifikasi dihapus", "info");
        }
    };

    // Save settings
    const handleSaveSettings = async () => {
        setIsSaving(true);
        try {
            const result = await saveSettings();

            if (result.success) {
                showToast("Pengaturan berhasil disimpan!", "success");
                setLocalHasChanges(false);
            } else {
                showToast(result.message || "Gagal menyimpan pengaturan", "error");
            }
        } catch (error) {
            showToast("Gagal menyimpan pengaturan", "error");
        } finally {
            setIsSaving(false);
        }
    };

    // Loading state
    if (!user) {
        return (
            <div className="min-h-screen flex items-center justify-center bg-slate-50">
                <div className="flex flex-col items-center gap-4">
                    <div
                        className="animate-spin h-10 w-10 border-4 border-t-transparent rounded-full"
                        style={{ borderColor: theme.accent, borderTopColor: "transparent" }}
                    ></div>
                    <p className="text-slate-500 text-sm">Memuat pengaturan...</p>
                </div>
            </div>
        );
    }

    const tabs = [
        { key: "general", label: "Identitas & SEO", icon: "fa-solid fa-globe" },
        { key: "appearance", label: "Tampilan & Tema", icon: "fa-solid fa-palette" },
        { key: "media", label: "Media & Dokumen", icon: "fa-solid fa-file-signature" },
        { key: "security", label: "Security Core", icon: "fa-solid fa-shield-halved", danger: true },
    ];

    return (
        <div className="bg-slate-50 font-[family-name:var(--font-inter)] text-slate-800 antialiased">
            <div className="flex h-screen overflow-hidden">
                {/* Sidebar Component */}
                <Sidebar
                    user={user}
                    sidebarOpen={sidebarOpen}
                    onClose={() => setSidebarOpen(false)}
                    onLogout={handleLogout}
                />

                {/* Main Content */}
                <main className="flex-1 flex flex-col h-screen overflow-hidden bg-slate-50 relative">
                    {/* Header Component */}
                    <Header
                        user={user}
                        notifications={notifications}
                        onLogout={handleLogout}
                        onMarkAllRead={handleMarkAllRead}
                        onClearNotifications={handleClearNotifications}
                        onToggleSidebar={() => setSidebarOpen(!sidebarOpen)}
                    />

                    {/* Page Header - Consistent with Dashboard */}
                    <div className="bg-white border-b border-slate-200 px-6 lg:px-8 py-4">
                        <div className="flex items-center justify-between">
                            <div>
                                <h1 className="text-2xl font-bold text-slate-800 font-[family-name:var(--font-merriweather)]">
                                    Pengaturan Portal
                                </h1>
                                <p className="text-slate-500 text-sm mt-1">
                                    Kelola identitas, tampilan, dan keamanan sistem.
                                </p>
                            </div>
                            <div className="hidden md:flex gap-2">
                                <span
                                    className="px-3 py-1 text-xs font-bold rounded-full flex items-center gap-1 border"
                                    style={{
                                        backgroundColor: `${theme.accent}15`,
                                        color: theme.accent,
                                        borderColor: `${theme.accent}30`,
                                    }}
                                >
                                    <span
                                        className="w-2 h-2 rounded-full animate-pulse"
                                        style={{ backgroundColor: theme.accent }}
                                    ></span>
                                    System Healthy
                                </span>
                            </div>
                        </div>
                    </div>

                    {/* Scrollable Content */}
                    <div className="flex-1 overflow-y-auto p-6 lg:p-8">
                        <div className="max-w-6xl mx-auto">
                            {/* Tab Navigation */}
                            <div className="bg-white rounded-2xl shadow-sm p-1.5 mb-8 flex flex-wrap gap-1">
                                {tabs.map((tab) => (
                                    <button
                                        key={tab.key}
                                        onClick={() => setActiveTab(tab.key)}
                                        className={`flex-1 py-2.5 px-4 rounded-xl text-sm transition-all flex justify-center items-center gap-2 ${activeTab === tab.key
                                            ? tab.danger
                                                ? "bg-red-50 text-red-700 shadow-sm font-bold"
                                                : "shadow-sm font-bold"
                                            : "text-slate-500 hover:text-slate-700 hover:bg-slate-50"
                                            }`}
                                        style={activeTab === tab.key && !tab.danger ? {
                                            backgroundColor: `${theme.accent}15`,
                                            color: theme.primary,
                                        } : undefined}
                                    >
                                        <i
                                            className={tab.icon}
                                            style={activeTab === tab.key && !tab.danger ? { color: theme.accent } : undefined}
                                        ></i>
                                        <span className="hidden sm:inline">{tab.label}</span>
                                    </button>
                                ))}
                            </div>

                            {/* Tab Content */}
                            <div className="animate-fade-in">
                                {activeTab === "general" && (
                                    <GeneralSettingsTab
                                        localSettings={localSettings}
                                        onChange={handleLocalSettingsChange}
                                    />
                                )}
                                {activeTab === "appearance" && (
                                    <AppearanceTab />
                                )}
                                {activeTab === "media" && (
                                    <MediaTab
                                        localSettings={localSettings}
                                        onChange={handleLocalSettingsChange}
                                    />
                                )}
                                {activeTab === "security" && (
                                    <SecurityTab
                                        localSettings={localSettings}
                                        onChange={handleLocalSettingsChange}
                                    />
                                )}
                            </div>
                        </div>
                    </div>
                </main>
            </div>

            {/* Toast */}
            <Toast data={toast} onClose={() => setToast(prev => ({ ...prev, show: false }))} />

            {/* SweetAlert2 is loaded via CDN and called via showConfirm function */}

            {/* Floating Action Bar - Redesigned with soft glassmorphism */}
            {floatingBarVisible && (
                <div className={`fixed bottom-6 left-0 right-0 z-50 px-4 sm:px-6 flex justify-center ${isFloatingBarExiting ? 'pointer-events-none' : ''}`}>
                    <div
                        className={`w-full max-w-4xl backdrop-blur-xl rounded-2xl shadow-2xl border transition-all duration-300 ${isFloatingBarExiting ? 'animate-slide-up-exit' : 'animate-slide-up-enter'} ${isDarkMode
                            ? 'bg-slate-900/80 border-slate-700/50'
                            : 'bg-white/80 border-white/50'
                            }`}
                        style={{
                            boxShadow: isDarkMode
                                ? `0 20px 40px -10px rgba(0,0,0,0.5), 0 0 0 1px var(--theme-border-soft)`
                                : `0 20px 40px -10px ${theme.accent}15, 0 0 0 1px ${theme.accent}10`
                        }}
                    >
                        <div className="px-4 py-3 sm:px-6 sm:py-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div className="flex items-center gap-4 w-full sm:w-auto">
                                <div
                                    className="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 animate-pulse"
                                    style={{
                                        background: `linear-gradient(135deg, ${theme.gradientFrom}, ${theme.gradientTo})`,
                                        boxShadow: `0 4px 12px ${theme.accent}40`
                                    }}
                                >
                                    <i className="fa-solid fa-pen-nib text-white text-sm"></i>
                                </div>
                                <div className="min-w-0 flex-1">
                                    <p className={`text-sm font-bold truncate ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>
                                        Perubahan Belum Disimpan
                                    </p>
                                    <p className={`text-xs hidden sm:block ${isDarkMode ? 'text-slate-400' : 'text-slate-500'}`}>
                                        Anda memiliki perubahan yang belum disimpan.
                                    </p>
                                </div>
                            </div>
                            <div className="flex items-center gap-3 w-full sm:w-auto">
                                <button
                                    onClick={handleCancelChanges}
                                    className={`flex-1 sm:flex-none px-5 py-2.5 rounded-xl text-sm font-bold transition-all ${isDarkMode
                                        ? 'bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white'
                                        : 'bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-900'
                                        }`}
                                >
                                    <i className="fa-solid fa-xmark mr-2"></i>
                                    Batal
                                </button>
                                <button
                                    onClick={handleSaveSettings}
                                    disabled={isSaving}
                                    className="flex-1 sm:flex-none px-6 py-2.5 rounded-xl text-sm font-bold shadow-lg hover:shadow-xl hover:scale-105 active:scale-95 transition-all flex items-center justify-center gap-2 text-white"
                                    style={{
                                        background: `linear-gradient(135deg, ${theme.gradientFrom}, ${theme.gradientTo})`
                                    }}
                                >
                                    {isSaving ? (
                                        <>
                                            <div className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                                            <span>Menyimpan...</span>
                                        </>
                                    ) : (
                                        <>
                                            <i className="fa-solid fa-check"></i>
                                            <span>Simpan Sekarang</span>
                                        </>
                                    )}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}
