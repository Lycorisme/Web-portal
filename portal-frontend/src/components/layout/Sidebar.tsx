"use client";

import { useState } from "react";
import { usePathname } from "next/navigation";
import Link from "next/link";
import { User } from "@/lib/auth";
import { useTheme } from "@/contexts/ThemeContext";

interface SidebarProps {
    user: User;
    sidebarOpen: boolean;
    onClose: () => void;
    onLogout: () => void;
}

function formatRole(role: string): string {
    const roleMap: Record<string, string> = {
        super_admin: "Super Admin",
        admin: "Admin",
        editor: "Editor",
    };
    return roleMap[role] || role;
}

interface NavItem {
    href: string;
    icon: string;
    label: string;
    badge?: string;
    badgeColor?: string;
    subItems?: NavItem[];
}

interface NavSection {
    title: string;
    isDanger?: boolean;
    icon?: string;
    items: NavItem[];
}

export default function Sidebar({ user, sidebarOpen, onClose, onLogout }: SidebarProps) {
    const pathname = usePathname();
    const { theme, settings } = useTheme();

    const navSections: NavSection[] = [
        {
            title: "Overview",
            items: [
                { href: "/dashboard", icon: "fa-solid fa-chart-line", label: "Dashboard" },
            ],
        },
        {
            title: "Redaksi",
            items: [
                { href: "/dashboard/articles", icon: "fa-regular fa-newspaper", label: "Artikel Berita" },
                { href: "/dashboard/categories", icon: "fa-solid fa-layer-group", label: "Kategori & Tag" },
                { href: "/dashboard/media", icon: "fa-regular fa-images", label: "Galeri Media" },
            ],
        },
        {
            title: "Security Center",
            isDanger: true,
            icon: "fa-solid fa-shield-halved",
            items: [
                { href: "/dashboard/logs", icon: "fa-solid fa-fingerprint", label: "Activity Logs" },
                { href: "/dashboard/firewall", icon: "fa-solid fa-ban", label: "Blocked IPs / Firewall", badge: "12", badgeColor: "bg-red-600" },
            ],
        },
        {
            title: "Sistem",
            items: [
                { href: "/dashboard/users", icon: "fa-solid fa-users-gear", label: "Manajemen Pengguna" },
                {
                    href: "#settings",
                    icon: "fa-solid fa-sliders",
                    label: "Pengaturan Situs",
                    subItems: [
                        { href: "/dashboard/settings/identity", icon: "fa-solid fa-globe", label: "Identitas & SEO" },
                        { href: "/dashboard/settings/appearance", icon: "fa-solid fa-palette", label: "Tampilan & Tema" },
                        { href: "/dashboard/settings/media", icon: "fa-solid fa-file-signature", label: "Media & Dokumen" },
                        { href: "/dashboard/settings/security", icon: "fa-solid fa-shield-halved", label: "Security Core" },
                    ]
                },
            ],
        },
    ];

    const [expandedMenus, setExpandedMenus] = useState<string[]>(["#settings"]);

    const toggleMenu = (href: string) => {
        setExpandedMenus(prev =>
            prev.includes(href)
                ? prev.filter(h => h !== href)
                : [...prev, href]
        );
    };

    const isActive = (href: string) => {
        if (href === "/dashboard") {
            return pathname === "/dashboard";
        }
        return pathname.startsWith(href);
    };

    const isParentActive = (item: NavItem) => {
        if (item.subItems) {
            return item.subItems.some(sub => pathname.startsWith(sub.href));
        }
        return isActive(item.href);
    };

    return (
        <>
            {/* Sidebar - z-50 to overlay header when open on mobile */}
            <aside
                className={`fixed inset-y-0 left-0 z-50 w-64 text-white transition-all duration-300 ease-out lg:static lg:translate-x-0 flex flex-col shadow-2xl ${sidebarOpen ? "translate-x-0" : "-translate-x-full"
                    }`}
                style={{ backgroundColor: theme.sidebar }}
            >
                {/* Logo Section - Desktop: Logo on top, Company name below, centered */}
                <div
                    className="hidden lg:flex flex-col items-center justify-center py-5 border-b border-white/10 sticky top-0 z-10"
                    style={{ backgroundColor: theme.sidebar }}
                >
                    {settings.logo_url ? (
                        <img
                            src={settings.logo_url}
                            alt={settings.site_name || "Logo"}
                            className="h-10 w-auto object-contain max-w-[160px] mb-2"
                        />
                    ) : (
                        <div
                            className="flex h-12 w-12 items-center justify-center rounded-xl text-white font-[family-name:var(--font-merriweather)] font-bold text-xl shadow-lg theme-gradient mb-2"
                        >
                            {settings.site_name ? settings.site_name.charAt(0).toUpperCase() : "P"}
                        </div>
                    )}
                    <span className="text-base font-bold tracking-tight font-[family-name:var(--font-merriweather)] text-center px-3">
                        {settings.site_name ? (
                            <>
                                {settings.site_name.split(" ")[0]}{" "}
                                <span className="theme-gradient-text">{settings.site_name.split(" ").slice(1).join(" ") || ""}</span>
                            </>
                        ) : (
                            <>
                                PORTAL <span className="theme-gradient-text">NEWS</span>
                            </>
                        )}
                    </span>
                </div>

                {/* Mobile Sidebar Header - Logo on top, Company name below, centered with close button */}
                <div
                    className="flex lg:hidden flex-col items-center justify-center py-4 border-b border-white/10 sticky top-0 z-10 relative"
                    style={{ backgroundColor: theme.sidebar }}
                >
                    {/* Close button - absolute positioned */}
                    <button
                        onClick={onClose}
                        className="absolute top-2 right-2 p-2 rounded-lg hover:bg-white/10 transition-colors text-slate-300 hover:text-white"
                        aria-label="Close sidebar"
                    >
                        <i className="fa-solid fa-times text-lg"></i>
                    </button>

                    {settings.logo_url ? (
                        <img
                            src={settings.logo_url}
                            alt={settings.site_name || "Logo"}
                            className="h-8 w-auto object-contain max-w-[140px] mb-1.5"
                        />
                    ) : (
                        <div className="flex h-10 w-10 items-center justify-center rounded-lg text-white font-[family-name:var(--font-merriweather)] font-bold text-lg theme-gradient mb-1.5">
                            {settings.site_name ? settings.site_name.charAt(0).toUpperCase() : "P"}
                        </div>
                    )}
                    <span className="text-sm font-bold tracking-tight font-[family-name:var(--font-merriweather)] text-center px-4">
                        {settings.site_name || "PORTAL NEWS"}
                    </span>
                </div>

                {/* Navigation */}
                <nav className="flex-1 overflow-y-auto sidebar-scroll py-6 px-3 space-y-1">
                    {navSections.map((section, sectionIndex) => (
                        <div key={sectionIndex}>
                            <p
                                className={`px-3 text-xs font-semibold uppercase tracking-wider mb-2 ${sectionIndex > 0 ? "mt-6" : "mt-2"} flex items-center gap-2`}
                                style={{ color: section.isDanger ? "#f87171" : "rgba(148, 163, 184, 0.7)" }}
                            >
                                {section.icon && <i className={`${section.icon} text-xs`}></i>}
                                {section.title}
                            </p>
                            {section.items.map((item) => {
                                const active = !item.subItems && isActive(item.href);
                                const parentActive = isParentActive(item);
                                const isExpanded = expandedMenus.includes(item.href);
                                const hasSubItems = item.subItems && item.subItems.length > 0;

                                return (
                                    <div key={item.label}>
                                        {hasSubItems ? (
                                            <button
                                                onClick={() => toggleMenu(item.href)}
                                                className="sidebar-nav-item w-full flex items-center"
                                                style={{
                                                    backgroundColor: parentActive ? `${theme.hoverColor}80` : "transparent",
                                                    borderLeft: parentActive ? `4px solid ${theme.accent}80` : "4px solid transparent",
                                                    color: parentActive ? "#fff" : "#cbd5e1",
                                                }}
                                            >
                                                <i
                                                    className={`${item.icon} w-5 text-center nav-icon`}
                                                    style={{ color: parentActive ? theme.iconAccent : "inherit" }}
                                                ></i>
                                                <span className="font-medium text-sm flex-1 text-left">{item.label}</span>
                                                <i className={`fa-solid fa-chevron-right text-xs transition-transform duration-200 ${isExpanded ? 'rotate-90' : ''}`}></i>
                                            </button>
                                        ) : (
                                            <Link
                                                href={item.href}
                                                className="sidebar-nav-item"
                                                style={{
                                                    backgroundColor: active ? theme.hoverColor : "transparent",
                                                    borderLeft: active ? `4px solid ${theme.accent}` : "4px solid transparent",
                                                    color: active ? "#fff" : "#cbd5e1",
                                                }}
                                            >
                                                <i
                                                    className={`${item.icon} w-5 text-center nav-icon`}
                                                    style={{ color: active ? theme.iconAccent : "inherit" }}
                                                ></i>
                                                <span className="font-medium text-sm">{item.label}</span>
                                                {item.badge && (
                                                    <span className={`ml-auto ${item.badgeColor || "bg-blue-600"} text-white text-[10px] px-1.5 py-0.5 rounded-full`}>
                                                        {item.badge}
                                                    </span>
                                                )}
                                            </Link>
                                        )}

                                        {/* Sub Items */}
                                        {hasSubItems && (
                                            <div
                                                className={`overflow-hidden transition-all duration-300 ease-in-out ${isExpanded ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0'}`}
                                            >
                                                <div className="mt-1 space-y-1 mb-2">
                                                    {item.subItems!.map((sub) => {
                                                        const subActive = isActive(sub.href);
                                                        return (
                                                            <Link
                                                                key={sub.href}
                                                                href={sub.href}
                                                                className="flex items-center gap-3 px-4 py-2 text-sm transition-colors rounded-r-lg mr-2 ml-4 border-l-2"
                                                                style={{
                                                                    color: subActive ? theme.accent : "#94a3b8",
                                                                    borderColor: subActive ? theme.accent : "transparent",
                                                                    backgroundColor: subActive ? `${theme.accent}10` : "transparent",
                                                                }}
                                                            >
                                                                <i className={`${sub.icon} text-xs w-4 text-center`}></i>
                                                                <span className="font-medium text-xs">{sub.label}</span>
                                                            </Link>
                                                        );
                                                    })}
                                                </div>
                                            </div>
                                        )}
                                    </div>
                                );
                            })}
                        </div>
                    ))}
                </nav>

                {/* User Profile */}
                <div
                    className="border-t border-white/10 p-4"
                    style={{ backgroundColor: theme.sidebar }}
                >
                    <div className="flex items-center gap-3">
                        <Link
                            href="/dashboard/profile"
                            className="flex items-center gap-3 flex-1 min-w-0 group hover:opacity-90 transition-opacity"
                        >
                            <div className="relative">
                                <img
                                    className="h-9 w-9 rounded-full object-cover border-2 transition-transform group-hover:scale-105"
                                    style={{ borderColor: theme.accent }}
                                    src={`https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=${theme.accent.replace("#", "")}&color=fff`}
                                    alt="User"
                                />
                                <div
                                    className="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full border-2"
                                    style={{
                                        backgroundColor: "#22c55e",
                                        borderColor: theme.sidebar
                                    }}
                                ></div>
                            </div>
                            <div className="flex-1 min-w-0">
                                <p className="text-sm font-medium text-white truncate">{user.name}</p>
                                <p className="text-xs text-slate-400 truncate">{formatRole(user.role)}</p>
                            </div>
                        </Link>
                        <button
                            onClick={onLogout}
                            className="text-slate-400 hover:text-white transition-colors duration-150 p-1.5 rounded hover:bg-white/10"
                            title="Logout"
                        >
                            <i className="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </div>
                </div>
            </aside>

            {/* Sidebar Overlay - z-40 to be below sidebar (z-50) but above header */}
            {sidebarOpen && (
                <div
                    className="fixed inset-0 bg-black/60 z-40 lg:hidden animate-fade-in-smooth backdrop-blur-sm"
                    onClick={onClose}
                ></div>
            )}
        </>
    );
}
