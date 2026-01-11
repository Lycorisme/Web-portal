<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class OtpCode extends Model
{
    protected $fillable = [
        'email',
        'code',
        'type',
        'attempts',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'attempts' => 'integer',
    ];

    /**
     * OTP types
     */
    const TYPE_PASSWORD_RESET = 'password_reset';
    const TYPE_LOGIN_2FA = 'login_2fa';
    const TYPE_EMAIL_VERIFICATION = 'email_verification';

    /**
     * Check if OTP is expired
     */
    public function isExpired(): bool
    {
        return Carbon::now()->gt($this->expires_at);
    }

    /**
     * Increment attempts counter
     */
    public function incrementAttempts(): void
    {
        $this->increment('attempts');
    }

    /**
     * Check if max attempts reached
     */
    public function isMaxAttemptsReached(): bool
    {
        $maxAttempts = SiteSetting::get('otp_max_attempts', 3);
        return $this->attempts >= $maxAttempts;
    }

    /**
     * Scope for valid OTP (not expired, not max attempts)
     */
    public function scopeValidFor(Builder $query, string $email, string $type = self::TYPE_PASSWORD_RESET): Builder
    {
        $maxAttempts = SiteSetting::get('otp_max_attempts', 3);
        
        return $query->where('email', $email)
                     ->where('type', $type)
                     ->where('expires_at', '>', Carbon::now())
                     ->where('attempts', '<', $maxAttempts);
    }

    /**
     * Scope for specific type
     */
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * Generate a new OTP code
     */
    public static function generate(string $email, string $type = self::TYPE_PASSWORD_RESET): self
    {
        // Delete any existing OTP for this email and type
        self::where('email', $email)->where('type', $type)->delete();

        // Get expiry time from settings
        $expiryMinutes = SiteSetting::get('otp_expiry_minutes', 10);

        // Generate 6-digit code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        return self::create([
            'email' => $email,
            'code' => $code,
            'type' => $type,
            'attempts' => 0,
            'expires_at' => Carbon::now()->addMinutes($expiryMinutes),
        ]);
    }

    /**
     * Verify OTP code
     * 
     * @return array ['valid' => bool, 'message' => string]
     */
    public static function verify(string $email, string $code, string $type = self::TYPE_PASSWORD_RESET): array
    {
        $otp = self::where('email', $email)
                   ->where('type', $type)
                   ->latest()
                   ->first();

        if (!$otp) {
            return [
                'valid' => false,
                'message' => 'Kode OTP tidak ditemukan. Silakan request kode baru.',
            ];
        }

        if ($otp->isExpired()) {
            $otp->delete();
            return [
                'valid' => false,
                'message' => 'Kode OTP sudah kadaluarsa. Silakan request kode baru.',
            ];
        }

        if ($otp->isMaxAttemptsReached()) {
            $otp->delete();
            return [
                'valid' => false,
                'message' => 'Terlalu banyak percobaan. Silakan request kode baru.',
            ];
        }

        if ($otp->code !== $code) {
            $otp->incrementAttempts();
            $remainingAttempts = SiteSetting::get('otp_max_attempts', 3) - $otp->attempts;
            
            if ($remainingAttempts <= 0) {
                $otp->delete();
                return [
                    'valid' => false,
                    'message' => 'Kode OTP salah. Silakan request kode baru.',
                ];
            }
            
            return [
                'valid' => false,
                'message' => "Kode OTP salah. Sisa percobaan: {$remainingAttempts}",
            ];
        }

        // OTP is valid - don't delete yet, let the password reset process handle it
        return [
            'valid' => true,
            'message' => 'Kode OTP valid.',
            'otp' => $otp,
        ];
    }

    /**
     * Clean up expired OTPs
     */
    public static function cleanupExpired(): int
    {
        return self::where('expires_at', '<', Carbon::now())->delete();
    }
}
