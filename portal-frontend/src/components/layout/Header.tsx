"use client";

import { useState, useEffect, useCallback, useRef } from "react";
import { User } from "@/lib/auth";
import Link from "next/link";
import { useTheme } from "@/contexts/ThemeContext";

interface Notification {
    id: number;
    type: "success" | "error" | "warning" | "info";
    title: string;
    message: string;
    time: string;
    read: boolean;
}

interface HeaderProps {
    user: User;
    notifications: Notification[];
    onLogout: () => void;
    onMarkAllRead: () => void;
    onClearNotifications: () => void;
    onToggleSidebar: () => void;
}

// ===================== DARK MODE TOGGLE COMPONENT =====================
function DarkModeToggle({
    isDarkMode,
    onToggle,
    toggleRef,
}: {
    isDarkMode: boolean;
    onToggle: (e: React.MouseEvent<HTMLButtonElement>) => void;
    toggleRef?: React.RefObject<HTMLButtonElement | null>;
}) {
    return (
        <button
            ref={toggleRef}
            onClick={onToggle}
            className={`dark-mode-toggle-simple ${isDarkMode ? 'dark' : 'light'}`}
            aria-label={isDarkMode ? "Switch to light mode" : "Switch to dark mode"}
            title={isDarkMode ? "Mode Terang" : "Mode Gelap"}
        >
            <i className="fa-solid fa-sun sun-icon"></i>
            <i className="fa-solid fa-moon moon-icon"></i>
        </button>
    );
}

// ===================== NOTIFICATION MODAL COMPONENT =====================
function NotificationModal({
    isVisible,
    isExiting,
    notifications,
    onClose,
    onMarkAllRead,
    onClearAll,
}: {
    isVisible: boolean;
    isExiting: boolean;
    notifications: Notification[];
    onClose: () => void;
    onMarkAllRead: () => void;
    onClearAll: () => void;
}) {
    const { theme, isDarkMode } = useTheme();

    useEffect(() => {
        if (isVisible) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
        return () => {
            document.body.style.overflow = '';
        };
    }, [isVisible]);

    useEffect(() => {
        const handleEscape = (e: KeyboardEvent) => {
            if (e.key === 'Escape') onClose();
        };
        if (isVisible) document.addEventListener('keydown', handleEscape);
        return () => document.removeEventListener('keydown', handleEscape);
    }, [isVisible, onClose]);

    if (!isVisible) return null;

    const getNotifIcon = (type: string) => {
        const icons: Record<string, { icon: string; color: string; bg: string }> = {
            success: { icon: "fa-check-circle", color: "text-green-500", bg: "bg-green-500/10" },
            error: { icon: "fa-times-circle", color: "text-red-500", bg: "bg-red-500/10" },
            warning: { icon: "fa-exclamation-triangle", color: "text-amber-500", bg: "bg-amber-500/10" },
            info: { icon: "fa-info-circle", color: "text-blue-500", bg: "bg-blue-500/10" },
        };
        return icons[type] || icons.info;
    };

    const unreadCount = notifications.filter(n => !n.read).length;

    return (
        <div className="notification-modal-overlay">
            {/* Backdrop with blur & soft tint */}
            <div
                className={`notification-modal-backdrop ${isExiting ? 'exiting' : 'entering'}`}
                style={{
                    backgroundColor: isDarkMode ? 'rgba(0,0,0,0.6)' : 'rgba(255,255,255,0.6)',
                    backdropFilter: 'blur(8px)'
                }}
                onClick={onClose}
            ></div>

            {/* Modal Content - Glassmorphism & Soft Theme */}
            <div
                className={`notification-modal-content overflow-hidden border transition-all duration-300 ${isExiting ? 'exiting' : 'entering'} ${isDarkMode ? 'bg-slate-900/90 border-slate-700/50' : 'bg-white/90 border-white/60'
                    }`}
                style={{
                    boxShadow: isDarkMode
                        ? `0 25px 50px -12px rgba(0,0,0,0.5), 0 0 0 1px var(--theme-border-soft)`
                        : `0 25px 50px -12px ${theme.accent}15, 0 0 0 1px ${theme.accent}10`
                }}
            >
                {/* Header - Softened Redesign */}
                <div className={`relative px-6 py-6 border-b transition-colors ${isDarkMode ? 'border-slate-700/50 bg-slate-800/20' : 'border-slate-100 bg-slate-50/50'
                    }`}>
                    <button
                        onClick={onClose}
                        className={`modal-close-btn absolute top-5 right-5 w-8 h-8 rounded-full flex items-center justify-center transition-all ${isDarkMode
                            ? 'bg-slate-800 text-slate-400 hover:bg-slate-700 hover:text-white'
                            : 'bg-white text-slate-500 hover:bg-slate-100 hover:text-slate-700 shadow-sm border border-slate-100'}`}
                        aria-label="Close"
                    >
                        <i className="fa-solid fa-times text-sm"></i>
                    </button>
                    <div className="flex items-center gap-5">
                        <div
                            className="w-14 h-14 rounded-2xl flex items-center justify-center shadow-lg transform transition-all hover:scale-105 hover:rotate-3"
                            style={{
                                background: `linear-gradient(135deg, ${theme.gradientFrom}, ${theme.gradientTo})`,
                                boxShadow: `0 10px 20px -5px ${theme.accent}40`
                            }}
                        >
                            <i className="fa-solid fa-bell text-white text-2xl animate-pulse-slow"></i>
                        </div>
                        <div>
                            <h3 className={`text-xl font-bold mb-1 ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>
                                Notifikasi
                            </h3>
                            <p className={`text-sm font-medium ${isDarkMode ? 'text-slate-400' : 'text-slate-500'}`}>
                                {unreadCount > 0
                                    ? <span className="px-2 py-0.5 rounded-md text-xs font-bold bg-opacity-10" style={{ color: theme.accent, backgroundColor: `${theme.accent}20` }}>{unreadCount} pesan baru</span>
                                    : <span className="flex items-center gap-1.5"><i className="fa-solid fa-check-circle text-green-500"></i>Semua sudah dibaca</span>}
                            </p>
                        </div>
                    </div>
                </div>

                {/* Notification List */}
                <div className={`max-h-[55vh] overflow-y-auto ${isDarkMode ? 'custom-scrollbar-dark' : 'custom-scrollbar'}`}>
                    {notifications.length === 0 ? (
                        <div className="notification-empty-state py-16">
                            <div
                                className="w-20 h-20 rounded-full flex items-center justify-center mb-4 transition-transform hover:scale-110 duration-500"
                                style={{ backgroundColor: `${theme.accent}10` }}
                            >
                                <i className="fa-regular fa-bell-slash text-4xl" style={{ color: theme.accent }}></i>
                            </div>
                            <h4 className={`text-lg font-bold mb-2 ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>
                                Tidak Ada Notifikasi
                            </h4>
                            <p className={`text-sm px-10 ${isDarkMode ? 'text-slate-400' : 'text-slate-500'}`}>
                                Belum ada pemberitahuan baru yang masuk untuk Anda saat ini.
                            </p>
                        </div>
                    ) : (
                        <div className="py-2">
                            {unreadCount > 0 && (
                                <div className={`px-6 py-2 text-xs font-bold uppercase tracking-wider flex justify-between items-center ${isDarkMode ? 'text-slate-500' : 'text-slate-400'}`}>
                                    <span>Hari Ini</span>
                                    <button
                                        onClick={onMarkAllRead}
                                        className="hover:underline cursor-pointer transition-colors hover:text-blue-500"
                                    >
                                        Tandai Dibaca
                                    </button>
                                </div>
                            )}
                            {notifications.map((notif) => {
                                const { icon, color, bg } = getNotifIcon(notif.type);
                                return (
                                    <div
                                        key={notif.id}
                                        className={`notification-item relative flex gap-4 px-6 py-4 cursor-pointer border-l-4 transition-all duration-200 ${!notif.read
                                            ? `bg-gradient-to-r from-transparent to-transparent ${isDarkMode ? 'hover:bg-slate-800/50' : 'hover:bg-slate-50'}`
                                            : `${isDarkMode ? 'opacity-70 hover:opacity-100 hover:bg-slate-800/30' : 'opacity-80 hover:opacity-100 hover:bg-slate-50'}`
                                            }`}
                                        style={{
                                            borderColor: !notif.read ? theme.accent : 'transparent',
                                            backgroundColor: !notif.read ? (isDarkMode ? `${theme.accent}08` : `${theme.accent}05`) : 'transparent'
                                        }}
                                    >
                                        <div className={`flex-shrink-0 w-11 h-11 rounded-xl ${isDarkMode ? 'bg-slate-800' : 'bg-white'} shadow-sm flex items-center justify-center z-10 border ${isDarkMode ? 'border-slate-700' : 'border-slate-100'}`}>
                                            <i className={`fa-solid ${icon} text-lg ${color}`}></i>
                                        </div>
                                        <div className="flex-1 min-w-0 z-10">
                                            <div className="flex items-start justify-between gap-2">
                                                <p className={`text-sm font-bold truncate ${isDarkMode ? 'text-slate-200' : 'text-slate-800'}`}>
                                                    {notif.title}
                                                </p>
                                                <span className={`text-[10px] font-medium px-2 py-0.5 rounded-full ${isDarkMode ? 'bg-slate-800 text-slate-400' : 'bg-slate-100 text-slate-500'}`}>
                                                    {notif.time}
                                                </span>
                                            </div>
                                            <p className={`text-sm mt-1 leading-relaxed line-clamp-2 ${isDarkMode ? 'text-slate-400' : 'text-slate-600'}`}>
                                                {notif.message}
                                            </p>
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    )}
                </div>

                {/* Footer */}
                {notifications.length > 0 && (
                    <div className={`p-4 border-t flex items-center justify-between ${isDarkMode ? 'bg-slate-800/40 border-slate-700' : 'bg-slate-50/80 border-slate-100'
                        }`}>
                        <button
                            onClick={onClearAll}
                            className={`text-xs font-bold transition-colors flex items-center gap-2 px-4 py-2.5 rounded-xl ${isDarkMode
                                ? 'text-red-400 hover:bg-red-900/20'
                                : 'text-red-600 hover:bg-red-50'
                                }`}
                        >
                            <i className="fa-solid fa-trash-can"></i>
                            Hapus Semua
                        </button>
                        <button
                            className={`text-xs font-bold transition-all flex items-center gap-2 px-5 py-2.5 rounded-xl shadow-sm hover:shadow-md transform hover:-translate-y-0.5`}
                            style={{
                                color: 'white',
                                background: `linear-gradient(135deg, ${theme.gradientFrom}, ${theme.gradientTo})`
                            }}
                        >
                            Lihat Semua
                            <i className="fa-solid fa-arrow-right-long"></i>
                        </button>
                    </div>
                )}
            </div>
        </div>
    );
}

export default function Header({
    user,
    notifications,
    onLogout,
    onMarkAllRead,
    onClearNotifications,
    onToggleSidebar,
}: HeaderProps) {
    const [dropdownOpen, setDropdownOpen] = useState(false);
    const [notifOpen, setNotifOpen] = useState(false);
    const { theme, isDarkMode, toggleDarkMode } = useTheme();
    const toggleBtnRef = useRef<HTMLButtonElement>(null);

    // Animation states for smooth exit
    const [notifVisible, setNotifVisible] = useState(false);
    const [notifExiting, setNotifExiting] = useState(false);
    const [dropdownVisible, setDropdownVisible] = useState(false);
    const [dropdownExiting, setDropdownExiting] = useState(false);

    // Apply dark mode class to body - Moved to ThemeContext but keeping here for insurance
    useEffect(() => {
        if (isDarkMode) {
            document.body.classList.add('dark-mode');
        } else {
            document.body.classList.remove('dark-mode');
        }
    }, [isDarkMode]);

    const handleDarkModeToggle = useCallback(() => {
        toggleDarkMode?.();
    }, [toggleDarkMode]);

    const handleNotifToggle = useCallback(() => {
        if (notifOpen) {
            setNotifExiting(true);
            setTimeout(() => {
                setNotifVisible(false);
                setNotifExiting(false);
                setNotifOpen(false);
            }, 300);
        } else {
            setNotifOpen(true);
            setNotifVisible(true);
            setNotifExiting(false);
        }
        if (dropdownOpen) {
            setDropdownExiting(true);
            setTimeout(() => {
                setDropdownVisible(false);
                setDropdownExiting(false);
                setDropdownOpen(false);
            }, 280);
        }
    }, [notifOpen, dropdownOpen]);

    const handleDropdownToggle = useCallback(() => {
        if (dropdownOpen) {
            setDropdownExiting(true);
            setTimeout(() => {
                setDropdownVisible(false);
                setDropdownExiting(false);
                setDropdownOpen(false);
            }, 280);
        } else {
            setDropdownOpen(true);
            setDropdownVisible(true);
            setDropdownExiting(false);
        }
    }, [dropdownOpen]);

    const handleNotifClose = useCallback(() => {
        setNotifExiting(true);
        setTimeout(() => {
            setNotifVisible(false);
            setNotifExiting(false);
            setNotifOpen(false);
        }, 300);
    }, []);

    const handleDropdownClose = useCallback(() => {
        setDropdownExiting(true);
        setTimeout(() => {
            setDropdownVisible(false);
            setDropdownExiting(false);
            setDropdownOpen(false);
        }, 280);
    }, []);

    const unreadCount = notifications.filter(n => !n.read).length;

    return (
        <>
            {/* Notification Modal */}
            <NotificationModal
                isVisible={notifVisible}
                isExiting={notifExiting}
                notifications={notifications}
                onClose={handleNotifClose}
                onMarkAllRead={onMarkAllRead}
                onClearAll={onClearNotifications}
            />

            {/* Mobile Header */}
            <div
                className={`lg:hidden flex items-center justify-between p-3 sticky top-0 z-30 shadow-md transform-gpu transition-colors duration-300 ${isDarkMode ? 'text-white' : 'text-white'
                    }`}
                style={{ backgroundColor: theme.sidebar }}
            >
                <button
                    onClick={onToggleSidebar}
                    className="text-slate-300 hover:text-white focus:outline-none p-2 rounded-lg hover:bg-white/10 transition-colors"
                    aria-label="Toggle menu"
                >
                    <i className="fa-solid fa-bars text-xl"></i>
                </button>

                <div className="flex items-center gap-1">
                    <div className="scale-75">
                        <DarkModeToggle
                            isDarkMode={isDarkMode}
                            onToggle={handleDarkModeToggle}
                        />
                    </div>

                    <button
                        onClick={handleNotifToggle}
                        className="relative p-2 rounded-lg hover:bg-white/10 transition-colors text-slate-300 hover:text-white"
                        aria-label="Notifications"
                    >
                        <i className="fa-regular fa-bell text-lg"></i>
                        {unreadCount > 0 && (
                            <span
                                className="absolute top-0.5 right-0.5 h-4 w-4 rounded-full text-[9px] text-white flex items-center justify-center font-bold animate-pulse"
                                style={{ backgroundColor: theme.gradientFrom }}
                            >
                                {unreadCount > 9 ? '9+' : unreadCount}
                            </span>
                        )}
                    </button>

                    <Link
                        href="/dashboard/profile"
                        className="p-1.5 rounded-lg hover:bg-white/10 transition-colors"
                        aria-label="Profile"
                    >
                        <img
                            className="h-7 w-7 rounded-full object-cover border-2"
                            style={{ borderColor: theme.accent }}
                            src={`https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=${theme.accent.replace("#", "")}&color=fff&size=64`}
                            alt="User"
                        />
                    </Link>
                </div>
            </div>

            {/* Desktop Header */}
            <header className={`hidden lg:flex h-16 backdrop-blur-md border-b items-center justify-between px-6 sticky top-0 z-20 header-desktop transition-all duration-300 ${isDarkMode
                ? 'bg-slate-900/95 border-slate-700'
                : 'bg-white/80 border-slate-200'
                }`}>
                <div className="relative w-64">
                    <i
                        className="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-sm"
                        style={{ color: theme.accent }}
                    ></i>
                    <input
                        type="text"
                        placeholder="Cari berita atau log..."
                        className={`w-full pl-10 pr-4 py-2 border-none rounded-full text-sm focus:bg-white transition-all duration-300 header-search-input ${isDarkMode
                            ? 'bg-slate-800 text-slate-200 placeholder-slate-500'
                            : 'bg-slate-100 text-slate-800 placeholder-slate-400'
                            }`}
                        style={{
                            "--tw-ring-color": theme.accent
                        } as React.CSSProperties}
                    />
                </div>

                <div className="flex items-center gap-4">
                    {/* Dark Mode Toggle - Desktop */}
                    <DarkModeToggle
                        isDarkMode={isDarkMode}
                        onToggle={handleDarkModeToggle}
                        toggleRef={toggleBtnRef}
                    />

                    {/* Notification Button */}
                    <button
                        onClick={handleNotifToggle}
                        className={`relative transition-colors duration-150 p-2 rounded-lg ${isDarkMode ? 'hover:bg-slate-700' : 'hover:bg-slate-100'
                            }`}
                        style={{ color: notifOpen ? theme.accent : isDarkMode ? '#94a3b8' : '#64748b' }}
                    >
                        <i className="fa-regular fa-bell text-xl"></i>
                        {unreadCount > 0 && (
                            <span
                                className="absolute -top-0.5 -right-0.5 h-5 w-5 rounded-full border-2 text-[10px] text-white flex items-center justify-center font-bold animate-pulse"
                                style={{
                                    backgroundColor: theme.gradientFrom,
                                    borderColor: isDarkMode ? '#0f172a' : 'white'
                                }}
                            >
                                {unreadCount}
                            </span>
                        )}
                    </button>

                    <div className={`h-8 w-[1px] mx-1 ${isDarkMode ? 'bg-slate-700' : 'bg-slate-200'}`}></div>

                    <a
                        href="http://localhost:3000"
                        target="_blank"
                        className="text-sm font-medium flex items-center gap-2 transition-colors duration-150 hover:opacity-80"
                        style={{ color: theme.accent }}
                    >
                        <i className="fa-solid fa-arrow-up-right-from-square"></i>
                        Lihat Website
                    </a>

                    {/* User Dropdown */}
                    <div className="relative">
                        <button
                            onClick={handleDropdownToggle}
                            className={`flex items-center gap-2 px-3 py-2 rounded-lg transition-all duration-150 ${isDarkMode ? 'hover:bg-slate-700' : 'hover:bg-slate-100'
                                }`}
                        >
                            <img
                                className="h-8 w-8 rounded-full object-cover border-2"
                                style={{ borderColor: theme.accent }}
                                src={`https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=${theme.accent.replace("#", "")}&color=fff`}
                                alt="User"
                            />
                            <i className={`fa-solid fa-chevron-down text-xs transition-transform duration-150 ${dropdownOpen ? "rotate-180" : ""
                                } ${isDarkMode ? 'text-slate-400' : 'text-slate-400'}`}></i>
                        </button>

                        {dropdownVisible && (
                            <>
                                <div
                                    className={`fixed inset-0 z-40 ${dropdownExiting ? 'animate-fade-out-smooth' : 'animate-fade-in-smooth'}`}
                                    onClick={handleDropdownClose}
                                ></div>
                                <div className={`absolute right-0 mt-2 w-56 rounded-xl shadow-xl border overflow-hidden z-50 dropdown-menu ${dropdownExiting ? 'animate-slide-down-exit' : 'animate-slide-down'
                                    } ${isDarkMode ? 'bg-slate-800 border-slate-700' : 'bg-white border-slate-100'}`}>
                                    <div className="px-4 py-3 border-b theme-gradient"
                                        style={{ borderColor: isDarkMode ? '#334155' : '#e2e8f0' }}
                                    >
                                        <p className="text-sm font-medium text-white">{user.name}</p>
                                        <p className="text-xs text-white/70">{user.email}</p>
                                    </div>
                                    <Link
                                        href="/dashboard/profile"
                                        className={`flex items-center gap-3 px-4 py-3 text-sm transition-colors duration-150 ${isDarkMode
                                            ? 'text-slate-300 hover:bg-slate-700'
                                            : 'text-slate-700 hover:bg-slate-50'
                                            }`}
                                    >
                                        <i
                                            className="fa-solid fa-user w-5"
                                            style={{ color: theme.accent }}
                                        ></i>
                                        Profil Saya
                                    </Link>
                                    <Link
                                        href="/dashboard/settings"
                                        className={`flex items-center gap-3 px-4 py-3 text-sm transition-colors duration-150 ${isDarkMode
                                            ? 'text-slate-300 hover:bg-slate-700'
                                            : 'text-slate-700 hover:bg-slate-50'
                                            }`}
                                    >
                                        <i
                                            className="fa-solid fa-cog w-5"
                                            style={{ color: theme.accent }}
                                        ></i>
                                        Pengaturan
                                    </Link>
                                    <hr className={isDarkMode ? 'border-slate-700' : 'border-slate-100'} />
                                    <button
                                        onClick={onLogout}
                                        className={`flex items-center gap-3 w-full px-4 py-3 text-sm text-red-500 transition-colors duration-150 ${isDarkMode ? 'hover:bg-red-500/10' : 'hover:bg-red-50'
                                            }`}
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
        </>
    );
}
