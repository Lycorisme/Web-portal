<?php

namespace App\Http\Controllers;

use App\Models\BlockedClient;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BlockedClientController extends Controller
{
    /**
     * Display the IP block management page.
     */
    public function index()
    {
        return view('blocked-clients.index');
    }

    /**
     * Get blocked clients data with filtering and pagination.
     */
    public function getData(Request $request)
    {
        $query = BlockedClient::query();

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'blocked') {
                $query->where('is_blocked', true);
            } elseif ($request->status === 'unblocked') {
                $query->where('is_blocked', false);
            } elseif ($request->status === 'expired') {
                $query->where('is_blocked', true)
                      ->whereNotNull('blocked_until')
                      ->where('blocked_until', '<', now());
            } elseif ($request->status === 'permanent') {
                $query->where('is_blocked', true)
                      ->whereNull('blocked_until');
            }
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ip_address', 'like', "%{$search}%")
                  ->orWhere('user_agent', 'like', "%{$search}%")
                  ->orWhere('reason', 'like', "%{$search}%")
                  ->orWhere('blocked_route', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortField = $request->input('sort_field', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        // Pagination
        $perPage = $request->input('per_page', 15);
        $data = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $data->items(),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'from' => $data->firstItem() ?? 0,
                'to' => $data->lastItem() ?? 0,
            ],
        ]);
    }

    /**
     * Get blocked clients count for sidebar badge.
     */
    public function getCount()
    {
        $count = BlockedClient::activeBlocks()->count();
        return response()->json(['count' => $count]);
    }

    /**
     * Store a new blocked client (manual block).
     */
    public function store(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip',
            'reason' => 'required|string|max:500',
            'duration' => 'nullable|integer|min:1', // in minutes, null = permanent
        ]);

        // Check if IP already exists
        $existing = BlockedClient::where('ip_address', $request->ip_address)->first();

        if ($existing) {
            $existing->update([
                'is_blocked' => true,
                'reason' => $request->reason,
                'blocked_until' => $request->duration ? now()->addMinutes($request->duration) : null,
                'attempt_count' => $existing->attempt_count + 1,
            ]);
            $blockedClient = $existing;
        } else {
            $blockedClient = BlockedClient::create([
                'ip_address' => $request->ip_address,
                'is_blocked' => true,
                'reason' => $request->reason,
                'blocked_until' => $request->duration ? now()->addMinutes($request->duration) : null,
                'attempt_count' => 1,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'IP berhasil diblokir',
            'data' => $blockedClient,
        ]);
    }

    /**
     * Get blocked client details.
     */
    public function show(BlockedClient $blockedClient)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $blockedClient->id,
                'ip_address' => $blockedClient->ip_address,
                'user_agent' => $blockedClient->user_agent,
                'attempt_count' => $blockedClient->attempt_count,
                'is_blocked' => $blockedClient->is_blocked,
                'blocked_until' => $blockedClient->blocked_until?->format('d M Y, H:i'),
                'blocked_until_raw' => $blockedClient->blocked_until?->toIso8601String(),
                'reason' => $blockedClient->reason,
                'blocked_route' => $blockedClient->blocked_route,
                'is_expired' => $blockedClient->isExpired(),
                'created_at' => $blockedClient->created_at->format('d M Y, H:i'),
                'updated_at' => $blockedClient->updated_at->format('d M Y, H:i'),
            ],
        ]);
    }

    /**
     * Update blocked client.
     */
    public function update(Request $request, BlockedClient $blockedClient)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
            'duration' => 'nullable|integer|min:1',
            'is_blocked' => 'boolean',
        ]);

        $blockedClient->update([
            'reason' => $request->reason,
            'is_blocked' => $request->is_blocked ?? $blockedClient->is_blocked,
            'blocked_until' => $request->filled('duration') 
                ? now()->addMinutes($request->duration) 
                : ($request->has('make_permanent') ? null : $blockedClient->blocked_until),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diperbarui',
            'data' => $blockedClient,
        ]);
    }

    /**
     * Unblock a client.
     */
    public function unblock(BlockedClient $blockedClient)
    {
        $blockedClient->unblock();

        return response()->json([
            'success' => true,
            'message' => 'IP berhasil di-unblock',
        ]);
    }

    /**
     * Delete blocked client record.
     */
    public function destroy(BlockedClient $blockedClient)
    {
        $blockedClient->delete();

        return response()->json([
            'success' => true,
            'message' => 'Record berhasil dihapus',
        ]);
    }

    /**
     * Bulk unblock clients.
     */
    public function bulkUnblock(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:blocked_clients,id',
        ]);

        BlockedClient::whereIn('id', $request->ids)->update([
            'is_blocked' => false,
            'attempt_count' => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => count($request->ids) . ' IP berhasil di-unblock',
        ]);
    }

    /**
     * Bulk delete clients.
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:blocked_clients,id',
        ]);

        BlockedClient::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => count($request->ids) . ' record berhasil dihapus',
        ]);
    }

    /**
     * Clear expired blocks.
     */
    public function clearExpired()
    {
        $count = BlockedClient::where('is_blocked', true)
            ->whereNotNull('blocked_until')
            ->where('blocked_until', '<', now())
            ->update([
                'is_blocked' => false,
                'attempt_count' => 0,
            ]);

        return response()->json([
            'success' => true,
            'message' => $count . ' expired block berhasil dibersihkan',
        ]);
    }
}
