<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Gallery;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class TrashController extends Controller
{
    protected array $modelMap = [
        'article' => Article::class,
        'category' => Category::class,
        'tag' => Tag::class,
        'gallery' => Gallery::class,
        'user' => User::class,
        'activity_log' => ActivityLog::class,
    ];

    protected array $typeLabels = [
        'article' => 'Artikel',
        'category' => 'Kategori',
        'tag' => 'Tag',
        'gallery' => 'Galeri',
        'user' => 'User',
        'activity_log' => 'Activity Log',
    ];

    protected array $typeIcons = [
        'article' => 'newspaper',
        'category' => 'folder-tree',
        'tag' => 'tags',
        'gallery' => 'images',
        'user' => 'users',
        'activity_log' => 'activity',
    ];

    public function index()
    {
        $counts = $this->getTrashedCounts();
        
        return view('trash.index', [
            'counts' => $counts,
            'typeLabels' => $this->typeLabels,
            'typeIcons' => $this->typeIcons,
        ]);
    }

    /**
     * Get trash count for sidebar badge (AJAX endpoint)
     */
    public function getCount()
    {
        return response()->json([
            'count' => $this->getTrashedCounts()['all']
        ]);
    }

    public function getData(Request $request)
    {
        $type = $request->input('type', 'all');
        $perPage = $request->input('per_page', 15);
        $search = $request->input('search', '');
        $sortBy = $request->input('sort_by', 'deleted_at');
        $sortDir = $request->input('sort_dir', 'desc');

        $items = collect();

        if ($type === 'all') {
            foreach ($this->modelMap as $modelType => $modelClass) {
                $query = $modelClass::onlyTrashed();
                
                if ($search) {
                    $query = $this->applySearch($query, $modelType, $search);
                }
                
                $trashed = $query->get()->map(function ($item) use ($modelType) {
                    return $this->formatItem($item, $modelType);
                });
                
                $items = $items->merge($trashed);
            }

            // Sort combined results
            $items = $items->sortByDesc('deleted_at_raw');
        } else {
            if (!isset($this->modelMap[$type])) {
                return response()->json(['success' => false, 'message' => 'Invalid type'], 400);
            }

            $modelClass = $this->modelMap[$type];
            $query = $modelClass::onlyTrashed();
            
            if ($search) {
                $query = $this->applySearch($query, $type, $search);
            }

            $items = $query->get()->map(function ($item) use ($type) {
                return $this->formatItem($item, $type);
            });

            // Sort
            $items = $sortDir === 'desc' 
                ? $items->sortByDesc('deleted_at_raw')
                : $items->sortBy('deleted_at_raw');
        }

        // Manual pagination
        $total = $items->count();
        $lastPage = (int) ceil($total / $perPage);
        $currentPage = (int) $request->input('page', 1);
        
        // Ensure current page doesn't exceed last page (fix for empty page bug)
        if ($currentPage > $lastPage && $lastPage > 0) {
            $currentPage = $lastPage;
        }
        if ($currentPage < 1) {
            $currentPage = 1;
        }
        
        $offset = ($currentPage - 1) * $perPage;
        $paginatedItems = $items->slice($offset, $perPage)->values();

        return response()->json([
            'success' => true,
            'data' => $paginatedItems,
            'meta' => [
                'current_page' => (int) $currentPage,
                'last_page' => $lastPage,
                'per_page' => (int) $perPage,
                'total' => $total,
                'from' => $total > 0 ? $offset + 1 : 0,
                'to' => min($offset + $perPage, $total),
            ],
            'counts' => $this->getTrashedCounts(),
        ]);
    }

    protected function applySearch($query, string $type, string $search)
    {
        switch ($type) {
            case 'article':
                return $query->where('title', 'like', "%{$search}%");
            case 'category':
            case 'tag':
                return $query->where('name', 'like', "%{$search}%");
            case 'gallery':
                return $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('alt_text', 'like', "%{$search}%");
                });
            case 'user':
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            case 'activity_log':
                return $query->where(function ($q) use ($search) {
                    $q->where('action', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            default:
                return $query;
        }
    }

    protected function formatItem($item, string $type): array
    {
        $baseData = [
            'id' => $item->id,
            'type' => $type,
            'type_label' => $this->typeLabels[$type],
            'type_icon' => $this->typeIcons[$type],
            'deleted_at' => $item->deleted_at->format('d M Y H:i'),
            'deleted_at_raw' => $item->deleted_at->timestamp,
            'deleted_at_human' => $item->deleted_at->diffForHumans(),
        ];

        switch ($type) {
            case 'article':
                return array_merge($baseData, [
                    'name' => $item->title,
                    'subtitle' => $item->category?->name ?? '-',
                    'extra' => $item->status,
                ]);
            case 'category':
                return array_merge($baseData, [
                    'name' => $item->name,
                    'subtitle' => $item->slug,
                    'extra' => $item->articles_count ?? 0,
                ]);
            case 'tag':
                return array_merge($baseData, [
                    'name' => $item->name,
                    'subtitle' => $item->slug,
                    'extra' => $item->is_active ? 'Aktif' : 'Nonaktif',
                ]);
            case 'gallery':
                return array_merge($baseData, [
                    'name' => $item->title ?: $item->alt_text ?: 'Untitled',
                    'subtitle' => $item->album ?? '-',
                    'extra' => ucfirst($item->media_type ?? 'image'),
                    'thumbnail' => $item->thumbnail_url ?? $item->file_url,
                ]);
            case 'user':
                return array_merge($baseData, [
                    'name' => $item->name,
                    'subtitle' => $item->email,
                    'extra' => ucfirst($item->role ?? 'User'),
                    'role_code' => $item->role,
                    'avatar' => $item->avatar_url,
                ]);
            case 'activity_log':
                return array_merge($baseData, [
                    'name' => $item->action,
                    'subtitle' => $item->description ?? '-',
                    'extra' => $item->level ?? 'info',
                ]);
            default:
                return $baseData;
        }
    }

    protected function getTrashedCounts(): array
    {
        $counts = ['all' => 0];
        
        foreach ($this->modelMap as $type => $modelClass) {
            $count = $modelClass::onlyTrashed()->count();
            $counts[$type] = $count;
            $counts['all'] += $count;
        }
        
        return $counts;
    }

    public function restore(Request $request, string $type, int $id)
    {
        if (!isset($this->modelMap[$type])) {
            \Log::error("Trash Restore: Invalid type {$type}");
            return response()->json(['success' => false, 'message' => 'Invalid type'], 400);
        }

        $modelClass = $this->modelMap[$type];
        
        try {
            // Find item (only in trash)
            $item = $modelClass::onlyTrashed()->find($id);

            if (!$item) {
                \Log::error("Trash Restore: Item not found ID {$id} Type {$type}");
                return response()->json(['success' => false, 'message' => 'Item tidak ditemukan di tong sampah'], 404);
            }

            // Restore item
            $item->restore();

            // Try to log activity, but don't fail if it fails
            try {
                ActivityLog::log(
                    ActivityLog::ACTION_RESTORE,
                    "Memulihkan {$this->typeLabels[$type]}: " . ($item->name ?? $item->title ?? $item->action ?? 'Item'),
                    $item
                );
            } catch (\Exception $logError) {
                \Log::error("Trash Restore Log Error: " . $logError->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => "{$this->typeLabels[$type]} berhasil dipulihkan",
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error("Trash Restore Query Error: " . $e->getMessage());
            // Check for duplicate entry error (Code 23000)
            if ($e->errorInfo[1] == 1062) {
                return response()->json([
                    'success' => false, 
                    'message' => "Gagal memulihkan: Data unik (slug/email/kode) sudah digunakan oleh data aktif lain."
                ], 409);
            }
            return response()->json(['success' => false, 'message' => 'Database error: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memulihkan item: ' . $e->getMessage()], 500);
        }
    }

    public function forceDelete(Request $request, string $type, int $id)
    {
        if (!isset($this->modelMap[$type])) {
            \Log::error("Trash ForceDelete: Invalid type {$type}");
            return response()->json(['success' => false, 'message' => 'Invalid type'], 400);
        }

        $modelClass = $this->modelMap[$type];

        try {
            $item = $modelClass::onlyTrashed()->find($id);

            if (!$item) {
                \Log::error("Trash ForceDelete: Item not found ID {$id} Type {$type}");
                return response()->json(['success' => false, 'message' => 'Item tidak ditemukan'], 404);
            }

            // Protect Super Admin from deletion
            if ($type === 'user' && $item->role === 'super_admin' && !auth()->user()->isSuperAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki wewenang untuk menghapus permanen Super Administrator.',
                ], 403);
            }

            $itemName = $item->name ?? $item->title ?? $item->action ?? 'Item';
            
            // Force delete
            $item->forceDelete();

            try {
                ActivityLog::log(
                    ActivityLog::ACTION_FORCE_DELETE,
                    "Menghapus permanen {$this->typeLabels[$type]}: {$itemName}"
                );
            } catch (\Exception $logError) {
                \Log::error("Trash ForceDelete Log Error: " . $logError->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => "{$this->typeLabels[$type]} berhasil dihapus permanen",
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error("Trash ForceDelete Query Error: " . $e->getMessage());
            // Check for foreign key constraint error (Code 23000)
            if ($e->errorInfo[1] == 1451) {
                return response()->json([
                    'success' => false, 
                    'message' => "Gagal menghapus: Item ini masih digunakan oleh data lain (Artikel, Log, dll)."
                ], 409);
            }
            return response()->json(['success' => false, 'message' => 'Database error: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus item: ' . $e->getMessage()], 500);
        }
    }

    public function bulkRestore(Request $request)
    {
        $items = $request->input('items', []); // Format: [['type' => 'article', 'id' => 1], ...]

        if (empty($items)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada item yang dipilih'], 400);
        }

        $restored = 0;

        DB::beginTransaction();
        try {
            foreach ($items as $item) {
                if (!isset($this->modelMap[$item['type']])) continue;
                
                $modelClass = $this->modelMap[$item['type']];
                $record = $modelClass::onlyTrashed()->find($item['id']);
                
                if ($record) {
                    $record->restore();
                    $restored++;
                }
            }
            DB::commit();

            ActivityLog::log(
                ActivityLog::ACTION_RESTORE,
                "Memulihkan {$restored} item dari tong sampah"
            );

            return response()->json([
                'success' => true,
                'message' => "{$restored} item berhasil dipulihkan",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal memulihkan item'], 500);
        }
    }

    public function bulkForceDelete(Request $request)
    {
        $items = $request->input('items', []);

        if (empty($items)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada item yang dipilih'], 400);
        }

        $deleted = 0;

        DB::beginTransaction();
        try {
            foreach ($items as $item) {
                if (!isset($this->modelMap[$item['type']])) continue;
                
                $modelClass = $this->modelMap[$item['type']];
                $record = $modelClass::onlyTrashed()->find($item['id']);
                
                if ($record) {
                    // Protect Super Admin
                    if ($item['type'] === 'user' && $record->role === 'super_admin' && !auth()->user()->isSuperAdmin()) {
                        continue; // Skip protected users
                    }

                    $record->forceDelete();
                    $deleted++;
                }
            }
            DB::commit();

            ActivityLog::log(
                ActivityLog::ACTION_FORCE_DELETE,
                "Menghapus permanen {$deleted} item dari tong sampah"
            );

            return response()->json([
                'success' => true,
                'message' => "{$deleted} item berhasil dihapus permanen",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menghapus item'], 500);
        }
    }

    public function emptyTrash(Request $request)
    {
        $type = $request->input('type', 'all');
        $deleted = 0;

        DB::beginTransaction();
        try {
            if ($type === 'all') {
                foreach ($this->modelMap as $modelClass) {
                    if ($type === 'user' && !auth()->user()->isSuperAdmin()) {
                        $query = $modelClass::onlyTrashed()->where('role', '!=', 'super_admin');
                        $count = $query->count();
                        $query->forceDelete();
                    } else {
                        $count = $modelClass::onlyTrashed()->count();
                        $modelClass::onlyTrashed()->forceDelete();
                    }
                    $deleted += $count;
                }
            } else {
                if (!isset($this->modelMap[$type])) {
                    return response()->json(['success' => false, 'message' => 'Invalid type'], 400);
                }
                $modelClass = $this->modelMap[$type];
                
                // Special handling for users to protect Super Admin
                if ($type === 'user' && !auth()->user()->isSuperAdmin()) {
                    $query = $modelClass::onlyTrashed()->where('role', '!=', 'super_admin');
                    $deleted = $query->count();
                    $query->forceDelete();
                } else {
                    $deleted = $modelClass::onlyTrashed()->count();
                    $modelClass::onlyTrashed()->forceDelete();
                }
            }
            
            DB::commit();

            ActivityLog::log(
                ActivityLog::ACTION_FORCE_DELETE,
                "Mengosongkan tong sampah ({$deleted} item)"
            );

            return response()->json([
                'success' => true,
                'message' => "{$deleted} item berhasil dihapus permanen",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal mengosongkan tong sampah'], 500);
        }
    }
}
