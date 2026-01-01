import { getToken } from "./auth";

const API_BASE_URL = "http://localhost:8000/api";

export interface SiteSettings {
    site_name: string;
    site_tagline: string;
    site_email: string;
    site_phone: string;
    site_address: string;
    site_description: string;
    meta_title: string;
    meta_description: string;
    meta_keywords: string;
    google_analytics_id: string;
    facebook_url: string;
    twitter_url: string;
    instagram_url: string;
    youtube_url: string;
    linkedin_url: string;
    theme_color: string;
    accent_color: string;
    sidebar_color: string;
    current_theme: string;
    rate_limit_per_minute: number;
    auto_ban_enabled: boolean;
    maintenance_mode: boolean;
    favicon_url: string;
    logo_url: string;
    letterhead_url: string;
    signature_url: string;
    stamp_url: string;
    // Footer Settings
    footer_text: string;
    footer_copyright: string;
    footer_show_social: boolean;
    // Organization & Leader Info (Dynamic for Government/Private)
    organization_type: 'government' | 'private' | 'ngo' | 'other';
    leader_name: string;
    leader_title: string;  // Jabatan pimpinan
    leader_nip: string;    // NIP untuk PNS
    leader_nik: string;    // NIK untuk swasta
    leader_custom_id: string; // Custom ID lainnya
    leader_custom_id_label: string; // Label untuk custom ID
    leader_phone: string;
    leader_email: string;
}

interface ApiResponse<T> {
    success: boolean;
    message?: string;
    data?: T;
}

// Get all settings (requires auth)
export async function getSettings(): Promise<SiteSettings | null> {
    const token = getToken();
    if (!token) return null;

    try {
        const response = await fetch(`${API_BASE_URL}/settings`, {
            method: "GET",
            headers: {
                Accept: "application/json",
                Authorization: `Bearer ${token}`,
            },
        });

        if (!response.ok) return null;

        const data: ApiResponse<{ settings: SiteSettings }> = await response.json();
        return data.data?.settings || null;
    } catch (error) {
        console.error("Error fetching settings:", error);
        return null;
    }
}

// Get public settings (no auth required)
export async function getPublicSettings(): Promise<Partial<SiteSettings> | null> {
    try {
        const response = await fetch(`${API_BASE_URL}/settings/public`, {
            method: "GET",
            headers: {
                Accept: "application/json",
            },
        });

        if (!response.ok) return null;

        const data: ApiResponse<{ settings: Partial<SiteSettings> }> = await response.json();
        return data.data?.settings || null;
    } catch (error) {
        console.error("Error fetching public settings:", error);
        return null;
    }
}

// Update settings (requires auth)
export async function updateSettings(settings: Partial<SiteSettings>): Promise<{ success: boolean; message: string }> {
    const token = getToken();
    if (!token) {
        return { success: false, message: "Unauthorized" };
    }

    try {
        const response = await fetch(`${API_BASE_URL}/settings`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                Authorization: `Bearer ${token}`,
            },
            body: JSON.stringify({ settings }),
        });

        const data: ApiResponse<null> = await response.json();

        if (!response.ok) {
            return { success: false, message: data.message || "Gagal menyimpan pengaturan" };
        }

        return { success: true, message: data.message || "Pengaturan berhasil disimpan" };
    } catch (error) {
        console.error("Error updating settings:", error);
        return { success: false, message: "Tidak dapat terhubung ke server" };
    }
}

// Get settings by group
export async function getSettingsByGroup(group: string): Promise<Record<string, any> | null> {
    const token = getToken();
    if (!token) return null;

    try {
        const response = await fetch(`${API_BASE_URL}/settings/group/${group}`, {
            method: "GET",
            headers: {
                Accept: "application/json",
                Authorization: `Bearer ${token}`,
            },
        });

        if (!response.ok) return null;

        const data: ApiResponse<{ settings: Record<string, any> }> = await response.json();
        return data.data?.settings || null;
    } catch (error) {
        console.error("Error fetching settings group:", error);
        return null;
    }
}

// Clear settings cache
export async function clearSettingsCache(): Promise<{ success: boolean; message: string }> {
    const token = getToken();
    if (!token) {
        return { success: false, message: "Unauthorized" };
    }

    try {
        const response = await fetch(`${API_BASE_URL}/settings/clear-cache`, {
            method: "POST",
            headers: {
                Accept: "application/json",
                Authorization: `Bearer ${token}`,
            },
        });

        const data: ApiResponse<null> = await response.json();

        if (!response.ok) {
            return { success: false, message: data.message || "Gagal membersihkan cache" };
        }

        return { success: true, message: data.message || "Cache berhasil dibersihkan" };
    } catch (error) {
        console.error("Error clearing cache:", error);
        return { success: false, message: "Tidak dapat terhubung ke server" };
    }
}
