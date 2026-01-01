"use client";

import { useState, useEffect } from "react";
import { useRouter } from "next/navigation";
import { getUser, login, isLoggedIn, wasDeviceRemembered } from "@/lib/auth";
import { useTheme } from "@/contexts/ThemeContext";
import Toast, { ToastData } from "@/components/ui/Toast";

// Password Strength Indicator
function PasswordStrength({ password, theme }: { password: string; theme: any }) {
    if (password.length === 0) return null;

    const getStrengthLabel = () => {
        if (password.length >= 12) return "Sangat Kuat";
        if (password.length >= 8) return "Kuat";
        if (password.length >= 4) return "Sedang";
        return "Lemah";
    };

    const getStrengthColor = () => {
        if (password.length >= 8) return "text-green-600";
        if (password.length >= 4) return "text-yellow-600";
        return "text-red-500";
    };

    const getBarColor = (index: number) => {
        if (index === 0) {
            if (password.length >= 8) return theme.gradientTo;
            if (password.length >= 4) return "#eab308";
            if (password.length >= 1) return "#f87171";
            return "#e2e8f0";
        }
        if (index === 1) {
            if (password.length >= 8) return theme.gradientVia || theme.gradientFrom;
            if (password.length >= 4) return "#eab308";
            return "#e2e8f0";
        }
        if (index === 2) {
            if (password.length >= 8) return theme.gradientFrom;
            return "#e2e8f0";
        }
        if (password.length >= 12) return theme.accent;
        return "#e2e8f0";
    };

    return (
        <div className="mt-2 login-animate-in" style={{ animationDelay: "0.1s" }}>
            <div className="flex gap-1">
                {[0, 1, 2, 3].map((i) => (
                    <div
                        key={i}
                        className="h-1 flex-1 rounded-full transition-all duration-200"
                        style={{ backgroundColor: getBarColor(i) }}
                    />
                ))}
            </div>
            <p className={`text-xs mt-1 transition-colors duration-150 ${getStrengthColor()}`}>
                {getStrengthLabel()}
            </p>
        </div>
    );
}

// Default SSR-safe values - MUST match exactly on server and client initial render
const DEFAULT_THEME = {
    sidebar: "#061122",
    gradientFrom: "#0ea5e9",
    gradientVia: "#3b82f6",
    gradientTo: "#6366f1",
    accent: "#3b82f6",
    primary: "#0a1628",
    hoverColor: "#0f2847",
    softTint: "#7dd3fc",
};
const DEFAULT_SITE_NAME = "PORTALNEWS";
const DEFAULT_SITE_TAGLINE = "Kelola berita, pantau trafik, dan amankan sistem.";
const DEFAULT_SITE_EMAIL = "nama@portalnews.id";

export default function LoginPage() {
    const router = useRouter();
    const { theme, settings, isDarkMode } = useTheme();
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [showPassword, setShowPassword] = useState(false);
    const [loading, setLoading] = useState(false);
    const [toast, setToast] = useState<ToastData>({ show: false, message: "", type: "success" });
    const [mounted, setMounted] = useState(false);
    const [rememberDevice, setRememberDevice] = useState(false);

    // Set mounted state after hydration and check if device was previously remembered
    useEffect(() => {
        setMounted(true);
        // Auto-check the box if device was previously remembered
        if (wasDeviceRemembered()) {
            setRememberDevice(true);
        }
    }, []);

    // Check if already logged in
    useEffect(() => {
        if (mounted && isLoggedIn()) {
            router.push("/dashboard");
        }
    }, [router, mounted]);

    const showToast = (message: string, type: ToastData["type"]) => {
        setToast({ show: true, message, type });
        setTimeout(() => setToast((prev) => ({ ...prev, show: false })), 4000);
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        if (loading) return;

        if (!email || !password) {
            showToast("Harap isi email dan kata sandi!", "error");
            return;
        }

        setLoading(true);

        // Call API login with remember device option
        const result = await login(email, password, rememberDevice);

        if (!result.success) {
            setLoading(false);
            showToast(result.message, "error");
            return;
        }

        showToast("Login berhasil! Mengalihkan ke dashboard...", "success");

        await new Promise((resolve) => setTimeout(resolve, 1000));
        router.push("/dashboard");
    };

    // Use actual values only after mounting to prevent hydration mismatch
    // For SSR, use default values that will be the same on server and client
    const currentTheme = mounted ? theme : DEFAULT_THEME;
    const siteName = mounted ? (settings.site_name || DEFAULT_SITE_NAME) : DEFAULT_SITE_NAME;
    const siteTagline = mounted ? (settings.site_tagline || DEFAULT_SITE_TAGLINE) : DEFAULT_SITE_TAGLINE;
    const siteEmail = mounted ? (settings.site_email || DEFAULT_SITE_EMAIL) : DEFAULT_SITE_EMAIL;
    const logoUrl = mounted ? (settings.logo_url || "") : "";
    const currentIsDarkMode = mounted ? isDarkMode : false;

    const siteNameParts = siteName.split(" ");
    const firstWord = siteNameParts[0] || "PORTAL";
    const restWords = siteNameParts.slice(1).join(" ") || "NEWS";

    // Dynamic background based on dark mode and theme
    const pageBackground = currentIsDarkMode
        ? currentTheme.primary
        : "#f8fafc";

    const formSectionBg = currentIsDarkMode
        ? `linear-gradient(180deg, ${currentTheme.sidebar}ee, ${currentTheme.primary})`
        : "#f8fafc";

    const textColor = currentIsDarkMode ? "#f1f5f9" : "#1e293b";
    const mutedTextColor = currentIsDarkMode ? "#94a3b8" : "#6b7280";
    const inputBg = currentIsDarkMode ? `${currentTheme.sidebar}` : "#ffffff";
    const inputBorder = currentIsDarkMode ? `${currentTheme.accent}30` : "#e2e8f0";
    const inputText = currentIsDarkMode ? "#f1f5f9" : "#1e293b";

    return (
        <div className="h-screen w-full overflow-hidden font-[family-name:var(--font-inter)]" style={{ backgroundColor: pageBackground, color: textColor }}>
            <div className="flex min-h-full flex-col lg:flex-row">
                {/* Mobile Header */}
                <div
                    className="relative h-64 w-full lg:hidden overflow-hidden"
                    style={{ backgroundColor: currentTheme.sidebar }}
                >
                    <img
                        src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?q=80&w=2070&auto=format&fit=crop"
                        className="absolute inset-0 h-full w-full object-cover opacity-60 mix-blend-overlay"
                        alt="Newsroom"
                    />
                    <div className="absolute inset-0 bg-gradient-to-b from-transparent to-slate-50/90"></div>

                    <div className="absolute top-6 left-6 flex items-center gap-2">
                        {logoUrl ? (
                            <img
                                src={logoUrl}
                                alt={siteName}
                                className="h-8 w-8 rounded object-cover shadow-lg"
                            />
                        ) : (
                            <div
                                className="flex h-8 w-8 items-center justify-center rounded text-white font-[family-name:var(--font-merriweather)] font-bold shadow-lg"
                                style={{ background: `linear-gradient(135deg, ${currentTheme.gradientFrom}, ${currentTheme.gradientTo})` }}
                            >
                                {firstWord.charAt(0)}
                            </div>
                        )}
                        <span className="text-xl font-bold tracking-tight text-white font-[family-name:var(--font-merriweather)] drop-shadow-md">
                            {firstWord}<span style={{ color: currentTheme.gradientFrom }}>{restWords}</span>
                        </span>
                    </div>
                </div>

                {/* Login Form Section */}
                <div className="flex flex-1 flex-col justify-center px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24 relative -mt-20 lg:mt-0 rounded-t-[2rem] lg:rounded-none z-10 lg:w-1/2" style={{ background: formSectionBg }}>
                    <div className="mx-auto w-full max-w-sm lg:w-96 login-animate-in">
                        {/* Desktop Logo */}
                        <div className="hidden lg:flex items-center gap-3 mb-10">
                            {logoUrl ? (
                                <img
                                    src={logoUrl}
                                    alt={siteName}
                                    className="h-10 w-10 rounded object-cover shadow-md"
                                />
                            ) : (
                                <div
                                    className="flex h-10 w-10 items-center justify-center rounded text-xl font-bold text-white font-[family-name:var(--font-merriweather)] shadow-md"
                                    style={{ background: `linear-gradient(135deg, ${currentTheme.gradientFrom}, ${currentTheme.gradientTo})` }}
                                >
                                    {firstWord.charAt(0)}
                                </div>
                            )}
                            <span
                                className="text-2xl font-bold tracking-tight font-[family-name:var(--font-merriweather)]"
                                style={{ color: currentTheme.sidebar }}
                            >
                                {firstWord}<span style={{ color: currentTheme.gradientFrom }}>{restWords}</span>
                            </span>
                        </div>

                        {/* Header Text */}
                        <div className="text-center lg:text-left mb-8">
                            <h2
                                className="text-2xl font-bold leading-9 tracking-tight"
                                style={{ color: currentTheme.sidebar }}
                            >
                                Masuk ke Ruang Redaksi
                            </h2>
                            <p className="mt-2 text-sm leading-6" style={{ color: mutedTextColor }}>
                                {siteTagline}
                            </p>
                        </div>

                        {/* Login Form */}
                        <form onSubmit={handleSubmit} className="space-y-6">
                            {/* Email Field */}
                            <div className="login-animate-in" style={{ animationDelay: "0.1s" }}>
                                <label
                                    htmlFor="email"
                                    className="block text-sm font-medium leading-6"
                                    style={{ color: textColor }}
                                >
                                    Email Redaksi
                                </label>
                                <div className="relative mt-2 group">
                                    <div className="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <i
                                            className="fa-regular fa-envelope transition-colors duration-150"
                                            style={{ color: email ? currentTheme.accent : mutedTextColor }}
                                        ></i>
                                    </div>
                                    <input
                                        id="email"
                                        name="email"
                                        type="email"
                                        autoComplete="email"
                                        required
                                        value={email}
                                        onChange={(e) => setEmail(e.target.value)}
                                        className="block w-full rounded-md border py-3 pl-10 shadow-sm focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6 transition-all duration-150"
                                        style={{
                                            backgroundColor: inputBg,
                                            borderColor: inputBorder,
                                            color: inputText,
                                            "--tw-ring-color": currentTheme.accent
                                        } as React.CSSProperties}
                                        placeholder={siteEmail}
                                    />
                                </div>
                            </div>

                            {/* Password Field */}
                            <div className="login-animate-in" style={{ animationDelay: "0.15s" }}>
                                <label
                                    htmlFor="password"
                                    className="block text-sm font-medium leading-6"
                                    style={{ color: textColor }}
                                >
                                    Kata Sandi
                                </label>
                                <div className="relative mt-2 group">
                                    <div className="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <i
                                            className="fa-solid fa-lock transition-colors duration-150"
                                            style={{ color: password ? currentTheme.accent : mutedTextColor }}
                                        ></i>
                                    </div>
                                    <input
                                        id="password"
                                        name="password"
                                        type={showPassword ? "text" : "password"}
                                        autoComplete="current-password"
                                        required
                                        value={password}
                                        onChange={(e) => setPassword(e.target.value)}
                                        className="block w-full rounded-md border py-3 pl-10 pr-10 shadow-sm focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6 transition-all duration-150"
                                        style={{
                                            backgroundColor: inputBg,
                                            borderColor: inputBorder,
                                            color: inputText,
                                            "--tw-ring-color": currentTheme.accent
                                        } as React.CSSProperties}
                                        placeholder="••••••••"
                                    />
                                    <button
                                        type="button"
                                        onClick={() => setShowPassword(!showPassword)}
                                        className="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer transition-colors duration-150 focus:outline-none"
                                        style={{ color: mutedTextColor }}
                                    >
                                        <i
                                            className={
                                                showPassword
                                                    ? "fa-solid fa-eye-slash"
                                                    : "fa-regular fa-eye"
                                            }
                                        ></i>
                                    </button>
                                </div>
                                <PasswordStrength password={password} theme={currentTheme} />
                            </div>

                            {/* Remember Me & Forgot Password */}
                            <div className="flex items-center justify-between login-animate-in" style={{ animationDelay: "0.2s" }}>
                                <div className="flex items-center">
                                    <input
                                        id="remember-me"
                                        name="remember-me"
                                        type="checkbox"
                                        checked={rememberDevice}
                                        onChange={(e) => setRememberDevice(e.target.checked)}
                                        className="h-4 w-4 rounded cursor-pointer"
                                        style={{
                                            accentColor: currentTheme.accent,
                                            borderColor: inputBorder
                                        }}
                                    />
                                    <label
                                        htmlFor="remember-me"
                                        className="ml-2 block text-sm leading-6 cursor-pointer"
                                        style={{ color: mutedTextColor }}
                                    >
                                        Ingat perangkat ini
                                    </label>
                                </div>
                                <div className="text-sm leading-6">
                                    <a
                                        href="#"
                                        className="font-semibold hover:opacity-80 transition-opacity duration-150"
                                        style={{ color: currentTheme.accent }}
                                    >
                                        Lupa sandi?
                                    </a>
                                </div>
                            </div>

                            {/* Submit Button */}
                            <div className="login-animate-in" style={{ animationDelay: "0.25s" }}>
                                <button
                                    type="submit"
                                    disabled={loading}
                                    className={`flex w-full justify-center items-center gap-2 rounded-md px-3 py-3 text-sm font-bold leading-6 text-white shadow-lg focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 transition-all duration-200 transform hover:scale-[1.01] hover:shadow-xl disabled:hover:scale-100 ${loading ? "opacity-75 cursor-not-allowed" : ""
                                        }`}
                                    style={{
                                        background: `linear-gradient(135deg, ${currentTheme.gradientFrom}, ${currentTheme.gradientVia || currentTheme.gradientFrom}, ${currentTheme.gradientTo})`,
                                        boxShadow: `0 4px 20px ${currentTheme.gradientFrom}40`
                                    }}
                                >
                                    {loading && (
                                        <svg
                                            className="animate-spin h-5 w-5 text-white"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                        >
                                            <circle
                                                className="opacity-25"
                                                cx="12"
                                                cy="12"
                                                r="10"
                                                stroke="currentColor"
                                                strokeWidth="4"
                                            ></circle>
                                            <path
                                                className="opacity-75"
                                                fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                            ></path>
                                        </svg>
                                    )}
                                    <span>{loading ? "MEMPROSES..." : "MASUK KE DASHBOARD"}</span>
                                </button>
                            </div>
                        </form>

                        {/* Footer */}
                        <div className="mt-10 pt-6 login-animate-in" style={{ animationDelay: "0.3s", borderTop: `1px solid ${inputBorder}` }}>
                            <div
                                className="flex items-center justify-center gap-2 text-xs py-2 rounded-full shadow-sm"
                                style={{
                                    backgroundColor: currentIsDarkMode ? `${currentTheme.sidebar}80` : "rgba(255,255,255,0.5)",
                                    color: mutedTextColor,
                                    border: `1px solid ${inputBorder}`
                                }}
                            >
                                <span className="relative flex h-2 w-2">
                                    <span
                                        className="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"
                                        style={{ backgroundColor: currentTheme.gradientFrom }}
                                    ></span>
                                    <span
                                        className="relative inline-flex rounded-full h-2 w-2"
                                        style={{ backgroundColor: currentTheme.accent }}
                                    ></span>
                                </span>
                                <span className="font-medium">Sistem Keamanan Aktif</span>
                                <span style={{ color: inputBorder }}>|</span>
                                <span>v1.2.0 (Next.js)</span>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Right Panel - Desktop Only */}
                <div
                    className="relative hidden w-0 flex-1 lg:block overflow-hidden"
                    style={{ backgroundColor: currentTheme.sidebar }}
                >
                    <img
                        className="absolute inset-0 h-full w-full object-cover opacity-30 mix-blend-overlay scale-105"
                        src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?q=80&w=2070&auto=format&fit=crop"
                        alt="Newsroom background"
                    />

                    <div className="absolute inset-0 bg-gradient-to-t from-current via-transparent to-transparent" style={{ color: currentTheme.sidebar }}></div>

                    {/* Animated Blobs */}
                    <div
                        className="absolute top-0 -left-4 w-96 h-96 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 animate-blob"
                        style={{ backgroundColor: currentTheme.gradientFrom }}
                    ></div>
                    <div
                        className="absolute bottom-0 -right-4 w-96 h-96 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 animate-blob animation-delay-2000"
                        style={{ backgroundColor: currentTheme.gradientTo }}
                    ></div>

                    {/* Scrolling News Bar */}
                    <div
                        className="absolute top-10 left-0 right-0 backdrop-blur border-y py-2 overflow-hidden"
                        style={{
                            backgroundColor: `${currentTheme.gradientFrom}15`,
                            borderColor: `${currentTheme.gradientFrom}30`
                        }}
                    >
                        <div
                            className="whitespace-nowrap animate-scroll flex items-center gap-8 font-mono text-xs font-bold tracking-widest uppercase"
                            style={{ color: currentTheme.gradientFrom }}
                        >
                            <span>BREAKING: SISTEM PORTAL BERITA MENDETEKSI LONJAKAN TRAFIK AMAN</span>
                            <span>•</span>
                            <span>UPDATE: FITUR RATE LIMITING BERHASIL MEMBLOKIR 15 IP MENCURIGAKAN</span>
                            <span>•</span>
                            <span>INFO: MAINTENANCE SERVER DIJADWALKAN PUKUL 02:00 WIB</span>
                            <span>•</span>
                            <span>SECURITY: HTML PURIFIER AKTIF MENCEGAH XSS</span>
                            <span>•</span>
                        </div>
                    </div>

                    {/* Status Panel */}
                    <div className="absolute inset-0 flex flex-col items-center justify-center p-12">
                        <div
                            className="glass-panel rounded-2xl p-8 max-w-md w-full transform transition hover:scale-[1.02] duration-300 shadow-2xl"
                            style={{ borderLeftColor: currentTheme.gradientFrom, borderLeftWidth: "4px" }}
                        >
                            <div className="flex justify-between items-start mb-6">
                                <div>
                                    <h3 className="text-white font-bold text-xl font-[family-name:var(--font-merriweather)]">
                                        Status Keamanan
                                    </h3>
                                    <p className="text-slate-400 text-xs uppercase tracking-wider mt-1">
                                        Realtime Monitoring Log
                                    </p>
                                </div>
                                <i
                                    className="fa-solid fa-shield-cat text-2xl"
                                    style={{ color: currentTheme.accent }}
                                ></i>
                            </div>

                            <div className="space-y-5">
                                <div>
                                    <div className="flex justify-between text-xs font-medium mb-2">
                                        <span className="text-slate-300">Traffic Load (Redis Cache)</span>
                                        <span style={{ color: currentTheme.accent }}>Stable (45ms)</span>
                                    </div>
                                    <div className="w-full bg-slate-700/50 rounded-full h-2">
                                        <div
                                            className="h-2 rounded-full relative overflow-hidden"
                                            style={{ width: "45%", backgroundColor: currentTheme.gradientFrom }}
                                        >
                                            <div className="absolute inset-0 bg-white/20 animate-pulse"></div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <div className="flex justify-between text-xs font-medium mb-2">
                                        <span className="text-slate-300">Blocked Attempts (24h)</span>
                                        <span className="text-red-400">12 IP Detected</span>
                                    </div>
                                    <div className="w-full bg-slate-700/50 rounded-full h-2">
                                        <div
                                            className="bg-red-500 h-2 rounded-full"
                                            style={{ width: "15%" }}
                                        ></div>
                                    </div>
                                </div>

                                <div className="pt-4 flex items-start gap-3 border-t border-slate-700/50 mt-4">
                                    <div className="bg-slate-800 p-2 rounded text-slate-300">
                                        <i className="fa-solid fa-terminal text-sm"></i>
                                    </div>
                                    <div>
                                        <p className="text-white text-xs font-bold font-mono">
                                            ACTIVITY_LOG::WRITE
                                        </p>
                                        <p className="text-slate-500 text-[10px] mt-0.5">
                                            Mencatat semua aktivitas admin ke database untuk audit trail.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Quote */}
                        <div className="mt-12 text-center max-w-lg">
                            <p className="text-slate-400 font-[family-name:var(--font-merriweather)] italic text-lg leading-relaxed">
                                &quot;The truth is not always beautiful, nor beautiful words the truth.&quot;
                            </p>
                            <p
                                className="text-sm font-bold mt-2 tracking-widest uppercase"
                                style={{ color: currentTheme.gradientFrom }}
                            >
                                - Editorial System
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {/* Toast Notification */}
            <Toast
                data={toast}
                onClose={() => setToast((prev) => ({ ...prev, show: false }))}
            />
        </div>
    );
}
