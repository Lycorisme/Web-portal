"use client";

import { useEffect, useState, useCallback } from "react";
import Link from "next/link";
import { useTheme } from "@/contexts/ThemeContext";
import { SiteSettings } from "@/lib/settings";
import SettingsShell from "@/components/layout/SettingsShell";

export default function SecuritySettingsPage() {
    const { theme, settings: globalSettings, updateSiteSettings, isDarkMode } = useTheme();
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
        backgroundColor: `${theme.sidebar}D9`,
        borderColor: `${theme.accent}20`
    } : {};

    const cardClass = `rounded-2xl shadow-sm border transition-all duration-300 ${isDarkMode
        ? 'backdrop-blur-sm'
        : 'bg-white border-slate-100 shadow-lg'
        }`;

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
                            background: 'linear-gradient(135deg, #ef4444, #dc2626)',
                            boxShadow: '0 8px 24px rgba(239, 68, 68, 0.3)'
                        }}
                    >
                        <i className="fa-solid fa-shield-halved text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 className={`text-2xl font-bold font-[family-name:var(--font-merriweather)] ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>
                            Security Core
                        </h2>
                        <p className={`text-sm ${isDarkMode ? 'text-slate-400' : 'text-slate-500'}`}>
                            Pengaturan keamanan, rate limiting, dan proteksi sistem
                        </p>
                    </div>
                </div>

                {/* Warning Banner */}
                <div className={`border-l-4 border-red-500 p-6 rounded-r-2xl shadow-sm ${isDarkMode
                    ? 'bg-gradient-to-r from-red-950/50 to-transparent'
                    : 'bg-gradient-to-r from-red-50 to-white'
                    }`}>
                    <div className="flex items-start gap-4">
                        <div className="flex-shrink-0 w-12 h-12 rounded-xl bg-red-500/10 flex items-center justify-center">
                            <i className="fa-solid fa-triangle-exclamation text-red-500 text-xl"></i>
                        </div>
                        <div>
                            <h3 className={`text-lg font-bold ${isDarkMode ? 'text-red-400' : 'text-red-800'}`}>Zona Bahaya</h3>
                            <p className={`text-sm mt-1 ${isDarkMode ? 'text-red-200/80' : 'text-red-700'}`}>
                                Pengaturan di bawah ini berhubungan langsung dengan firewall dan proteksi data.
                                Perubahan akan dicatat dalam Audit Log.
                            </p>
                        </div>
                    </div>
                </div>

                {/* Rate Limiting */}
                <div className={`${cardClass} p-8`} style={cardStyle}>
                    <h3 className={`text-lg font-bold mb-8 flex items-center gap-3 ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>
                        <div className="w-10 h-10 rounded-xl flex items-center justify-center" style={{ backgroundColor: `${theme.accent}15` }}>
                            <i className="fa-solid fa-shield-cat" style={{ color: theme.accent }}></i>
                        </div>
                        Parameter Rate Limiting
                    </h3>
                    <div className="space-y-1">
                        {/* API Threshold */}
                        <div className={`flex flex-col md:flex-row md:items-center justify-between py-5 border-b ${isDarkMode ? 'border-slate-700/50' : 'border-slate-100'}`}>
                            <div className="mb-3 md:mb-0">
                                <h4 className={`font-bold ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>API Threshold</h4>
                                <p className={`text-sm ${isDarkMode ? 'text-slate-400' : 'text-slate-500'}`}>Batas request per menit untuk endpoint publik.</p>
                            </div>
                            <div className={`flex items-center rounded-xl p-1.5 ${isDarkMode ? 'bg-slate-900/70' : 'bg-slate-100'}`}>
                                <input
                                    type="number"
                                    value={localSettings.rate_limit_per_minute || 60}
                                    onChange={(e) => handleChange("rate_limit_per_minute", parseInt(e.target.value))}
                                    className={`w-20 bg-transparent border-none text-center font-bold focus:ring-0 ${isDarkMode ? 'text-white' : 'text-slate-700'}`}
                                />
                                <span className={`text-xs font-bold pr-3 ${isDarkMode ? 'text-slate-400' : 'text-slate-500'}`}>REQ/MIN</span>
                            </div>
                        </div>

                        {/* Auto-Ban IP */}
                        <div className={`flex flex-col md:flex-row md:items-center justify-between py-5 border-b ${isDarkMode ? 'border-slate-700/50' : 'border-slate-100'}`}>
                            <div className="mb-3 md:mb-0">
                                <h4 className={`font-bold ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>Auto-Ban IP</h4>
                                <p className={`text-sm ${isDarkMode ? 'text-slate-400' : 'text-slate-500'}`}>Blokir permanen IP yang melakukan spam 1000x/jam.</p>
                            </div>
                            <label className="relative inline-flex items-center cursor-pointer">
                                <input
                                    type="checkbox"
                                    checked={localSettings.auto_ban_enabled ?? true}
                                    onChange={(e) => handleChange("auto_ban_enabled", e.target.checked)}
                                    className="sr-only peer"
                                />
                                <div
                                    className={`w-14 h-7 peer-focus:outline-none peer-focus:ring-4 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:start-[4px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all transition-colors ${isDarkMode ? 'bg-slate-700 border-slate-600' : 'bg-slate-200'
                                        }`}
                                    style={{
                                        backgroundColor: localSettings.auto_ban_enabled ? theme.accent : undefined,
                                        "--tw-ring-color": `${theme.accent}40`,
                                    } as React.CSSProperties}
                                ></div>
                            </label>
                        </div>

                        {/* Maintenance Mode */}
                        <div className="flex flex-col md:flex-row md:items-center justify-between py-5">
                            <div className="mb-3 md:mb-0">
                                <h4 className={`font-bold ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>Maintenance Mode</h4>
                                <p className={`text-sm ${isDarkMode ? 'text-slate-400' : 'text-slate-500'}`}>Aktifkan mode pemeliharaan untuk pengunjung.</p>
                            </div>
                            <label className="relative inline-flex items-center cursor-pointer">
                                <input
                                    type="checkbox"
                                    checked={localSettings.maintenance_mode ?? false}
                                    onChange={(e) => handleChange("maintenance_mode", e.target.checked)}
                                    className="sr-only peer"
                                />
                                <div className={`w-14 h-7 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:start-[4px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600 ${isDarkMode ? 'bg-slate-700' : 'bg-slate-200'
                                    }`}></div>
                            </label>
                        </div>
                    </div>
                </div>

                {/* Security Actions */}
                <div className={`${cardClass} p-8`} style={cardStyle}>
                    <h3 className={`text-lg font-bold mb-6 flex items-center gap-3 ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>
                        <div className="w-10 h-10 rounded-xl flex items-center justify-center bg-amber-500/10">
                            <i className="fa-solid fa-key text-amber-500"></i>
                        </div>
                        Aksi Keamanan
                    </h3>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {/* Regenerate API Keys */}
                        <button className={`p-5 border rounded-2xl transition-all text-left group hover:scale-[1.02] ${isDarkMode
                            ? 'border-slate-700/50 hover:bg-slate-700/30 bg-slate-800/30'
                            : 'border-slate-200 hover:bg-slate-50 hover:border-slate-300'
                            }`}>
                            <div className="flex items-center gap-4">
                                <div
                                    className="w-12 h-12 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform"
                                    style={{ backgroundColor: `${theme.accent}15`, color: theme.accent }}
                                >
                                    <i className="fa-solid fa-rotate text-lg"></i>
                                </div>
                                <div>
                                    <p className={`font-bold ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>Regenerate API Keys</p>
                                    <p className={`text-sm ${isDarkMode ? 'text-slate-400' : 'text-slate-500'}`}>Buat ulang semua API key</p>
                                </div>
                            </div>
                        </button>

                        {/* Clear Cache */}
                        <button className={`p-5 border rounded-2xl transition-all text-left group hover:scale-[1.02] ${isDarkMode
                            ? 'border-slate-700/50 hover:bg-slate-700/30 bg-slate-800/30'
                            : 'border-slate-200 hover:bg-slate-50 hover:border-slate-300'
                            }`}>
                            <div className="flex items-center gap-4">
                                <div className="w-12 h-12 bg-green-500/10 rounded-xl flex items-center justify-center text-green-500 group-hover:scale-110 transition-transform">
                                    <i className="fa-solid fa-database text-lg"></i>
                                </div>
                                <div>
                                    <p className={`font-bold ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>Clear Cache</p>
                                    <p className={`text-sm ${isDarkMode ? 'text-slate-400' : 'text-slate-500'}`}>Bersihkan semua cache sistem</p>
                                </div>
                            </div>
                        </button>

                        {/* Force Logout All */}
                        <button className={`p-5 border rounded-2xl transition-all text-left group hover:scale-[1.02] ${isDarkMode
                            ? 'border-slate-700/50 hover:bg-slate-700/30 bg-slate-800/30'
                            : 'border-slate-200 hover:bg-slate-50 hover:border-slate-300'
                            }`}>
                            <div className="flex items-center gap-4">
                                <div className="w-12 h-12 bg-purple-500/10 rounded-xl flex items-center justify-center text-purple-500 group-hover:scale-110 transition-transform">
                                    <i className="fa-solid fa-user-lock text-lg"></i>
                                </div>
                                <div>
                                    <p className={`font-bold ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>Force Logout All</p>
                                    <p className={`text-sm ${isDarkMode ? 'text-slate-400' : 'text-slate-500'}`}>Paksa logout semua sesi aktif</p>
                                </div>
                            </div>
                        </button>

                        {/* Reset All Settings */}
                        <button className={`p-5 border rounded-2xl transition-all text-left group hover:scale-[1.02] ${isDarkMode
                            ? 'border-red-900/50 hover:bg-red-950/30 bg-red-950/20'
                            : 'border-red-200 hover:bg-red-50 hover:border-red-300'
                            }`}>
                            <div className="flex items-center gap-4">
                                <div className="w-12 h-12 bg-red-500/10 rounded-xl flex items-center justify-center text-red-500 group-hover:scale-110 transition-transform">
                                    <i className="fa-solid fa-trash-can text-lg"></i>
                                </div>
                                <div>
                                    <p className={`font-bold ${isDarkMode ? 'text-red-400' : 'text-red-800'}`}>Reset All Settings</p>
                                    <p className={`text-sm ${isDarkMode ? 'text-red-400/70' : 'text-red-500'}`}>Kembalikan ke pengaturan default</p>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>

                {/* Security Status Overview */}
                <div className={`${cardClass} p-8`} style={cardStyle}>
                    <h3 className={`text-lg font-bold mb-6 flex items-center gap-3 ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>
                        <div className="w-10 h-10 rounded-xl flex items-center justify-center bg-emerald-500/10">
                            <i className="fa-solid fa-shield-check text-emerald-500"></i>
                        </div>
                        Status Keamanan
                    </h3>
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {/* Firewall Status */}
                        <div className={`p-5 rounded-2xl ${isDarkMode ? 'bg-slate-900/50' : 'bg-slate-50'}`}>
                            <div className="flex items-center gap-3 mb-3">
                                <div className="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></div>
                                <span className={`text-sm font-bold ${isDarkMode ? 'text-slate-300' : 'text-slate-600'}`}>Firewall</span>
                            </div>
                            <p className={`text-2xl font-bold ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>Active</p>
                            <p className={`text-xs ${isDarkMode ? 'text-slate-500' : 'text-slate-400'}`}>12 IPs blocked</p>
                        </div>

                        {/* SSL Status */}
                        <div className={`p-5 rounded-2xl ${isDarkMode ? 'bg-slate-900/50' : 'bg-slate-50'}`}>
                            <div className="flex items-center gap-3 mb-3">
                                <div className="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></div>
                                <span className={`text-sm font-bold ${isDarkMode ? 'text-slate-300' : 'text-slate-600'}`}>SSL Certificate</span>
                            </div>
                            <p className={`text-2xl font-bold ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>Valid</p>
                            <p className={`text-xs ${isDarkMode ? 'text-slate-500' : 'text-slate-400'}`}>Expires in 89 days</p>
                        </div>

                        {/* Last Backup */}
                        <div className={`p-5 rounded-2xl ${isDarkMode ? 'bg-slate-900/50' : 'bg-slate-50'}`}>
                            <div className="flex items-center gap-3 mb-3">
                                <div className="w-3 h-3 rounded-full bg-amber-500 animate-pulse"></div>
                                <span className={`text-sm font-bold ${isDarkMode ? 'text-slate-300' : 'text-slate-600'}`}>Last Backup</span>
                            </div>
                            <p className={`text-2xl font-bold ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>2 days</p>
                            <p className={`text-xs ${isDarkMode ? 'text-slate-500' : 'text-slate-400'}`}>Recommended: daily</p>
                        </div>
                    </div>
                </div>
            </div>
        </SettingsShell>
    );
}
