const API_BASE_URL = "http://localhost:8000/api";

export interface User {
    id: number;
    email: string;
    name: string;
    role: string;
}

export interface AuthResponse {
    success: boolean;
    message: string;
    data?: {
        user: User;
        token: string;
    };
}

export interface ApiError {
    message: string;
    errors?: Record<string, string[]>;
}

// Get stored token
export function getToken(): string | null {
    if (typeof window === "undefined") return null;
    return localStorage.getItem("auth_token");
}

// Get stored user
export function getUser(): User | null {
    if (typeof window === "undefined") return null;
    const userData = localStorage.getItem("user");
    if (!userData) return null;
    try {
        return JSON.parse(userData) as User;
    } catch {
        return null;
    }
}

// Store auth data
export function setAuthData(token: string, user: User): void {
    localStorage.setItem("auth_token", token);
    localStorage.setItem("user", JSON.stringify(user));
    localStorage.setItem("isLoggedIn", "true");
}

// Clear auth data
export function removeAuthData(): void {
    localStorage.removeItem("auth_token");
    localStorage.removeItem("user");
    localStorage.removeItem("isLoggedIn");
}

// Check if logged in
export function isLoggedIn(): boolean {
    if (typeof window === "undefined") return false;
    return !!getToken() && !!getUser();
}

// Login API call
export async function login(
    email: string,
    password: string
): Promise<{ success: boolean; message: string; user?: User }> {
    try {
        const response = await fetch(`${API_BASE_URL}/login`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
            },
            body: JSON.stringify({ email, password }),
        });

        const data = await response.json();

        if (!response.ok) {
            // Handle validation errors
            if (data.errors) {
                const errorMessages = Object.values(data.errors).flat().join(", ");
                return { success: false, message: errorMessages };
            }
            return { success: false, message: data.message || "Login gagal" };
        }

        if (data.success && data.data) {
            setAuthData(data.data.token, data.data.user);
            return { success: true, message: data.message, user: data.data.user };
        }

        return { success: false, message: "Terjadi kesalahan" };
    } catch (error) {
        console.error("Login error:", error);
        return { success: false, message: "Tidak dapat terhubung ke server" };
    }
}

// Logout API call
export async function logout(): Promise<boolean> {
    const token = getToken();

    if (token) {
        try {
            await fetch(`${API_BASE_URL}/logout`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    Authorization: `Bearer ${token}`,
                },
            });
        } catch (error) {
            console.error("Logout error:", error);
        }
    }

    removeAuthData();
    return true;
}

// Get current user from API
export async function fetchCurrentUser(): Promise<User | null> {
    const token = getToken();
    if (!token) return null;

    try {
        const response = await fetch(`${API_BASE_URL}/user`, {
            method: "GET",
            headers: {
                Accept: "application/json",
                Authorization: `Bearer ${token}`,
            },
        });

        if (!response.ok) {
            removeAuthData();
            return null;
        }

        const data = await response.json();
        if (data.success && data.data?.user) {
            return data.data.user;
        }

        return null;
    } catch (error) {
        console.error("Fetch user error:", error);
        return null;
    }
}
