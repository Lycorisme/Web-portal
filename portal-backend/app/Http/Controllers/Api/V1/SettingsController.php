<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SettingsController extends Controller
{
    /**
     * Get public site settings.
     */
    public function index(): JsonResponse
    {
        $settings = SiteSetting::getPublic();

        return response()->json([
            'success' => true,
            'data' => [
                'site_name' => $settings['site_name'] ?? 'Web Portal',
                'site_tagline' => $settings['site_tagline'] ?? '',
                'site_description' => $settings['site_description'] ?? '',
                'site_logo' => $settings['site_logo'] ?? null,
                'site_favicon' => $settings['site_favicon'] ?? null,
                'contact_email' => $settings['contact_email'] ?? null,
                'contact_phone' => $settings['contact_phone'] ?? null,
                'contact_address' => $settings['contact_address'] ?? null,
                'social_links' => [
                    'facebook' => $settings['social_facebook'] ?? null,
                    'twitter' => $settings['social_twitter'] ?? null,
                    'instagram' => $settings['social_instagram'] ?? null,
                    'youtube' => $settings['social_youtube'] ?? null,
                    'linkedin' => $settings['social_linkedin'] ?? null,
                ],
                'copyright_text' => $settings['copyright_text'] ?? null,
            ],
        ]);
    }

    /**
     * Get settings by group (authenticated - admin only).
     */
    public function group(Request $request, string $group): JsonResponse
    {
        if (!$request->user()->canAccessSettings()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengakses pengaturan',
            ], 403);
        }

        $settings = SiteSetting::where('group', $group)->get()->pluck('value', 'key');

        return response()->json([
            'success' => true,
            'data' => $settings,
        ]);
    }

    /**
     * Update settings (authenticated - admin only).
     */
    public function update(Request $request): JsonResponse
    {
        if (!$request->user()->canAccessSettings()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengubah pengaturan',
            ], 403);
        }

        $settings = $request->all();

        foreach ($settings as $key => $value) {
            SiteSetting::set($key, $value);
        }

        // Clear cache
        SiteSetting::clearCache();

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan berhasil diperbarui',
        ]);
    }
}
