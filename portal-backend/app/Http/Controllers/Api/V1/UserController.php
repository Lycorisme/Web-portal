<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of users (authenticated - admin only).
     */
    public function index(Request $request): JsonResponse
    {
        if (!$request->user()->canManageUsers()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk melihat daftar pengguna',
            ], 403);
        }

        $query = User::withCount('articles');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = min($request->get('per_page', 15), 50);
        $users = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => UserResource::collection($users),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ],
        ]);
    }

    /**
     * Display the specified user (public profile).
     */
    public function show(int $id): JsonResponse
    {
        $user = User::withCount('articles')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new UserResource($user),
        ]);
    }

    /**
     * Update the specified user (authenticated - admin only).
     */
    public function update(Request $request, int $id): JsonResponse
    {
        if (!$request->user()->canManageUsers()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengedit pengguna',
            ], 403);
        }

        $user = User::findOrFail($id);

        // Prevent editing super admin by non-super admin
        if ($user->isSuperAdmin() && !$request->user()->isSuperAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat mengedit Super Admin',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
            'role' => 'sometimes|required|in:super_admin,admin,editor,author,member',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:100',
            'bio' => 'nullable|string|max:500',
            'location' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        // Only super admin can set super_admin role
        if (isset($data['role']) && $data['role'] === 'super_admin' && !$request->user()->isSuperAdmin()) {
            unset($data['role']);
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil diperbarui',
            'data' => new UserResource($user),
        ]);
    }

    /**
     * Remove the specified user (authenticated - admin only).
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        if (!$request->user()->canManageUsers()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menghapus pengguna',
            ], 403);
        }

        $user = User::findOrFail($id);

        // Prevent deleting super admin
        if ($user->isSuperAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Super Admin tidak dapat dihapus',
            ], 403);
        }

        // Prevent self-deletion
        if ($user->id === $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat menghapus akun sendiri',
            ], 403);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil dihapus',
        ]);
    }
}
