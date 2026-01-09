<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display the user management page.
     */
    public function index(Request $request)
    {
        return view('users.index');
    }

    /**
     * Get filtered users (AJAX).
     */
    public function getData(Request $request): JsonResponse
    {
        $query = User::query();

        // Handle status filter (active vs trash)
        if ($request->status === 'trash') {
            $query->onlyTrashed();
        }

        // Apply sorting
        $sortField = $request->get('sort_field', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('is_locked')) {
            if ($request->is_locked === 'true') {
                $query->whereNotNull('locked_until')->where('locked_until', '>', now());
            } else {
                $query->where(function ($q) {
                    $q->whereNull('locked_until')->orWhere('locked_until', '<=', now());
                });
            }
        }

        // Paginate results
        $perPage = $request->get('per_page', 15);
        $users = $query->withCount('articles')->paginate($perPage);

        // Transform data for frontend
        $data = $users->getCollection()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'role_label' => $this->getRoleLabel($user->role),
                'profile_photo' => $user->avatar,
                'phone' => $user->phone,
                'position' => $user->position,
                'bio' => $user->bio,
                'location' => $user->location,
                'articles_count' => $user->articles_count ?? 0,
                'last_login_at' => $user->last_login_at?->format('d M Y H:i'),
                'last_login_ip' => $user->last_login_ip,
                'is_locked' => $user->isLocked(),
                'locked_until' => $user->locked_until?->format('d M Y H:i'),
                'failed_login_count' => $user->failed_login_count,
                'created_at' => $user->created_at->format('d M Y H:i'),
                'created_at_human' => $user->created_at->diffForHumans(),
                'updated_at' => $user->updated_at->format('d M Y H:i'),
                'deleted_at' => $user->deleted_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem(),
            ],
            'links' => [
                'first' => $users->url(1),
                'last' => $users->url($users->lastPage()),
                'prev' => $users->previousPageUrl(),
                'next' => $users->nextPageUrl(),
            ],
        ]);
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:super_admin,admin,author',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'location' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        try {
            $data = $request->only(['name', 'email', 'role', 'phone', 'position', 'bio', 'location']);
            $data['password'] = Hash::make($request->password);

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                $path = $request->file('profile_photo')->store('profile-photos', 'public');
                $data['profile_photo'] = $path;
            }

            $user = User::create($data);

            ActivityLog::log(
                ActivityLog::ACTION_CREATE,
                "Membuat user baru: {$user->name}",
                $user,
                null,
                array_diff_key($user->toArray(), ['password' => '']),
                ActivityLog::LEVEL_INFO
            );

            return response()->json([
                'success' => true,
                'message' => 'User berhasil ditambahkan.',
                'data' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan user: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get single user detail.
     */
    public function show(User $user): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'role_label' => $this->getRoleLabel($user->role),
                'profile_photo' => $user->avatar,
                'phone' => $user->phone,
                'position' => $user->position,
                'bio' => $user->bio,
                'location' => $user->location,
                'articles_count' => $user->articles()->count(),
                'last_login_at' => $user->last_login_at?->format('d M Y H:i:s'),
                'last_login_ip' => $user->last_login_ip,
                'is_locked' => $user->isLocked(),
                'locked_until' => $user->locked_until?->format('d M Y H:i:s'),
                'failed_login_count' => $user->failed_login_count,
                'created_at' => $user->created_at->format('d M Y H:i:s'),
                'updated_at' => $user->updated_at->format('d M Y H:i:s'),
            ],
        ]);
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:super_admin,admin,author',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'location' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        try {
            $data = $request->only(['name', 'email', 'role', 'phone', 'position', 'bio', 'location']);
            
            // Only update password if provided
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                // Delete old photo if exists
                if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                    Storage::disk('public')->delete($user->profile_photo);
                }
                $path = $request->file('profile_photo')->store('profile-photos', 'public');
                $data['profile_photo'] = $path;
            }

            $oldValues = $user->getOriginal();
            $user->update($data);
            $newValues = $user->getChanges();

            ActivityLog::log(
                ActivityLog::ACTION_UPDATE,
                "Mengubah user: {$user->name}",
                $user,
                array_diff_key($oldValues, ['password' => '']),
                array_diff_key($newValues, ['password' => '']),
                ActivityLog::LEVEL_INFO
            );

            return response()->json([
                'success' => true,
                'message' => 'User berhasil diperbarui.',
                'data' => $user->fresh(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui user: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a user (soft delete).
     */
    public function destroy(User $user): JsonResponse
    {
        try {
            // Prevent deleting yourself
            if ($user->id === auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak dapat menghapus akun sendiri.',
                ], 403);
            }

            // Prevent deleting Super Admin by non-Super Admin
            if ($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki wewenang untuk menghapus Super Administrator.',
                ], 403);
            }

            $user->delete();

            ActivityLog::log(
                ActivityLog::ACTION_DELETE,
                "Menghapus user (tong sampah): {$user->name}",
                $user,
                null,
                null,
                ActivityLog::LEVEL_WARNING
            );

            return response()->json([
                'success' => true,
                'message' => 'User berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus user: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk delete users.
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:users,id',
        ]);

        try {
            // Remove current user ID from the list
            $ids = array_filter($request->ids, fn($id) => $id != auth()->id());

            $users = User::whereIn('id', $ids)->get();

            // Check for Super Admin protection
            if (!auth()->user()->isSuperAdmin()) {
                foreach ($users as $user) {
                    if ($user->isSuperAdmin()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Terdapat akun Super Administrator yang tidak dapat Anda hapus.',
                        ], 403);
                    }
                }
            }
            $count = 0;

            foreach ($users as $user) {
                $user->delete();
                $count++;
                
                ActivityLog::log(
                    ActivityLog::ACTION_DELETE,
                    "Menghapus user (massal): {$user->name}",
                    $user,
                    null,
                    null,
                    ActivityLog::LEVEL_WARNING
                );
            }

            return response()->json([
                'success' => true,
                'message' => "{$count} user berhasil dihapus.",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus user: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Restore a soft-deleted user.
     */
    public function restore($id): JsonResponse
    {
        try {
            $user = User::onlyTrashed()->findOrFail($id);
            $user->restore();

            ActivityLog::log(
                ActivityLog::ACTION_RESTORE,
                "Memulihkan user: {$user->name}",
                $user,
                null,
                null,
                ActivityLog::LEVEL_INFO
            );

            return response()->json([
                'success' => true,
                'message' => 'User berhasil dipulihkan.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memulihkan user: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Force delete a user.
     */
    public function forceDelete($id): JsonResponse
    {
        try {
            $user = User::withTrashed()->findOrFail($id);
            
            // Prevent deleting yourself
            if ($user->id === auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak dapat menghapus akun sendiri secara permanen.',
                ], 403);
            }

            // Prevent deleting Super Admin by non-Super Admin
            if ($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki wewenang untuk menghapus permanen Super Administrator.',
                ], 403);
            }

            $oldData = $user->toArray();
            
            // Delete profile photo if exists
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $user->forceDelete();

            ActivityLog::log(
                ActivityLog::ACTION_FORCE_DELETE,
                "Menghapus permanen user: {$user->name}",
                $user,
                array_diff_key($oldData, ['password' => '']),
                null,
                ActivityLog::LEVEL_DANGER
            );

            return response()->json([
                'success' => true,
                'message' => 'User berhasil dihapus permanen.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus permanen user: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk restore users.
     */
    public function bulkRestore(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:users,id',
        ]);

        try {
            $users = User::onlyTrashed()->whereIn('id', $request->ids)->get();
            
            foreach ($users as $user) {
                $user->restore();

                ActivityLog::log(
                    ActivityLog::ACTION_RESTORE,
                    "Memulihkan user (massal): {$user->name}",
                    $user,
                    null,
                    null,
                    ActivityLog::LEVEL_INFO
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'User terpilih berhasil dipulihkan.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memulihkan user: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk force delete users.
     */
    public function bulkForceDelete(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:users,id',
        ]);

        try {
            // Remove current user ID from the list
            $ids = array_filter($request->ids, fn($id) => $id != auth()->id());

            $users = User::withTrashed()->whereIn('id', $ids)->get();
            
            // Check for Super Admin protection
            if (!auth()->user()->isSuperAdmin()) {
                foreach ($users as $user) {
                    if ($user->isSuperAdmin()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Terdapat akun Super Administrator yang tidak dapat Anda hapus permanen.',
                        ], 403);
                    }
                }
            }
            
            foreach ($users as $user) {
                $oldData = $user->toArray();
                
                // Delete profile photo if exists
                if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                    Storage::disk('public')->delete($user->profile_photo);
                }

                $user->forceDelete();

                ActivityLog::log(
                    ActivityLog::ACTION_FORCE_DELETE,
                    "Menghapus permanen user (massal): {$user->name}",
                    $user,
                    array_diff_key($oldData, ['password' => '']),
                    null,
                    ActivityLog::LEVEL_DANGER
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'User terpilih berhasil dihapus permanen.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus permanen user: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Unlock a locked user account.
     */
    public function unlock(User $user): JsonResponse
    {
        try {
            $user->unlock();

            ActivityLog::log(
                ActivityLog::ACTION_UPDATE,
                "Membuka kunci akun user: {$user->name}",
                $user,
                ['locked_until' => $user->getOriginal('locked_until')],
                ['locked_until' => null],
                ActivityLog::LEVEL_INFO
            );

            return response()->json([
                'success' => true,
                'message' => 'Akun user berhasil dibuka kuncinya.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuka kunci akun: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get role label.
     */
    private function getRoleLabel(string $role): string
    {
        return match ($role) {
            'super_admin' => 'Super Admin',
            'admin' => 'Admin',
            'author' => 'Penulis',
            default => ucfirst($role),
        };
    }
}
