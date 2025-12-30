"use client";

import { useEffect } from "react";
import { useTheme } from "@/contexts/ThemeContext";

/**
 * DynamicHead Component
 * 
 * This component dynamically updates the document head elements (favicon, title, etc.)
 * based on the settings stored in ThemeContext.
 * 
 * It runs on client-side and updates the DOM directly since Next.js metadata is static.
 */
export default function DynamicHead() {
    const { settings, isLoading } = useTheme();

    useEffect(() => {
        if (isLoading) return;

        // Update favicon
        if (settings.favicon_url) {
            // Remove existing favicon links
            const existingFavicons = document.querySelectorAll('link[rel="icon"], link[rel="shortcut icon"]');
            existingFavicons.forEach(el => el.remove());

            // Create new favicon link
            const faviconLink = document.createElement('link');
            faviconLink.rel = 'icon';
            faviconLink.href = settings.favicon_url;

            // Determine type based on URL
            if (settings.favicon_url.includes('.ico')) {
                faviconLink.type = 'image/x-icon';
            } else if (settings.favicon_url.includes('.png') || settings.favicon_url.startsWith('data:image/png')) {
                faviconLink.type = 'image/png';
            } else if (settings.favicon_url.includes('.svg')) {
                faviconLink.type = 'image/svg+xml';
            }

            document.head.appendChild(faviconLink);
        }

        // Update document title
        if (settings.site_name) {
            const currentPath = window.location.pathname;
            let pageTitle = settings.site_name;

            if (currentPath.includes('/dashboard/settings')) {
                pageTitle = `Pengaturan - ${settings.site_name}`;
            } else if (currentPath.includes('/dashboard')) {
                pageTitle = `Dashboard - ${settings.site_name}`;
            } else if (currentPath === '/') {
                pageTitle = settings.meta_title || `Login - ${settings.site_name}`;
            }

            document.title = pageTitle;
        }

        // Update meta description
        if (settings.meta_description) {
            let metaDesc = document.querySelector('meta[name="description"]');
            if (!metaDesc) {
                metaDesc = document.createElement('meta');
                metaDesc.setAttribute('name', 'description');
                document.head.appendChild(metaDesc);
            }
            metaDesc.setAttribute('content', settings.meta_description);
        }

        // Update meta keywords
        if (settings.meta_keywords) {
            let metaKeywords = document.querySelector('meta[name="keywords"]');
            if (!metaKeywords) {
                metaKeywords = document.createElement('meta');
                metaKeywords.setAttribute('name', 'keywords');
                document.head.appendChild(metaKeywords);
            }
            metaKeywords.setAttribute('content', settings.meta_keywords);
        }

        // Update theme color meta
        if (settings.theme_color) {
            let metaThemeColor = document.querySelector('meta[name="theme-color"]');
            if (!metaThemeColor) {
                metaThemeColor = document.createElement('meta');
                metaThemeColor.setAttribute('name', 'theme-color');
                document.head.appendChild(metaThemeColor);
            }
            metaThemeColor.setAttribute('content', settings.theme_color);
        }

    }, [settings, isLoading]);

    // This component doesn't render anything visible
    return null;
}
