"use client";

import { useState, useEffect, useCallback } from "react";
import { useRouter } from "next/navigation";
import { getUser, isLoggedIn, User, logout } from "@/lib/auth";
import Sidebar from "@/components/layout/Sidebar";
import Header from "@/components/layout/Header";
import Toast, { ToastData } from "@/components/ui/Toast";
import { useTheme } from "@/contexts/ThemeContext";

// Declare SweetAlert2 type for TypeScript
declare const Swal: any;

interface Notification {
    id: number;
    type: "success" | "error" | "warning" | "info";
    title: string;
    message: string;
    time: string;
    read: boolean;
}

export default function SettingsShell({
    children,
}: {
    children: React.ReactNode;
}) {
    const router = useRouter();
    const { theme, saveSettings, resetSettings, hasChanges: globalHasChanges, isDarkMode } = useTheme();

    // States
    const [user, setUserState] = useState<User | null>(null);
    const [sidebarOpen, setSidebarOpen] = useState(false);
    const [isSaving, setIsSaving] = useState(false);

    // Toast state
    const [toast, setToast] = useState<ToastData>({ show: false, message: "", type: "success" });

    // Notifications
    const [notifications, setNotifications] = useState<Notification[]>([]);

    // Floating bar animation state
    const [floatingBarVisible, setFloatingBarVisible] = useState(false);
    const [isFloatingBarExiting, setIsFloatingBarExiting] = useState(false);

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

    // Control floating bar visibility with exit animation
    useEffect(() => {
        if (globalHasChanges) {
            setIsFloatingBarExiting(false);
            setFloatingBarVisible(true);
        } else if (floatingBarVisible) {
            setIsFloatingBarExiting(true);
            const timer = setTimeout(() => {
                setFloatingBarVisible(false);
                setIsFloatingBarExiting(false);
            }, 350);
            return () => clearTimeout(timer);
        }
    }, [globalHasChanges, floatingBarVisible]);

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

    const handleCancelChanges = useCallback(() => {
        resetSettings();
    }, [resetSettings]);

    const handleSaveSettings = async () => {
        setIsSaving(true);
        try {
            const result = await saveSettings();
            if (result.success) {
                showToast("Pengaturan berhasil disimpan!", "success");
            } else {
                showToast(result.message || "Gagal menyimpan pengaturan", "error");
            }
        } catch (error) {
            showToast("Gagal menyimpan pengaturan", "error");
        } finally {
            setIsSaving(false);
        }
    };

    // Loading state
    if (!user) {
        return (
            <div className={`min-h-screen flex items-center justify-center ${isDarkMode ? 'bg-slate-900' : 'bg-slate-50'}`}>
                <div className="flex flex-col items-center gap-4">
                    <div
                        className="animate-spin h-10 w-10 border-4 border-t-transparent rounded-full"
                        style={{ borderColor: theme.accent, borderTopColor: "transparent" }}
                    ></div>
                    <p className={`text-sm ${isDarkMode ? 'text-slate-400' : 'text-slate-500'}`}>Memuat...</p>
                </div>
            </div>
        );
    }

    return (
        <div className={`font-[family-name:var(--font-inter)] antialiased ${isDarkMode ? 'dark-mode' : ''}`}>
            <div className="flex h-screen overflow-hidden">
                <Sidebar
                    user={user}
                    sidebarOpen={sidebarOpen}
                    onClose={() => setSidebarOpen(false)}
                    onLogout={handleLogout}
                />

                <main
                    className={`flex-1 flex flex-col h-screen overflow-hidden relative transition-colors duration-500`}
                    style={{
                        backgroundColor: isDarkMode ? theme.primary : '#f8fafc'
                    }}
                >
                    <Header
                        user={user}
                        notifications={notifications}
                        onLogout={handleLogout}
                        onMarkAllRead={handleMarkAllRead}
                        onClearNotifications={handleClearNotifications}
                        onToggleSidebar={() => setSidebarOpen(!sidebarOpen)}
                    />

                    <div className="flex-1 overflow-y-auto p-6 lg:p-8">
                        <div className="max-w-6xl mx-auto animation-fade-in">
                            {children}
                        </div>
                    </div>
                </main>
            </div>

            <Toast data={toast} onClose={() => setToast(prev => ({ ...prev, show: false }))} />

            {floatingBarVisible && (
                <div className={`fixed bottom-6 left-0 right-0 z-50 px-4 sm:px-6 flex justify-center ${isFloatingBarExiting ? 'pointer-events-none' : ''}`}>
                    <div
                        className={`w-full max-w-4xl backdrop-blur-xl rounded-2xl shadow-2xl border transition-all duration-300 ${isFloatingBarExiting ? 'animate-slide-up-exit' : 'animate-slide-up-enter'} ${isDarkMode
                            ? 'bg-slate-900/90 border-slate-700/50'
                            : 'bg-white/90 border-white/50'
                            }`}
                        style={{
                            boxShadow: isDarkMode
                                ? `0 20px 40px -10px rgba(0,0,0,0.5), 0 0 0 1px var(--theme-border-soft)`
                                : `0 20px 40px -10px ${theme.accent}15, 0 0 0 1px ${theme.accent}10`
                        }}
                    >
                        {/* Floating bar content - exact match to previous layout */}
                        <div className="px-4 py-3 sm:px-6 sm:py-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div className="flex items-center gap-4 w-full sm:w-auto">
                                <div
                                    className="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 animate-pulse"
                                    style={{
                                        background: `linear-gradient(135deg, ${theme.gradientFrom}, ${theme.gradientTo})`,
                                        boxShadow: `0 4px 12px ${theme.accent}40`
                                    }}
                                >
                                    <i className="fa-solid fa-pen-nib text-white text-sm"></i>
                                </div>
                                <div className="min-w-0 flex-1">
                                    <p className={`text-sm font-bold truncate ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>
                                        Perubahan Belum Disimpan
                                    </p>
                                    <p className={`text-xs hidden sm:block ${isDarkMode ? 'text-slate-400' : 'text-slate-500'}`}>
                                        Anda memiliki perubahan yang belum disimpan.
                                    </p>
                                </div>
                            </div>
                            <div className="flex items-center gap-3 w-full sm:w-auto">
                                <button
                                    onClick={handleCancelChanges}
                                    className={`flex-1 sm:flex-none px-5 py-2.5 rounded-xl text-sm font-bold transition-all ${isDarkMode
                                        ? 'bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white'
                                        : 'bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-900'
                                        }`}
                                >
                                    <i className="fa-solid fa-xmark mr-2"></i>
                                    Batal
                                </button>
                                <button
                                    onClick={handleSaveSettings}
                                    disabled={isSaving}
                                    className="flex-1 sm:flex-none px-6 py-2.5 rounded-xl text-sm font-bold shadow-lg hover:shadow-xl hover:scale-105 active:scale-95 transition-all flex items-center justify-center gap-2 text-white"
                                    style={{
                                        background: `linear-gradient(135deg, ${theme.gradientFrom}, ${theme.gradientTo})`
                                    }}
                                >
                                    {isSaving ? (
                                        <>
                                            <div className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                                            <span>Menyimpan...</span>
                                        </>
                                    ) : (
                                        <>
                                            <i className="fa-solid fa-check"></i>
                                            <span>Simpan</span>
                                        </>
                                    )}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}
