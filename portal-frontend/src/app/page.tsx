"use client";

import { useState, useEffect } from "react";
import { useRouter } from "next/navigation";
import { getUser, login, isLoggedIn } from "@/lib/auth";
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
        <div className="mt-2 animate-fade-in-up">
            <div className="flex gap-1">
                {[0, 1, 2, 3].map((i) => (
                    <div
                        key={i}
                        className="h-1 flex-1 rounded-full transition-all duration-300"
                        style={{ backgroundColor: getBarColor(i) }}
                    />
                ))}
            </div>
            <p className={`text-xs mt-1 transition-colors duration-200 ${getStrengthColor()}`}>
                {getStrengthLabel()}
            </p>
        </div>
    );
}

export default function LoginPage() {
    const router = useRouter();
    const { theme, settings } = useTheme();
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [showPassword, setShowPassword] = useState(false);
    const [loading, setLoading] = useState(false);
    const [toast, setToast] = useState<ToastData>({ show: false, message: "", type: "success" });

    // Check if already logged in
    useEffect(() => {
        if (isLoggedIn()) {
            router.push("/dashboard");
        }
    }, [router]);

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

        // Call API login
        const result = await login(email, password);

        if (!result.success) {
            setLoading(false);
            showToast(result.message, "error");
            return;
        }

        showToast("Login berhasil! Mengalihkan ke dashboard...", "success");

        await new Promise((resolve) => setTimeout(resolve, 1000));
        router.push("/dashboard");
    };

    // Get site name from settings
    const siteName = settings.site_name || "PORTALNEWS";
    const siteNameParts = siteName.split(" ");
    const firstWord = siteNameParts[0] || "PORTAL";
    const restWords = siteNameParts.slice(1).join(" ") || "NEWS";

    return (
        <div className="h-screen w-full overflow-hidden font-[family-name:var(--font-inter)] text-slate-600">
            <div className="flex min-h-full flex-col lg:flex-row">
                {/* Mobile Header */}
                <div
                    className="relative h-64 w-full lg:hidden overflow-hidden"
                    style={{ backgroundColor: theme.sidebar }}
                >
                    <img
                        src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?q=80&w=2070&auto=format&fit=crop"
                        className="absolute inset-0 h-full w-full object-cover opacity-60 mix-blend-overlay"
                        alt="Newsroom"
                    />
                    <div className="absolute inset-0 bg-gradient-to-b from-transparent to-slate-50/90"></div>

                    <div className="absolute top-6 left-6 flex items-center gap-2">
                        <div
                            className="flex h-8 w-8 items-center justify-center rounded text-white font-[family-name:var(--font-merriweather)] font-bold shadow-lg"
                            style={{ background: `linear-gradient(135deg, ${theme.gradientFrom}, ${theme.gradientTo})` }}
                        >
                            {firstWord.charAt(0)}
                        </div>
                        <span className="text-xl font-bold tracking-tight text-white font-[family-name:var(--font-merriweather)] drop-shadow-md">
                            {firstWord}<span style={{ color: theme.gradientFrom }}>{restWords}</span>
                        </span>
                    </div>
                </div>

                {/* Login Form Section */}
                <div className="flex flex-1 flex-col justify-center px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24 bg-slate-50 relative -mt-20 lg:mt-0 rounded-t-[2rem] lg:rounded-none z-10 lg:w-1/2">
                    <div className="mx-auto w-full max-w-sm lg:w-96 animate-fade-in-up">
                        {/* Desktop Logo */}
                        <div className="hidden lg:flex items-center gap-3 mb-10">
                            <div
                                className="flex h-10 w-10 items-center justify-center rounded text-xl font-bold text-white font-[family-name:var(--font-merriweather)] shadow-md"
                                style={{ background: `linear-gradient(135deg, ${theme.gradientFrom}, ${theme.gradientTo})` }}
                            >
                                {firstWord.charAt(0)}
                            </div>
                            <span
                                className="text-2xl font-bold tracking-tight font-[family-name:var(--font-merriweather)]"
                                style={{ color: theme.sidebar }}
                            >
                                {firstWord}<span style={{ color: theme.gradientFrom }}>{restWords}</span>
                            </span>
                        </div>

                        {/* Header Text */}
                        <div className="text-center lg:text-left mb-8">
                            <h2
                                className="text-2xl font-bold leading-9 tracking-tight"
                                style={{ color: theme.sidebar }}
                            >
                                Masuk ke Ruang Redaksi
                            </h2>
                            <p className="mt-2 text-sm leading-6 text-gray-500">
                                {settings.site_tagline || "Kelola berita, pantau trafik, dan amankan sistem."}
                            </p>
                        </div>

                        {/* Login Form */}
                        <form onSubmit={handleSubmit} className="space-y-6">
                            {/* Email Field */}
                            <div>
                                <label
                                    htmlFor="email"
                                    className="block text-sm font-medium leading-6"
                                    style={{ color: theme.sidebar }}
                                >
                                    Email Redaksi
                                </label>
                                <div className="relative mt-2 group">
                                    <div className="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <i
                                            className="fa-regular fa-envelope text-gray-400 group-focus-within:text-current transition-colors duration-300"
                                            style={{ color: email ? theme.accent : undefined }}
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
                                        className="block w-full rounded-md border-0 py-3 pl-10 text-slate-800 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6 transition-all duration-200"
                                        style={{
                                            "--tw-ring-color": theme.accent
                                        } as React.CSSProperties}
                                        placeholder={settings.site_email || "nama@portalnews.id"}
                                    />
                                </div>
                            </div>

                            {/* Password Field */}
                            <div>
                                <label
                                    htmlFor="password"
                                    className="block text-sm font-medium leading-6"
                                    style={{ color: theme.sidebar }}
                                >
                                    Kata Sandi
                                </label>
                                <div className="relative mt-2 group">
                                    <div className="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <i
                                            className="fa-solid fa-lock text-gray-400 group-focus-within:text-current transition-colors duration-300"
                                            style={{ color: password ? theme.accent : undefined }}
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
                                        className="block w-full rounded-md border-0 py-3 pl-10 pr-10 text-slate-800 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6 transition-all duration-200"
                                        style={{
                                            "--tw-ring-color": theme.accent
                                        } as React.CSSProperties}
                                        placeholder="••••••••"
                                    />
                                    <button
                                        type="button"
                                        onClick={() => setShowPassword(!showPassword)}
                                        className="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-gray-400 hover:text-gray-600 transition-colors duration-200 focus:outline-none"
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
                                <PasswordStrength password={password} theme={theme} />
                            </div>

                            {/* Remember Me & Forgot Password */}
                            <div className="flex items-center justify-between">
                                <div className="flex items-center">
                                    <input
                                        id="remember-me"
                                        name="remember-me"
                                        type="checkbox"
                                        className="h-4 w-4 rounded border-gray-300 cursor-pointer"
                                        style={{
                                            accentColor: theme.accent
                                        }}
                                    />
                                    <label
                                        htmlFor="remember-me"
                                        className="ml-2 block text-sm leading-6 text-gray-700 cursor-pointer"
                                    >
                                        Ingat perangkat ini
                                    </label>
                                </div>
                                <div className="text-sm leading-6">
                                    <a
                                        href="#"
                                        className="font-semibold hover:opacity-80 transition-colors"
                                        style={{ color: theme.accent }}
                                    >
                                        Lupa sandi?
                                    </a>
                                </div>
                            </div>

                            {/* Submit Button */}
                            <div>
                                <button
                                    type="submit"
                                    disabled={loading}
                                    className={`flex w-full justify-center items-center gap-2 rounded-md px-3 py-3 text-sm font-bold leading-6 text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 transition-all duration-300 transform hover:scale-[1.02] disabled:hover:scale-100 ${loading ? "opacity-75 cursor-not-allowed" : ""
                                        }`}
                                    style={{
                                        background: `linear-gradient(135deg, ${theme.gradientFrom}, ${theme.gradientVia || theme.gradientFrom}, ${theme.gradientTo})`,
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
                        <div className="mt-10 border-t border-gray-200 pt-6">
                            <div
                                className="flex items-center justify-center gap-2 text-xs text-gray-500 bg-white/50 py-2 rounded-full border border-gray-100 shadow-sm"
                            >
                                <span className="relative flex h-2 w-2">
                                    <span
                                        className="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"
                                        style={{ backgroundColor: theme.gradientFrom }}
                                    ></span>
                                    <span
                                        className="relative inline-flex rounded-full h-2 w-2"
                                        style={{ backgroundColor: theme.accent }}
                                    ></span>
                                </span>
                                <span className="font-medium">Sistem Keamanan Aktif</span>
                                <span className="text-gray-300">|</span>
                                <span>v1.2.0 (Next.js)</span>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Right Panel - Desktop Only */}
                <div
                    className="relative hidden w-0 flex-1 lg:block overflow-hidden"
                    style={{ backgroundColor: theme.sidebar }}
                >
                    <img
                        className="absolute inset-0 h-full w-full object-cover opacity-30 mix-blend-overlay scale-105"
                        src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?q=80&w=2070&auto=format&fit=crop"
                        alt="Newsroom background"
                    />

                    <div className="absolute inset-0 bg-gradient-to-t from-current via-transparent to-transparent" style={{ color: theme.sidebar }}></div>

                    {/* Animated Blobs */}
                    <div
                        className="absolute top-0 -left-4 w-96 h-96 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 animate-blob"
                        style={{ backgroundColor: theme.gradientFrom }}
                    ></div>
                    <div
                        className="absolute bottom-0 -right-4 w-96 h-96 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 animate-blob animation-delay-2000"
                        style={{ backgroundColor: theme.gradientTo }}
                    ></div>

                    {/* Scrolling News Bar */}
                    <div
                        className="absolute top-10 left-0 right-0 backdrop-blur border-y py-2 overflow-hidden"
                        style={{
                            backgroundColor: `${theme.gradientFrom}15`,
                            borderColor: `${theme.gradientFrom}30`
                        }}
                    >
                        <div
                            className="whitespace-nowrap animate-scroll flex items-center gap-8 font-mono text-xs font-bold tracking-widest uppercase"
                            style={{ color: theme.gradientFrom }}
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
                            className="glass-panel rounded-2xl p-8 max-w-md w-full transform transition hover:scale-105 duration-500 shadow-2xl"
                            style={{ borderLeftColor: theme.gradientFrom, borderLeftWidth: "4px" }}
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
                                    style={{ color: theme.accent }}
                                ></i>
                            </div>

                            <div className="space-y-5">
                                <div>
                                    <div className="flex justify-between text-xs font-medium mb-2">
                                        <span className="text-slate-300">Traffic Load (Redis Cache)</span>
                                        <span style={{ color: theme.accent }}>Stable (45ms)</span>
                                    </div>
                                    <div className="w-full bg-slate-700/50 rounded-full h-2">
                                        <div
                                            className="h-2 rounded-full relative overflow-hidden"
                                            style={{ width: "45%", backgroundColor: theme.gradientFrom }}
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
                                style={{ color: theme.gradientFrom }}
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
