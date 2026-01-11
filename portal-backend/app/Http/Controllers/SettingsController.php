<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

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
            'email' => SiteSetting::getByGroup('email'),
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
        // Handle email test request
        if ($group === 'email-test') {
            return $this->sendTestEmail($request);
        }

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

    /**
     * Send a test email using the provided configuration
     */
    protected function sendTestEmail(Request $request)
    {
        $testEmail = $request->input('test_email');

        if (!$testEmail || !filter_var($testEmail, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'success' => false,
                'message' => 'Alamat email tidak valid.'
            ]);
        }

        try {
            // Get mail settings from request (not yet saved)
            $mailDriver = $request->input('mail_driver', 'smtp');
            $smtpHost = $request->input('smtp_host');
            $smtpPort = $request->input('smtp_port', 465);
            $smtpUsername = $request->input('smtp_username');
            $smtpPassword = $request->input('smtp_password');
            $smtpEncryption = $request->input('smtp_encryption', 'ssl');
            $fromAddress = $request->input('mail_from_address') ?: $smtpUsername;
            $fromName = $request->input('mail_from_name') ?: SiteSetting::get('site_name', 'Portal');

            // Validate required fields
            if (!$smtpHost || !$smtpUsername) {
                return response()->json([
                    'success' => false,
                    'message' => 'SMTP Host dan Username wajib diisi.'
                ]);
            }

            // Temporarily configure mail settings
            Config::set('mail.default', $mailDriver);
            Config::set('mail.mailers.smtp.host', $smtpHost);
            Config::set('mail.mailers.smtp.port', (int) $smtpPort);
            Config::set('mail.mailers.smtp.username', $smtpUsername);
            Config::set('mail.mailers.smtp.password', $smtpPassword);
            Config::set('mail.mailers.smtp.encryption', $smtpEncryption === 'none' ? null : $smtpEncryption);
            Config::set('mail.from.address', $fromAddress);
            Config::set('mail.from.name', $fromName);

            // Prepare email data
            $siteName = SiteSetting::get('site_name', 'Portal');
            $logoUrl = SiteSetting::get('logo_url');
            $logoBase64 = null;
            
            // Convert logo to base64 for email embedding
            if ($logoUrl) {
                try {
                    // Remove leading slash if present and check if it's a storage path
                    $logoPath = ltrim($logoUrl, '/');
                    
                    // Try to get the file from public storage
                    if (str_starts_with($logoPath, 'storage/')) {
                        $storagePath = str_replace('storage/', '', $logoPath);
                        if (Storage::disk('public')->exists($storagePath)) {
                            $fileContents = Storage::disk('public')->get($storagePath);
                            $mimeType = Storage::disk('public')->mimeType($storagePath);
                            $logoBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($fileContents);
                        }
                    } else {
                        // Try public path directly
                        $publicPath = public_path($logoPath);
                        if (file_exists($publicPath)) {
                            $fileContents = file_get_contents($publicPath);
                            $mimeType = mime_content_type($publicPath);
                            $logoBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($fileContents);
                        }
                    }
                } catch (\Exception $e) {
                    // If conversion fails, logo will show fallback icon
                    \Log::warning('Failed to convert logo to base64', ['error' => $e->getMessage()]);
                }
            }
            
            $emailData = [
                'siteName' => $siteName,
                'logoBase64' => $logoBase64,
                'sentAt' => now()->format('d M Y, H:i:s'),
                'serverIp' => $request->ip() ?: '127.0.0.1',
            ];

            // Send test email with HTML template
            Mail::send('emails.test-email', $emailData, function ($message) use ($testEmail, $fromAddress, $fromName, $siteName) {
                $message->to($testEmail)
                        ->from($fromAddress, $fromName)
                        ->subject('Test Email - ' . $siteName);
            });

            return response()->json([
                'success' => true,
                'message' => 'Email test berhasil dikirim ke ' . $testEmail . '! Periksa inbox Anda.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Test email failed', [
                'email' => $testEmail,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim email: ' . $e->getMessage()
            ]);
        }
    }
}

