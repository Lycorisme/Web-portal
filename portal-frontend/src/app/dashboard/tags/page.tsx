"use client";

import React, { useState } from "react";
import { useTheme } from "@/contexts/ThemeContext";

export default function TagsPage() {
    const { theme, isDarkMode } = useTheme();

    const tags = ["Prabowo", "Gibran", "Pilkada 2024", "Timnas Indonesia", "Apple Vision Pro", "IHSG", "Metaverse", "Sustainability", "Artificial Intelligence", "Crypto News"];

    return (
        <div className="space-y-6 animate-in fade-in duration-700">
            {/* Header Section */}
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 className="text-2xl font-bold tracking-tight" style={{ color: isDarkMode ? "#fff" : "#1e293b" }}>
                        Manajemen Tags
                    </h1>
                    <p className="text-sm" style={{ color: isDarkMode ? "#94a3b8" : "#64748b" }}>
                        Kelola kata kunci (keywords) untuk optimasi SEO berita Anda.
                    </p>
                </div>
                <div className="flex gap-2">
                    <button
                        className="flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl text-white font-semibold transition-all hover:scale-[1.02] active:scale-[0.98] shadow-lg shadow-indigo-500/20"
                        style={{ background: `linear-gradient(135deg, ${theme.gradientFrom}, ${theme.gradientTo})` }}
                    >
                        <i className="fa-solid fa-tag"></i>
                        Tambah Tag
                    </button>
                </div>
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-4 gap-6">
                {/* Search & Bulk Section */}
                <div
                    className="lg:col-span-4 p-6 rounded-2xl border"
                    style={{
                        backgroundColor: isDarkMode ? `${theme.softTint}08` : "#fff",
                        borderColor: isDarkMode ? `${theme.softTint}15` : "#e2e8f0"
                    }}
                >
                    <div className="flex flex-col md:flex-row gap-4">
                        <div className="relative flex-1">
                            <i className="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input
                                type="text"
                                placeholder="Cari tag..."
                                className="w-full pl-10 pr-4 py-2.5 rounded-xl border bg-transparent text-sm focus:outline-none focus:ring-2"
                                style={{
                                    borderColor: isDarkMode ? `${theme.softTint}20` : "#e2e8f0",
                                    color: isDarkMode ? "#fff" : "#1e293b"
                                }}
                            />
                        </div>
                        <select
                            className="px-4 py-2.5 rounded-xl border bg-transparent text-sm focus:outline-none focus:ring-2"
                            style={{
                                borderColor: isDarkMode ? `${theme.softTint}20` : "#e2e8f0",
                                color: isDarkMode ? "#fff" : "#1e293b",
                                backgroundColor: isDarkMode ? "#1e293b" : "#fff"
                            }}
                        >
                            <option>Tampilkan 20</option>
                            <option>Tampilkan 50</option>
                            <option>Tampilkan 100</option>
                        </select>
                    </div>
                </div>

                {/* Popular Tags */}
                <div className="lg:col-span-4 flex flex-wrap gap-3">
                    {tags.map((tag, index) => (
                        <div
                            key={index}
                            className="px-4 py-3 rounded-2xl border flex items-center gap-3 transition-all hover:shadow-md cursor-pointer group"
                            style={{
                                backgroundColor: isDarkMode ? `${theme.softTint}05` : "#fff",
                                borderColor: isDarkMode ? `${theme.softTint}10` : "#e2e8f0"
                            }}
                        >
                            <span className="font-medium text-sm" style={{ color: isDarkMode ? "#cbd5e1" : "#1e293b" }}>#{tag}</span>
                            <div className="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button className="text-slate-400 hover:text-indigo-500 transition-colors">
                                    <i className="fa-solid fa-pen text-[10px]"></i>
                                </button>
                                <button className="text-slate-400 hover:text-red-500 transition-colors">
                                    <i className="fa-solid fa-xmark text-[10px]"></i>
                                </button>
                            </div>
                        </div>
                    ))}
                </div>

                {/* Empty State / Bottom Info */}
                <div className="lg:col-span-4 py-12 text-center opacity-50">
                    <p className="text-xs" style={{ color: isDarkMode ? "#94a3b8" : "#64748b" }}>
                        Menampilkan {tags.length} dari total 452 tags sistem.
                    </p>
                </div>
            </div>
        </div>
    );
}
