import type { Metadata } from "next";
import { Inter, Merriweather } from "next/font/google";
import "./globals.css";

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
        <html lang="id">
            <head>
                <link
                    rel="stylesheet"
                    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
                />
            </head>
            <body
                className={`${inter.variable} ${merriweather.variable} antialiased`}
            >
                {children}
            </body>
        </html>
    );
}
