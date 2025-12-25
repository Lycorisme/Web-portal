"use client";

import { useState, useEffect, useRef, useCallback } from "react";
import { useRouter } from "next/navigation";
import { getUser, logout, isLoggedIn, User } from "@/lib/auth";

// ===================== TYPES =====================
interface Notification {
    id: number;
    type: "success" | "error" | "warning" | "info";
    title: string;
    message: string;
    time: string;
    read: boolean;
}

interface ToastData {
    show: boolean;
    message: string;
    type: "success" | "error" | "warning" | "info";
}

// ===================== HELPER FUNCTIONS =====================
function formatRole(role: string): string {
    const roleMap: Record<string, string> = {
        super_admin: "Super Admin",
        admin: "Admin",
        editor: "Editor",
    };
    return roleMap[role] || role;
}

// ===================== TOAST COMPONENT =====================
function Toast({ data, onClose }: { data: ToastData; onClose: () => void }) {
    if (!data.show) return null;

    const config = {
        success: { bg: "bg-gradient-to-r from-emerald-500 to-green-600", icon: "fa-check-circle" },
        error: { bg: "bg-gradient-to-r from-red-500 to-rose-600", icon: "fa-times-circle" },
        warning: { bg: "bg-gradient-to-r from-amber-500 to-orange-600", icon: "fa-exclamation-triangle" },
        info: { bg: "bg-gradient-to-r from-blue-500 to-indigo-600", icon: "fa-info-circle" },
    };

    const { bg, icon } = config[data.type];

    return (
        <div className="fixed bottom-6 right-6 z-[100] animate-slide-in-right">
            <div
                className={`${bg} text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-4 min-w-[320px] backdrop-blur-sm border border-white/20`}
            >
                <div className="flex-shrink-0 w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                    <i className={`fa-solid ${icon} text-xl`}></i>
                </div>
                <div className="flex-1">
                    <p className="font-semibold text-sm uppercase tracking-wide opacity-90">
                        {data.type === "success" && "Berhasil"}
                        {data.type === "error" && "Error"}
                        {data.type === "warning" && "Peringatan"}
                        {data.type === "info" && "Info"}
                    </p>
                    <p className="text-white/90 text-sm">{data.message}</p>
                </div>
                <button
                    onClick={onClose}
                    className="hover:bg-white/20 p-2 rounded-full transition-all duration-150"
                >
                    <i className="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>
    );
}

// ===================== ALERT DIALOG COMPONENT =====================
function AlertDialog({
    show,
    type,
    title,
    message,
    onConfirm,
    onCancel,
}: {
    show: boolean;
    type: "warning" | "danger" | "info";
    title: string;
    message: string;
    onConfirm: () => void;
    onCancel: () => void;
}) {
    if (!show) return null;

    const config = {
        warning: { icon: "fa-exclamation-triangle", iconBg: "bg-amber-100", iconColor: "text-amber-600", confirmBg: "bg-amber-600 hover:bg-amber-700" },
        danger: { icon: "fa-trash-alt", iconBg: "bg-red-100", iconColor: "text-red-600", confirmBg: "bg-red-600 hover:bg-red-700" },
        info: { icon: "fa-info-circle", iconBg: "bg-blue-100", iconColor: "text-blue-600", confirmBg: "bg-blue-600 hover:bg-blue-700" },
    };

    const { icon, iconBg, iconColor, confirmBg } = config[type];

    return (
        <div className="fixed inset-0 z-[100] flex items-center justify-center">
            <div
                className="absolute inset-0 bg-black/50 backdrop-blur-sm animate-fade-in"
                onClick={onCancel}
            ></div>
            <div className="relative bg-white rounded-2xl shadow-2xl p-6 max-w-md w-full mx-4 animate-scale-in">
                <div className="flex flex-col items-center text-center">
                    <div className={`w-16 h-16 ${iconBg} rounded-full flex items-center justify-center mb-4`}>
                        <i className={`fa-solid ${icon} text-2xl ${iconColor}`}></i>
                    </div>
                    <h3 className="text-xl font-bold text-slate-800 mb-2">{title}</h3>
                    <p className="text-slate-500 mb-6">{message}</p>
                    <div className="flex gap-3 w-full">
                        <button
                            onClick={onCancel}
                            className="flex-1 px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-medium transition-all duration-150"
                        >
                            Batal
                        </button>
                        <button
                            onClick={onConfirm}
                            className={`flex-1 px-4 py-3 ${confirmBg} text-white rounded-xl font-medium transition-all duration-150`}
                        >
                            Konfirmasi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    );
}

// ===================== NOTIFICATION PANEL COMPONENT =====================
function NotificationPanel({
    show,
    notifications,
    onClose,
    onMarkAllRead,
    onClearAll,
}: {
    show: boolean;
    notifications: Notification[];
    onClose: () => void;
    onMarkAllRead: () => void;
    onClearAll: () => void;
}) {
    if (!show) return null;

    const getNotifIcon = (type: string) => {
        const icons: Record<string, { icon: string; color: string }> = {
            success: { icon: "fa-check-circle", color: "text-green-500" },
            error: { icon: "fa-times-circle", color: "text-red-500" },
            warning: { icon: "fa-exclamation-triangle", color: "text-amber-500" },
            info: { icon: "fa-info-circle", color: "text-blue-500" },
        };
        return icons[type] || icons.info;
    };

    return (
        <>
            <div
                className="fixed inset-0 z-40"
                onClick={onClose}
            ></div>
            <div className="absolute right-0 mt-3 w-96 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden z-50 animate-slide-down">
                <div className="bg-gradient-to-r from-slate-800 to-slate-900 px-5 py-4 flex items-center justify-between">
                    <div>
                        <h3 className="text-white font-bold">Notifikasi</h3>
                        <p className="text-slate-400 text-xs">
                            {notifications.filter(n => !n.read).length} belum dibaca
                        </p>
                    </div>
                    <div className="flex gap-2">
                        <button
                            onClick={onMarkAllRead}
                            className="text-xs text-slate-400 hover:text-white transition-colors px-2 py-1 hover:bg-white/10 rounded"
                        >
                            Tandai dibaca
                        </button>
                    </div>
                </div>

                <div className="max-h-80 overflow-y-auto">
                    {notifications.length === 0 ? (
                        <div className="py-12 text-center">
                            <i className="fa-regular fa-bell-slash text-4xl text-slate-300 mb-3"></i>
                            <p className="text-slate-400 text-sm">Tidak ada notifikasi</p>
                        </div>
                    ) : (
                        notifications.map((notif) => {
                            const { icon, color } = getNotifIcon(notif.type);
                            return (
                                <div
                                    key={notif.id}
                                    className={`flex gap-3 px-5 py-4 border-b border-slate-50 hover:bg-slate-50 transition-colors cursor-pointer ${!notif.read ? "bg-blue-50/50" : ""
                                        }`}
                                >
                                    <div className={`flex-shrink-0 w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center ${color}`}>
                                        <i className={`fa-solid ${icon}`}></i>
                                    </div>
                                    <div className="flex-1 min-w-0">
                                        <p className="text-sm font-medium text-slate-800 truncate">{notif.title}</p>
                                        <p className="text-xs text-slate-500 mt-0.5">{notif.message}</p>
                                        <p className="text-xs text-slate-400 mt-1">{notif.time}</p>
                                    </div>
                                    {!notif.read && (
                                        <div className="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                    )}
                                </div>
                            );
                        })
                    )}
                </div>

                <div className="p-3 bg-slate-50 border-t border-slate-100 flex justify-between">
                    <button
                        onClick={onClearAll}
                        className="text-xs text-red-500 hover:text-red-700 font-medium transition-colors"
                    >
                        Hapus Semua
                    </button>
                    <button className="text-xs text-blue-600 hover:text-blue-800 font-medium transition-colors">
                        Lihat Semua →
                    </button>
                </div>
            </div>
        </>
    );
}

// ===================== STAT CARD COMPONENT =====================
function StatCard({
    title,
    value,
    change,
    changeType,
    icon,
    iconBg,
    iconColor,
    highlight,
}: {
    title: string;
    value: string;
    change: string;
    changeType: "up" | "down" | "neutral";
    icon: string;
    iconBg: string;
    iconColor: string;
    highlight?: boolean;
}) {
    const changeColors = {
        up: "text-green-500",
        down: "text-red-500",
        neutral: "text-slate-400",
    };

    return (
        <div className={`bg-white p-5 rounded-xl shadow-sm border ${highlight ? "border-red-100" : "border-slate-100"} relative overflow-hidden group hover:shadow-lg transition-all duration-200`}>
            {highlight && (
                <div className="absolute -right-6 -top-6 w-24 h-24 bg-red-50 rounded-full z-0 group-hover:scale-125 transition-transform duration-200"></div>
            )}
            <div className="flex justify-between items-start z-10 relative">
                <div>
                    <p className={`text-xs font-bold uppercase tracking-wide ${highlight ? "text-red-500" : "text-slate-500"}`}>
                        {title}
                    </p>
                    <h3 className="text-2xl font-bold text-slate-800 mt-1">{value}</h3>
                    <p className={`text-xs mt-2 font-medium ${changeColors[changeType]}`}>
                        {changeType === "up" && <i className="fa-solid fa-arrow-trend-up mr-1"></i>}
                        {changeType === "down" && <i className="fa-solid fa-arrow-trend-down mr-1"></i>}
                        {change}
                    </p>
                </div>
                <div className={`${iconBg} p-3 rounded-lg ${iconColor}`}>
                    <i className={`${icon} text-xl`}></i>
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
    const colors = {
        error: "bg-red-500",
        success: "bg-green-500",
        warning: "bg-orange-500",
        info: "bg-blue-500",
    };

    return (
        <div className="flex gap-3 p-3 hover:bg-slate-50 rounded-lg transition-colors border-b border-slate-50 last:border-0">
            <div className="mt-1">
                <div className={`w-2 h-2 rounded-full ${colors[type]}`}></div>
            </div>
            <div>
                <p className="text-xs font-bold text-slate-700">{title}</p>
                <p className="text-[10px] text-slate-400 mt-0.5">
                    {detail} • <span className="text-slate-500">{time}</span>
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

    // States
    const [user, setUserState] = useState<User | null>(null);
    const [sidebarOpen, setSidebarOpen] = useState(false);
    const [dropdownOpen, setDropdownOpen] = useState(false);
    const [notifOpen, setNotifOpen] = useState(false);
    const [currentTime, setCurrentTime] = useState("");

    // Toast state
    const [toast, setToast] = useState<ToastData>({ show: false, message: "", type: "success" });

    // Alert Dialog state
    const [alertDialog, setAlertDialog] = useState({
        show: false,
        type: "warning" as "warning" | "danger" | "info",
        title: "",
        message: "",
        onConfirm: () => { },
    });

    // Notifications
    const [notifications, setNotifications] = useState<Notification[]>([
        { id: 1, type: "error", title: "Login Gagal (3x)", message: "IP: 192.168.1.45 mencoba masuk", time: "2 menit lalu", read: false },
        { id: 2, type: "info", title: "Update Artikel", message: "Artikel 'Pembangunan IKN' diperbarui", time: "15 menit lalu", read: false },
        { id: 3, type: "success", title: "Login Berhasil", message: "User Super Admin masuk ke sistem", time: "1 jam lalu", read: true },
        { id: 4, type: "warning", title: "Komentar Spam", message: "5 komentar spam terdeteksi", time: "3 jam lalu", read: true },
    ]);

    // Security Logs
    const securityLogs = [
        { type: "error" as const, title: "Login Gagal (3x)", detail: "IP: 192.168.1.45", time: "2 menit lalu" },
        { type: "info" as const, title: "Update Artikel: \"Pembangunan IKN\"", detail: "User: Editor", time: "15 menit lalu" },
        { type: "success" as const, title: "User Login Berhasil", detail: "User: Super Admin", time: "1 jam lalu" },
        { type: "warning" as const, title: "Komentar Spam Terdeteksi", detail: "System Purifier", time: "3 jam lalu" },
    ];

    // Toast handler
    const showToast = useCallback((message: string, type: ToastData["type"]) => {
        setToast({ show: true, message, type });
        setTimeout(() => setToast(prev => ({ ...prev, show: false })), 4000);
    }, []);

    // Alert handler
    const showAlert = useCallback((
        type: "warning" | "danger" | "info",
        title: string,
        message: string,
        onConfirm: () => void
    ) => {
        setAlertDialog({ show: true, type, title, message, onConfirm });
    }, []);

    const closeAlert = useCallback(() => {
        setAlertDialog(prev => ({ ...prev, show: false }));
    }, []);

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

    // Chart initialization
    useEffect(() => {
        if (!chartRef.current) return;

        const loadChart = async () => {
            const Chart = (await import("chart.js/auto")).default;

            if (chartInstanceRef.current) {
                chartInstanceRef.current.destroy();
            }

            const ctx = chartRef.current?.getContext("2d");
            if (!ctx) return;

            chartInstanceRef.current = new Chart(ctx, {
                type: "line",
                data: {
                    labels: ["Sen", "Sel", "Rab", "Kam", "Jum", "Sab", "Min"],
                    datasets: [
                        {
                            label: "Traffic (Views)",
                            data: [1200, 1900, 3000, 5000, 2400, 3200, 4500],
                            borderColor: "#2563eb",
                            backgroundColor: "rgba(37, 99, 235, 0.1)",
                            tension: 0.4,
                            fill: true,
                        },
                        {
                            label: "Percobaan Serangan (Blocked)",
                            data: [5, 12, 8, 25, 4, 7, 10],
                            borderColor: "#dc2626",
                            backgroundColor: "transparent",
                            borderDash: [5, 5],
                            tension: 0.4,
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
    }, [user]);

    // Handlers
    const handleLogout = async () => {
        showAlert(
            "warning",
            "Konfirmasi Logout",
            "Apakah Anda yakin ingin keluar dari sistem?",
            async () => {
                closeAlert();
                await logout();
                showToast("Logout berhasil!", "success");
                setTimeout(() => router.push("/"), 500);
            }
        );
    };

    const handleMarkAllRead = () => {
        setNotifications(prev => prev.map(n => ({ ...n, read: true })));
        showToast("Semua notifikasi ditandai dibaca", "success");
    };

    const handleClearNotifications = () => {
        showAlert(
            "danger",
            "Hapus Semua Notifikasi",
            "Apakah Anda yakin ingin menghapus semua notifikasi?",
            () => {
                setNotifications([]);
                closeAlert();
                showToast("Semua notifikasi dihapus", "info");
            }
        );
    };

    // Loading state
    if (!user) {
        return (
            <div className="min-h-screen flex items-center justify-center bg-slate-50">
                <div className="flex flex-col items-center gap-4">
                    <div className="animate-spin h-10 w-10 border-4 border-[#0f172a] border-t-transparent rounded-full"></div>
                    <p className="text-slate-500 text-sm">Memuat dashboard...</p>
                </div>
            </div>
        );
    }

    const unreadCount = notifications.filter(n => !n.read).length;

    return (
        <div className="bg-slate-50 font-[family-name:var(--font-inter)] text-slate-800 antialiased">
            {/* Mobile Header */}
            <div className="lg:hidden flex items-center justify-between bg-[#0f172a] text-white p-4 sticky top-0 z-50 shadow-md">
                <div className="flex items-center gap-2">
                    <div className="flex h-8 w-8 items-center justify-center rounded bg-[#dc2626] text-white font-[family-name:var(--font-merriweather)] font-bold">
                        P
                    </div>
                    <span className="font-[family-name:var(--font-merriweather)] font-bold tracking-tight">
                        PORTAL<span className="text-[#dc2626]">NEWS</span>
                    </span>
                </div>
                <button
                    onClick={() => setSidebarOpen(!sidebarOpen)}
                    className="text-slate-300 hover:text-white focus:outline-none"
                >
                    <i className="fa-solid fa-bars text-xl"></i>
                </button>
            </div>

            <div className="flex h-screen overflow-hidden">
                {/* Sidebar */}
                <aside
                    className={`fixed inset-y-0 left-0 z-40 w-64 bg-[#0f172a] text-white transition-transform duration-150 ease-out lg:static lg:translate-x-0 flex flex-col shadow-2xl ${sidebarOpen ? "translate-x-0" : "-translate-x-full"
                        }`}
                >
                    {/* Logo */}
                    <div className="flex items-center justify-center h-20 border-b border-[#1e293b] bg-[#0f172a] sticky top-0 z-10">
                        <div className="flex items-center gap-2">
                            <div className="flex h-9 w-9 items-center justify-center rounded bg-[#dc2626] text-white font-[family-name:var(--font-merriweather)] font-bold text-lg shadow-lg">
                                P
                            </div>
                            <span className="text-xl font-bold tracking-tight font-[family-name:var(--font-merriweather)]">
                                PORTAL<span className="text-[#dc2626]">NEWS</span>
                            </span>
                        </div>
                    </div>

                    {/* Navigation */}
                    <nav className="flex-1 overflow-y-auto sidebar-scroll py-6 px-3 space-y-1">
                        <p className="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-2">
                            Overview
                        </p>
                        <a href="#" className="flex items-center gap-3 px-3 py-2.5 bg-[#1e293b] text-white rounded-lg group transition-all duration-150 border-l-4 border-[#dc2626]">
                            <i className="fa-solid fa-chart-line w-5 text-center text-[#dc2626]"></i>
                            <span className="font-medium text-sm">Dashboard</span>
                        </a>

                        <p className="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-6">
                            Redaksi
                        </p>
                        <a href="#" className="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:text-white hover:bg-[#1e293b] rounded-lg group transition-all duration-150">
                            <i className="fa-regular fa-newspaper w-5 text-center group-hover:text-blue-400"></i>
                            <span className="font-medium text-sm">Artikel Berita</span>
                        </a>
                        <a href="#" className="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:text-white hover:bg-[#1e293b] rounded-lg group transition-all duration-150">
                            <i className="fa-solid fa-layer-group w-5 text-center group-hover:text-blue-400"></i>
                            <span className="font-medium text-sm">Kategori & Tag</span>
                        </a>
                        <a href="#" className="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:text-white hover:bg-[#1e293b] rounded-lg group transition-all duration-150">
                            <i className="fa-regular fa-images w-5 text-center group-hover:text-blue-400"></i>
                            <span className="font-medium text-sm">Galeri Media</span>
                        </a>

                        <p className="px-3 text-xs font-semibold text-[#dc2626] uppercase tracking-wider mb-2 mt-6 flex items-center gap-2">
                            <i className="fa-solid fa-shield-halved text-xs"></i> Security Center
                        </p>
                        <a href="#" className="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:text-white hover:bg-[#1e293b] rounded-lg group transition-all duration-150">
                            <i className="fa-solid fa-fingerprint w-5 text-center text-green-500"></i>
                            <span className="font-medium text-sm">Activity Logs</span>
                        </a>
                        <a href="#" className="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:text-white hover:bg-[#1e293b] rounded-lg group transition-all duration-150">
                            <i className="fa-solid fa-ban w-5 text-center text-red-500"></i>
                            <span className="font-medium text-sm">Blocked IPs / Firewall</span>
                            <span className="ml-auto bg-red-600 text-white text-[10px] px-1.5 py-0.5 rounded-full">12</span>
                        </a>

                        <p className="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-6">
                            Sistem
                        </p>
                        <a href="#" className="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:text-white hover:bg-[#1e293b] rounded-lg group transition-all duration-150">
                            <i className="fa-solid fa-users-gear w-5 text-center group-hover:text-blue-400"></i>
                            <span className="font-medium text-sm">Manajemen Pengguna</span>
                        </a>
                        <a href="#" className="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:text-white hover:bg-[#1e293b] rounded-lg group transition-all duration-150">
                            <i className="fa-solid fa-sliders w-5 text-center group-hover:text-blue-400"></i>
                            <span className="font-medium text-sm">Pengaturan Situs</span>
                        </a>
                    </nav>

                    {/* User Profile */}
                    <div className="border-t border-[#1e293b] p-4 bg-[#0f172a]">
                        <div className="flex items-center gap-3">
                            <img
                                className="h-9 w-9 rounded-full object-cover border border-slate-600"
                                src={`https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=2563eb&color=fff`}
                                alt="User"
                            />
                            <div className="flex-1 min-w-0">
                                <p className="text-sm font-medium text-white truncate">{user.name}</p>
                                <p className="text-xs text-slate-400 truncate">{formatRole(user.role)}</p>
                            </div>
                            <button
                                onClick={handleLogout}
                                className="text-slate-400 hover:text-white transition-colors duration-150"
                            >
                                <i className="fa-solid fa-right-from-bracket"></i>
                            </button>
                        </div>
                    </div>
                </aside>

                {/* Sidebar Overlay */}
                {sidebarOpen && (
                    <div
                        className="fixed inset-0 bg-black/50 z-30 lg:hidden animate-fade-in"
                        onClick={() => setSidebarOpen(false)}
                    ></div>
                )}

                {/* Main Content */}
                <main className="flex-1 flex flex-col h-screen overflow-hidden bg-slate-50 relative">
                    {/* Header */}
                    <header className="h-16 bg-white/80 backdrop-blur-md border-b border-slate-200 flex items-center justify-between px-6 sticky top-0 z-20">
                        <div className="relative w-64 hidden md:block">
                            <i className="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input
                                type="text"
                                placeholder="Cari berita atau log..."
                                className="w-full pl-10 pr-4 py-2 bg-slate-100 border-none rounded-full text-sm focus:ring-2 focus:ring-[#2563eb] focus:bg-white transition-all duration-150"
                            />
                        </div>

                        <div className="flex items-center gap-4">
                            {/* Notification Button */}
                            <div className="relative">
                                <button
                                    onClick={() => setNotifOpen(!notifOpen)}
                                    className="relative text-slate-500 hover:text-[#2563eb] transition-colors duration-150"
                                >
                                    <i className="fa-regular fa-bell text-xl"></i>
                                    {unreadCount > 0 && (
                                        <span className="absolute -top-1 -right-1 h-5 w-5 bg-red-500 rounded-full border-2 border-white text-[10px] text-white flex items-center justify-center font-bold">
                                            {unreadCount}
                                        </span>
                                    )}
                                </button>

                                <NotificationPanel
                                    show={notifOpen}
                                    notifications={notifications}
                                    onClose={() => setNotifOpen(false)}
                                    onMarkAllRead={handleMarkAllRead}
                                    onClearAll={handleClearNotifications}
                                />
                            </div>

                            <div className="h-8 w-[1px] bg-slate-200 mx-1"></div>

                            <a
                                href="http://localhost:3000"
                                target="_blank"
                                className="text-sm font-medium text-[#2563eb] hover:text-[#0f172a] flex items-center gap-2 transition-colors duration-150"
                            >
                                <i className="fa-solid fa-arrow-up-right-from-square"></i>
                                Lihat Website
                            </a>

                            {/* User Dropdown */}
                            <div className="relative">
                                <button
                                    onClick={() => setDropdownOpen(!dropdownOpen)}
                                    className="flex items-center gap-2 hover:bg-slate-100 px-3 py-2 rounded-lg transition-all duration-150"
                                >
                                    <img
                                        className="h-8 w-8 rounded-full object-cover border-2 border-slate-200"
                                        src={`https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=2563eb&color=fff`}
                                        alt="User"
                                    />
                                    <i className={`fa-solid fa-chevron-down text-xs text-slate-400 transition-transform duration-150 ${dropdownOpen ? "rotate-180" : ""}`}></i>
                                </button>

                                {dropdownOpen && (
                                    <>
                                        <div
                                            className="fixed inset-0 z-40"
                                            onClick={() => setDropdownOpen(false)}
                                        ></div>
                                        <div className="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden z-50 animate-slide-down">
                                            <div className="px-4 py-3 border-b border-slate-100 bg-slate-50">
                                                <p className="text-sm font-medium text-[#0f172a]">{user.name}</p>
                                                <p className="text-xs text-slate-500">{user.email}</p>
                                            </div>
                                            <a href="#" className="flex items-center gap-3 px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 transition-colors duration-150">
                                                <i className="fa-solid fa-user text-slate-400 w-5"></i>
                                                Profil Saya
                                            </a>
                                            <a href="#" className="flex items-center gap-3 px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 transition-colors duration-150">
                                                <i className="fa-solid fa-cog text-slate-400 w-5"></i>
                                                Pengaturan
                                            </a>
                                            <hr className="border-slate-100" />
                                            <button
                                                onClick={handleLogout}
                                                className="flex items-center gap-3 w-full px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors duration-150"
                                            >
                                                <i className="fa-solid fa-sign-out-alt w-5"></i>
                                                Keluar
                                            </button>
                                        </div>
                                    </>
                                )}
                            </div>
                        </div>
                    </header>

                    {/* Scrollable Content */}
                    <div className="flex-1 overflow-y-auto p-6 lg:p-8">
                        {/* Page Title */}
                        <div className="mb-8 flex items-center justify-between">
                            <div>
                                <h1 className="text-2xl font-bold text-slate-800 font-[family-name:var(--font-merriweather)]">
                                    Dashboard Overview
                                </h1>
                                <p className="text-slate-500 text-sm mt-1">
                                    Pantau performa konten dan status keamanan sistem hari ini.
                                </p>
                            </div>
                            <div className="hidden md:flex gap-2">
                                <span className="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full flex items-center gap-1 border border-green-200">
                                    <span className="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                    System Healthy
                                </span>
                            </div>
                        </div>

                        {/* Stats Grid */}
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                            <StatCard
                                title="Total Pembaca"
                                value="45.2K"
                                change="+12.5% hari ini"
                                changeType="up"
                                icon="fa-regular fa-eye"
                                iconBg="bg-blue-50"
                                iconColor="text-[#2563eb]"
                            />
                            <StatCard
                                title="Artikel Terbit"
                                value="1,204"
                                change="5 artikel draft"
                                changeType="neutral"
                                icon="fa-regular fa-file-lines"
                                iconBg="bg-purple-50"
                                iconColor="text-purple-600"
                            />
                            <StatCard
                                title="Ancaman Diblokir"
                                value="28 IP"
                                change="Rate Limit Active"
                                changeType="down"
                                icon="fa-solid fa-ban"
                                iconBg="bg-red-50"
                                iconColor="text-red-600"
                                highlight
                            />
                            <StatCard
                                title="Komentar Baru"
                                value="15"
                                change="Perlu Moderasi"
                                changeType="neutral"
                                icon="fa-regular fa-comments"
                                iconBg="bg-orange-50"
                                iconColor="text-orange-500"
                            />
                        </div>

                        {/* Charts & Logs Grid */}
                        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            {/* Traffic Chart */}
                            <div className="bg-white p-6 rounded-xl shadow-sm border border-slate-100 lg:col-span-2">
                                <div className="flex items-center justify-between mb-6">
                                    <h3 className="font-bold text-slate-800">Analitik Trafik vs Serangan</h3>
                                    <select className="text-xs border-slate-200 rounded-md text-slate-500 px-3 py-1.5">
                                        <option>7 Hari Terakhir</option>
                                        <option>Bulan Ini</option>
                                    </select>
                                </div>
                                <div className="relative h-72 w-full">
                                    <canvas ref={chartRef}></canvas>
                                </div>
                            </div>

                            {/* Security Logs */}
                            <div className="bg-white p-0 rounded-xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
                                <div className="p-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                                    <h3 className="font-bold text-slate-800 text-sm">Log Keamanan Terbaru</h3>
                                    <a href="#" className="text-xs text-[#2563eb] hover:underline">Lihat Semua</a>
                                </div>
                                <div className="flex-1 overflow-y-auto max-h-[300px] p-2">
                                    {securityLogs.map((log, index) => (
                                        <SecurityLogItem key={index} {...log} />
                                    ))}
                                </div>
                            </div>
                        </div>

                        {/* Footer */}
                        <div className="mt-12 border-t border-slate-200 pt-6 flex flex-col md:flex-row justify-between items-center text-xs text-slate-400">
                            <p>&copy; 2025 Portal News Redaksi. All rights reserved.</p>
                            <div className="flex gap-4 mt-2 md:mt-0">
                                <span>Laravel v12</span>
                                <span>Next.js v15</span>
                                <span>Security Enabled</span>
                            </div>
                        </div>
                    </div>
                </main>
            </div>

            {/* Toast */}
            <Toast data={toast} onClose={() => setToast(prev => ({ ...prev, show: false }))} />

            {/* Alert Dialog */}
            <AlertDialog
                show={alertDialog.show}
                type={alertDialog.type}
                title={alertDialog.title}
                message={alertDialog.message}
                onConfirm={alertDialog.onConfirm}
                onCancel={closeAlert}
            />
        </div>
    );
}
