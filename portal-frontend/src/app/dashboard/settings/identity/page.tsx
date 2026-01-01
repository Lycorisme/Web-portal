"use client";

import { useEffect, useState, useCallback } from "react";
import Link from "next/link";
import { useTheme } from "@/contexts/ThemeContext";
import { SiteSettings } from "@/lib/settings";
import SettingsShell from "@/components/layout/SettingsShell";

export default function IdentitySettingsPage() {
    const { theme, settings: globalSettings, updateSiteSettings, initialSettings, isDarkMode } = useTheme();
    const [localSettings, setLocalSettings] = useState<Partial<SiteSettings>>({});

    // Sync global settings to local
    useEffect(() => {
        setLocalSettings(globalSettings);
    }, [globalSettings]);

    // Handle local settings change
    const handleChange = useCallback((key: keyof SiteSettings, value: any) => {
        const newSettings = { ...localSettings, [key]: value };
        setLocalSettings(newSettings);
        updateSiteSettings({ [key]: value });
    }, [updateSiteSettings, localSettings]);

    // Shared dynamic styles
    const cardStyle = isDarkMode ? {
        backgroundColor: `${theme.sidebar}D9`, // 85% opacity
        borderColor: `${theme.accent}20`
    } : {};

    const cardClass = `rounded-2xl shadow-sm border p-8 transition-all duration-300 ${isDarkMode
        ? 'backdrop-blur-sm'
        : 'bg-white border-slate-100 shadow-lg'
        }`;

    const inputClass = `w-full rounded-xl border px-4 py-3 focus:ring-2 focus:border-transparent transition-all duration-200 ${isDarkMode
        ? 'bg-slate-900/50 border-slate-600 text-slate-100 placeholder-slate-500 focus:ring-offset-2 focus:ring-offset-slate-800'
        : 'bg-white border-slate-300 text-slate-800 placeholder-slate-400'
        }`;

    const labelClass = `block text-sm font-bold mb-2 ${isDarkMode ? 'text-slate-200' : 'text-slate-700'}`;

    const helperClass = `text-xs mt-1.5 ${isDarkMode ? 'text-slate-500' : 'text-slate-400'}`;

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
                        <i className="fa-solid fa-globe text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 className={`text-2xl font-bold font-[family-name:var(--font-merriweather)] ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>
                            Identitas & SEO
                        </h2>
                        <p className={`text-sm ${isDarkMode ? 'text-slate-400' : 'text-slate-500'}`}>
                            Kelola informasi dasar portal dan optimasi mesin pencari
                        </p>
                    </div>
                </div>

                {/* Basic Website Info */}
                <div className={cardClass} style={cardStyle}>
                    <h3 className={`text-lg font-bold mb-6 flex items-center gap-3 ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>
                        <div className="w-10 h-10 rounded-xl flex items-center justify-center" style={{ backgroundColor: `${theme.accent}15` }}>
                            <i className="fa-solid fa-building" style={{ color: theme.accent }}></i>
                        </div>
                        Informasi Dasar Website
                    </h3>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div className="space-y-1">
                            <label className={labelClass}>Nama Portal</label>
                            <input
                                type="text"
                                value={localSettings.site_name || ""}
                                onChange={(e) => handleChange("site_name", e.target.value)}
                                className={inputClass}
                                placeholder="Masukkan nama portal"
                                style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                            />
                        </div>
                        <div className="space-y-1">
                            <label className={labelClass}>Tagline</label>
                            <input
                                type="text"
                                value={localSettings.site_tagline || ""}
                                onChange={(e) => handleChange("site_tagline", e.target.value)}
                                className={inputClass}
                                placeholder="Slogan atau tagline website"
                                style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                            />
                        </div>
                        <div className="space-y-1">
                            <label className={labelClass}>Email Redaksi</label>
                            <input
                                type="email"
                                value={localSettings.site_email || ""}
                                onChange={(e) => handleChange("site_email", e.target.value)}
                                className={inputClass}
                                placeholder="email@portal.id"
                                style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                            />
                        </div>
                        <div className="space-y-1">
                            <label className={labelClass}>Nomor Telepon</label>
                            <input
                                type="tel"
                                value={localSettings.site_phone || ""}
                                onChange={(e) => handleChange("site_phone", e.target.value)}
                                className={inputClass}
                                placeholder="+62 xxx xxxx xxxx"
                                style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                            />
                        </div>
                        <div className="md:col-span-2 space-y-1">
                            <label className={labelClass}>Alamat Redaksi</label>
                            <textarea
                                value={localSettings.site_address || ""}
                                onChange={(e) => handleChange("site_address", e.target.value)}
                                rows={2}
                                className={inputClass}
                                placeholder="Alamat lengkap kantor redaksi"
                                style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                            />
                        </div>
                        <div className="md:col-span-2 space-y-1">
                            <label className={labelClass}>Deskripsi Website</label>
                            <textarea
                                value={localSettings.site_description || ""}
                                onChange={(e) => handleChange("site_description", e.target.value)}
                                rows={3}
                                className={inputClass}
                                placeholder="Deskripsi singkat tentang portal berita Anda"
                                style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                            />
                        </div>
                    </div>
                </div>

                {/* SEO Settings */}
                <div className={cardClass} style={cardStyle}>
                    <h3 className={`text-lg font-bold mb-6 flex items-center gap-3 ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>
                        <div className="w-10 h-10 rounded-xl flex items-center justify-center bg-green-500/10">
                            <i className="fa-solid fa-magnifying-glass text-green-500"></i>
                        </div>
                        Pengaturan SEO
                    </h3>
                    <div className="grid grid-cols-1 gap-6">
                        <div className="space-y-1">
                            <label className={labelClass}>Meta Title</label>
                            <input
                                type="text"
                                value={localSettings.meta_title || ""}
                                onChange={(e) => handleChange("meta_title", e.target.value)}
                                className={inputClass}
                                placeholder="Judul yang muncul di hasil pencarian"
                                style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                            />
                            <p className={helperClass}>Rekomendasi: 50-60 karakter</p>
                        </div>
                        <div className="space-y-1">
                            <label className={labelClass}>Meta Description</label>
                            <textarea
                                value={localSettings.meta_description || ""}
                                onChange={(e) => handleChange("meta_description", e.target.value)}
                                rows={3}
                                className={inputClass}
                                placeholder="Deskripsi yang muncul di hasil pencarian Google"
                                style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                            />
                            <p className={helperClass}>Rekomendasi: 150-160 karakter</p>
                        </div>
                        <div className="space-y-1">
                            <label className={labelClass}>Meta Keywords</label>
                            <input
                                type="text"
                                value={localSettings.meta_keywords || ""}
                                onChange={(e) => handleChange("meta_keywords", e.target.value)}
                                className={inputClass}
                                placeholder="berita, portal, indonesia, terkini"
                                style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                            />
                            <p className={helperClass}>Pisahkan dengan koma</p>
                        </div>
                        <div className="space-y-1">
                            <label className={labelClass}>Google Analytics ID</label>
                            <input
                                type="text"
                                value={localSettings.google_analytics_id || ""}
                                onChange={(e) => handleChange("google_analytics_id", e.target.value)}
                                className={inputClass}
                                placeholder="UA-XXXXXXXXX-X atau G-XXXXXXXXXX"
                                style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                            />
                        </div>
                    </div>
                </div>

                {/* Social Media */}
                <div className={cardClass} style={cardStyle}>
                    <h3 className={`text-lg font-bold mb-6 flex items-center gap-3 ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>
                        <div className="w-10 h-10 rounded-xl flex items-center justify-center bg-purple-500/10">
                            <i className="fa-solid fa-share-nodes text-purple-500"></i>
                        </div>
                        Media Sosial
                    </h3>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div className="space-y-1">
                            <label className={labelClass}>
                                <i className="fa-brands fa-facebook text-blue-600 mr-2"></i>Facebook
                            </label>
                            <input
                                type="url"
                                value={localSettings.facebook_url || ""}
                                onChange={(e) => handleChange("facebook_url", e.target.value)}
                                className={inputClass}
                                placeholder="https://facebook.com/yourpage"
                                style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                            />
                        </div>
                        <div className="space-y-1">
                            <label className={labelClass}>
                                <i className="fa-brands fa-twitter text-sky-500 mr-2"></i>Twitter / X
                            </label>
                            <input
                                type="url"
                                value={localSettings.twitter_url || ""}
                                onChange={(e) => handleChange("twitter_url", e.target.value)}
                                className={inputClass}
                                placeholder="https://twitter.com/yourhandle"
                                style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                            />
                        </div>
                        <div className="space-y-1">
                            <label className={labelClass}>
                                <i className="fa-brands fa-instagram text-pink-500 mr-2"></i>Instagram
                            </label>
                            <input
                                type="url"
                                value={localSettings.instagram_url || ""}
                                onChange={(e) => handleChange("instagram_url", e.target.value)}
                                className={inputClass}
                                placeholder="https://instagram.com/yourprofile"
                                style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                            />
                        </div>
                        <div className="space-y-1">
                            <label className={labelClass}>
                                <i className="fa-brands fa-youtube text-red-600 mr-2"></i>YouTube
                            </label>
                            <input
                                type="url"
                                value={localSettings.youtube_url || ""}
                                onChange={(e) => handleChange("youtube_url", e.target.value)}
                                className={inputClass}
                                placeholder="https://youtube.com/c/yourchannel"
                                style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                            />
                        </div>
                        <div className="md:col-span-2 space-y-1">
                            <label className={labelClass}>
                                <i className="fa-brands fa-linkedin text-blue-700 mr-2"></i>LinkedIn
                            </label>
                            <input
                                type="url"
                                value={localSettings.linkedin_url || ""}
                                onChange={(e) => handleChange("linkedin_url", e.target.value)}
                                className={inputClass}
                                placeholder="https://linkedin.com/company/yourcompany"
                                style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                            />
                        </div>
                    </div>
                </div>
            </div>
        </SettingsShell>
    );
}
