<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\OtpCode;
use App\Models\ActivityLog;
use App\Models\SiteSetting;
use App\Mail\OtpVerificationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules\Password;

class PasswordResetController extends Controller
{
    /**
     * Send OTP to user's email for password reset
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $email = strtolower($request->email);

        // Rate limiting - max 3 OTP requests per email per 10 minutes
        $throttleKey = 'otp-request:' . $email;
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return response()->json([
                'success' => false,
                'message' => "Terlalu banyak permintaan. Coba lagi dalam " . ceil($seconds / 60) . " menit.",
            ], 429);
        }

        // Check if email exists in database
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            // Don't reveal if email doesn't exist for security
            // But for internal portal, we can show this
            return response()->json([
                'success' => false,
                'message' => 'Email tidak terdaftar dalam sistem.',
            ], 404);
        }

        // Check if user is soft deleted
        if ($user->trashed()) {
            return response()->json([
                'success' => false,
                'message' => 'Akun ini telah dinonaktifkan. Hubungi administrator.',
            ], 403);
        }

        try {
            // Generate OTP
            $otp = OtpCode::generate($email, OtpCode::TYPE_PASSWORD_RESET);

            // Send email
            Mail::to($email)->send(new OtpVerificationMail(
                $otp->code,
                $user->name,
                'password_reset'
            ));

            // Increment rate limiter
            RateLimiter::hit($throttleKey, 600); // 10 minutes

            // Log activity
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'password_reset_requested',
                'model_type' => User::class,
                'model_id' => $user->id,
                'description' => 'Permintaan reset password - OTP dikirim ke email',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            $expiryMinutes = SiteSetting::get('otp_expiry_minutes', 10);

            return response()->json([
                'success' => true,
                'message' => 'Kode OTP telah dikirim ke email Anda.',
                'expires_in' => $expiryMinutes * 60, // in seconds for countdown
                'email_masked' => $this->maskEmail($email),
            ]);

        } catch (\Exception $e) {
            // Log error
            \Log::error('Failed to send OTP email', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim email. Periksa konfigurasi email atau coba lagi nanti.',
                'debug' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Verify OTP code
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
        ], [
            'email.required' => 'Email wajib diisi.',
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.size' => 'Kode OTP harus 6 digit.',
        ]);

        $email = strtolower($request->email);
        $code = $request->otp;

        // Verify OTP
        $result = OtpCode::verify($email, $code, OtpCode::TYPE_PASSWORD_RESET);

        if (!$result['valid']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 400);
        }

        // Generate a temporary token for password reset step
        $resetToken = bin2hex(random_bytes(32));
        
        // Store token in session or cache (valid for 5 minutes)
        cache()->put("password_reset_token:{$email}", $resetToken, now()->addMinutes(5));

        return response()->json([
            'success' => true,
            'message' => 'Kode OTP valid. Silakan buat password baru.',
            'reset_token' => $resetToken,
        ]);
    }

    /**
     * Reset password with new password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'reset_token' => 'required|string',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ], [
            'email.required' => 'Email wajib diisi.',
            'reset_token.required' => 'Token reset tidak valid.',
            'password.required' => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        $email = strtolower($request->email);
        $resetToken = $request->reset_token;

        // Verify reset token from cache
        $cachedToken = cache()->get("password_reset_token:{$email}");

        if (!$cachedToken || $cachedToken !== $resetToken) {
            return response()->json([
                'success' => false,
                'message' => 'Token reset tidak valid atau sudah kadaluarsa. Silakan ulangi proses.',
            ], 400);
        }

        // Find user
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan.',
            ], 404);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->remember_token = null; // Invalidate all remember tokens
        $user->save();

        // Delete OTP and reset token
        OtpCode::where('email', $email)->where('type', OtpCode::TYPE_PASSWORD_RESET)->delete();
        cache()->forget("password_reset_token:{$email}");

        // Log activity
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'password_reset_completed',
            'model_type' => User::class,
            'model_id' => $user->id,
            'description' => 'Password berhasil direset via OTP email',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diperbarui. Silakan login dengan password baru.',
        ]);
    }

    /**
     * Resend OTP code
     */
    public function resendOtp(Request $request)
    {
        // This is essentially the same as sendOtp
        return $this->sendOtp($request);
    }

    /**
     * Mask email for display (e.g., j***@gmail.com)
     */
    private function maskEmail(string $email): string
    {
        $parts = explode('@', $email);
        $name = $parts[0];
        $domain = $parts[1] ?? '';

        if (strlen($name) <= 2) {
            $masked = $name[0] . '***';
        } else {
            $masked = $name[0] . str_repeat('*', strlen($name) - 2) . substr($name, -1);
        }

        return $masked . '@' . $domain;
    }
}
