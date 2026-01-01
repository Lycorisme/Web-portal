"use client";

import { useState, useEffect } from "react";
import { useRouter } from "next/navigation";
import { getUser, isLoggedIn, User } from "@/lib/auth";
import Breadcrumb from "@/components/ui/Breadcrumb";
import { useTheme } from "@/contexts/ThemeContext";

export default function ProfilePage() {
    const router = useRouter();
    const { theme, isDarkMode } = useTheme();

    const [user, setUserState] = useState<User | null>(null);

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

    if (!user) {
        return null;
    }

    return (
        <>
            <Breadcrumb />

            <div className="max-w-4xl mx-auto">
                {/* Profile Card */}
                <div className={`rounded-2xl shadow-sm border overflow-hidden ${isDarkMode
                    ? 'bg-slate-800 border-slate-700'
                    : 'bg-white border-slate-100'
                    }`}>
                    {/* Header with Gradient */}
                    <div className="h-32 relative theme-gradient">
                        <div className="absolute inset-0 bg-gradient-to-r from-black/20 to-transparent"></div>
                    </div>

                    {/* Profile Info */}
                    <div className="px-6 pb-6 -mt-16 relative z-10">
                        <div className="flex flex-col sm:flex-row items-center gap-4">
                            <img
                                className="h-28 w-28 rounded-full object-cover border-4 shadow-lg"
                                style={{ borderColor: isDarkMode ? theme.sidebar : 'white' }}
                                src={`https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=${theme.accent.replace("#", "")}&color=fff&size=200`}
                                alt="User"
                            />
                            <div className="text-center sm:text-left mt-4 sm:mt-8">
                                <h2 className={`text-2xl font-bold ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>{user.name}</h2>
                                <p className={isDarkMode ? 'text-slate-400' : 'text-slate-500'}>{user.email}</p>
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
                <div className={`mt-8 rounded-2xl shadow-sm border p-8 text-center ${isDarkMode
                    ? 'bg-slate-800 border-slate-700'
                    : 'bg-white border-slate-100'
                    }`}>
                    <div
                        className="w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-4"
                        style={{ backgroundColor: `${theme.accent}10` }}
                    >
                        <i
                            className="fa-solid fa-gear fa-spin text-3xl"
                            style={{ color: theme.accent }}
                        ></i>
                    </div>
                    <h3 className={`text-xl font-bold mb-2 ${isDarkMode ? 'text-white' : 'text-slate-800'}`}>
                        Halaman Profil Sedang Dikembangkan
                    </h3>
                    <p className={`max-w-md mx-auto ${isDarkMode ? 'text-slate-400' : 'text-slate-500'}`}>
                        Fitur edit profil, ubah password, dan pengaturan akun lainnya akan segera hadir.
                        Pantau terus update terbaru dari kami!
                    </p>
                </div>
            </div>
        </>
    );
}
