"use client";

import React, { useState } from "react";
import { useTheme } from "@/contexts/ThemeContext";

export default function CategoriesPage() {
    const { theme, isDarkMode } = useTheme();

    const initialCategories = [
        { id: 1, name: "Nasional", slug: "nasional", count: 425 },
        { id: 2, name: "Politik", slug: "politik", count: 218 },
        { id: 3, name: "Olahraga", slug: "olahraga", count: 184 },
        { id: 4, name: "Tekno", slug: "tekno", count: 156 },
    ];

    return (
        <div className="space-y-6 animate-in fade-in duration-700">
            {/* Header Section */}
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 className="text-2xl font-bold tracking-tight" style={{ color: isDarkMode ? "#fff" : "#1e293b" }}>
                        Kategori & Kanal
                    </h1>
                    <p className="text-sm" style={{ color: isDarkMode ? "#94a3b8" : "#64748b" }}>
                        Atur struktur kanal berita untuk navigasi pembaca.
                    </p>
                </div>
                <button
                    className="flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl text-white font-semibold transition-all hover:scale-[1.02] active:scale-[0.98] shadow-lg shadow-indigo-500/20"
                    style={{ background: `linear-gradient(135deg, ${theme.gradientFrom}, ${theme.gradientTo})` }}
                >
                    <i className="fa-solid fa-folder-plus"></i>
                    Tambah Kategori
                </button>
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {/* Form Section */}
                <div
                    className="lg:col-span-1 p-6 rounded-2xl border h-fit sticky top-24"
                    style={{
                        backgroundColor: isDarkMode ? `${theme.softTint}08` : "#fff",
                        borderColor: isDarkMode ? `${theme.softTint}15` : "#e2e8f0"
                    }}
                >
                    <h2 className="text-lg font-bold mb-4" style={{ color: isDarkMode ? "#f1f5f9" : "#1e293b" }}>Tambah Baru</h2>
                    <div className="space-y-4">
                        <div>
                            <label className="block text-xs font-semibold uppercase tracking-wider mb-1.5" style={{ color: isDarkMode ? "#64748b" : "#94a3b8" }}>Nama Kategori</label>
                            <input
                                type="text"
                                className="w-full px-4 py-2.5 rounded-xl border bg-transparent text-sm focus:outline-none focus:ring-2"
                                style={{
                                    borderColor: isDarkMode ? `${theme.softTint}20` : "#e2e8f0",
                                    color: isDarkMode ? "#fff" : "#1e293b"
                                }}
                                placeholder="Contoh: Nasional"
                            />
                        </div>
                        <div>
                            <label className="block text-xs font-semibold uppercase tracking-wider mb-1.5" style={{ color: isDarkMode ? "#64748b" : "#94a3b8" }}>Slug</label>
                            <input
                                type="text"
                                className="w-full px-4 py-2.5 rounded-xl border bg-transparent text-sm focus:outline-none focus:ring-2"
                                style={{
                                    borderColor: isDarkMode ? `${theme.softTint}20` : "#e2e8f0",
                                    color: isDarkMode ? "#fff" : "#1e293b"
                                }}
                                placeholder="nasional"
                            />
                        </div>
                        <div>
                            <label className="block text-xs font-semibold uppercase tracking-wider mb-1.5" style={{ color: isDarkMode ? "#64748b" : "#94a3b8" }}>Deskripsi</label>
                            <textarea
                                className="w-full px-4 py-2.5 rounded-xl border bg-transparent text-sm focus:outline-none focus:ring-2 min-h-[100px]"
                                style={{
                                    borderColor: isDarkMode ? `${theme.softTint}20` : "#e2e8f0",
                                    color: isDarkMode ? "#fff" : "#1e293b"
                                }}
                                placeholder="Jelaskan konten dalam kategori ini..."
                            />
                        </div>
                        <button
                            className="w-full py-3 rounded-xl text-white font-bold transition-transform active:scale-95"
                            style={{ background: theme.accent }}
                        >
                            Simpan Kategori
                        </button>
                    </div>
                </div>

                {/* List Section */}
                <div className="lg:col-span-2 space-y-4">
                    {initialCategories.map((cat) => (
                        <div
                            key={cat.id}
                            className="p-4 rounded-2xl border flex items-center justify-between group transition-all hover:translate-x-1"
                            style={{
                                backgroundColor: isDarkMode ? `${theme.softTint}05` : "#fff",
                                borderColor: isDarkMode ? `${theme.softTint}10` : "#e2e8f0"
                            }}
                        >
                            <div className="flex items-center gap-4">
                                <div
                                    className="w-10 h-10 rounded-xl flex items-center justify-center text-lg"
                                    style={{ backgroundColor: `${theme.accent}15`, color: theme.accent }}
                                >
                                    <i className="fa-solid fa-folder"></i>
                                </div>
                                <div>
                                    <h3 className="font-bold" style={{ color: isDarkMode ? "#f1f5f9" : "#1e293b" }}>{cat.name}</h3>
                                    <p className="text-xs" style={{ color: isDarkMode ? "#64748b" : "#94a3b8" }}>/{cat.slug} • {cat.count} Artikel</p>
                                </div>
                            </div>
                            <div className="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button className="p-2 rounded-lg hover:bg-indigo-500/10 text-indigo-500 transition-colors">
                                    <i className="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button className="p-2 rounded-lg hover:bg-red-500/10 text-red-500 transition-colors">
                                    <i className="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
}
