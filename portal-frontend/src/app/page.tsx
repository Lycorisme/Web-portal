"use client";

import { useState, useEffect } from "react";
import { useRouter } from "next/navigation";
import { getUser, login, isLoggedIn } from "@/lib/auth";

// Toast Component
function Toast({
    show,
    message,
    type,
    onClose,
}: {
    show: boolean;
    message: string;
    type: "success" | "error" | "info";
    onClose: () => void;
}) {
    if (!show) return null;

    const bgColor =
        type === "success"
            ? "bg-green-500"
            : type === "error"
                ? "bg-red-500"
                : "bg-blue-500";

    const icon =
        type === "success"
            ? "fa-check-circle"
            : type === "error"
                ? "fa-times-circle"
                : "fa-info-circle";

    return (
        <div className="fixed bottom-6 right-6 z-50 animate-fade-in-up">
            <div
                className={`${bgColor} text-white px-6 py-4 rounded-lg shadow-2xl flex items-center gap-3 min-w-[300px]`}
            >
                <i className={`fa-solid ${icon} text-xl`}></i>
                <span className="font-medium">{message}</span>
                <button
                    onClick={onClose}
                    className="ml-auto hover:opacity-75 transition-opacity"
                >
                    <i className="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>
    );
}

// Password Strength Indicator
function PasswordStrength({ password }: { password: string }) {
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
            if (password.length >= 8) return "bg-green-500";
            if (password.length >= 4) return "bg-yellow-500";
            if (password.length >= 1) return "bg-red-400";
            return "bg-gray-200";
        }
        if (index === 1) {
            if (password.length >= 8) return "bg-green-500";
            if (password.length >= 4) return "bg-yellow-500";
            return "bg-gray-200";
        }
        if (index === 2) {
            if (password.length >= 8) return "bg-green-500";
            return "bg-gray-200";
        }
        if (password.length >= 12) return "bg-green-500";
        return "bg-gray-200";
    };

    return (
        <div className="mt-2 animate-fade-in-up">
            <div className="flex gap-1">
                {[0, 1, 2, 3].map((i) => (
                    <div
                        key={i}
                        className={`h-1 flex-1 rounded-full transition-all duration-300 ${getBarColor(
                            i
                        )}`}
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
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [showPassword, setShowPassword] = useState(false);
    const [loading, setLoading] = useState(false);
    const [toast, setToast] = useState<{
        show: boolean;
        message: string;
        type: "success" | "error" | "info";
    }>({ show: false, message: "", type: "success" });

    // Check if already logged in
    useEffect(() => {
        if (isLoggedIn()) {
            router.push("/dashboard");
        }
    }, [router]);

    const showToast = (message: string, type: "success" | "error" | "info") => {
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

    return (
        <div className="h-screen w-full overflow-hidden font-[family-name:var(--font-inter)] text-slate-600">
            <div className="flex min-h-full flex-col lg:flex-row">
                {/* Mobile Header */}
                <div className="relative h-64 w-full bg-[#0f172a] lg:hidden overflow-hidden">
                    <img
                        src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?q=80&w=2070&auto=format&fit=crop"
                        className="absolute inset-0 h-full w-full object-cover opacity-60 mix-blend-overlay"
                        alt="Newsroom"
                    />
                    <div className="absolute inset-0 bg-gradient-to-b from-transparent to-slate-50/90"></div>

                    <div className="absolute top-6 left-6 flex items-center gap-2">
                        <div className="flex h-8 w-8 items-center justify-center rounded bg-[#dc2626] text-white font-[family-name:var(--font-merriweather)] font-bold shadow-lg">
                            P
                        </div>
                        <span className="text-xl font-bold tracking-tight text-white font-[family-name:var(--font-merriweather)] drop-shadow-md">
                            PORTAL<span className="text-[#dc2626]">NEWS</span>
                        </span>
                    </div>
                </div>

                {/* Login Form Section */}
                <div className="flex flex-1 flex-col justify-center px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24 bg-slate-50 relative -mt-20 lg:mt-0 rounded-t-[2rem] lg:rounded-none z-10 lg:w-1/2">
                    <div className="mx-auto w-full max-w-sm lg:w-96 animate-fade-in-up">
                        {/* Desktop Logo */}
                        <div className="hidden lg:flex items-center gap-3 mb-10">
                            <div className="flex h-10 w-10 items-center justify-center rounded bg-[#dc2626] text-xl font-bold text-white font-[family-name:var(--font-merriweather)] shadow-md">
                                P
                            </div>
                            <span className="text-2xl font-bold tracking-tight text-[#0f172a] font-[family-name:var(--font-merriweather)]">
                                PORTAL<span className="text-[#dc2626]">NEWS</span>
                            </span>
                        </div>

                        {/* Header Text */}
                        <div className="text-center lg:text-left mb-8">
                            <h2 className="text-2xl font-bold leading-9 tracking-tight text-[#0f172a]">
                                Masuk ke Ruang Redaksi
                            </h2>
                            <p className="mt-2 text-sm leading-6 text-gray-500">
                                Kelola berita, pantau trafik, dan amankan sistem.
                            </p>
                        </div>

                        {/* Login Form */}
                        <form onSubmit={handleSubmit} className="space-y-6">
                            {/* Email Field */}
                            <div>
                                <label
                                    htmlFor="email"
                                    className="block text-sm font-medium leading-6 text-[#0f172a]"
                                >
                                    Email Redaksi
                                </label>
                                <div className="relative mt-2 group">
                                    <div className="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <i className="fa-regular fa-envelope text-gray-400 group-focus-within:text-[#2563eb] transition-colors duration-300"></i>
                                    </div>
                                    <input
                                        id="email"
                                        name="email"
                                        type="email"
                                        autoComplete="email"
                                        required
                                        value={email}
                                        onChange={(e) => setEmail(e.target.value)}
                                        className="block w-full rounded-md border-0 py-3 pl-10 text-[#0f172a] shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-[#2563eb] sm:text-sm sm:leading-6 transition-all duration-200"
                                        placeholder="nama@portalnews.id"
                                    />
                                </div>
                            </div>

                            {/* Password Field */}
                            <div>
                                <label
                                    htmlFor="password"
                                    className="block text-sm font-medium leading-6 text-[#0f172a]"
                                >
                                    Kata Sandi
                                </label>
                                <div className="relative mt-2 group">
                                    <div className="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <i className="fa-solid fa-lock text-gray-400 group-focus-within:text-[#2563eb] transition-colors duration-300"></i>
                                    </div>
                                    <input
                                        id="password"
                                        name="password"
                                        type={showPassword ? "text" : "password"}
                                        autoComplete="current-password"
                                        required
                                        value={password}
                                        onChange={(e) => setPassword(e.target.value)}
                                        className="block w-full rounded-md border-0 py-3 pl-10 pr-10 text-[#0f172a] shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-[#2563eb] sm:text-sm sm:leading-6 transition-all duration-200"
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
                                <PasswordStrength password={password} />
                            </div>

                            {/* Remember Me & Forgot Password */}
                            <div className="flex items-center justify-between">
                                <div className="flex items-center">
                                    <input
                                        id="remember-me"
                                        name="remember-me"
                                        type="checkbox"
                                        className="h-4 w-4 rounded border-gray-300 text-[#2563eb] focus:ring-[#2563eb] cursor-pointer"
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
                                        className="font-semibold text-[#2563eb] hover:text-blue-500 transition-colors"
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
                                    className={`flex w-full justify-center items-center gap-2 rounded-md bg-[#0f172a] px-3 py-3 text-sm font-bold leading-6 text-white shadow-sm hover:bg-slate-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#0f172a] transition-all duration-300 transform hover:scale-[1.02] disabled:hover:scale-100 ${loading ? "opacity-75 cursor-not-allowed" : ""
                                        }`}
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
                            <div className="flex items-center justify-center gap-2 text-xs text-gray-500 bg-white/50 py-2 rounded-full border border-gray-100 shadow-sm">
                                <span className="relative flex h-2 w-2">
                                    <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                    <span className="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                </span>
                                <span className="font-medium">Sistem Keamanan Aktif</span>
                                <span className="text-gray-300">|</span>
                                <span>v1.2.0 (Next.js)</span>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Right Panel - Desktop Only */}
                <div className="relative hidden w-0 flex-1 lg:block bg-[#0f172a] overflow-hidden">
                    <img
                        className="absolute inset-0 h-full w-full object-cover opacity-30 mix-blend-overlay scale-105"
                        src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?q=80&w=2070&auto=format&fit=crop"
                        alt="Newsroom background"
                    />

                    <div className="absolute inset-0 bg-gradient-to-t from-[#0f172a] via-transparent to-transparent"></div>

                    {/* Animated Blobs */}
                    <div className="absolute top-0 -left-4 w-96 h-96 bg-blue-600 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 animate-blob"></div>
                    <div className="absolute bottom-0 -right-4 w-96 h-96 bg-purple-600 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 animate-blob animation-delay-2000"></div>

                    {/* Scrolling News Bar */}
                    <div className="absolute top-10 left-0 right-0 bg-[#dc2626]/10 backdrop-blur border-y border-[#dc2626]/20 py-2 overflow-hidden">
                        <div className="whitespace-nowrap animate-scroll flex items-center gap-8 text-[#dc2626] font-mono text-xs font-bold tracking-widest uppercase">
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
                        <div className="glass-panel rounded-2xl p-8 max-w-md w-full border-l-4 border-[#dc2626] transform transition hover:scale-105 duration-500 shadow-2xl">
                            <div className="flex justify-between items-start mb-6">
                                <div>
                                    <h3 className="text-white font-bold text-xl font-[family-name:var(--font-merriweather)]">
                                        Status Keamanan
                                    </h3>
                                    <p className="text-slate-400 text-xs uppercase tracking-wider mt-1">
                                        Realtime Monitoring Log
                                    </p>
                                </div>
                                <i className="fa-solid fa-shield-cat text-2xl text-green-400"></i>
                            </div>

                            <div className="space-y-5">
                                <div>
                                    <div className="flex justify-between text-xs font-medium mb-2">
                                        <span className="text-slate-300">Traffic Load (Redis Cache)</span>
                                        <span className="text-green-400">Stable (45ms)</span>
                                    </div>
                                    <div className="w-full bg-slate-700/50 rounded-full h-2">
                                        <div
                                            className="bg-blue-500 h-2 rounded-full relative overflow-hidden"
                                            style={{ width: "45%" }}
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
                                            className="bg-[#dc2626] h-2 rounded-full"
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
                            <p className="text-[#dc2626] text-sm font-bold mt-2 tracking-widest uppercase">
                                - Editorial System
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {/* Toast Notification */}
            <Toast
                show={toast.show}
                message={toast.message}
                type={toast.type}
                onClose={() => setToast((prev) => ({ ...prev, show: false }))}
            />
        </div>
    );
}
