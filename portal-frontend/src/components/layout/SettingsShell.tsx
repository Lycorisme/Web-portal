"use client";

import Breadcrumb from "@/components/ui/Breadcrumb";

// SettingsShell is now a simple wrapper since Sidebar/Header are in layout.tsx
export default function SettingsShell({
    children,
}: {
    children: React.ReactNode;
}) {
    return (
        <>
            <Breadcrumb />
            {children}
        </>
    );
}
