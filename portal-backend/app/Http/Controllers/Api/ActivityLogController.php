<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display the specified activity log.
     */
    public function show(ActivityLog $activityLog)
    {
        $activityLog->load('user');

        // Format action labels
        $actionLabels = [
            'CREATE' => 'Buat',
            'UPDATE' => 'Ubah',
            'DELETE' => 'Hapus',
            'LOGIN' => 'Login',
            'LOGOUT' => 'Logout',
            'LOGIN_FAILED' => 'Login Gagal',
        ];

        // Format data for modal display
        $data = [
            'id' => $activityLog->id,
            'action' => $activityLog->action,
            'action_label' => $actionLabels[$activityLog->action] ?? $activityLog->action,
            'level' => $activityLog->level ?? 'info',
            'description' => $activityLog->description,
            'subject_type' => $activityLog->subject_type ? class_basename($activityLog->subject_type) : null,
            'subject_id' => $activityLog->subject_id,
            'old_values' => $activityLog->old_values,
            'new_values' => $activityLog->new_values,
            'user_name' => $activityLog->user->name ?? 'System',
            'user_avatar' => $activityLog->user && $activityLog->user->avatar ? asset('storage/' . $activityLog->user->avatar) : null,
            'ip_address' => $activityLog->ip_address,
            'user_agent' => $activityLog->user_agent,
            'url' => $activityLog->url,
            'created_at' => $activityLog->created_at->format('d M Y, H:i:s'),
            'created_at_human' => $activityLog->created_at->diffForHumans(),
        ];

        return response()->json($data);
    }
}
