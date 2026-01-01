"use client";

import { useState, useEffect, useRef } from "react";
import { useRouter } from "next/navigation";
import { getUser, isLoggedIn, User } from "@/lib/auth";
import { useTheme } from "@/contexts/ThemeContext";

// ===================== COLORFUL STAT CARD =====================
function ColorfulStatCard({
    title,
    value,
    change,
    changeType,
    icon,
    variant = "primary", // primary, accent, success, warning
    delay = 0
}: {
    title: string;
    value: string;
    change: string;
    changeType: "up" | "down" | "neutral";
    icon: string;
    variant?: "primary" | "accent" | "success" | "warning" | "info" | "danger";
    delay?: number;
}) {
    const { theme, isDarkMode } = useTheme();

    // Mapping variants to theme colors
    const getColors = () => {
        switch (variant) {
            case "accent":
                return {
                    bg: theme.accent,
                    gradient: `linear-gradient(135deg, ${theme.accent}, ${theme.gradientTo})`,
                    text: "#ffffff",
                    subtext: "rgba(255,255,255,0.8)",
                    iconBg: "rgba(255,255,255,0.2)"
                };
            case "success":
                return {
                    bg: "#10b981",
                    gradient: `linear-gradient(135deg, #10b981, #34d399)`,
                    text: "#ffffff",
                    subtext: "rgba(255,255,255,0.8)",
                    iconBg: "rgba(255,255,255,0.2)"
                };
            case "warning":
                return {
                    bg: "#f59e0b",
                    gradient: `linear-gradient(135deg, #f59e0b, #fbbf24)`,
                    text: "#ffffff",
                    subtext: "rgba(255,255,255,0.8)",
                    iconBg: "rgba(255,255,255,0.2)"
                };
            case "danger":
                return {
                    bg: "#ef4444",
                    gradient: `linear-gradient(135deg, #ef4444, #f87171)`,
                    text: "#ffffff",
                    subtext: "rgba(255,255,255,0.8)",
                    iconBg: "rgba(255,255,255,0.2)"
                };
            case "info":
                return {
                    bg: "#3b82f6",
                    gradient: `linear-gradient(135deg, #3b82f6, #60a5fa)`,
                    text: "#ffffff",
                    subtext: "rgba(255,255,255,0.8)",
                    iconBg: "rgba(255,255,255,0.2)"
                };
            case "primary":
            default:
                return {
                    bg: theme.gradientFrom,
                    gradient: `linear-gradient(135deg, ${theme.gradientFrom}, ${theme.gradientVia || theme.gradientFrom})`,
                    text: "#ffffff",
                    subtext: "rgba(255,255,255,0.8)",
                    iconBg: "rgba(255,255,255,0.2)"
                };
        }
    };

    const colors = getColors();

    return (
        <div
            className="rounded-2xl p-6 relative overflow-hidden shadow-lg transition-transform duration-300 hover:-translate-y-1 hover:shadow-xl"
            style={{
                background: colors.gradient,
                animation: `fadeInUp 0.5s ease-out ${delay}s backwards`
            }}
        >
            {/* Decorative circles */}
            <div className="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-white opacity-10 blur-xl"></div>
            <div className="absolute -left-6 -bottom-6 w-20 h-20 rounded-full bg-black opacity-5 blur-xl"></div>

            <div className="relative z-10 flex justify-between items-start">
                <div>
                    <p className="text-xs font-bold uppercase tracking-wider mb-1" style={{ color: colors.subtext }}>
                        {title}
                    </p>
                    <h3 className="text-3xl font-bold mb-2" style={{ color: colors.text }}>
                        {value}
                    </h3>
                    <div className="flex items-center gap-1.5 text-xs font-medium bg-black/10 w-fit px-2 py-1 rounded-full backdrop-blur-sm" style={{ color: colors.text }}>
                        {changeType === "up" && <i className="fa-solid fa-arrow-trend-up"></i>}
                        {changeType === "down" && <i className="fa-solid fa-arrow-trend-down"></i>}
                        <span>{change}</span>
                    </div>
                </div>
                <div
                    className="w-12 h-12 rounded-xl flex items-center justify-center text-xl shadow-inner backdrop-blur-md"
                    style={{
                        backgroundColor: colors.iconBg,
                        color: colors.text
                    }}
                >
                    <i className={icon}></i>
                </div>
            </div>
        </div>
    );
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

    const getIcon = () => {
        switch (type) {
            case "error": return "fa-triangle-exclamation";
            case "success": return "fa-check-circle";
            case "warning": return "fa-bell";
            case "info": return "fa-circle-info";
        }
    };

    const getColor = () => {
        switch (type) {
            case "error": return "#ef4444";
            case "success": return "#10b981";
            case "warning": return "#f59e0b";
            case "info": return theme.accent;
        }
    };

    const color = getColor();

    return (
        <div className={`flex gap-4 p-4 rounded-xl mb-3 transition-all border-l-4 ${isDarkMode
            ? 'bg-slate-800/50 hover:bg-slate-800 border-l-transparent'
            : 'bg-white/60 hover:bg-white border-l-transparent'
            }`}
            style={{ borderLeftColor: color }}
        >
            <div
                className="w-10 h-10 rounded-full flex flex-shrink-0 items-center justify-center text-lg shadow-sm"
                style={{ backgroundColor: `${color}20`, color: color }}
            >
                <i className={`fa-solid ${getIcon()}`}></i>
            </div>
            <div className="flex-1 min-w-0">
                <div className="flex justify-between items-start">
                    <h4 className={`text-sm font-bold truncate ${isDarkMode ? 'text-slate-200' : 'text-slate-800'}`}>
                        {title}
                    </h4>
                    <span className={`text-[10px] whitespace-nowrap px-2 py-0.5 rounded-full ${isDarkMode ? 'bg-slate-700 text-slate-400' : 'bg-slate-100 text-slate-500'}`}>
                        {time}
                    </span>
                </div>
                <p className={`text-xs mt-1 truncate ${isDarkMode ? 'text-slate-400' : 'text-slate-500'}`}>
                    {detail}
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
    const [currentTime, setCurrentTime] = useState("");

    // Security Logs
    const [securityLogs, setSecurityLogs] = useState<{ type: "error" | "success" | "warning" | "info"; title: string; detail: string; time: string }[]>([]);

    // Stats
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
            const response = await fetch("http://localhost:8000/api/dashboard", {
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
            const now = new Date();
            const timeString = now.toLocaleTimeString("id-ID", { hour: '2-digit', minute: '2-digit' });
            const dateString = now.toLocaleDateString("id-ID", { weekday: "long", year: "numeric", month: "long", day: "numeric" });
            setCurrentTime(`${dateString} • ${timeString}`);
        };
        updateTime();
        const interval = setInterval(updateTime, 1000);
        return () => clearInterval(interval);
    }, []);

    // Chart initialization
    useEffect(() => {
        if (!chartRef.current || !theme) return;

        const loadChart = async () => {
            const Chart = (await import("chart.js/auto")).default;

            if (chartInstanceRef.current) {
                chartInstanceRef.current.destroy();
            }

            const ctx = chartRef.current?.getContext("2d");
            if (!ctx) return;

            // Gradient for fill
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, `${theme.accent}50`);
            gradient.addColorStop(1, `${theme.accent}00`);

            chartInstanceRef.current = new Chart(ctx, {
                type: "line",
                data: {
                    labels: ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"],
                    datasets: [
                        {
                            label: "Traffic",
                            data: [65, 59, 80, 81, 56, 55, 90], // Demo data if 0
                            borderColor: theme.accent,
                            backgroundColor: gradient,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: theme.accent,
                            pointBorderColor: "#fff",
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8
                        },
                        {
                            label: "Threats",
                            data: [5, 2, 8, 1, 4, 2, 1], // Demo data
                            borderColor: "#ef4444",
                            backgroundColor: "transparent",
                            borderDash: [5, 5],
                            tension: 0.4,
                            pointBackgroundColor: "#ef4444",
                            pointBorderColor: "#fff",
                            pointBorderWidth: 2,
                            pointRadius: 4,
                        }
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: isDarkMode ? 'rgba(15, 23, 42, 0.9)' : 'rgba(255, 255, 255, 0.9)',
                            titleColor: isDarkMode ? '#fff' : '#0f172a',
                            bodyColor: isDarkMode ? '#cbd5e1' : '#475569',
                            borderColor: theme.accent,
                            borderWidth: 1,
                            padding: 10,
                            displayColors: true,
                            usePointStyle: true,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: isDarkMode ? "rgba(255,255,255,0.05)" : "rgba(0,0,0,0.05)" },
                            ticks: { color: isDarkMode ? "#94a3b8" : "#64748b" }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: isDarkMode ? "#94a3b8" : "#64748b" }
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
    }, [user, theme, isDarkMode]);

    if (!user) return null;

    return (
        <div className="animate-fade-in mb-10">
            {/* HERRO BANNER SECTION */}
            <div
                className="relative rounded-3xl p-8 lg:p-10 mb-10 overflow-hidden shadow-2xl"
                style={{
                    background: `linear-gradient(135deg, ${theme.primary} 0%, ${theme.sidebar} 100%)`
                }}
            >
                {/* Abstract Background Shapes */}
                <div className="absolute top-0 right-0 w-[500px] h-[500px] rounded-full mix-blend-overlay filter blur-3xl opacity-20 -translate-y-1/2 translate-x-1/3"
                    style={{ background: theme.gradientFrom }}></div>
                <div className="absolute bottom-0 left-0 w-[400px] h-[400px] rounded-full mix-blend-overlay filter blur-3xl opacity-20 translate-y-1/3 -translate-x-1/3"
                    style={{ background: theme.accent }}></div>

                {/* Content */}
                <div className="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-6 text-white">
                    <div>
                        <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-xs font-semibold mb-3">
                            <span className="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                            System Operational
                        </div>
                        <h1 className="text-3xl md:text-4xl font-bold font-[family-name:var(--font-merriweather)] mb-2 leading-tight">
                            Selamat Datang, {user.name.split(' ')[0]}!
                        </h1>
                        <p className="text-white/70 max-w-xl text-sm md:text-base">
                            Pantau performa portal berita Anda, cek statistik pembaca, dan kelola keamanan sistem dalam satu tampilan terpadu.
                        </p>
                    </div>
                    <div className="text-right hidden md:block">
                        <p className="text-white/60 text-xs uppercase tracking-widest font-bold mb-1">Last Updated</p>
                        <p className="text-xl font-medium font-mono">{currentTime}</p>
                    </div>
                </div>
            </div>

            {/* STATS GRID */}
            <h2 className={`text-lg font-bold mb-5 flex items-center gap-2 ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>
                <i className="fa-solid fa-chart-pie text-sm" style={{ color: theme.accent }}></i>
                Statistik Hari Ini
            </h2>
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <ColorfulStatCard
                    title="Total Pembaca"
                    value={stats.totalReaders}
                    change={stats.totalReaderChange}
                    changeType={stats.totalReaderChangeType}
                    icon="fa-regular fa-eye"
                    variant="primary"
                    delay={0.1}
                />
                <ColorfulStatCard
                    title="Artikel Terbit"
                    value={stats.totalArticles}
                    change={stats.totalArticlesChange}
                    changeType="neutral"
                    icon="fa-regular fa-file-lines"
                    variant="accent"
                    delay={0.2}
                />
                <ColorfulStatCard
                    title="Ancaman Diblokir"
                    value={stats.blockedThreats}
                    change={stats.blockedThreatsChange}
                    changeType="neutral"
                    icon="fa-solid fa-shield-virus"
                    variant="danger"
                    delay={0.3}
                />
                <ColorfulStatCard
                    title="Komentar Baru"
                    value={stats.newComments}
                    change={stats.newCommentsChange}
                    changeType="neutral"
                    icon="fa-regular fa-comments"
                    variant="info"
                    delay={0.4}
                />
            </div>

            {/* CHARTS & LOGS GRID */}
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {/* Traffic Chart */}
                <div className={`lg:col-span-2 rounded-2xl shadow-sm border p-1 transition-colors ${isDarkMode ? 'bg-slate-800 border-slate-700' : 'bg-white border-slate-100'
                    }`}>
                    <div className={`h-full rounded-xl p-6 ${isDarkMode ? 'bg-slate-900/50' : 'bg-slate-50/50'}`}>
                        <div className="flex items-center justify-between mb-8">
                            <div>
                                <h3 className={`font-bold text-lg ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>
                                    Analitik Trafik
                                </h3>
                                <p className={`text-xs ${isDarkMode ? 'text-slate-400' : 'text-slate-500'}`}>
                                    Overview pengunjung vs serangan
                                </p>
                            </div>
                            <div className="flex gap-2">
                                <span className="flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-lg border border-transparent"
                                    style={{ backgroundColor: `${theme.accent}15`, color: theme.accent }}>
                                    <span className="w-2 h-2 rounded-full" style={{ backgroundColor: theme.accent }}></span>
                                    Views
                                </span>
                                <span className="flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-lg border border-transparent bg-red-500/10 text-red-500">
                                    <span className="w-2 h-2 rounded-full bg-red-500"></span>
                                    Attacks
                                </span>
                            </div>
                        </div>
                        <div className="relative h-80 w-full">
                            <canvas ref={chartRef}></canvas>
                        </div>
                    </div>
                </div>

                {/* Security Logs */}
                <div className={`rounded-2xl shadow-sm border overflow-hidden flex flex-col h-[500px] lg:h-auto ${isDarkMode ? 'bg-slate-800 border-slate-700' : 'bg-white border-slate-100'
                    }`}>
                    <div className="p-6 pb-4 border-b border-dashed" style={{ borderColor: isDarkMode ? '#334155' : '#e2e8f0' }}>
                        <div className="flex justify-between items-center mb-2">
                            <h3 className={`font-bold text-lg ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>
                                Activity Log
                            </h3>
                            <button className={`w-8 h-8 rounded-full flex items-center justify-center transition-colors ${isDarkMode ? 'hover:bg-slate-700 text-slate-400' : 'hover:bg-slate-100 text-slate-500'
                                }`}>
                                <i className="fa-solid fa-ellipsis"></i>
                            </button>
                        </div>
                        <p className={`text-xs ${isDarkMode ? 'text-slate-400' : 'text-slate-500'}`}>
                            Pantauan aktivitas keamanan realtime
                        </p>
                    </div>

                    <div className="flex-1 overflow-y-auto p-4 custom-scrollbar">
                        {securityLogs.length === 0 ? (
                            <div className="flex flex-col items-center justify-center h-full text-center p-6">
                                <div className="w-16 h-16 rounded-full flex items-center justify-center mb-4"
                                    style={{ backgroundColor: `${theme.accent}10` }}>
                                    <i className="fa-solid fa-shield-cat text-2xl" style={{ color: theme.accent }}></i>
                                </div>
                                <h4 className={`font-medium text-sm ${isDarkMode ? 'text-slate-300' : 'text-slate-700'}`}>Aman Terkendali</h4>
                                <p className={`text-xs mt-1 max-w-[200px] ${isDarkMode ? 'text-slate-500' : 'text-slate-400'}`}>
                                    Belum ada log keamanan mencurigakan yang terdeteksi hari ini.
                                </p>
                            </div>
                        ) : (
                            <div className="space-y-1">
                                {securityLogs.map((log, index) => (
                                    <SecurityLogItem key={index} {...log} />
                                ))}
                            </div>
                        )}
                    </div>
                    <div className={`p-4 border-t ${isDarkMode ? 'border-slate-700 bg-slate-800' : 'border-slate-100 bg-slate-50'}`}>
                        <button className={`w-full py-2.5 rounded-xl text-xs font-semibold uppercase tracking-wider transition-all duration-300 ${isDarkMode ? 'bg-slate-700 hover:bg-slate-600 text-white' : 'bg-white hover:bg-gray-50 text-slate-700 border border-slate-200'
                            }`}>
                            View All Logs
                        </button>
                    </div>
                </div>
            </div>

            <style jsx global>{`
                @keyframes fadeInUp {
                    from { opacity: 0; transform: translateY(20px); }
                    to { opacity: 1; transform: translateY(0); }
                }
                .custom-scrollbar::-webkit-scrollbar {
                    width: 4px;
                }
                .custom-scrollbar::-webkit-scrollbar-track {
                    background: transparent;
                }
                .custom-scrollbar::-webkit-scrollbar-thumb {
                    background: ${theme.softTint}50;
                    border-radius: 4px;
                }
            `}</style>
        </div>
    );
}
