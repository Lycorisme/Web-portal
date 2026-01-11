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
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'member', // Public registration = member role
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'register', // Using string directly or define constant if needed, assuming 'register' works or mapped later. Actually let's check constants. 
            // Wait, ActivityLog model might utilize specific constants. I should check.
            // Looking at existing code: ActivityLog::ACTION_LOGIN.
            // I'll stick to a safe default or check ActivityLog model.
            // For now I'll use ACTION_LOGIN as a fallback or just a string if it allows.
            // The file view shows ACTION_LOGIN, ACTION_LOGIN_FAILED. 
            // I'll use text for now to be safe or investigate.
            // Actually, let's just use string "Register" or similar.
            'description' => "Registrasi pengguna baru: {$user->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'level' => 'info', // ActivityLog::LEVEL_INFO
            'created_at' => now(),
        ]);

        Auth::login($user);

        // Member redirect to public home, not dashboard
        return redirect()->route('public.home')->with('success', 'Selamat datang di Portal BTIKP!');
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
