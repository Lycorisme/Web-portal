"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { useTheme } from "@/contexts/ThemeContext";

interface BreadcrumbItem {
    label: string;
    href: string;
    icon?: string;
    isHome?: boolean;
}

// Route map for translating paths to labels
const routeLabels: Record<string, { label: string; icon?: string }> = {
    dashboard: { label: "Dashboard", icon: "fa-solid fa-chart-line" },
    settings: { label: "Pengaturan", icon: "fa-solid fa-sliders" },
    identity: { label: "Identitas & SEO", icon: "fa-solid fa-globe" },
    appearance: { label: "Tampilan & Tema", icon: "fa-solid fa-palette" },
    media: { label: "Media & Dokumen", icon: "fa-solid fa-file-signature" },
    security: { label: "Security Core", icon: "fa-solid fa-shield-halved" },
    articles: { label: "Artikel Berita", icon: "fa-regular fa-newspaper" },
    categories: { label: "Kategori & Tag", icon: "fa-solid fa-layer-group" },
    logs: { label: "Activity Logs", icon: "fa-solid fa-fingerprint" },
    firewall: { label: "Blocked IPs", icon: "fa-solid fa-ban" },
    users: { label: "Manajemen Pengguna", icon: "fa-solid fa-users-gear" },
    profile: { label: "Profil Saya", icon: "fa-solid fa-user" },
};

// Parent segments that should be skipped in breadcrumb (only show their children)
const skipParentSegments = ["settings", "dashboard"];

export default function Breadcrumb() {
    const pathname = usePathname();
    const { theme, isDarkMode } = useTheme();

    // Don't show breadcrumb on dashboard
    if (pathname === "/dashboard") {
        return null;
    }

    const pathSegments = pathname.split("/").filter(Boolean);

    // Build breadcrumb items
    const items: BreadcrumbItem[] = [];

    // Add home icon (dashboard) - only icon, no text
    items.push({
        label: "",
        href: "/dashboard",
        icon: "fa-solid fa-house",
        isHome: true,
    });

    // Build path progressively, skipping parent segments that have children
    let currentPath = "";
    pathSegments.forEach((segment, index) => {
        currentPath += `/${segment}`;
        const routeInfo = routeLabels[segment];
        const isParentSegment = skipParentSegments.includes(segment);
        const hasNextSegment = index < pathSegments.length - 1;

        // Skip this segment if it's a parent segment with a child following
        // (e.g., skip "settings" when path is /dashboard/settings/identity)
        if (isParentSegment && hasNextSegment) {
            return;
        }

        // Skip dashboard since we already have home icon
        if (segment === "dashboard") {
            return;
        }

        if (routeInfo) {
            items.push({
                label: routeInfo.label,
                href: currentPath,
                icon: routeInfo.icon,
            });
        }
    });

    return (
        <nav className="mb-6 animate-fade-in" aria-label="Breadcrumb">
            <ol className="flex items-center gap-2 flex-wrap">
                {items.map((item, index) => {
                    const isLast = index === items.length - 1;
                    const isFirst = index === 0;
                    const isHome = item.isHome;

                    return (
                        <li key={`breadcrumb-${index}-${item.href}`} className="flex items-center gap-2">
                            {/* Separator */}
                            {!isFirst && (
                                <i
                                    className={`fa-solid fa-chevron-right text-[10px] ${isDarkMode ? 'text-slate-600' : 'text-slate-300'
                                        }`}
                                ></i>
                            )}

                            {/* Breadcrumb Item */}
                            {isLast ? (
                                <span
                                    className={`flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-semibold ${isDarkMode
                                        ? 'text-white bg-white/5'
                                        : 'text-slate-700 bg-slate-100'
                                        }`}
                                >
                                    {isHome ? (
                                        <i
                                            className={`${item.icon} text-sm`}
                                            style={{ color: theme.accent }}
                                        ></i>
                                    ) : (
                                        <>
                                            {item.icon && (
                                                <i
                                                    className={`${item.icon} text-xs`}
                                                    style={{ color: theme.accent }}
                                                ></i>
                                            )}
                                            <span>{item.label}</span>
                                        </>
                                    )}
                                </span>
                            ) : (
                                <Link
                                    href={item.href}
                                    className={`flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200 hover:scale-105 group ${isDarkMode
                                        ? 'text-slate-400 hover:text-white hover:bg-white/5'
                                        : 'text-slate-500 hover:text-slate-700 hover:bg-slate-100'
                                        }`}
                                    style={{
                                        boxShadow: 'none',
                                    }}
                                    onMouseEnter={(e) => {
                                        e.currentTarget.style.boxShadow = `0 0 0 1px ${theme.accent}30`;
                                    }}
                                    onMouseLeave={(e) => {
                                        e.currentTarget.style.boxShadow = 'none';
                                    }}
                                >
                                    {isHome ? (
                                        <i
                                            className={`${item.icon} text-sm transition-colors`}
                                            style={{ color: isDarkMode ? theme.softTint : theme.accent }}
                                        ></i>
                                    ) : (
                                        <>
                                            {item.icon && (
                                                <i
                                                    className={`${item.icon} text-xs opacity-60 group-hover:opacity-100 transition-opacity`}
                                                ></i>
                                            )}
                                            <span>{item.label}</span>
                                        </>
                                    )}
                                </Link>
                            )}
                        </li>
                    );
                })}
            </ol>
        </nav>
    );
}
