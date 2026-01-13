<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\SiteSetting;

class OtpVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $otpCode;
    public string $userName;
    public string $type;
    public int $expiryMinutes;
    public string $siteName;
    public ?string $logoPath;
    public ?string $logoDataUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(string $otpCode, string $userName = 'Pengguna', string $type = 'password_reset')
    {
        $this->otpCode = $otpCode;
        $this->userName = $userName;
        $this->type = $type;
        $this->expiryMinutes = (int) SiteSetting::get('otp_expiry_minutes', 10);
        $this->siteName = SiteSetting::get('site_name', 'Portal');
        $this->logoPath = $this->getLogoPath();
        $this->logoDataUrl = $this->getLogoDataUrl();
    }

    /**
     * Get physical path to logo file
     */
    protected function getLogoPath(): ?string
    {
        $logoUrl = SiteSetting::get('logo_url');
        
        if (!$logoUrl) {
            return null;
        }

        try {
            $logoPath = ltrim($logoUrl, '/');
            
            if (str_starts_with($logoPath, 'storage/')) {
                $storagePath = str_replace('storage/', '', $logoPath);
                $fullPath = storage_path('app/public/' . $storagePath);
                
                if (file_exists($fullPath)) {
                    return $fullPath;
                }
            } else {
                $publicPath = public_path($logoPath);
                if (file_exists($publicPath)) {
                    return $publicPath;
                }
            }
        } catch (\Exception $e) {
            \Log::warning('Failed to get logo path', ['error' => $e->getMessage()]);
        }

        return null;
    }

    /**
     * Convert logo to base64 data URL
     */
    protected function getLogoDataUrl(): ?string
    {
        if (!$this->logoPath || !file_exists($this->logoPath)) {
            return null;
        }

        try {
            $fileContents = file_get_contents($this->logoPath);
            $mimeType = mime_content_type($this->logoPath);
            return 'data:' . $mimeType . ';base64,' . base64_encode($fileContents);
        } catch (\Exception $e) {
            \Log::warning('Failed to convert logo to data URL', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = match($this->type) {
            'password_reset' => 'Kode OTP Reset Password - ' . $this->siteName,
            'login_2fa' => 'Kode OTP Verifikasi Login - ' . $this->siteName,
            'email_verification' => 'Kode OTP Verifikasi Email - ' . $this->siteName,
            default => 'Kode OTP Verifikasi - ' . $this->siteName,
        };

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Build the message
     */
    public function build()
    {
        $mail = $this->view('emails.otp-verification')
            ->with([
                'otpCode' => $this->otpCode,
                'userName' => $this->userName,
                'type' => $this->type,
                'expiryMinutes' => $this->expiryMinutes,
                'siteName' => $this->siteName,
                'logoPath' => $this->logoPath,
                'logoDataUrl' => $this->logoDataUrl,
            ]);

        return $mail;
    }
}
