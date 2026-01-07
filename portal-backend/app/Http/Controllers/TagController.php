<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class TagController extends Controller
{
    /**
     * Display a listing of tags.
     */
    public function index(Request $request)
    {
        return view('tags.index');
    }

    /**
     * Get filtered tags (AJAX).
     */
    public function getData(Request $request): JsonResponse
    {
        $query = Tag::query();

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
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Paginate results
        $perPage = $request->get('per_page', 15);
        $tags = $query->withCount('articles')->paginate($perPage);

        // Transform data for frontend
        $data = $tags->getCollection()->map(function ($tag) {
            return [
                'id' => $tag->id,
                'name' => $tag->name,
                'slug' => $tag->slug,
                'articles_count' => $tag->articles_count,
                'created_at' => $tag->created_at->format('d M Y H:i'),
                'created_at_human' => $tag->created_at->diffForHumans(),
                'updated_at' => $tag->updated_at->format('d M Y H:i'),
                'deleted_at' => $tag->deleted_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'meta' => [
                'current_page' => $tags->currentPage(),
                'last_page' => $tags->lastPage(),
                'per_page' => $tags->perPage(),
                'total' => $tags->total(),
                'from' => $tags->firstItem(),
                'to' => $tags->lastItem(),
            ],
            'links' => [
                'first' => $tags->url(1),
                'last' => $tags->url($tags->lastPage()),
                'prev' => $tags->previousPageUrl(),
                'next' => $tags->nextPageUrl(),
            ],
        ]);
    }

    /**
     * Store a newly created tag.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:tags,slug',
        ]);

        try {
            $data = $request->only(['name']);
            
            // Generate slug if not provided
            $data['slug'] = $request->slug ?: Str::slug($request->name);
            
            // Make slug unique if it already exists
            $originalSlug = $data['slug'];
            $counter = 1;
            while (Tag::where('slug', $data['slug'])->exists()) {
                $data['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }

            $tag = Tag::create($data);

            ActivityLog::log(
                ActivityLog::ACTION_CREATE,
                "Membuat tag baru: {$tag->name}",
                $tag,
                null,
                $tag->toArray(),
                ActivityLog::LEVEL_INFO
            );

            return response()->json([
                'success' => true,
                'message' => 'Tag berhasil ditambahkan.',
                'data' => $tag,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan tag: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get single tag detail.
     */
    public function show(Tag $tag): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $tag->id,
                'name' => $tag->name,
                'slug' => $tag->slug,
                'articles_count' => $tag->articles()->count(),
                'created_at' => $tag->created_at->format('d M Y H:i:s'),
                'updated_at' => $tag->updated_at->format('d M Y H:i:s'),
            ],
        ]);
    }

    /**
     * Update the specified tag.
     */
    public function update(Request $request, Tag $tag): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:tags,slug,' . $tag->id,
        ]);

        try {
            $data = $request->only(['name']);
            
            // Generate slug if not provided
            if ($request->filled('slug')) {
                $data['slug'] = $request->slug;
            } else {
                $data['slug'] = Str::slug($request->name);
            }

            // Make slug unique if it already exists (excluding current tag)
            $originalSlug = $data['slug'];
            $counter = 1;
            while (Tag::where('slug', $data['slug'])->where('id', '!=', $tag->id)->exists()) {
                $data['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }

            $oldValues = $tag->getOriginal();
            $tag->update($data);
            $newValues = $tag->getChanges();

            ActivityLog::log(
                ActivityLog::ACTION_UPDATE,
                "Mengubah tag: {$tag->name}",
                $tag,
                $oldValues,
                $newValues,
                ActivityLog::LEVEL_INFO
            );

            return response()->json([
                'success' => true,
                'message' => 'Tag berhasil diperbarui.',
                'data' => $tag->fresh(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui tag: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a tag (soft delete).
     */
    public function destroy(Tag $tag): JsonResponse
    {
        try {
            $tag->delete();

            ActivityLog::log(
                ActivityLog::ACTION_DELETE,
                "Menghapus tag (tong sampah): {$tag->name}",
                $tag,
                null,
                null,
                ActivityLog::LEVEL_WARNING
            );

            return response()->json([
                'success' => true,
                'message' => 'Tag berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus tag: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk delete tags.
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:tags,id',
        ]);

        try {
            $tags = Tag::whereIn('id', $request->ids)->get();
            $count = 0;

            foreach ($tags as $tag) {
                $tag->delete();
                $count++;
                
                ActivityLog::log(
                    ActivityLog::ACTION_DELETE,
                    "Menghapus tag (massal): {$tag->name}",
                    $tag,
                    null,
                    null,
                    ActivityLog::LEVEL_WARNING
                );
            }

            return response()->json([
                'success' => true,
                'message' => "{$count} tag berhasil dihapus.",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus tag: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Restore a soft-deleted tag.
     */
    public function restore($id): JsonResponse
    {
        try {
            $tag = Tag::onlyTrashed()->findOrFail($id);
            $tag->restore();

            ActivityLog::log(
                ActivityLog::ACTION_RESTORE,
                "Memulihkan tag: {$tag->name}",
                $tag,
                null,
                null,
                ActivityLog::LEVEL_INFO
            );

            return response()->json([
                'success' => true,
                'message' => 'Tag berhasil dipulihkan.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memulihkan tag: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Force delete a tag.
     */
    public function forceDelete($id): JsonResponse
    {
        try {
            $tag = Tag::withTrashed()->findOrFail($id);
            $oldData = $tag->toArray();
            $tag->forceDelete();

            ActivityLog::log(
                ActivityLog::ACTION_FORCE_DELETE,
                "Menghapus permanen tag: {$tag->name}",
                $tag,
                $oldData,
                null,
                ActivityLog::LEVEL_DANGER
            );

            return response()->json([
                'success' => true,
                'message' => 'Tag berhasil dihapus permanen.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus permanen tag: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk restore tags.
     */
    public function bulkRestore(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:tags,id',
        ]);

        try {
            $tags = Tag::onlyTrashed()->whereIn('id', $request->ids)->get();
            
            foreach ($tags as $tag) {
                $tag->restore();

                ActivityLog::log(
                    ActivityLog::ACTION_RESTORE,
                    "Memulihkan tag (massal): {$tag->name}",
                    $tag,
                    null,
                    null,
                    ActivityLog::LEVEL_INFO
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Tag terpilih berhasil dipulihkan.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memulihkan tag: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk force delete tags.
     */
    public function bulkForceDelete(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:tags,id',
        ]);

        try {
            $tags = Tag::withTrashed()->whereIn('id', $request->ids)->get();
            
            foreach ($tags as $tag) {
                $oldData = $tag->toArray();
                $tag->forceDelete();

                ActivityLog::log(
                    ActivityLog::ACTION_FORCE_DELETE,
                    "Menghapus permanen tag (massal): {$tag->name}",
                    $tag,
                    $oldData,
                    null,
                    ActivityLog::LEVEL_DANGER
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Tag terpilih berhasil dihapus permanen.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus permanen tag: ' . $e->getMessage(),
            ], 500);
        }
    }
}
