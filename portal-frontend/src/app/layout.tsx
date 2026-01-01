import type { Metadata } from "next";
import { Inter, Merriweather } from "next/font/google";
import "./globals.css";
import { ThemeProvider } from "@/contexts/ThemeContext";
import DynamicHead from "@/components/layout/DynamicHead";
import DarkModeScript from "@/components/layout/DarkModeScript";

const inter = Inter({
    variable: "--font-inter",
    subsets: ["latin"],
    weight: ["300", "400", "500", "600", "700"],
});

const merriweather = Merriweather({
    variable: "--font-merriweather",
    subsets: ["latin"],
    weight: ["400", "700"],
    style: ["normal", "italic"],
});

export const metadata: Metadata = {
    title: "Portal Berita - Sistem Redaksi",
    description: "Sistem Manajemen Portal Berita",
};

export default function RootLayout({
    children,
}: Readonly<{
    children: React.ReactNode;
}>) {
    return (
        <html lang="id" suppressHydrationWarning>
            <head>
                {/* Dark Mode Script - Runs before React hydration to prevent flash */}
                <DarkModeScript />
                <link
                    rel="stylesheet"
                    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
                />
                {/* SweetAlert2 CDN */}
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            </head>
            <body
                className={`${inter.variable} ${merriweather.variable} antialiased`}
                suppressHydrationWarning
            >
                <ThemeProvider>
                    <DynamicHead />
                    {children}
                </ThemeProvider>
            </body>
        </html>
    );
}
