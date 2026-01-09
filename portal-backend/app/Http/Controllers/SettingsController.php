<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Display the settings page
     */
    public function index()
    {
        // Get all settings grouped by category
        $settings = [
            'general' => SiteSetting::getByGroup('general'),
            'seo' => SiteSetting::getByGroup('seo'),
            'social' => SiteSetting::getByGroup('social'),
            'appearance' => SiteSetting::getByGroup('appearance'),
            'security' => SiteSetting::getByGroup('security'),
            'media' => SiteSetting::getByGroup('media'),
            'letterhead' => SiteSetting::getByGroup('letterhead'),
            'signature' => SiteSetting::getByGroup('signature'),
        ];

        // Get raw settings for easy access
        $rawSettings = SiteSetting::getAll();

        return view('settings', compact('settings', 'rawSettings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        // Handle file uploads
        $fileFields = ['logo_url', 'favicon_url', 'letterhead_url', 'signature_url', 'stamp_url'];
        
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = $field . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('settings', $filename, 'public');
                $data[$field] = '/storage/' . $path;
            } elseif ($request->has($field . '_current')) {
                // Keep existing file if no new file uploaded
                $data[$field] = $request->input($field . '_current');
            }
        }

        // Update all settings
        SiteSetting::setMany($data);

        // Clear cache
        SiteSetting::clearCache();

        // Get updated settings for response
        $updatedSettings = SiteSetting::getAll();

        // Check if request wants JSON response (AJAX)
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => 'Pengaturan berhasil disimpan!',
                'settings' => $updatedSettings
            ]);
        }

        return redirect()->route('settings')
            ->with('success', 'Pengaturan berhasil disimpan!');
    }

    /**
     * Update a specific group of settings via AJAX
     */
    public function updateGroup(Request $request, string $group)
    {
        $data = $request->except(['_token', '_method']);

        // Handle file uploads if present
        $fileFields = ['logo_url', 'favicon_url', 'letterhead_url', 'signature_url', 'stamp_url'];
        
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = $field . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('settings', $filename, 'public');
                $data[$field] = '/storage/' . $path;
            }
        }

        // Update settings
        SiteSetting::setMany($data);

        // Clear cache
        SiteSetting::clearCache();

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan berhasil disimpan!'
        ]);
    }
}
