<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\SiteSetting;

class OtpVerificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $otpCode;
    public string $userName;
    public string $type;
    public int $expiryMinutes;
    public string $siteName;
    public ?string $logoUrl;

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
        $this->logoUrl = SiteSetting::get('logo_url');
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
                'logoUrl' => $this->logoUrl,
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
