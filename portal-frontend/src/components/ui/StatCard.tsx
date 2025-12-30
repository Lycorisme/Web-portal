"use client";

import { useTheme } from "@/contexts/ThemeContext";

interface StatCardProps {
    title: string;
    value: string;
    change: string;
    changeType: "up" | "down" | "neutral";
    icon: string;
    iconBg: string;
    iconColor: string;
    highlight?: boolean;
}

export default function StatCard({
    title,
    value,
    change,
    changeType,
    icon,
    iconBg,
    iconColor,
    highlight,
}: StatCardProps) {
    const { isDarkMode } = useTheme();

    const changeColors = {
        up: "text-green-500",
        down: "text-red-500",
        neutral: isDarkMode ? "text-slate-500" : "text-slate-400",
    };

    return (
        <div
            className={`p-5 rounded-xl shadow-sm border relative overflow-hidden group hover:shadow-lg transition-all duration-300 stat-card-themed hover-glow card ${isDarkMode
                    ? `bg-slate-800 ${highlight ? "border-red-900/50" : "border-slate-700"}`
                    : `bg-white ${highlight ? "border-red-100" : "border-slate-100"}`
                }`}
        >
            {/* Background decoration */}
            {highlight ? (
                <div className={`absolute -right-6 -top-6 w-24 h-24 rounded-full z-0 group-hover:scale-125 transition-transform duration-200 ${isDarkMode ? 'bg-red-900/30' : 'bg-red-50'
                    }`}></div>
            ) : (
                <div
                    className="absolute -right-8 -top-8 w-28 h-28 rounded-full z-0 group-hover:scale-125 transition-transform duration-300 opacity-50"
                    style={{ backgroundColor: iconBg }}
                ></div>
            )}

            <div className="flex justify-between items-start z-10 relative">
                <div>
                    <p
                        className={`text-xs font-bold uppercase tracking-wide ${highlight ? "text-red-500" : ""}`}
                        style={!highlight ? { color: iconColor } : undefined}
                    >
                        {title}
                    </p>
                    <h3 className={`text-2xl font-bold mt-1 ${isDarkMode ? 'text-slate-100' : 'text-slate-800'}`}>{value}</h3>
                    <p className={`text-xs mt-2 font-medium ${changeColors[changeType]}`}>
                        {changeType === "up" && <i className="fa-solid fa-arrow-trend-up mr-1"></i>}
                        {changeType === "down" && <i className="fa-solid fa-arrow-trend-down mr-1"></i>}
                        {change}
                    </p>
                </div>
                <div
                    className="p-3 rounded-lg transition-transform group-hover:scale-110 duration-200"
                    style={{ backgroundColor: iconBg, color: iconColor }}
                >
                    <i className={`${icon} text-xl`}></i>
                </div>
            </div>
        </div>
    );
}

