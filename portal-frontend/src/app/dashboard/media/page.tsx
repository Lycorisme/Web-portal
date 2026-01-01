"use client";

import React, { useState } from "react";
import { useTheme } from "@/contexts/ThemeContext";

export default function MediaPage() {
    const { theme, isDarkMode } = useTheme();
    const [view, setView] = useState("grid");

    return (
        <div className="space-y-6 animate-in fade-in duration-700">
            {/* Header Section */}
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 className="text-2xl font-bold tracking-tight" style={{ color: isDarkMode ? "#fff" : "#1e293b" }}>
                        Galeri Media
                    </h1>
                    <p className="text-sm" style={{ color: isDarkMode ? "#94a3b8" : "#64748b" }}>
                        Kelola aset gambar dan video untuk digunakan dalam artikel.
                    </p>
                </div>
                <button
                    className="flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl text-white font-semibold transition-all hover:scale-[1.02] active:scale-[0.98] shadow-lg shadow-indigo-500/20"
                    style={{ background: `linear-gradient(135deg, ${theme.gradientFrom}, ${theme.gradientTo})` }}
                >
                    <i className="fa-solid fa-cloud-arrow-up"></i>
                    Upload Media
                </button>
            </div>

            {/* Media Container */}
            <div
                className="rounded-3xl border min-h-[500px] flex flex-col"
                style={{
                    backgroundColor: isDarkMode ? `${theme.softTint}05` : "#fff",
                    borderColor: isDarkMode ? `${theme.softTint}10` : "#e2e8f0"
                }}
            >
                {/* Toolbar */}
                <div className="p-4 border-b flex flex-wrap items-center justify-between gap-4" style={{ borderColor: isDarkMode ? `${theme.softTint}10` : "#e2e8f0" }}>
                    <div className="flex items-center gap-2">
                        <button
                            onClick={() => setView("grid")}
                            className={`p-2 rounded-lg transition-colors ${view === "grid" ? "bg-indigo-500/10 text-indigo-500" : "text-slate-400"}`}
                        >
                            <i className="fa-solid fa-border-all"></i>
                        </button>
                        <button
                            onClick={() => setView("list")}
                            className={`p-2 rounded-lg transition-colors ${view === "list" ? "bg-indigo-500/10 text-indigo-500" : "text-slate-400"}`}
                        >
                            <i className="fa-solid fa-list"></i>
                        </button>
                    </div>

                    <div className="flex items-center gap-4 flex-1 justify-end">
                        <div className="relative max-w-xs w-full">
                            <i className="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input
                                type="text"
                                placeholder="Cari media..."
                                className="w-full pl-10 pr-4 py-2 rounded-xl border bg-transparent text-sm focus:outline-none focus:ring-2"
                                style={{
                                    borderColor: isDarkMode ? `${theme.softTint}20` : "#e2e8f0",
                                    color: isDarkMode ? "#fff" : "#1e293b"
                                }}
                            />
                        </div>
                    </div>
                </div>

                {/* Grid View Placeholder */}
                <div className="flex-1 p-6 flex items-center justify-center text-center">
                    <div className="max-w-md">
                        <div
                            className="w-24 h-24 rounded-3xl flex items-center justify-center mx-auto mb-6 text-4xl shadow-inner"
                            style={{ backgroundColor: isDarkMode ? `${theme.softTint}08` : "#f8fafc", color: isDarkMode ? `${theme.softTint}30` : "#cbd5e1" }}
                        >
                            <i className="fa-regular fa-images"></i>
                        </div>
                        <h3 className="text-xl font-bold mb-2" style={{ color: isDarkMode ? "#f1f5f9" : "#1e293b" }}>Galeri Anda Masih Kosong</h3>
                        <p className="text-sm mb-8" style={{ color: isDarkMode ? "#94a3b8" : "#64748b" }}>
                            Upload gambar atau video berkualitas tinggi untuk meningkatkan interaksi dan visualisasi berita Anda.
                        </p>
                        <div className="flex flex-col sm:flex-row items-center justify-center gap-3">
                            <button
                                className="w-full sm:w-auto px-8 py-3 rounded-2xl text-white font-bold transition-transform active:scale-95 shadow-xl shadow-indigo-500/20"
                                style={{ background: theme.accent }}
                            >
                                Mulai Upload
                            </button>
                            <button
                                className="w-full sm:w-auto px-8 py-3 rounded-2xl font-bold transition-colors"
                                style={{
                                    backgroundColor: isDarkMode ? `${theme.softTint}10` : "#f1f5f9",
                                    color: isDarkMode ? "#cbd5e1" : "#475569"
                                }}
                            >
                                Pelajari Caranya
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {/* Storage Info */}
            <div
                className="p-6 rounded-2xl border flex flex-col md:flex-row md:items-center justify-between gap-4"
                style={{
                    backgroundColor: isDarkMode ? `${theme.softTint}08` : "#fff",
                    borderColor: isDarkMode ? `${theme.softTint}15` : "#e2e8f0"
                }}
            >
                <div className="flex items-center gap-4">
                    <div className="w-10 h-10 rounded-full bg-blue-500/10 flex items-center justify-center text-blue-500">
                        <i className="fa-solid fa-hdd"></i>
                    </div>
                    <div>
                        <p className="text-xs font-bold uppercase tracking-widest" style={{ color: isDarkMode ? "#64748b" : "#94a3b8" }}>Penyimpanan</p>
                        <p className="text-sm font-semibold" style={{ color: isDarkMode ? "#fff" : "#1e293b" }}>1.2 GB dari 10 GB digunakan</p>
                    </div>
                </div>
                <div className="w-full md:w-64 h-2 rounded-full bg-slate-200 dark:bg-slate-800 overflow-hidden">
                    <div className="h-full bg-blue-500 rounded-full" style={{ width: "12%" }}></div>
                </div>
            </div>
        </div>
    );
}
