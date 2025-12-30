"use client";

import { useEffect, useRef, useState, useCallback } from "react";
import { useTheme } from "@/contexts/ThemeContext";

interface AlertDialogProps {
    show: boolean;
    type: "warning" | "danger" | "info";
    title: string;
    message: string;
    onConfirm: () => void;
    onCancel: () => void;
    confirmText?: string;
    cancelText?: string;
}

export default function AlertDialog({
    show,
    type,
    title,
    message,
    onConfirm,
    onCancel,
    confirmText = "Konfirmasi",
    cancelText = "Batal",
}: AlertDialogProps) {
    const { theme } = useTheme();
    const [focusedButton, setFocusedButton] = useState<"cancel" | "confirm">("confirm");
    const [isClosing, setIsClosing] = useState(false);
    const dialogRef = useRef<HTMLDivElement>(null);
    const cancelBtnRef = useRef<HTMLButtonElement>(null);
    const confirmBtnRef = useRef<HTMLButtonElement>(null);

    // Handle keyboard navigation
    useEffect(() => {
        if (!show) return;

        const handleKeyDown = (e: KeyboardEvent) => {
            switch (e.key) {
                case "Escape":
                    e.preventDefault();
                    handleClose();
                    break;
                case "Enter":
                    e.preventDefault();
                    if (focusedButton === "confirm") {
                        handleConfirm();
                    } else {
                        handleClose();
                    }
                    break;
                case "ArrowLeft":
                    e.preventDefault();
                    setFocusedButton("cancel");
                    cancelBtnRef.current?.focus();
                    break;
                case "ArrowRight":
                    e.preventDefault();
                    setFocusedButton("confirm");
                    confirmBtnRef.current?.focus();
                    break;
                case "Tab":
                    e.preventDefault();
                    setFocusedButton(prev => prev === "cancel" ? "confirm" : "cancel");
                    if (focusedButton === "cancel") {
                        confirmBtnRef.current?.focus();
                    } else {
                        cancelBtnRef.current?.focus();
                    }
                    break;
            }
        };

        document.addEventListener("keydown", handleKeyDown);
        // Focus confirm button by default
        setTimeout(() => confirmBtnRef.current?.focus(), 100);

        return () => {
            document.removeEventListener("keydown", handleKeyDown);
        };
    }, [show, focusedButton]);

    const handleClose = useCallback(() => {
        setIsClosing(true);
        setTimeout(() => {
            setIsClosing(false);
            onCancel();
        }, 200);
    }, [onCancel]);

    const handleConfirm = useCallback(() => {
        setIsClosing(true);
        setTimeout(() => {
            setIsClosing(false);
            onConfirm();
        }, 200);
    }, [onConfirm]);

    if (!show && !isClosing) return null;

    // Theme-influenced config
    const config = {
        warning: {
            icon: "fa-exclamation-triangle",
            iconBg: `${theme.gradientFrom}20`,
            iconColor: theme.gradientFrom,
            confirmBg: `linear-gradient(135deg, ${theme.gradientFrom}, ${theme.gradientTo})`,
            confirmRing: `${theme.accent}50`
        },
        danger: {
            icon: "fa-trash-alt",
            iconBg: "rgba(239, 68, 68, 0.15)",
            iconColor: "#dc2626",
            confirmBg: "linear-gradient(135deg, #dc2626, #b91c1c)",
            confirmRing: "rgba(239, 68, 68, 0.5)"
        },
        info: {
            icon: "fa-info-circle",
            iconBg: `${theme.accent}20`,
            iconColor: theme.accent,
            confirmBg: `linear-gradient(135deg, ${theme.gradientFrom}, ${theme.gradientVia || theme.gradientFrom}, ${theme.gradientTo})`,
            confirmRing: `${theme.accent}50`
        },
    };

    const { icon, iconBg, iconColor, confirmBg, confirmRing } = config[type];

    return (
        <div className="fixed inset-0 z-[100] flex items-center justify-center">
            {/* Backdrop with enhanced animation */}
            <div
                className={`absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity duration-200 ${isClosing ? "opacity-0" : "animate-fade-in"}`}
                onClick={handleClose}
            ></div>

            {/* Dialog with enhanced animation */}
            <div
                ref={dialogRef}
                className={`relative bg-white rounded-2xl shadow-2xl p-6 max-w-md w-full mx-4 transition-all duration-200 ${isClosing ? "scale-95 opacity-0" : "animate-alert-in"}`}
                role="dialog"
                aria-modal="true"
                aria-labelledby="alert-title"
            >
                {/* Decorative gradient bar */}
                <div
                    className="absolute top-0 left-0 right-0 h-1 rounded-t-2xl"
                    style={{ background: confirmBg }}
                ></div>

                <div className="flex flex-col items-center text-center pt-2">
                    {/* Animated Icon with theme colors */}
                    <div
                        className={`w-16 h-16 rounded-full flex items-center justify-center mb-4 transition-transform duration-300 ${!isClosing ? "animate-icon-bounce" : ""}`}
                        style={{ backgroundColor: iconBg }}
                    >
                        <i
                            className={`fa-solid ${icon} text-2xl`}
                            style={{ color: iconColor }}
                        ></i>
                    </div>

                    <h3 id="alert-title" className="text-xl font-bold text-slate-800 mb-2">{title}</h3>
                    <p className="text-slate-500 mb-6">{message}</p>

                    {/* Keyboard hint */}
                    <div className="text-xs text-slate-400 mb-4 flex items-center gap-3 flex-wrap justify-center">
                        <span className="flex items-center gap-1">
                            <kbd className="px-1.5 py-0.5 bg-slate-100 rounded text-slate-500 font-mono text-[10px]">←</kbd>
                            <kbd className="px-1.5 py-0.5 bg-slate-100 rounded text-slate-500 font-mono text-[10px]">→</kbd>
                            <span className="ml-1">Pilih</span>
                        </span>
                        <span className="flex items-center gap-1">
                            <kbd className="px-1.5 py-0.5 bg-slate-100 rounded text-slate-500 font-mono text-[10px]">Enter</kbd>
                            <span className="ml-1">OK</span>
                        </span>
                        <span className="flex items-center gap-1">
                            <kbd className="px-1.5 py-0.5 bg-slate-100 rounded text-slate-500 font-mono text-[10px]">Esc</kbd>
                            <span className="ml-1">Batal</span>
                        </span>
                    </div>

                    {/* Buttons with focus ring and theme colors */}
                    <div className="flex gap-3 w-full">
                        <button
                            ref={cancelBtnRef}
                            onClick={handleClose}
                            onFocus={() => setFocusedButton("cancel")}
                            className={`flex-1 px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-medium transition-all duration-150 focus:outline-none ${focusedButton === "cancel" ? "ring-4 ring-slate-300/50" : ""}`}
                        >
                            {cancelText}
                        </button>
                        <button
                            ref={confirmBtnRef}
                            onClick={handleConfirm}
                            onFocus={() => setFocusedButton("confirm")}
                            className={`flex-1 px-4 py-3 text-white rounded-xl font-medium transition-all duration-150 focus:outline-none ${focusedButton === "confirm" ? "ring-4" : ""}`}
                            style={{
                                background: confirmBg,
                                boxShadow: focusedButton === "confirm" ? `0 0 0 4px ${confirmRing}` : undefined
                            }}
                        >
                            {confirmText}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    );
}
