"use client";

import { useState, useEffect } from "react";
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

export default function ProfilePage() {
    const router = useRouter();
    const { theme } = useTheme();

    const [user, setUserState] = useState<User | null>(null);
    const [sidebarOpen, setSidebarOpen] = useState(false);
    const [notifications, setNotifications] = useState<Notification[]>([]);

    // Toast state
    const [toast, setToast] = useState<ToastData>({ show: false, message: "", type: "success" });

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

    const showToast = (message: string, type: ToastData["type"]) => {
        setToast({ show: true, message, type });
        setTimeout(() => setToast(prev => ({ ...prev, show: false })), 4000);
    };

    // SweetAlert2 confirm dialog
    const showConfirm = async (
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
    };

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

    if (!user) {
        return (
            <div className="min-h-screen flex items-center justify-center bg-slate-50">
                <div className="flex flex-col items-center gap-4">
                    <div
                        className="animate-spin h-10 w-10 border-4 border-t-transparent rounded-full"
                        style={{ borderColor: theme.accent, borderTopColor: "transparent" }}
                    ></div>
                    <p className="text-slate-500 text-sm">Memuat profil...</p>
                </div>
            </div>
        );
    }

    return (
        <div className="bg-slate-50 font-[family-name:var(--font-inter)] text-slate-800 antialiased">
            <div className="flex h-screen overflow-hidden">
                {/* Sidebar Component */}
                <Sidebar
                    user={user}
                    sidebarOpen={sidebarOpen}
                    onClose={() => setSidebarOpen(false)}
                    onLogout={handleLogout}
                />

                {/* Main Content */}
                <main className="flex-1 flex flex-col h-screen overflow-hidden bg-slate-50 relative">
                    {/* Header Component */}
                    <Header
                        user={user}
                        notifications={notifications}
                        onLogout={handleLogout}
                        onMarkAllRead={handleMarkAllRead}
                        onClearNotifications={handleClearNotifications}
                        onToggleSidebar={() => setSidebarOpen(!sidebarOpen)}
                    />

                    {/* Page Header */}
                    <div className="bg-white border-b border-slate-200 px-6 lg:px-8 py-4">
                        <h1 className="text-xl font-bold text-slate-800 font-[family-name:var(--font-merriweather)]">
                            Profil Saya
                        </h1>
                    </div>

                    {/* Scrollable Content */}
                    <div className="flex-1 overflow-y-auto p-6 lg:p-8">
                        <div className="max-w-4xl mx-auto">
                            {/* Profile Card */}
                            <div className="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                                {/* Header with Gradient */}
                                <div className="h-32 relative theme-gradient">
                                    <div className="absolute inset-0 bg-gradient-to-r from-black/20 to-transparent"></div>
                                </div>

                                {/* Profile Info */}
                                <div className="px-6 pb-6 -mt-16 relative z-10">
                                    <div className="flex flex-col sm:flex-row items-center gap-4">
                                        <img
                                            className="h-28 w-28 rounded-full object-cover border-4 border-white shadow-lg"
                                            src={`https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=${theme.accent.replace("#", "")}&color=fff&size=200`}
                                            alt="User"
                                        />
                                        <div className="text-center sm:text-left mt-4 sm:mt-8">
                                            <h2 className="text-2xl font-bold text-slate-800">{user.name}</h2>
                                            <p className="text-slate-500">{user.email}</p>
                                            <span
                                                className="inline-block mt-2 px-3 py-1 rounded-full text-sm font-medium"
                                                style={{
                                                    backgroundColor: `${theme.accent}20`,
                                                    color: theme.accent
                                                }}
                                            >
                                                {user.role === 'super_admin' ? 'Super Admin' :
                                                    user.role === 'admin' ? 'Admin' :
                                                        user.role === 'editor' ? 'Editor' : user.role}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* Coming Soon Section */}
                            <div className="mt-8 bg-white rounded-2xl shadow-sm border border-slate-100 p-8 text-center">
                                <div
                                    className="w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-4"
                                    style={{ backgroundColor: `${theme.accent}10` }}
                                >
                                    <i
                                        className="fa-solid fa-gear fa-spin text-3xl"
                                        style={{ color: theme.accent }}
                                    ></i>
                                </div>
                                <h3 className="text-xl font-bold text-slate-800 mb-2">Halaman Profil Sedang Dikembangkan</h3>
                                <p className="text-slate-500 max-w-md mx-auto">
                                    Fitur edit profil, ubah password, dan pengaturan akun lainnya akan segera hadir.
                                    Pantau terus update terbaru dari kami!
                                </p>
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
