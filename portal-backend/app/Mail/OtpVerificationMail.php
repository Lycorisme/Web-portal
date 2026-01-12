<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Models\SiteSetting;

class OtpVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $otpCode;
    public string $userName;
    public string $type;
    public int $expiryMinutes;
    public string $siteName;
    public ?string $logoBase64;

    /**
     * Create a new message instance.
     */
    public function __construct(string $otpCode, string $userName = 'Pengguna', string $type = 'password_reset')
    {
        $this->otpCode = $otpCode;
        $this->userName = $userName;
        $this->type = $type;
        $this->expiryMinutes = SiteSetting::get('otp_expiry_minutes', 10);
        $this->siteName = SiteSetting::get('site_name', 'Portal');
        $this->logoBase64 = $this->convertLogoToBase64();
    }

    /**
     * Convert logo to base64 for email embedding
     */
    protected function convertLogoToBase64(): ?string
    {
        $logoUrl = SiteSetting::get('logo_url');
        
        if (!$logoUrl) {
            return null;
        }

        try {
            $logoPath = ltrim($logoUrl, '/');
            
            if (str_starts_with($logoPath, 'storage/')) {
                $storagePath = str_replace('storage/', '', $logoPath);
                if (Storage::disk('public')->exists($storagePath)) {
                    $fileContents = Storage::disk('public')->get($storagePath);
                    $mimeType = Storage::disk('public')->mimeType($storagePath);
                    return 'data:' . $mimeType . ';base64,' . base64_encode($fileContents);
                }
            } else {
                $publicPath = public_path($logoPath);
                if (file_exists($publicPath)) {
                    $fileContents = file_get_contents($publicPath);
                    $mimeType = mime_content_type($publicPath);
                    return 'data:' . $mimeType . ';base64,' . base64_encode($fileContents);
                }
            }
        } catch (\Exception $e) {
            \Log::warning('Failed to convert logo to base64 for OTP email', ['error' => $e->getMessage()]);
        }

        return null;
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
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.otp-verification',
            with: [
                'otpCode' => $this->otpCode,
                'userName' => $this->userName,
                'type' => $this->type,
                'expiryMinutes' => $this->expiryMinutes,
                'siteName' => $this->siteName,
                'logoBase64' => $this->logoBase64,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
