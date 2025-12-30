<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Spatie\Activitylog\Facades\LogBatch;
use Spatie\Activitylog\Models\Activity;

class SettingsController extends Controller
{
    /**
     * Get all settings (for admin)
     */
    public function index(): JsonResponse
    {
        $settings = SiteSetting::getAll();

        return response()->json([
            'success' => true,
            'data' => [
                'settings' => $settings,
            ],
        ]);
    }

    /**
     * Get public settings (for frontend)
     */
    public function public(): JsonResponse
    {
        $settings = SiteSetting::getPublic();

        return response()->json([
            'success' => true,
            'data' => [
                'settings' => $settings,
            ],
        ]);
    }

    /**
     * Get settings by group
     */
    public function group(string $group): JsonResponse
    {
        $settings = SiteSetting::getByGroup($group);

        return response()->json([
            'success' => true,
            'data' => [
                'group' => $group,
                'settings' => $settings,
            ],
        ]);
    }

    /**
     * Update settings
     */
    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable',
        ]);

        try {
            SiteSetting::setMany($validated['settings']);

            // Log activity (wrapped in try-catch to prevent failures)
            try {
                if (function_exists('activity')) {
                    activity()
                        ->causedBy($request->user())
                        ->withProperties(['settings_updated' => array_keys($validated['settings'])])
                        ->log('Settings updated');
                }
            } catch (\Exception $logError) {
                // Silently fail logging
            }

            return response()->json([
                'success' => true,
                'message' => 'Pengaturan berhasil disimpan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan pengaturan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get a single setting
     */
    public function show(string $key): JsonResponse
    {
        $setting = SiteSetting::where('key', $key)->first();

        if (!$setting) {
            return response()->json([
                'success' => false,
                'message' => 'Setting tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'key' => $setting->key,
                'value' => SiteSetting::get($key),
                'type' => $setting->type,
                'group' => $setting->group,
                'label' => $setting->label,
                'description' => $setting->description,
            ],
        ]);
    }

    /**
     * Update a single setting
     */
    public function updateSingle(Request $request, string $key): JsonResponse
    {
        $validated = $request->validate([
            'value' => 'nullable',
        ]);

        $success = SiteSetting::set($key, $validated['value']);

        if (!$success) {
            return response()->json([
                'success' => false,
                'message' => 'Setting tidak ditemukan',
            ], 404);
        }

        // Log activity (wrapped in try-catch to prevent failures)
        try {
            if (function_exists('activity')) {
                activity()
                    ->causedBy($request->user())
                    ->withProperties(['key' => $key, 'new_value' => $validated['value']])
                    ->log("Setting '{$key}' updated");
            }
        } catch (\Exception $logError) {
            // Silently fail logging
        }

        return response()->json([
            'success' => true,
            'message' => 'Setting berhasil diperbarui',
        ]);
    }

    /**
     * Clear settings cache
     */
    public function clearCache(): JsonResponse
    {
        SiteSetting::clearCache();

        return response()->json([
            'success' => true,
            'message' => 'Cache berhasil dibersihkan',
        ]);
    }
}
