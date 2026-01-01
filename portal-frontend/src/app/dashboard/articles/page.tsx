"use client";

import React, { useState } from "react";
import { useTheme } from "@/contexts/ThemeContext";

export default function ArticlesPage() {
    const { theme, isDarkMode } = useTheme();
    const [filter, setFilter] = useState("all");

    const stats = [
        { label: "Total Berita", value: "1,284", icon: "fa-solid fa-newspaper", color: theme.accent },
        { label: "Published", value: "956", icon: "fa-solid fa-circle-check", color: "#22c55e" },
        { label: "Draft", value: "142", icon: "fa-solid fa-file-pen", color: "#f59e0b" },
        { label: "Pending", value: "186", icon: "fa-solid fa-clock", color: "#3b82f6" },
    ];

    return (
        <div className="space-y-6 animate-in fade-in duration-700">
            {/* Header Section */}
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 className="text-2xl font-bold tracking-tight" style={{ color: isDarkMode ? "#fff" : "#1e293b" }}>
                        Artikel Berita
                    </h1>
                    <p className="text-sm" style={{ color: isDarkMode ? "#94a3b8" : "#64748b" }}>
                        Kelola semua berita, draft, dan publikasi Anda di sini.
                    </p>
                </div>
                <button
                    className="flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl text-white font-semibold transition-all hover:scale-[1.02] active:scale-[0.98] shadow-lg shadow-indigo-500/20"
                    style={{ background: `linear-gradient(135deg, ${theme.gradientFrom}, ${theme.gradientTo})` }}
                >
                    <i className="fa-solid fa-plus"></i>
                    Tulis Berita Baru
                </button>
            </div>

            {/* Stats Grid */}
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                {stats.map((stat, index) => (
                    <div
                        key={index}
                        className="p-4 rounded-2xl border transition-all duration-300 hover:shadow-xl group"
                        style={{
                            backgroundColor: isDarkMode ? `${theme.softTint}08` : "#fff",
                            borderColor: isDarkMode ? `${theme.softTint}15` : "#e2e8f0"
                        }}
                    >
                        <div className="flex items-center gap-4">
                            <div
                                className="w-12 h-12 rounded-xl flex items-center justify-center text-xl transition-transform group-hover:scale-110"
                                style={{ backgroundColor: `${stat.color}15`, color: stat.color }}
                            >
                                <i className={stat.icon}></i>
                            </div>
                            <div>
                                <p className="text-xs font-medium uppercase tracking-wider" style={{ color: isDarkMode ? "#64748b" : "#94a3b8" }}>
                                    {stat.label}
                                </p>
                                <p className="text-xl font-bold" style={{ color: isDarkMode ? "#f1f5f9" : "#1e293b" }}>
                                    {stat.value}
                                </p>
                            </div>
                        </div>
                    </div>
                ))}
            </div>

            {/* Content Table / Placeholder */}
            <div
                className="rounded-2xl border overflow-hidden"
                style={{
                    backgroundColor: isDarkMode ? `${theme.softTint}05` : "#fff",
                    borderColor: isDarkMode ? `${theme.softTint}10` : "#e2e8f0"
                }}
            >
                <div className="p-6 border-b" style={{ borderColor: isDarkMode ? `${theme.softTint}10` : "#e2e8f0" }}>
                    <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div className="flex items-center gap-2 p-1 rounded-lg bg-slate-100 dark:bg-slate-800/50 w-fit">
                            {["all", "published", "pending", "draft"].map((tab) => (
                                <button
                                    key={tab}
                                    onClick={() => setFilter(tab)}
                                    className={`px-4 py-1.5 rounded-md text-xs font-medium transition-all ${filter === tab
                                            ? "bg-white dark:bg-slate-700 shadow-sm text-indigo-600 dark:text-indigo-400"
                                            : "text-slate-500 hover:text-slate-700 dark:hover:text-slate-300"
                                        }`}
                                >
                                    {tab.charAt(0).toUpperCase() + tab.slice(1)}
                                </button>
                            ))}
                        </div>
                        <div className="relative">
                            <i className="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input
                                type="text"
                                placeholder="Cari berita..."
                                className="pl-10 pr-4 py-2 rounded-xl border bg-transparent text-sm w-full md:w-64 focus:outline-none focus:ring-2"
                                style={{
                                    borderColor: isDarkMode ? `${theme.softTint}20` : "#e2e8f0",
                                    color: isDarkMode ? "#fff" : "#1e293b"
                                }}
                            />
                        </div>
                    </div>
                </div>

                <div className="p-12 text-center">
                    <div
                        className="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl"
                        style={{ backgroundColor: isDarkMode ? `${theme.softTint}08` : "#f8fafc", color: isDarkMode ? `${theme.softTint}40` : "#cbd5e1" }}
                    >
                        <i className="fa-regular fa-newspaper"></i>
                    </div>
                    <h3 className="text-lg font-semibold mb-1" style={{ color: isDarkMode ? "#f1f5f9" : "#1e293b" }}>Belum ada berita</h3>
                    <p className="text-sm max-w-xs mx-auto mb-6" style={{ color: isDarkMode ? "#94a3b8" : "#64748b" }}>
                        Mulai tulis berita pertama Anda untuk menginformasikan pembaca setia Anda.
                    </p>
                    <button
                        className="text-white px-6 py-2 rounded-xl font-medium transition-transform active:scale-95"
                        style={{ background: theme.accent }}
                    >
                        Tulis Sekarang
                    </button>
                </div>
            </div>
        </div>
    );
}
