<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use App\Models\ActivityLog;
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
            'contact' => SiteSetting::getByGroup('contact'),
            'seo' => SiteSetting::getByGroup('seo'),
            'social' => SiteSetting::getByGroup('social'),
            'appearance' => SiteSetting::getByGroup('appearance'),
            'security' => SiteSetting::getByGroup('security'),
            'media' => SiteSetting::getByGroup('media'),
            'letterhead' => SiteSetting::getByGroup('letterhead'),
            'signature' => SiteSetting::getByGroup('signature'),
            'email' => SiteSetting::getByGroup('email'),
            'hosting' => SiteSetting::getByGroup('hosting'),
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

        // Get old values for audit trail
        $oldSettings = SiteSetting::getAll();
        
        // Fields that need XSS sanitization (text content that could contain HTML)
        $textFieldsToSanitize = [
            'site_history',
            'site_vision_mission',
            'site_description',
            'site_address',
        ];
        
        // Sanitize text fields to prevent XSS attacks
        foreach ($textFieldsToSanitize as $field) {
            if (isset($data[$field])) {
                $data[$field] = $this->sanitizeInput($data[$field]);
            }
        }
        
        // Special handling for Google Maps embed code - only allow iframe from google.com
        if (isset($data['site_map_code'])) {
            $data['site_map_code'] = $this->sanitizeMapCode($data['site_map_code']);
        }

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

        // Handle hosting password encryption
        $encryptedFields = ['hosting_db_password', 'hosting_ftp_password'];
        foreach ($encryptedFields as $field) {
            if (isset($data[$field]) && !empty($data[$field])) {
                // Only encrypt if the value has changed (not the masked placeholder)
                $currentValue = SiteSetting::get($field);
                if ($data[$field] !== $currentValue && !str_starts_with($data[$field], 'encrypted:')) {
                    // Store as plain text but mark that it should be handled securely
                    // Note: We're storing plain text here for now since the .env generator needs readable values
                    // In a production environment with sensitive data, use encrypt() helper
                    $data[$field] = $data[$field];
                }
            }
        }

        // Handle checkbox for hosting debug mode
        if (!isset($data['hosting_app_debug'])) {
            $data['hosting_app_debug'] = 'false';
        } else {
            $data['hosting_app_debug'] = 'true';
        }

        // Update all settings
        SiteSetting::setMany($data);

        // Clear cache
        SiteSetting::clearCache();

        // Get updated settings for response and audit trail
        $updatedSettings = SiteSetting::getAll();
        
        // Identify what changed for the audit trail
        $changedFields = [];
        foreach ($data as $key => $newValue) {
            // Skip file current fields and empty comparisons
            if (str_ends_with($key, '_current')) continue;
            
            $oldValue = $oldSettings[$key] ?? null;
            if ($oldValue !== $newValue && !($oldValue === '' && $newValue === null)) {
                $changedFields[$key] = [
                    'old' => is_string($oldValue) && strlen($oldValue) > 100 ? substr($oldValue, 0, 100) . '...' : $oldValue,
                    'new' => is_string($newValue) && strlen($newValue) > 100 ? substr($newValue, 0, 100) . '...' : $newValue,
                ];
            }
        }
        
        // Log to activity_logs if there are changes
        if (!empty($changedFields)) {
            ActivityLog::log(
                ActivityLog::ACTION_SETTINGS_UPDATE,
                'Mengubah Pengaturan Situs (' . count($changedFields) . ' field diubah)',
                null,
                ['changed_fields' => array_keys($changedFields)],
                $changedFields,
                ActivityLog::LEVEL_INFO
            );
        }

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
            $fromName = $request->input('mail_from_name') ?: SiteSetting::get('site_name', 'Portal');

            // Configure based on driver type
            if ($mailDriver === 'resend') {
                // Resend uses HTTP API - not affected by SMTP port blocking
                $resendApiKey = $request->input('resend_api_key');
                $fromAddress = $request->input('mail_from_address') ?: 'onboarding@resend.dev';

                if (!$resendApiKey) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Resend API Key wajib diisi. Dapatkan di resend.com/api-keys'
                    ]);
                }

                // Configure Resend
                Config::set('mail.default', 'resend');
                Config::set('resend.api_key', $resendApiKey);
                Config::set('mail.from.address', $fromAddress);
                Config::set('mail.from.name', $fromName);

            } elseif ($mailDriver === 'log') {
                // Log driver - for development/testing
                Config::set('mail.default', 'log');
                $fromAddress = $request->input('mail_from_address') ?: 'test@example.com';
                Config::set('mail.from.address', $fromAddress);
                Config::set('mail.from.name', $fromName);

            } else {
                // SMTP driver
                $smtpHost = $request->input('smtp_host');
                $smtpPort = $request->input('smtp_port', 587);
                $smtpUsername = $request->input('smtp_username');
                $smtpPassword = $request->input('smtp_password');
                $smtpEncryption = $request->input('smtp_encryption', 'tls');
                $fromAddress = $request->input('mail_from_address') ?: $smtpUsername;

                // Validate required fields for SMTP
                if (!$smtpHost || !$smtpUsername) {
                    return response()->json([
                        'success' => false,
                        'message' => 'SMTP Host dan Username wajib diisi.'
                    ]);
                }

                // Configure SMTP
                Config::set('mail.default', 'smtp');
                Config::set('mail.mailers.smtp.host', $smtpHost);
                Config::set('mail.mailers.smtp.port', (int) $smtpPort);
                Config::set('mail.mailers.smtp.username', $smtpUsername);
                Config::set('mail.mailers.smtp.password', $smtpPassword);
                Config::set('mail.mailers.smtp.encryption', $smtpEncryption === 'none' ? null : $smtpEncryption);
                Config::set('mail.from.address', $fromAddress);
                Config::set('mail.from.name', $fromName);
            }

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

    /**
     * Sanitize text input to prevent XSS attacks
     * Removes dangerous HTML tags while preserving safe formatting
     */
    protected function sanitizeInput(string $input): string
    {
        // First, decode any HTML entities to catch encoded attacks
        $input = html_entity_decode($input, ENT_QUOTES, 'UTF-8');
        
        // Remove any script tags and their contents
        $input = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $input);
        
        // Remove any style tags and their contents
        $input = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $input);
        
        // Remove all HTML tags except safe ones (if needed for future use)
        // For now, we strip all HTML for text content
        $input = strip_tags($input);
        
        // Encode special HTML characters to prevent XSS
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8', false);
        
        // Decode back to allow normal text display (but now safe)
        $input = htmlspecialchars_decode($input, ENT_QUOTES);
        
        // Trim whitespace
        return trim($input);
    }

    /**
     * Sanitize Google Maps embed code
     * Only allows iframe from google.com/maps domains
     */
    protected function sanitizeMapCode(string $code): string
    {
        // If empty, return as is
        if (empty(trim($code))) {
            return '';
        }
        
        // Check if it's a valid Google Maps iframe
        // Pattern matches: <iframe src="https://www.google.com/maps/embed?..." ...></iframe>
        $pattern = '/<iframe[^>]*src=["\']https?:\/\/(www\.)?google\.com\/maps\/embed[^"\']*["\'][^>]*>\s*<\/iframe>/i';
        
        if (preg_match($pattern, $code, $matches)) {
            // Return the matched iframe (sanitized)
            $iframe = $matches[0];
            
            // Additional safety: ensure no onclick, onerror, or other event handlers
            $iframe = preg_replace('/\s+on\w+\s*=\s*["\'][^"\']*["\']/i', '', $iframe);
            
            return $iframe;
        }
        
        // If not a valid Google Maps iframe, log warning and return empty
        \Log::warning('Invalid Google Maps embed code rejected', [
            'user_id' => auth()->id(),
            'code_preview' => substr($code, 0, 200)
        ]);
        
        return '';
    }
}

