<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use App\Models\ArticleLike;
use App\Models\ArticleComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class PublicProfileController extends Controller
{
    /**
     * Display the public profile page for members
     */
    public function index()
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Get liked articles with pagination
        $likedArticles = ArticleLike::where('user_id', $user->id)
            ->with(['article' => function ($query) {
                $query->with(['categoryRelation', 'author']);
            }])
            ->latest()
            ->paginate(5, ['*'], 'likes_page');

        // Get user comments with pagination
        $userComments = ArticleComment::where('user_id', $user->id)
            ->with(['article'])
            ->latest()
            ->paginate(5, ['*'], 'comments_page');

        // Get login history (activity logs for this user)
        $loginHistory = ActivityLog::where('user_id', $user->id)
            ->whereIn('action', [
                ActivityLog::ACTION_LOGIN,
                ActivityLog::ACTION_LOGOUT,
                ActivityLog::ACTION_LOGIN_FAILED,
                ActivityLog::ACTION_PASSWORD_CHANGE,
            ])
            ->latest('created_at')
            ->take(10)
            ->get();

        // Get active sessions (if using database session driver)
        $activeSessions = collect();
        if (config('session.driver') === 'database') {
            $activeSessions = DB::table(config('session.table', 'sessions'))
                ->where('user_id', $user->id)
                ->orderBy('last_activity', 'desc')
                ->take(5)
                ->get()
                ->map(function ($session) {
                    $payload = unserialize(base64_decode($session->payload));
                    return (object) [
                        'id' => $session->id,
                        'ip_address' => $session->ip_address,
                        'user_agent' => $session->user_agent,
                        'last_activity' => \Carbon\Carbon::createFromTimestamp($session->last_activity),
                        'is_current' => $session->id === session()->getId(),
                        'device' => $this->parseUserAgent($session->user_agent),
                    ];
                });
        }

        // Statistics
        $stats = [
            'likes_count' => ArticleLike::where('user_id', $user->id)->count(),
            'comments_count' => ArticleComment::where('user_id', $user->id)->count(),
            'member_since' => $user->created_at,
            'last_login' => $user->last_login_at,
        ];

        return view('public.profile.index', compact(
            'user',
            'likedArticles',
            'userComments',
            'loginHistory',
            'activeSessions',
            'stats'
        ));
    }

    /**
     * Update profile information (AJAX)
     */
    public function updateInfo(Request $request)
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
        ], [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
        ]);

        $user->update($validated);

        // Log activity
        ActivityLog::log(
            ActivityLog::ACTION_UPDATE,
            'Memperbarui informasi profil',
            $user
        );

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui!',
            'user' => $user->fresh()
        ]);
    }

    /**
     * Update profile photo (AJAX)
     */
    public function updatePhoto(Request $request)
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'profile_photo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
        ], [
            'profile_photo.required' => 'Foto profil wajib dipilih',
            'profile_photo.image' => 'File harus berupa gambar',
            'profile_photo.mimes' => 'Format yang diizinkan: jpeg, png, jpg, gif, webp',
            'profile_photo.max' => 'Ukuran maksimal 5MB',
        ]);

        // Delete old photo if exists
        if ($user->profile_photo) {
            $this->deletePhotoFile($user->profile_photo);
        }

        // Store new photo
        $file = $request->file('profile_photo');
        $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('profiles', $filename, 'public');
        
        $user->update([
            'profile_photo' => '/storage/' . $path
        ]);

        // Log activity
        ActivityLog::log(
            ActivityLog::ACTION_UPDATE,
            'Memperbarui foto profil',
            $user
        );

        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil diperbarui!',
            'photo_url' => $user->fresh()->avatar
        ]);
    }

    /**
     * Delete profile photo (AJAX)
     */
    public function deletePhoto(Request $request)
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        if ($user->profile_photo) {
            $this->deletePhotoFile($user->profile_photo);
            $user->update(['profile_photo' => null]);

            ActivityLog::log(
                ActivityLog::ACTION_DELETE,
                'Menghapus foto profil',
                $user
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil dihapus!'
        ]);
    }

    /**
     * Update password (AJAX)
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi',
            'password.required' => 'Password baru wajib diisi',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.min' => 'Password minimal 8 karakter',
        ]);

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password saat ini tidak sesuai'
            ], 422);
        }

        // Check if new password is same as old
        if (Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password baru tidak boleh sama dengan password lama'
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Log activity
        ActivityLog::log(
            ActivityLog::ACTION_PASSWORD_CHANGE,
            'Mengubah password akun',
            $user
        );

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah!'
        ]);
    }

    /**
     * Logout from all other devices
     */
    public function logoutAllDevices(Request $request)
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'password' => ['required'],
        ], [
            'password.required' => 'Password wajib diisi untuk verifikasi',
        ]);

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password tidak sesuai'
            ], 422);
        }

        // Reset remember token
        $user->setRememberToken(\Illuminate\Support\Str::random(60));
        $user->save();

        // Delete other sessions
        if (config('session.driver') === 'database') {
            DB::table(config('session.table', 'sessions'))
                ->where('user_id', $user->id)
                ->where('id', '!=', session()->getId())
                ->delete();
        }

        // Log activity
        ActivityLog::log(
            'SECURITY',
            'Keluar dari semua perangkat lain',
            $user,
            null,
            null,
            ActivityLog::LEVEL_WARNING
        );

        return response()->json([
            'success' => true,
            'message' => 'Berhasil keluar dari semua perangkat lain'
        ]);
    }

    /**
     * Soft delete user account
     */
    public function deleteAccount(Request $request)
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'password' => ['required'],
            'confirmation' => ['required', 'in:HAPUS AKUN'],
        ], [
            'password.required' => 'Password wajib diisi',
            'confirmation.required' => 'Konfirmasi wajib diisi',
            'confirmation.in' => 'Ketik "HAPUS AKUN" untuk mengkonfirmasi',
        ]);

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password tidak sesuai'
            ], 422);
        }

        // Log activity before delete
        ActivityLog::log(
            ActivityLog::ACTION_DELETE,
            'Menghapus akun sendiri (soft delete)',
            $user,
            null,
            null,
            ActivityLog::LEVEL_CRITICAL
        );

        // Logout
        auth()->logout();

        // Soft delete user
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Akun berhasil dihapus. Terima kasih telah menggunakan layanan kami.',
            'redirect' => route('public.home')
        ]);
    }

    /**
     * Parse user agent to get device info
     */
    private function parseUserAgent($userAgent)
    {
        $browser = 'Unknown';
        $os = 'Unknown';
        $device = 'Desktop';

        // Detect browser
        if (preg_match('/Firefox/i', $userAgent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/Chrome/i', $userAgent)) {
            $browser = 'Chrome';
        } elseif (preg_match('/Safari/i', $userAgent)) {
            $browser = 'Safari';
        } elseif (preg_match('/Edge/i', $userAgent)) {
            $browser = 'Edge';
        } elseif (preg_match('/Opera|OPR/i', $userAgent)) {
            $browser = 'Opera';
        }

        // Detect OS
        if (preg_match('/Windows/i', $userAgent)) {
            $os = 'Windows';
        } elseif (preg_match('/Mac/i', $userAgent)) {
            $os = 'macOS';
        } elseif (preg_match('/Linux/i', $userAgent)) {
            $os = 'Linux';
        } elseif (preg_match('/Android/i', $userAgent)) {
            $os = 'Android';
            $device = 'Mobile';
        } elseif (preg_match('/iPhone|iPad/i', $userAgent)) {
            $os = 'iOS';
            $device = preg_match('/iPad/i', $userAgent) ? 'Tablet' : 'Mobile';
        }

        // Detect device type
        if (preg_match('/Mobile|Android|iPhone/i', $userAgent) && $device === 'Desktop') {
            $device = 'Mobile';
        } elseif (preg_match('/Tablet|iPad/i', $userAgent)) {
            $device = 'Tablet';
        }

        return (object) [
            'browser' => $browser,
            'os' => $os,
            'device' => $device,
            'icon' => $this->getDeviceIcon($device),
        ];
    }

    /**
     * Get icon for device type
     */
    private function getDeviceIcon($device)
    {
        return match ($device) {
            'Mobile' => 'fa-mobile-alt',
            'Tablet' => 'fa-tablet-alt',
            default => 'fa-desktop',
        };
    }

    /**
     * Delete photo file from storage
     */
    private function deletePhotoFile(string $photoPath): void
    {
        $path = str_replace('/storage/', '', $photoPath);
        
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
