<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the profile page
     */
    public function index()
    {
        // For demo, get first user or create dummy data
        $user = User::first() ?? $this->getDemoUser();
        
        // Get recent activities for this user
        $recentActivities = ActivityLog::where('user_id', $user->id ?? 1)
            ->latest()
            ->take(5)
            ->get();
        
        // Get statistics
        $stats = [
            'articles_count' => $user->articles()->count() ?? 0,
            'total_views' => $user->articles()->sum('views') ?? 0,
            'login_count' => ActivityLog::where('user_id', $user->id ?? 1)
                ->where('action', 'login')
                ->count(),
            'member_since' => $user->created_at ?? now()->subMonths(6),
        ];
        
        return view('profile.index', compact('user', 'recentActivities', 'stats'));
    }

    /**
     * Update profile information
     */
    public function updateInfo(Request $request)
    {
        $user = User::first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'position' => ['nullable', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:500'],
            'location' => ['nullable', 'string', 'max:100'],
        ]);

        $user->update($validated);

        // Log activity
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'update',
            'model_type' => 'User',
            'model_id' => $user->id,
            'description' => 'Memperbarui informasi profil',
            'new_values' => $validated,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui!',
                'user' => $user->fresh()
            ]);
        }

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Update profile photo
     */
    public function updatePhoto(Request $request)
    {
        $user = User::first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'profile_photo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'], // 5MB max
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
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'update',
            'model_type' => 'User',
            'model_id' => $user->id,
            'description' => 'Memperbarui foto profil',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil diperbarui!',
                'photo_url' => $user->profile_photo
            ]);
        }

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }

    /**
     * Delete profile photo
     */
    public function deletePhoto(Request $request = null)
    {
        $user = User::first();
        
        if (!$user) {
            if ($request && ($request->ajax() || $request->wantsJson())) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan'
                ], 404);
            }
            return back()->with('error', 'User tidak ditemukan');
        }

        if ($user->profile_photo) {
            $this->deletePhotoFile($user->profile_photo);
            
            $user->update(['profile_photo' => null]);

            // Log activity
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'delete',
                'model_type' => 'User',
                'model_id' => $user->id,
                'description' => 'Menghapus foto profil',
                'ip_address' => $request ? $request->ip() : null,
                'user_agent' => $request ? $request->userAgent() : null,
            ]);
        }

        if ($request && ($request->ajax() || $request->wantsJson())) {
            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil dihapus!'
            ]);
        }

        return back()->with('success', 'Foto profil berhasil dihapus!');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $user = User::first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password saat ini tidak sesuai'
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Log activity
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'update',
            'model_type' => 'User',
            'model_id' => $user->id,
            'description' => 'Mengubah password',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diubah!'
            ]);
        }

        return back()->with('success', 'Password berhasil diubah!');
    }

    /**
     * Delete photo file from storage completely
     */
    private function deletePhotoFile(string $photoPath): void
    {
        // Remove /storage/ prefix to get the actual storage path
        $path = str_replace('/storage/', '', $photoPath);
        
        // Delete from public disk
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
        
        // Also check if there's an empty directory and clean it up
        $directory = dirname($path);
        if ($directory !== '.' && Storage::disk('public')->exists($directory)) {
            $files = Storage::disk('public')->files($directory);
            if (empty($files)) {
                Storage::disk('public')->deleteDirectory($directory);
            }
        }
    }

    /**
     * Get demo user data when no user exists
     */
    private function getDemoUser(): object
    {
        return (object) [
            'id' => 1,
            'name' => 'Admin BTIKP',
            'email' => 'admin@btikp.go.id',
            'phone' => '+62 812 3456 7890',
            'position' => 'Super Administrator',
            'bio' => 'Administrator sistem portal berita BTIKP Samarinda.',
            'location' => 'Samarinda, Kalimantan Timur',
            'profile_photo' => null,
            'role' => 'super_admin',
            'created_at' => now()->subMonths(6),
            'last_login_at' => now(),
            'last_login_ip' => '192.168.1.1',
        ];
    }
}
