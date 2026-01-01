"use client";

import { useEffect, useRef, useCallback, useState } from "react";
import { useTheme } from "@/contexts/ThemeContext";

export interface ToastData {
    id?: string;
    show: boolean;
    message: string;
    type: "success" | "error" | "warning" | "info";
}

export interface ToastItem {
    id: string;
    message: string;
    type: "success" | "error" | "warning" | "info";
    isExiting?: boolean;
}

interface ToastProps {
    data: ToastData;
    onClose: () => void;
}

interface ToastStackProps {
    toasts: ToastItem[];
    onRemove: (id: string) => void;
}

// Single Toast Item for Stack
function ToastItemComponent({ toast, onRemove, index }: { toast: ToastItem; onRemove: (id: string) => void; index: number }) {
    const { theme } = useTheme();
    const toastRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        const timer = setTimeout(() => {
            onRemove(toast.id);
        }, 4000);

        return () => clearTimeout(timer);
    }, [toast.id, onRemove]);

    const config = {
        success: {
            bg: "bg-gradient-to-r from-emerald-500 to-green-600",
            icon: "fa-check-circle",
            label: "Berhasil"
        },
        error: {
            bg: "bg-gradient-to-r from-red-500 to-rose-600",
            icon: "fa-times-circle",
            label: "Error"
        },
        warning: {
            bg: "bg-gradient-to-r from-amber-500 to-orange-600",
            icon: "fa-exclamation-triangle",
            label: "Peringatan"
        },
        info: {
            bg: "",
            icon: "fa-info-circle",
            label: "Info"
        },
    };

    const { bg, icon, label } = config[toast.type];

    return (
        <div
            ref={toastRef}
            className={`toast-item text-white px-5 py-3.5 rounded-xl shadow-2xl flex items-center gap-3 min-w-[320px] max-w-[400px] backdrop-blur-sm border border-white/20 ${toast.type !== "info" ? bg : "theme-gradient"} ${toast.isExiting ? "toast-exit" : "toast-enter"}`}
            style={{
                transform: `translateY(${index * -8}px) scale(${1 - index * 0.02})`,
                zIndex: 100 - index,
                opacity: Math.max(1 - index * 0.15, 0.6),
            }}
        >
            <div className="flex-shrink-0 w-9 h-9 bg-white/20 rounded-full flex items-center justify-center">
                <i className={`fa-solid ${icon} text-lg`}></i>
            </div>
            <div className="flex-1 min-w-0">
                <p className="font-semibold text-xs uppercase tracking-wide opacity-90">
                    {label}
                </p>
                <p className="text-white/95 text-sm truncate">{toast.message}</p>
            </div>
            <button
                onClick={() => onRemove(toast.id)}
                className="hover:bg-white/20 p-1.5 rounded-full transition-all duration-150 flex-shrink-0"
            >
                <i className="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
    );
}

// Toast Stack Component for Multiple Notifications
export function ToastStack({ toasts, onRemove }: ToastStackProps) {
    if (toasts.length === 0) return null;

    return (
        <div className="fixed top-6 right-6 z-[100] flex flex-col-reverse gap-2">
            {toasts.slice(0, 5).map((toast, index) => (
                <ToastItemComponent
                    key={toast.id}
                    toast={toast}
                    onRemove={onRemove}
                    index={index}
                />
            ))}
        </div>
    );
}

// Default Single Toast (backward compatible) - WITH ENTER/EXIT ANIMATIONS
export default function Toast({ data, onClose }: ToastProps) {
    const { theme } = useTheme();
    const [isVisible, setIsVisible] = useState(false);
    const [isExiting, setIsExiting] = useState(false);
    const prevShowRef = useRef(data.show);

    // Handle enter animation
    useEffect(() => {
        if (data.show && !prevShowRef.current) {
            // Toast is being shown - trigger enter animation
            setIsExiting(false);
            // Small delay to ensure DOM is ready for animation
            requestAnimationFrame(() => {
                setIsVisible(true);
            });
        } else if (!data.show && prevShowRef.current) {
            // Toast is being hidden - trigger exit animation
            setIsExiting(true);
            const timer = setTimeout(() => {
                setIsVisible(false);
                setIsExiting(false);
            }, 400); // Match animation duration
            return () => clearTimeout(timer);
        }
        prevShowRef.current = data.show;
    }, [data.show]);

    // Initial mount
    useEffect(() => {
        if (data.show) {
            requestAnimationFrame(() => {
                setIsVisible(true);
            });
        }
    }, []);

    // Handle close with animation
    const handleClose = useCallback(() => {
        setIsExiting(true);
        setTimeout(() => {
            onClose();
        }, 350); // Slightly less than animation duration for smooth transition
    }, [onClose]);

    // Don't render if not visible and not showing
    if (!data.show && !isExiting) return null;

    const config = {
        success: {
            bg: "bg-gradient-to-r from-emerald-500 to-green-600",
            icon: "fa-check-circle"
        },
        error: {
            bg: "bg-gradient-to-r from-red-500 to-rose-600",
            icon: "fa-times-circle"
        },
        warning: {
            bg: "bg-gradient-to-r from-amber-500 to-orange-600",
            icon: "fa-exclamation-triangle"
        },
        info: {
            bg: "",
            icon: "fa-info-circle"
        },
    };

    const { bg, icon } = config[data.type];

    return (
        <div
            className={`fixed z-[100] ${isExiting ? 'toast-exit' : 'toast-enter'}
                top-4 right-4 left-4 sm:left-auto sm:right-6 sm:top-6`}
        >
            <div
                className={`toast-item text-white rounded-xl shadow-2xl flex items-center backdrop-blur-sm border border-white/20 ${data.type !== "info" ? bg : "theme-gradient"}
                    px-4 py-3 gap-3 sm:px-6 sm:py-4 sm:gap-4 sm:min-w-[320px] sm:max-w-[420px]`}
                style={{
                    boxShadow: '0 20px 50px -12px rgba(0, 0, 0, 0.35)'
                }}
            >
                <div className="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 bg-white/20 rounded-full flex items-center justify-center">
                    <i className={`fa-solid ${icon} text-base sm:text-xl`}></i>
                </div>
                <div className="flex-1 min-w-0">
                    <p className="font-semibold text-xs sm:text-sm uppercase tracking-wide opacity-90">
                        {data.type === "success" && "Berhasil"}
                        {data.type === "error" && "Error"}
                        {data.type === "warning" && "Peringatan"}
                        {data.type === "info" && "Info"}
                    </p>
                    <p className="text-white/90 text-xs sm:text-sm truncate sm:whitespace-normal">{data.message}</p>
                </div>
                <button
                    onClick={handleClose}
                    className="flex-shrink-0 hover:bg-white/20 p-1.5 sm:p-2 rounded-full transition-all duration-150 hover:scale-110 active:scale-95"
                >
                    <i className="fa-solid fa-xmark text-sm sm:text-base"></i>
                </button>
            </div>
        </div>
    );
}

// Hook for managing toast stack
export function useToastStack(maxToasts: number = 5) {
    const [toasts, setToasts] = useState<ToastItem[]>([]);

    const addToast = useCallback((message: string, type: ToastItem["type"]) => {
        const id = `toast-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;
        setToasts(prev => {
            const newToasts = [{ id, message, type }, ...prev];
            return newToasts.slice(0, maxToasts);
        });
    }, [maxToasts]);

    const removeToast = useCallback((id: string) => {
        setToasts(prev => prev.map(t =>
            t.id === id ? { ...t, isExiting: true } : t
        ));
        setTimeout(() => {
            setToasts(prev => prev.filter(t => t.id !== id));
        }, 300);
    }, []);

    return { toasts, addToast, removeToast };
}
