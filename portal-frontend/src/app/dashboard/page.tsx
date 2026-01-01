"use client";

import { useState, useEffect, useRef, useCallback } from "react";
import { useRouter } from "next/navigation";
import { getUser, logout, isLoggedIn, User } from "@/lib/auth";
import Sidebar from "@/components/layout/Sidebar";
import Header from "@/components/layout/Header";
import Toast, { ToastData } from "@/components/ui/Toast";
import StatCard from "@/components/ui/StatCard";
import { useTheme } from "@/contexts/ThemeContext";

// Declare SweetAlert2 type for TypeScript
declare const Swal: any;

// ===================== TYPES =====================
interface Notification {
    id: number;
    type: "success" | "error" | "warning" | "info";
    title: string;
    message: string;
    time: string;
    read: boolean;
}

// ===================== SECURITY LOG COMPONENT =====================
function SecurityLogItem({
    type,
    title,
    detail,
    time,
}: {
    type: "error" | "success" | "warning" | "info";
    title: string;
    detail: string;
    time: string;
}) {
    const { theme, isDarkMode } = useTheme();

    const colors = {
        error: "bg-red-500",
        success: "bg-green-500",
        warning: "bg-orange-500",
        info: theme.accent,
    };

    return (
        <div className={`flex gap-3 p-3 rounded-lg transition-colors border-b last:border-0 ${isDarkMode
            ? 'hover:bg-slate-700/50 border-slate-700'
            : 'hover:bg-slate-50 border-slate-50'
            }`}>
            <div className="mt-1">
                <div
                    className={`w-2 h-2 rounded-full ${type !== "info" ? colors[type] : ""}`}
                    style={type === "info" ? { backgroundColor: theme.accent } : undefined}
                ></div>
            </div>
            <div>
                <p className={`text-xs font-bold ${isDarkMode ? 'text-slate-200' : 'text-slate-700'}`}>{title}</p>
                <p className={`text-[10px] mt-0.5 ${isDarkMode ? 'text-slate-400' : 'text-slate-400'}`}>
                    {detail} • <span className={isDarkMode ? 'text-slate-300' : 'text-slate-500'}>{time}</span>
                </p>
            </div>
        </div>
    );
}

// ===================== MAIN DASHBOARD COMPONENT =====================
export default function DashboardPage() {
    const router = useRouter();
    const chartRef = useRef<HTMLCanvasElement>(null);
    const chartInstanceRef = useRef<any>(null);
    const { theme, settings, isDarkMode } = useTheme();

    // States
    const [user, setUserState] = useState<User | null>(null);
    const [sidebarOpen, setSidebarOpen] = useState(false);
    const [currentTime, setCurrentTime] = useState("");

    // Toast state
    const [toast, setToast] = useState<ToastData>({ show: false, message: "", type: "success" });

    // Notifications - Start with empty array
    const [notifications, setNotifications] = useState<Notification[]>([]);

    // Security Logs - Start with empty array
    const [securityLogs, setSecurityLogs] = useState<{ type: "error" | "success" | "warning" | "info"; title: string; detail: string; time: string }[]>([]);

    // Stats - Start with empty/zero values
    const [stats, setStats] = useState({
        totalReaders: "0",
        totalReaderChange: "Belum ada data",
        totalReaderChangeType: "neutral" as "up" | "down" | "neutral",
        totalArticles: "0",
        totalArticlesChange: "Belum ada data",
        blockedThreats: "0",
        blockedThreatsChange: "Belum ada data",
        newComments: "0",
        newCommentsChange: "Belum ada data",
    });

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

    // Auth check & Data Fetching
    useEffect(() => {
        if (!isLoggedIn()) {
            router.push("/");
            return;
        }
        const userData = getUser();
        if (userData) {
            setUserState(userData);
            fetchDashboardData();
        }
    }, [router]);

    const fetchDashboardData = async () => {
        const token = localStorage.getItem("auth_token");
        if (!token) return;

        try {
            const response = await fetch("http://localhost:8001/api/dashboard", {
                headers: {
                    "Authorization": `Bearer ${token}`,
                    "Accept": "application/json"
                }
            });

            if (response.ok) {
                const data = await response.json();
                if (data.success && data.data) {
                    const apiStats = data.data.stats;
                    setStats({
                        totalReaders: apiStats.total_readers.toString(),
                        totalReaderChange: apiStats.total_reader_change,
                        totalReaderChangeType: apiStats.total_reader_change_type,
                        totalArticles: apiStats.total_articles.toString(),
                        totalArticlesChange: apiStats.total_articles_change,
                        blockedThreats: apiStats.blocked_threats.toString(),
                        blockedThreatsChange: apiStats.blocked_threats_change,
                        newComments: apiStats.new_comments.toString(),
                        newCommentsChange: apiStats.new_comments_change,
                    });

                    if (data.data.security_logs) {
                        setSecurityLogs(data.data.security_logs);
                    }
                }
            }
        } catch (error) {
            console.error("Failed to fetch dashboard data:", error);
        }
    };

    // Time updater
    useEffect(() => {
        const updateTime = () => {
            setCurrentTime(
                new Date().toLocaleString("id-ID", {
                    weekday: "long",
                    year: "numeric",
                    month: "long",
                    day: "numeric",
                    hour: "2-digit",
                    minute: "2-digit",
                })
            );
        };
        updateTime();
        const interval = setInterval(updateTime, 1000);
        return () => clearInterval(interval);
    }, []);

    // Chart initialization with theme colors
    useEffect(() => {
        if (!chartRef.current || !theme) return;

        const loadChart = async () => {
            const Chart = (await import("chart.js/auto")).default;

            if (chartInstanceRef.current) {
                chartInstanceRef.current.destroy();
            }

            const ctx = chartRef.current?.getContext("2d");
            if (!ctx) return;

            // Create gradient for chart
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, `${theme.accent}30`);
            gradient.addColorStop(1, `${theme.accent}05`);

            // Empty chart data
            chartInstanceRef.current = new Chart(ctx, {
                type: "line",
                data: {
                    labels: ["Sen", "Sel", "Rab", "Kam", "Jum", "Sab", "Min"],
                    datasets: [
                        {
                            label: "Traffic (Views)",
                            data: [0, 0, 0, 0, 0, 0, 0],
                            borderColor: theme.accent,
                            backgroundColor: gradient,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: theme.accent,
                            pointBorderColor: "#fff",
                            pointBorderWidth: 2,
                            pointRadius: 4,
                        },
                        {
                            label: "Percobaan Serangan (Blocked)",
                            data: [0, 0, 0, 0, 0, 0, 0],
                            borderColor: "#dc2626",
                            backgroundColor: "transparent",
                            borderDash: [5, 5],
                            tension: 0.4,
                            pointBackgroundColor: "#dc2626",
                            pointBorderColor: "#fff",
                            pointBorderWidth: 2,
                            pointRadius: 4,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: "top",
                            labels: { usePointStyle: true },
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: "rgba(0,0,0,0.05)" },
                        },
                        x: {
                            grid: { display: false },
                        },
                    },
                },
            });
        };

        loadChart();

        return () => {
            if (chartInstanceRef.current) {
                chartInstanceRef.current.destroy();
            }
        };
    }, [user, theme]);

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

    // Loading state
    if (!user) {
        return (
            <div className={`min-h-screen flex items-center justify-center transition-colors duration-500 ${isDarkMode ? 'bg-slate-900' : 'bg-slate-50'
                }`}>
                <div className="flex flex-col items-center gap-4">
                    <div
                        className="animate-spin h-10 w-10 border-4 border-t-transparent rounded-full spinner-themed"
                        style={{ borderColor: theme.accent, borderTopColor: "transparent" }}
                    ></div>
                    <p className={`text-sm ${isDarkMode ? 'text-slate-400' : 'text-slate-500'}`}>Memuat dashboard...</p>
                </div>
            </div>
        );
    }

    return (
        <div className={`font-[family-name:var(--font-inter)] antialiased transition-colors duration-500 ${isDarkMode ? 'bg-slate-900 text-slate-200' : 'bg-slate-50 text-slate-800'
            }`}>
            <div className="flex h-screen overflow-hidden">
                {/* Sidebar Component */}
                <Sidebar
                    user={user}
                    sidebarOpen={sidebarOpen}
                    onClose={() => setSidebarOpen(false)}
                    onLogout={handleLogout}
                />

                {/* Main Content */}
                <main className={`flex-1 flex flex-col h-screen overflow-hidden relative main-content transition-colors duration-500 ${isDarkMode ? 'bg-slate-900' : 'bg-slate-50'
                    }`}>
                    {/* Header Component */}
                    <Header
                        user={user}
                        notifications={notifications}
                        onLogout={handleLogout}
                        onMarkAllRead={handleMarkAllRead}
                        onClearNotifications={handleClearNotifications}
                        onToggleSidebar={() => setSidebarOpen(!sidebarOpen)}
                    />

                    {/* Scrollable Content */}
                    <div className="flex-1 overflow-y-auto p-6 lg:p-8">
                        {/* Page Title */}
                        <div className="mb-8 flex items-center justify-between">
                            <div>
                                <h1 className={`text-2xl font-bold font-[family-name:var(--font-merriweather)] ${isDarkMode ? 'text-slate-100' : 'text-slate-800'
                                    }`}>
                                    Dashboard Overview
                                </h1>
                                <p className={`text-sm mt-1 ${isDarkMode ? 'text-slate-400' : 'text-slate-500'}`}>
                                    Pantau performa konten dan status keamanan sistem hari ini.
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

                        {/* Stats Grid */}
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                            <StatCard
                                title="Total Pembaca"
                                value={stats.totalReaders}
                                change={stats.totalReaderChange}
                                changeType={stats.totalReaderChangeType}
                                icon="fa-regular fa-eye"
                                iconBg={`${theme.accent}15`}
                                iconColor={theme.accent}
                            />
                            <StatCard
                                title="Artikel Terbit"
                                value={stats.totalArticles}
                                change={stats.totalArticlesChange}
                                changeType="neutral"
                                icon="fa-regular fa-file-lines"
                                iconBg={`${theme.gradientVia || theme.gradientFrom}15`}
                                iconColor={theme.gradientVia || theme.gradientFrom}
                            />
                            <StatCard
                                title="Ancaman Diblokir"
                                value={stats.blockedThreats}
                                change={stats.blockedThreatsChange}
                                changeType="neutral"
                                icon="fa-solid fa-ban"
                                iconBg="rgba(239, 68, 68, 0.1)"
                                iconColor="#dc2626"
                                highlight
                            />
                            <StatCard
                                title="Komentar Baru"
                                value={stats.newComments}
                                change={stats.newCommentsChange}
                                changeType="neutral"
                                icon="fa-regular fa-comments"
                                iconBg={`${theme.gradientTo}15`}
                                iconColor={theme.gradientTo}
                            />
                        </div>

                        {/* Charts & Logs Grid */}
                        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            {/* Traffic Chart */}
                            <div className={`p-6 rounded-xl shadow-sm border lg:col-span-2 stat-card-themed card transition-colors duration-500 ${isDarkMode
                                ? 'bg-slate-800 border-slate-700'
                                : 'bg-white border-slate-100'
                                }`}>
                                <div className="flex items-center justify-between mb-6">
                                    <h3 className={`font-bold ${isDarkMode ? 'text-slate-100' : 'text-slate-800'}`}>Analitik Trafik vs Serangan</h3>
                                    <select
                                        className={`text-xs rounded-md px-3 py-1.5 input-themed ${isDarkMode
                                            ? 'bg-slate-700 border-slate-600 text-slate-300'
                                            : 'border-slate-200 text-slate-500'
                                            }`}
                                    >
                                        <option>7 Hari Terakhir</option>
                                        <option>Bulan Ini</option>
                                    </select>
                                </div>
                                <div className="relative h-72 w-full">
                                    <canvas ref={chartRef}></canvas>
                                </div>
                            </div>

                            {/* Security Logs */}
                            <div className={`p-0 rounded-xl shadow-sm border overflow-hidden flex flex-col card transition-colors duration-500 ${isDarkMode
                                ? 'bg-slate-800 border-slate-700'
                                : 'bg-white border-slate-100'
                                }`}>
                                <div
                                    className="p-5 border-b flex justify-between items-center theme-gradient"
                                    style={{ borderColor: isDarkMode ? '#334155' : '#f1f5f9' }}
                                >
                                    <h3 className="font-bold text-white text-sm">Log Keamanan Terbaru</h3>
                                    <a
                                        href="#"
                                        className="text-xs text-white/80 hover:text-white hover:underline"
                                    >
                                        Lihat Semua
                                    </a>
                                </div>
                                <div className="flex-1 overflow-y-auto max-h-[300px] p-2">
                                    {securityLogs.length === 0 ? (
                                        <div className="flex flex-col items-center justify-center py-12 text-center">
                                            <i
                                                className="fa-solid fa-shield-check text-4xl mb-3"
                                                style={{ color: `${theme.accent}40` }}
                                            ></i>
                                            <p className={`text-sm ${isDarkMode ? 'text-slate-400' : 'text-slate-400'}`}>Belum ada log keamanan</p>
                                            <p className={`text-xs mt-1 ${isDarkMode ? 'text-slate-500' : 'text-slate-300'}`}>Aktivitas akan muncul di sini</p>
                                        </div>
                                    ) : (
                                        securityLogs.map((log, index) => (
                                            <SecurityLogItem key={index} {...log} />
                                        ))
                                    )}
                                </div>
                            </div>
                        </div>

                        {/* Footer */}
                        <div className={`mt-12 border-t pt-6 flex flex-col md:flex-row justify-between items-center text-xs ${isDarkMode ? 'border-slate-700 text-slate-500' : 'border-slate-200 text-slate-400'
                            }`}>
                            <p>&copy; 2025 {settings.site_name || "Portal News Redaksi"}. All rights reserved.</p>
                            <div className="flex gap-4 mt-2 md:mt-0">
                                <span>Laravel v12</span>
                                <span>Next.js v15</span>
                                <span
                                    className="flex items-center gap-1"
                                    style={{ color: theme.accent }}
                                >
                                    <i className="fa-solid fa-shield-check"></i>
                                    Security Enabled
                                </span>
                            </div>
                        </div>
                    </div>
                </main>
            </div>

            {/* Toast */}
            <Toast data={toast} onClose={() => setToast(prev => ({ ...prev, show: false }))} />

            {/* SweetAlert2 is loaded via CDN and called via showConfirm function */}
        </div>
    );
}
