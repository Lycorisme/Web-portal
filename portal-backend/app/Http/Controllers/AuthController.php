<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Handle registration request
     */
    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        // IP Rate Limiting for Registration
        $ipThrottleKey = 'register_ip|'.$request->ip();
        if (RateLimiter::tooManyAttempts($ipThrottleKey, 5)) { // 5 registrations per hour per IP
             $seconds = RateLimiter::availableIn($ipThrottleKey);
             
             // Manage Blocked Client Entry
             $blockedClient = \App\Models\BlockedClient::firstOrCreate(
                 ['ip_address' => $request->ip()],
                 ['user_agent' => $request->userAgent(), 'blocked_route' => 'register']
             );
             $blockedClient->incrementAttempt();
             
             // Auto-block if too many specific rate limit hits (e.g., > 10 hits while limited)
             if ($blockedClient->shouldBlock(10)) {
                 $blockedClient->block('Excessive registration attempts', 60 * 24); // Block for 24 hours
             }

             // Log blocked attempt
             ActivityLog::create([
                'user_id' => null,
                'action' => 'register_blocked',
                'description' => "Registrasi diblokir (rate limit IP) untuk email: {$request->email}. Percobaan: {$blockedClient->attempt_count}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'level' => ActivityLog::LEVEL_WARNING,
             ]);

             throw ValidationException::withMessages([
                 'email' => ["Terlalu banyak permintaan pendaftaran dari IP Anda. Coba lagi dalam {$seconds} detik."],
             ]);
        }
        RateLimiter::hit($ipThrottleKey, 3600);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Security Sanitization
        $safeName = strip_tags($request->name);

        $user = User::create([
            'name' => $safeName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'member', 
            'email_verified_at' => null, // Wajib verifikasi
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'register_init',
            'description' => "Registrasi awal pengguna baru: {$user->name} (Menunggu Verifikasi)",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'level' => ActivityLog::LEVEL_INFO,
        ]);

        // Generate OTP
        $otp = \App\Models\OtpCode::generate($user->email, \App\Models\OtpCode::TYPE_EMAIL_VERIFICATION);
        
        // Send Email
        $emailSent = true;
        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\OtpVerificationMail(
                $otp->code, 
                $user->name, 
                'email_verification'
            ));
        } catch (\Exception $e) {
            $emailSent = false;
            \Log::error("Gagal mengirim email OTP ke {$user->email}: " . $e->getMessage());
        }

        // For Development: Log OTP
        \Log::info("OTP untuk {$user->email}: {$otp->code}");
        
        $message = $emailSent 
            ? 'Registrasi berhasil. Kode verifikasi telah dikirim ke email Anda.'
            : 'Registrasi berhasil, tetapi gagal mengirim email kode OTP. Silakan hubungi admin.';
            
        $status = $emailSent ? 'success' : 'error';

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'email' => $user->email,
                'message' => $status === 'success' ? 'Registrasi berhasil. Kode verifikasi terkirim.' : 'Registrasi berhasil, tetapi email gagal terkirim.'
            ]);
        }

        // Redirect to verify page
        return redirect()->route('verification.notice', ['email' => $user->email])
            ->with($status, $message);
    }

    /**
     * Show verification form
     */
    public function showVerifyForm(Request $request) 
    {
        $email = $request->query('email') ?? session('email');
        if (!$email) {
            return redirect()->route('login');
        }
        
        $siteName = SiteSetting::get('site_name', 'BTIKP Portal');
        $logoUrl = SiteSetting::get('logo_url', '');

        return view('auth.verify-email', compact('email', 'siteName', 'logoUrl'));
    }

    /**
     * Handle Email Verification
     */
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string|size:6',
        ]);

        $result = \App\Models\OtpCode::verify(
            $request->email, 
            $request->otp, 
            \App\Models\OtpCode::TYPE_EMAIL_VERIFICATION
        );

        if (!$result['valid']) {
            throw ValidationException::withMessages([
                'otp' => [$result['message']],
            ]);
        }

        // Activate User
        $user = User::where('email', $request->email)->first();
        $user->email_verified_at = now();
        $user->save();

        // Delete OTP
        if (isset($result['otp'])) {
            $result['otp']->delete();
        }

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'register_verified',
            'description' => "Verifikasi email berhasil: {$user->email}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'level' => ActivityLog::LEVEL_INFO,
        ]);
        
        // Auto Login
        Auth::login($user);

        return redirect()->route('public.home')->with('success', 'Akun berhasil diverifikasi. Selamat datang!');
    }

    /**
     * Resend Verification OTP
     */
    public function resendVerificationOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);
        
        // Rate Limit Resend
        $throttleKey = 'resend_otp|'.$request->email;
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) { // 3 times per 5 minutes
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->with('error', "Mohon tunggu {$seconds} detik sebelum meminta kode baru.");
        }
        RateLimiter::hit($throttleKey, 300);

        $otp = \App\Models\OtpCode::generate($request->email, \App\Models\OtpCode::TYPE_EMAIL_VERIFICATION);
        $user = User::where('email', $request->email)->first();

        // Send Email
        $emailSent = true;
        try {
            \Illuminate\Support\Facades\Mail::to($request->email)->send(new \App\Mail\OtpVerificationMail(
                $otp->code, 
                $user ? $user->name : 'Pengguna', 
                'email_verification'
            ));
        } catch (\Exception $e) {
            $emailSent = false;
            \Log::error("Gagal mengirim ulang email OTP ke {$request->email}: " . $e->getMessage());
        }

        // For Development: Log OTP
        \Log::info("Resend OTP untuk {$request->email}: {$otp->code}");
        
        if ($emailSent) {
            return back()->with('success', 'Kode verifikasi baru telah dikirim.');
        } 
        
        return back()->with('error', 'Gagal mengirim email kode OTP. Cek log server.');
    }

    /**
     * Maximum login attempts before lockout
     */
    protected int $maxAttempts = 5;

    /**
     * Lockout duration in minutes
     */
    protected int $decayMinutes = 15;

    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        $siteName = SiteSetting::get('site_name', 'BTIKP Portal');
        $logoUrl = SiteSetting::get('logo_url', '');

        return view('auth.login', compact('siteName', 'logoUrl'));
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Check rate limiting
        $throttleKey = $this->throttleKey($request);
        
        if (RateLimiter::tooManyAttempts($throttleKey, $this->maxAttempts)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            
            // Log failed attempt due to rate limiting
            ActivityLog::create([
                'user_id' => null,
                'action' => ActivityLog::ACTION_LOGIN_FAILED,
                'description' => "Login diblokir (rate limit) untuk email: {$request->email}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'level' => ActivityLog::LEVEL_WARNING,
                'created_at' => now(),
            ]);

            throw ValidationException::withMessages([
                'email' => ["Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik."],
            ]);
        }

        // Check if user exists
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            RateLimiter::hit($throttleKey, $this->decayMinutes * 60);
            
            ActivityLog::create([
                'user_id' => null,
                'action' => ActivityLog::ACTION_LOGIN_FAILED,
                'description' => "Login gagal: email tidak ditemukan ({$request->email})",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'level' => ActivityLog::LEVEL_WARNING,
                'created_at' => now(),
            ]);

            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        // Check if account is locked
        if ($user->isLocked()) {
            $remainingMinutes = now()->diffInMinutes($user->locked_until);
            
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => ActivityLog::ACTION_LOGIN_FAILED,
                'description' => "Login diblokir: akun terkunci hingga {$user->locked_until}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'level' => ActivityLog::LEVEL_WARNING,
                'created_at' => now(),
            ]);

            throw ValidationException::withMessages([
                'email' => ["Akun Anda terkunci. Silakan coba lagi dalam {$remainingMinutes} menit."],
            ]);
        }

        // Attempt login
        if (!Hash::check($request->password, $user->password)) {
            RateLimiter::hit($throttleKey, $this->decayMinutes * 60);
            
            // Record failed attempt
            $failedCount = $user->recordFailedLogin();
            
            // Lock account if too many failed attempts
            if ($failedCount >= $this->maxAttempts) {
                $user->lockFor($this->decayMinutes);
                
                ActivityLog::create([
                    'user_id' => $user->id,
                    'action' => ActivityLog::ACTION_LOGIN_FAILED,
                    'description' => "Akun dikunci setelah {$failedCount} percobaan login gagal",
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'url' => $request->fullUrl(),
                    'level' => ActivityLog::LEVEL_DANGER,
                    'created_at' => now(),
                ]);

                throw ValidationException::withMessages([
                    'email' => ["Akun Anda telah dikunci selama {$this->decayMinutes} menit karena terlalu banyak percobaan login gagal."],
                ]);
            }

            ActivityLog::create([
                'user_id' => $user->id,
                'action' => ActivityLog::ACTION_LOGIN_FAILED,
                'description' => "Login gagal: password salah (percobaan ke-{$failedCount})",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'level' => ActivityLog::LEVEL_WARNING,
                'created_at' => now(),
            ]);

            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        // Login successful
        RateLimiter::clear($throttleKey);
        
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // Record successful login
        $user->recordSuccessfulLogin($request->ip());

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => ActivityLog::ACTION_LOGIN,
            'description' => "Login berhasil sebagai {$user->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'level' => ActivityLog::LEVEL_INFO,
            'created_at' => now(),
        ]);

        // Redirect based on role
        if ($user->isMember()) {
            // Members cannot access dashboard
            $intended = session()->pull('url.intended');
            if ($intended && !str_contains($intended, '/dashboard') && !str_contains($intended, '/articles') && !str_contains($intended, '/settings')) {
                return redirect()->to($intended);
            }
            return redirect()->route('public.home');
        }

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => ActivityLog::ACTION_LOGOUT,
                'description' => "Logout: {$user->name}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'level' => ActivityLog::LEVEL_INFO,
                'created_at' => now(),
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Get the rate limiting throttle key for the request
     */
    protected function throttleKey(Request $request): string
    {
        return strtolower($request->input('email')) . '|' . $request->ip();
    }
}
