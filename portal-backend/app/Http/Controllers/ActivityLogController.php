<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of activity logs.
     */
    public function index(Request $request)
    {
        // Get users for filter dropdown
        $users = User::select('id', 'name')->orderBy('name')->get();
        
        // Get unique actions for filter
        $actions = ActivityLog::select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        // Get unique levels for filter
        $levels = [
            ActivityLog::LEVEL_INFO => 'Info',
            ActivityLog::LEVEL_WARNING => 'Warning',
            ActivityLog::LEVEL_DANGER => 'Danger',
            ActivityLog::LEVEL_CRITICAL => 'Critical',
        ];

        return view('activity-log.index', compact('users', 'actions', 'levels'));
    }

    /**
     * Get filtered activity logs (AJAX).
     */
    public function getData(Request $request): JsonResponse
    {
        $query = ActivityLog::with('user');

        // Handle status filter (active vs trash)
        // By default, show only active (non-deleted) records
        // If status is 'trash', show only soft-deleted records
        if ($request->status === 'trash') {
            $query->onlyTrashed();
        }
        // Note: If status is 'active' or not provided, SoftDeletes trait 
        // automatically excludes deleted records

        $query->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Paginate results
        $perPage = $request->get('per_page', 15);
        $logs = $query->paginate($perPage);

        // Transform data for frontend
        $data = $logs->getCollection()->map(function ($log) {
            return [
                'id' => $log->id,
                'user_name' => $log->user ? $log->user->name : 'System (Mencurigakan)',
                'user_avatar' => $log->user?->avatar,
                'user_id' => $log->user_id,
                'action' => $log->action,
                'action_label' => $log->action_label,
                'description' => $log->description,
                'level' => $log->user ? $log->level : 'warning', // Force warning visual if system/suspicious
                'level_badge_class' => $log->user ? $log->level_badge_class : 'bg-amber-100 text-amber-800',
                'ip_address' => $log->ip_address,
                'user_agent' => $log->user_agent,
                'url' => $log->url,
                'subject_type' => $log->subject_type ? class_basename($log->subject_type) : null,
                'subject_id' => $log->subject_id,
                'old_values' => $log->old_values,
                'new_values' => $log->new_values,
                'created_at' => $log->created_at->format('d M Y H:i:s'),
                'created_at_human' => $log->created_at->diffForHumans(),
                'deleted_at' => $log->deleted_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'meta' => [
                'current_page' => $logs->currentPage(),
                'last_page' => $logs->lastPage(),
                'per_page' => $logs->perPage(),
                'total' => $logs->total(),
                'from' => $logs->firstItem(),
                'to' => $logs->lastItem(),
            ],
            'links' => [
                'first' => $logs->url(1),
                'last' => $logs->url($logs->lastPage()),
                'prev' => $logs->previousPageUrl(),
                'next' => $logs->nextPageUrl(),
            ],
        ]);
    }

    /**
     * Get single activity log detail.
     */
    public function show(ActivityLog $activityLog): JsonResponse
    {
        $activityLog->load('user');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $activityLog->id,
                'user_name' => $activityLog->user ? $activityLog->user->name : 'System (Mencurigakan)',
                'user_avatar' => $activityLog->user?->avatar,
                'user_email' => $activityLog->user->email ?? null,
                'action' => $activityLog->action,
                'action_label' => $activityLog->action_label,
                'description' => $activityLog->description,
                'level' => $activityLog->user ? $activityLog->level : 'warning',
                'level_badge_class' => $activityLog->user ? $activityLog->level_badge_class : 'bg-amber-100 text-amber-800',
                'ip_address' => $activityLog->ip_address,
                'user_agent' => $activityLog->user_agent,
                'url' => $activityLog->url,
                'subject_type' => $activityLog->subject_type ? class_basename($activityLog->subject_type) : null,
                'subject_id' => $activityLog->subject_id,
                'old_values' => $activityLog->old_values,
                'new_values' => $activityLog->new_values,
                'created_at' => $activityLog->created_at->format('d M Y H:i:s'),
                'created_at_human' => $activityLog->created_at->diffForHumans(),
            ],
        ]);
    }

    /**
     * Delete an activity log.
     */
    public function destroy(ActivityLog $activityLog): JsonResponse
    {
        try {
            $activityLog->delete();

            return response()->json([
                'success' => true,
                'message' => 'Log aktivitas berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus log aktivitas: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk delete activity logs.
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:activity_logs,id',
        ]);

        try {
            $deleted = ActivityLog::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => "{$deleted} log aktivitas berhasil dihapus.",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus log aktivitas: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Clear old logs (older than X days).
     */
    public function clearOld(Request $request): JsonResponse
    {
        $request->validate([
            'days' => 'required|integer|min:1|max:365',
        ]);

        try {
            $date = now()->subDays($request->days);
            $deleted = ActivityLog::where('created_at', '<', $date)->delete();

            return response()->json([
                'success' => true,
                'message' => "{$deleted} log aktivitas yang lebih lama dari {$request->days} hari berhasil dihapus.",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus log aktivitas: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Restore a soft-deleted activity log.
     */
    public function restore($id): JsonResponse
    {
        try {
            $log = ActivityLog::onlyTrashed()->findOrFail($id);
            $log->restore();

            return response()->json([
                'success' => true,
                'message' => 'Log aktivitas berhasil dipulihkan.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memulihkan log: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Force delete an activity log.
     */
    public function forceDelete($id): JsonResponse
    {
        try {
            $log = ActivityLog::withTrashed()->findOrFail($id);
            $log->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'Log aktivitas berhasil dihapus permanen.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus permanen log: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk restore activity logs.
     */
    public function bulkRestore(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:activity_logs,id',
        ]);

        try {
            ActivityLog::onlyTrashed()->whereIn('id', $request->ids)->restore();

            return response()->json([
                'success' => true,
                'message' => 'Log aktivitas terpilih berhasil dipulihkan.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memulihkan log: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk force delete activity logs.
     */
    public function bulkForceDelete(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:activity_logs,id',
        ]);

        try {
            ActivityLog::withTrashed()->whereIn('id', $request->ids)->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'Log aktivitas terpilih berhasil dihapus permanen.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus permanen log: ' . $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Get auto-delete settings.
     */
    public function getSettings(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'enabled' => SiteSetting::get('activity_log_cleanup_enabled', false),
                'retention_days' => (int) SiteSetting::get('activity_log_retention_days', 30),
                'schedule' => SiteSetting::get('activity_log_cleanup_schedule', 'daily'),
                'time' => SiteSetting::get('activity_log_cleanup_time', '00:00'),
            ],
        ]);
    }

    /**
     * Update auto-delete settings.
     */
    public function updateSettings(Request $request): JsonResponse
    {
        $request->validate([
            'enabled' => 'required|boolean',
            'retention_days' => 'required|integer|min:1|max:3650',
            'schedule' => 'required|string|in:daily,weekly,monthly',
            'time' => 'required|date_format:H:i',
        ]);

        try {
            SiteSetting::set('activity_log_cleanup_enabled', $request->enabled);
            SiteSetting::set('activity_log_retention_days', $request->retention_days);
            SiteSetting::set('activity_log_cleanup_schedule', $request->schedule);
            SiteSetting::set('activity_log_cleanup_time', $request->time);

            return response()->json([
                'success' => true,
                'message' => 'Pengaturan auto-delete berhasil disimpan.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan pengaturan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
