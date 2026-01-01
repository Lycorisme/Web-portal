"use client";

import { useEffect, useState, useCallback, useRef } from "react";
import Link from "next/link";
import { useTheme } from "@/contexts/ThemeContext";
import { SiteSettings } from "@/lib/settings";
import SettingsShell from "@/components/layout/SettingsShell";

export default function MediaSettingsPage() {
    const { theme, settings: globalSettings, updateSiteSettings, isDarkMode } = useTheme();
    const [localSettings, setLocalSettings] = useState<Partial<SiteSettings>>({});

    // File input refs
    const signatureInputRef = useRef<HTMLInputElement>(null);
    const stampInputRef = useRef<HTMLInputElement>(null);
    const faviconInputRef = useRef<HTMLInputElement>(null);
    const logoInputRef = useRef<HTMLInputElement>(null);

    const orgTypes = [
        { value: 'government', label: 'Pemerintah', icon: 'fa-landmark', description: 'Instansi pemerintahan' },
        { value: 'private', label: 'Swasta', icon: 'fa-building', description: 'Perusahaan swasta' },
        { value: 'other', label: 'Lainnya', icon: 'fa-ellipsis', description: 'Organisasi lainnya' },
    ];

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
            handleChange(key, base64);
        };
        reader.readAsDataURL(file);
    };

    // Shared dynamic styles
    const cardStyle = isDarkMode ? {
        backgroundColor: `${theme.sidebar}D9`,
        borderColor: `${theme.accent}20`
    } : {};

    const cardClass = `rounded-2xl shadow-sm border transition-all duration-300 overflow-hidden ${isDarkMode
        ? 'backdrop-blur-sm'
        : 'bg-white border-slate-100 shadow-lg'
        }`;

    const inputClass = `w-full rounded-xl border px-4 py-3 focus:ring-2 focus:border-transparent transition-all duration-200 ${isDarkMode
        ? 'bg-slate-900/50 border-slate-600 text-slate-100 placeholder-slate-500 focus:ring-offset-2 focus:ring-offset-slate-800'
        : 'bg-white border-slate-300 text-slate-800 placeholder-slate-400'
        }`;

    const labelClass = `block text-sm font-bold mb-2 ${isDarkMode ? 'text-slate-200' : 'text-slate-700'}`;

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
                        <i className="fa-solid fa-file-signature text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 className={`text-2xl font-bold font-[family-name:var(--font-merriweather)] ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>
                            Media & Dokumen
                        </h2>
                        <p className={`text-sm ${isDarkMode ? 'text-slate-400' : 'text-slate-500'}`}>
                            Kelola logo, favicon, dan aset persuratan resmi
                        </p>
                    </div>
                </div>

                {/* Branding Assets */}
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {/* Favicon */}
                    <div className={`${cardClass} p-6 flex flex-col items-center text-center`} style={cardStyle}>
                        <div
                            className={`h-20 w-20 rounded-2xl mb-4 flex items-center justify-center border-2 border-dashed overflow-hidden transition-all hover:scale-105 ${isDarkMode ? 'bg-slate-900/50' : 'bg-slate-50'
                                }`}
                            style={{ borderColor: `${theme.accent}50` }}
                        >
                            {localSettings.favicon_url ? (
                                <img src={localSettings.favicon_url} className="h-12 w-12 object-contain" alt="Favicon" />
                            ) : (
                                <i className="fa-solid fa-image text-3xl" style={{ color: theme.accent }}></i>
                            )}
                        </div>
                        <h4 className={`font-bold text-lg ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>Favicon Website</h4>
                        <p className={`text-xs mb-4 ${isDarkMode ? 'text-slate-400' : 'text-slate-500'}`}>ICO/PNG (Max 512px)</p>
                        <div className="flex gap-2 w-full">
                            <label className="flex-1 cursor-pointer group">
                                <div
                                    className={`w-full py-2.5 px-4 rounded-xl border-2 border-dashed transition-all text-sm font-bold text-center ${isDarkMode ? 'hover:bg-slate-700' : 'hover:bg-slate-50'
                                        }`}
                                    style={{ borderColor: theme.accent, color: theme.accent }}
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
                            {localSettings.favicon_url && (
                                <button
                                    onClick={() => handleChange('favicon_url', '')}
                                    className="p-2.5 rounded-xl bg-red-500/10 text-red-500 hover:bg-red-500/20 transition-colors"
                                >
                                    <i className="fa-solid fa-trash"></i>
                                </button>
                            )}
                        </div>
                    </div>

                    {/* Logo */}
                    <div className={`${cardClass} p-6 flex flex-col items-center text-center lg:col-span-2`} style={cardStyle}>
                        <label
                            className={`w-full h-40 rounded-2xl flex flex-col items-center justify-center cursor-pointer mb-4 border-2 border-dashed transition-all group hover:scale-[1.02] ${isDarkMode ? 'bg-slate-900/50 hover:bg-slate-800' : 'hover:bg-slate-50'
                                }`}
                            style={{ borderColor: `${theme.accent}50` }}
                        >
                            {localSettings.logo_url ? (
                                <img src={localSettings.logo_url} className="h-24 object-contain" alt="Logo" />
                            ) : (
                                <>
                                    <i
                                        className="fa-regular fa-image text-4xl mb-3 transition-colors"
                                        style={{ color: theme.accent }}
                                    ></i>
                                    <span className={`text-sm font-bold ${isDarkMode ? 'text-slate-300' : 'text-slate-600'}`}>
                                        Klik untuk upload Logo Utama
                                    </span>
                                    <span className={`text-xs ${isDarkMode ? 'text-slate-500' : 'text-slate-400'}`}>
                                        Format PNG Transparan disarankan
                                    </span>
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
                        <div className="flex items-center gap-3">
                            <h4 className={`font-bold text-lg ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>Logo Utama</h4>
                            {localSettings.logo_url && (
                                <button
                                    onClick={() => handleChange('logo_url', '')}
                                    className="px-3 py-1 rounded-lg text-xs font-bold bg-red-500/10 text-red-500 hover:bg-red-500/20 transition-colors"
                                >
                                    <i className="fa-solid fa-trash mr-1"></i>Hapus
                                </button>
                            )}
                        </div>
                    </div>
                </div>

                {/* Organization & Leader Info */}
                <div className={cardClass} style={cardStyle}>
                    <div className="p-6 border-b theme-gradient" style={{ borderColor: isDarkMode ? 'transparent' : 'rgba(0,0,0,0.05)' }}>
                        <h3 className="text-xl font-bold text-white flex items-center gap-3">
                            <i className="fa-solid fa-id-card-clip"></i>
                            Informasi Organisasi & Pimpinan
                        </h3>
                        <p className="text-sm text-white/70 mt-1">Data ini digunakan untuk dokumen resmi, surat tugas, dan laporan.</p>
                    </div>
                    <div className="p-6 space-y-6">
                        {/* Organization Type */}
                        <div>
                            <label className={`${labelClass} mb-4`}>Tipe Organisasi</label>
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                                {orgTypes.map((org) => (
                                    <button
                                        key={org.value}
                                        type="button"
                                        onClick={() => handleChange('organization_type', org.value as any)}
                                        className={`p-5 rounded-2xl border-2 transition-all text-left group hover:scale-[1.02] ${localSettings.organization_type === org.value
                                            ? ''
                                            : isDarkMode
                                                ? 'border-slate-700 hover:border-slate-600 bg-slate-900/50'
                                                : 'border-slate-200 hover:border-slate-300 bg-white'
                                            }`}
                                        style={localSettings.organization_type === org.value ? {
                                            borderColor: theme.accent,
                                            backgroundColor: `${theme.accent}10`,
                                        } : undefined}
                                    >
                                        <div className="flex items-center gap-4">
                                            <div
                                                className={`w-12 h-12 rounded-xl flex items-center justify-center transition-colors ${localSettings.organization_type === org.value
                                                    ? ''
                                                    : isDarkMode ? 'bg-slate-800' : 'bg-slate-100'
                                                    }`}
                                                style={localSettings.organization_type === org.value ? {
                                                    backgroundColor: `${theme.accent}20`,
                                                    color: theme.accent
                                                } : undefined}
                                            >
                                                <i className={`fa-solid ${org.icon} text-xl ${localSettings.organization_type !== org.value
                                                    ? isDarkMode ? 'text-slate-500' : 'text-slate-400'
                                                    : ''
                                                    }`}></i>
                                            </div>
                                            <div>
                                                <p className={`font-bold ${isDarkMode ? 'text-white' : 'text-slate-800'}`}
                                                    style={localSettings.organization_type === org.value ? { color: theme.accent } : undefined}
                                                >{org.label}</p>
                                                <p className={`text-xs ${isDarkMode ? 'text-slate-500' : 'text-slate-400'}`}>{org.description}</p>
                                            </div>
                                        </div>
                                    </button>
                                ))}
                            </div>
                        </div>

                        {/* Leader Info */}
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div className="space-y-1">
                                <label className={labelClass}>Nama Pimpinan</label>
                                <input
                                    type="text"
                                    value={localSettings.leader_name || ""}
                                    onChange={(e) => handleChange("leader_name", e.target.value)}
                                    className={inputClass}
                                    placeholder="Nama lengkap pimpinan"
                                    style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                                />
                            </div>
                            <div className="space-y-1">
                                <label className={labelClass}>Jabatan</label>
                                <input
                                    type="text"
                                    value={localSettings.leader_title || ""}
                                    onChange={(e) => handleChange("leader_title", e.target.value)}
                                    className={inputClass}
                                    placeholder={localSettings.organization_type === 'government' ? 'Kepala Dinas / Direktur' : 'CEO / Direktur Utama'}
                                    style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                                />
                            </div>
                        </div>

                        {/* Dynamic ID fields based on org type */}
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {localSettings.organization_type === 'government' ? (
                                <div className="space-y-1">
                                    <label className={labelClass}>
                                        <i className="fa-solid fa-id-badge mr-2" style={{ color: theme.accent }}></i>NIP (Nomor Induk Pegawai)
                                    </label>
                                    <input
                                        type="text"
                                        value={localSettings.leader_nip || ""}
                                        onChange={(e) => handleChange("leader_nip", e.target.value)}
                                        className={inputClass}
                                        placeholder="19XXXXXXXXXXXXXX"
                                        style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                                    />
                                </div>
                            ) : (
                                <div className="space-y-1">
                                    <label className={labelClass}>
                                        <i className="fa-solid fa-id-card mr-2" style={{ color: theme.accent }}></i>NIK (Nomor Induk Kependudukan)
                                    </label>
                                    <input
                                        type="text"
                                        value={localSettings.leader_nik || ""}
                                        onChange={(e) => handleChange("leader_nik", e.target.value)}
                                        className={inputClass}
                                        placeholder="3XXXXXXXXXXXXXXX"
                                        style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                                    />
                                </div>
                            )}
                            <div className="space-y-1">
                                <div className="flex items-center gap-2 mb-2">
                                    <input
                                        type="text"
                                        value={localSettings.leader_custom_id_label || ""}
                                        onChange={(e) => handleChange("leader_custom_id_label", e.target.value)}
                                        className={`text-sm font-bold bg-transparent border-none p-0 focus:ring-0 w-auto ${isDarkMode ? 'text-slate-200' : 'text-slate-700'}`}
                                        placeholder="ID Lainnya (klik untuk edit)"
                                        style={{ minWidth: '180px' }}
                                    />
                                    <span className={`text-xs ${isDarkMode ? 'text-slate-500' : 'text-slate-400'}`}>(opsional)</span>
                                </div>
                                <input
                                    type="text"
                                    value={localSettings.leader_custom_id || ""}
                                    onChange={(e) => handleChange("leader_custom_id", e.target.value)}
                                    className={inputClass}
                                    placeholder="Nomor identitas lainnya"
                                    style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                                />
                            </div>
                        </div>

                        {/* Contact Info */}
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div className="space-y-1">
                                <label className={labelClass}>
                                    <i className="fa-solid fa-phone mr-2" style={{ color: theme.accent }}></i>No. Telepon
                                </label>
                                <input
                                    type="tel"
                                    value={localSettings.leader_phone || ""}
                                    onChange={(e) => handleChange("leader_phone", e.target.value)}
                                    className={inputClass}
                                    placeholder="+62 8XX XXXX XXXX"
                                    style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                                />
                            </div>
                            <div className="space-y-1">
                                <label className={labelClass}>
                                    <i className="fa-solid fa-envelope mr-2" style={{ color: theme.accent }}></i>Email
                                </label>
                                <input
                                    type="email"
                                    value={localSettings.leader_email || ""}
                                    onChange={(e) => handleChange("leader_email", e.target.value)}
                                    className={inputClass}
                                    placeholder="pimpinan@organisasi.id"
                                    style={{ "--tw-ring-color": theme.accent } as React.CSSProperties}
                                />
                            </div>
                        </div>
                    </div>
                </div>

                {/* Official Documents */}
                <div className={cardClass} style={cardStyle}>
                    <div className="p-6 border-b theme-gradient" style={{ borderColor: isDarkMode ? 'transparent' : 'rgba(0,0,0,0.05)' }}>
                        <h3 className="text-xl font-bold text-white flex items-center gap-3">
                            <i className="fa-solid fa-file-signature"></i>
                            Aset Persuratan (Official)
                        </h3>
                        <p className="text-sm text-white/70 mt-1">Aset ini akan digunakan otomatis pada fitur "Generate Surat Tugas" & Laporan.</p>
                    </div>
                    <div className="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                        {/* Letterhead */}
                        <div className="space-y-3">
                            <label className={labelClass}>Header Kop Surat (Image)</label>
                            <label
                                className={`h-40 rounded-2xl flex flex-col items-center justify-center cursor-pointer relative group border-2 border-dashed transition-all hover:scale-[1.02] ${isDarkMode ? 'bg-slate-900/50 hover:bg-slate-800' : 'hover:bg-slate-50'
                                    }`}
                                style={{ borderColor: `${theme.accent}50` }}
                            >
                                <div className="text-center group-hover:scale-105 transition-transform">
                                    <i className="fa-solid fa-file-invoice text-3xl mb-2" style={{ color: theme.accent }}></i>
                                    <p className={`text-sm font-bold ${isDarkMode ? 'text-slate-300' : 'text-slate-600'}`}>Upload Kop Surat</p>
                                    <p className={`text-xs ${isDarkMode ? 'text-slate-500' : 'text-slate-400'}`}>Lebar: 1920px (High Res)</p>
                                </div>
                                <input type="file" className="hidden" accept=".png,.jpg,.jpeg" />
                            </label>
                        </div>

                        {/* Signature & Stamp */}
                        <div className="space-y-6">
                            <div className="space-y-3">
                                <label className={labelClass}>Tanda Tangan Digital</label>
                                <div className="flex items-center gap-4">
                                    <div
                                        className={`h-20 w-32 border-2 border-dashed rounded-xl flex items-center justify-center overflow-hidden ${isDarkMode ? 'bg-slate-900/50' : 'bg-slate-50'
                                            }`}
                                        style={{ borderColor: `${theme.accent}50` }}
                                    >
                                        {localSettings.signature_url ? (
                                            <img src={localSettings.signature_url} className="h-full object-contain" alt="Signature" />
                                        ) : (
                                            <div className="text-center">
                                                <i className="fa-solid fa-signature text-xl mb-1" style={{ color: theme.accent }}></i>
                                                <p className={`text-[10px] ${isDarkMode ? 'text-slate-500' : 'text-slate-400'}`}>Preview</p>
                                            </div>
                                        )}
                                    </div>
                                    <div className="flex flex-col gap-2">
                                        <label className="cursor-pointer">
                                            <span
                                                className="px-4 py-2.5 text-sm font-bold rounded-xl transition-all inline-block"
                                                style={{
                                                    backgroundColor: `${theme.accent}15`,
                                                    color: theme.accent,
                                                    border: `1px solid ${theme.accent}30`
                                                }}
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
                                                onClick={() => handleChange('signature_url', '')}
                                                className="text-xs font-bold text-red-500 hover:text-red-600"
                                            >
                                                <i className="fa-solid fa-trash mr-1"></i>Hapus
                                            </button>
                                        )}
                                    </div>
                                </div>
                                <p className={`text-xs ${isDarkMode ? 'text-slate-500' : 'text-slate-400'}`}>PNG transparan disarankan (maks 2MB)</p>
                            </div>
                            <div className="space-y-3">
                                <label className={labelClass}>Stempel Perusahaan (Cap Basah)</label>
                                <div className="flex items-center gap-4">
                                    <div
                                        className={`h-20 w-20 border-2 border-dashed rounded-full flex items-center justify-center overflow-hidden ${isDarkMode ? 'bg-slate-900/50' : 'bg-slate-50'
                                            }`}
                                        style={{ borderColor: `${theme.accent}50` }}
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
                                                className="px-4 py-2.5 text-sm font-bold rounded-xl transition-all inline-block"
                                                style={{
                                                    backgroundColor: `${theme.accent}15`,
                                                    color: theme.accent,
                                                    border: `1px solid ${theme.accent}30`
                                                }}
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
                                                onClick={() => handleChange('stamp_url', '')}
                                                className="text-xs font-bold text-red-500 hover:text-red-600"
                                            >
                                                <i className="fa-solid fa-trash mr-1"></i>Hapus
                                            </button>
                                        )}
                                    </div>
                                </div>
                                <p className={`text-xs ${isDarkMode ? 'text-slate-500' : 'text-slate-400'}`}>PNG/JPG (maks 2MB)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </SettingsShell>
    );
}
