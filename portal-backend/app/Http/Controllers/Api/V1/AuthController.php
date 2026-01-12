<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Login and get access token.
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
            'device_name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak terdaftar',
            ], 401);
        }

        // Check if user is locked
        if ($user->isLocked()) {
            $remainingMinutes = now()->diffInMinutes($user->locked_until);
            return response()->json([
                'success' => false,
                'message' => "Akun terkunci. Coba lagi dalam {$remainingMinutes} menit.",
            ], 423);
        }

        if (!Hash::check($request->password, $user->password)) {
            $failCount = $user->recordFailedLogin();
            
            // Lock after 5 failed attempts
            if ($failCount >= 5) {
                $user->lockFor(15);
                return response()->json([
                    'success' => false,
                    'message' => 'Terlalu banyak percobaan gagal. Akun terkunci selama 15 menit.',
                ], 423);
            }

            return response()->json([
                'success' => false,
                'message' => 'Password salah',
            ], 401);
        }

        // Check if email is verified (optional)
        // if (!$user->hasVerifiedEmail()) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Email belum diverifikasi',
        //     ], 403);
        // }

        // Record successful login
        $user->recordSuccessfulLogin($request->ip());

        // Create token
        $deviceName = $request->device_name ?? 'API Token';
        $token = $user->createToken($deviceName)->plainTextToken;

        // Log activity
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'api_login',
            'description' => "Login via API dari {$deviceName}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => new UserResource($user),
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ]);
    }

    /**
     * Register a new user.
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'member'; // Default role for API registration

        $user = User::create($data);

        // Create token
        $token = $user->createToken('API Token')->plainTextToken;

        // Log activity
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'api_register',
            'description' => 'Registrasi akun via API',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil',
            'data' => [
                'user' => new UserResource($user),
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ], 201);
    }

    /**
     * Get current authenticated user.
     */
    public function user(Request $request): JsonResponse
    {
        $user = $request->user()->loadCount('articles');

        return response()->json([
            'success' => true,
            'data' => new UserResource($user),
        ]);
    }

    /**
     * Update current user profile.
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
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

        $user->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => new UserResource($user),
        ]);
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password saat ini salah',
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Revoke all other tokens
        $user->tokens()->where('id', '!=', $request->user()->currentAccessToken()->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diperbarui',
        ]);
    }

    /**
     * Logout (revoke current token).
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
        ]);
    }

    /**
     * Logout from all devices (revoke all tokens).
     */
    public function logoutAll(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout dari semua perangkat berhasil',
        ]);
    }
}
