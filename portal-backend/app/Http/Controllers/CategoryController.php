<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(Request $request)
    {
        return view('categories.index');
    }

    /**
     * Get filtered categories (AJAX).
     */
    public function getData(Request $request): JsonResponse
    {
        $query = Category::query();

        // Handle status filter (active vs trash)
        if ($request->status === 'trash') {
            $query->onlyTrashed();
        }

        // Apply sorting
        $sortField = $request->get('sort_field', 'sort_order');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortField, $sortDirection);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active === 'true' || $request->is_active === '1');
        }

        // Paginate results
        $perPage = $request->get('per_page', 15);
        $categories = $query->withCount('articles')->paginate($perPage);

        // Transform data for frontend
        $data = $categories->getCollection()->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'color' => $category->color,
                'icon' => $category->icon,
                'sort_order' => $category->sort_order,
                'is_active' => $category->is_active,
                'articles_count' => $category->articles_count,
                'created_at' => $category->created_at->format('d M Y H:i'),
                'created_at_human' => $category->created_at->diffForHumans(),
                'updated_at' => $category->updated_at->format('d M Y H:i'),
                'deleted_at' => $category->deleted_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'meta' => [
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
                'per_page' => $categories->perPage(),
                'total' => $categories->total(),
                'from' => $categories->firstItem(),
                'to' => $categories->lastItem(),
            ],
            'links' => [
                'first' => $categories->url(1),
                'last' => $categories->url($categories->lastPage()),
                'prev' => $categories->previousPageUrl(),
                'next' => $categories->nextPageUrl(),
            ],
        ]);
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string|max:1000',
            'color' => 'nullable|string|max:20',
            'icon' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        try {
            $data = $request->only(['name', 'description', 'color', 'icon', 'sort_order', 'is_active']);
            
            // Generate slug if not provided
            $data['slug'] = $request->slug ?: Str::slug($request->name);
            
            // Make slug unique if it already exists
            $originalSlug = $data['slug'];
            $counter = 1;
            while (Category::where('slug', $data['slug'])->exists()) {
                $data['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            // Set default sort_order if not provided
            if (!isset($data['sort_order'])) {
                $data['sort_order'] = Category::max('sort_order') + 1;
            }

            $category = Category::create($data);

            ActivityLog::log(
                ActivityLog::ACTION_CREATE,
                "Membuat kategori baru: {$category->name}",
                $category,
                null,
                $category->toArray(),
                ActivityLog::LEVEL_INFO
            );

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil ditambahkan.',
                'data' => $category,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan kategori: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get single category detail.
     */
    public function show(Category $category): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'color' => $category->color,
                'icon' => $category->icon,
                'sort_order' => $category->sort_order,
                'is_active' => $category->is_active,
                'articles_count' => $category->articles()->count(),
                'created_at' => $category->created_at->format('d M Y H:i:s'),
                'updated_at' => $category->updated_at->format('d M Y H:i:s'),
            ],
        ]);
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, Category $category): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string|max:1000',
            'color' => 'nullable|string|max:20',
            'icon' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        try {
            $data = $request->only(['name', 'description', 'color', 'icon', 'sort_order', 'is_active']);
            
            // Generate slug if not provided
            if ($request->filled('slug')) {
                $data['slug'] = $request->slug;
            } else {
                $data['slug'] = Str::slug($request->name);
            }

            // Make slug unique if it already exists (excluding current category)
            $originalSlug = $data['slug'];
            $counter = 1;
            while (Category::where('slug', $data['slug'])->where('id', '!=', $category->id)->exists()) {
                $data['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }

            $oldValues = $category->getOriginal();
            $category->update($data);
            $newValues = $category->getChanges();

            ActivityLog::log(
                ActivityLog::ACTION_UPDATE,
                "Mengubah kategori: {$category->name}",
                $category,
                $oldValues,
                $newValues,
                ActivityLog::LEVEL_INFO
            );

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil diperbarui.',
                'data' => $category->fresh(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui kategori: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a category (soft delete).
     */
    public function destroy(Category $category): JsonResponse
    {
        try {
            $category->delete();

            ActivityLog::log(
                ActivityLog::ACTION_DELETE,
                "Menghapus kategori (tong sampah): {$category->name}",
                $category,
                null,
                null,
                ActivityLog::LEVEL_WARNING
            );

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus kategori: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk delete categories.
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:categories,id',
        ]);

        try {

            $categories = Category::whereIn('id', $request->ids)->get();
            $count = 0;

            foreach ($categories as $category) {
                $category->delete();
                $count++;
                
                ActivityLog::log(
                    ActivityLog::ACTION_DELETE,
                    "Menghapus kategori (massal): {$category->name}",
                    $category,
                    null,
                    null,
                    ActivityLog::LEVEL_WARNING
                );
            }

            return response()->json([
                'success' => true,
                'message' => "{$count} kategori berhasil dihapus.",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus kategori: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Restore a soft-deleted category.
     */
    public function restore($id): JsonResponse
    {
        try {
            $category = Category::onlyTrashed()->findOrFail($id);
            $category->restore();

            ActivityLog::log(
                ActivityLog::ACTION_RESTORE,
                "Memulihkan kategori: {$category->name}",
                $category,
                null,
                null,
                ActivityLog::LEVEL_INFO
            );

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil dipulihkan.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memulihkan kategori: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Force delete a category.
     */
    public function forceDelete($id): JsonResponse
    {
        try {
            $category = Category::withTrashed()->findOrFail($id);
            $oldData = $category->toArray();
            $category->forceDelete();

            ActivityLog::log(
                ActivityLog::ACTION_FORCE_DELETE,
                "Menghapus permanen kategori: {$category->name}",
                $category,
                $oldData,
                null,
                ActivityLog::LEVEL_DANGER
            );

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil dihapus permanen.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus permanen kategori: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk restore categories.
     */
    public function bulkRestore(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:categories,id',
        ]);

        try {
            $categories = Category::onlyTrashed()->whereIn('id', $request->ids)->get();
            
            foreach ($categories as $category) {
                $category->restore();

                ActivityLog::log(
                    ActivityLog::ACTION_RESTORE,
                    "Memulihkan kategori (massal): {$category->name}",
                    $category,
                    null,
                    null,
                    ActivityLog::LEVEL_INFO
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Kategori terpilih berhasil dipulihkan.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memulihkan kategori: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk force delete categories.
     */
    public function bulkForceDelete(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:categories,id',
        ]);

        try {
            $categories = Category::withTrashed()->whereIn('id', $request->ids)->get();
            
            foreach ($categories as $category) {
                $oldData = $category->toArray();
                $category->forceDelete();

                ActivityLog::log(
                    ActivityLog::ACTION_FORCE_DELETE,
                    "Menghapus permanen kategori (massal): {$category->name}",
                    $category,
                    $oldData,
                    null,
                    ActivityLog::LEVEL_DANGER
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Kategori terpilih berhasil dihapus permanen.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus permanen kategori: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update sort order for categories.
     */
    public function updateSort(Request $request): JsonResponse
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:categories,id',
            'orders.*.sort_order' => 'required|integer|min:0',
        ]);

        try {
            foreach ($request->orders as $order) {
                Category::where('id', $order['id'])->update(['sort_order' => $order['sort_order']]);
            }

            ActivityLog::log(
                ActivityLog::ACTION_UPDATE,
                "Memperbarui urutan kategori",
                null,
                null,
                ['orders_count' => count($request->orders)],
                ActivityLog::LEVEL_INFO
            );

            return response()->json([
                'success' => true,
                'message' => 'Urutan kategori berhasil diperbarui.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui urutan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle category active status.
     */
    public function toggleActive(Category $category): JsonResponse
    {
        try {
            $oldActive = $category->is_active;
            $category->update(['is_active' => !$category->is_active]);

            ActivityLog::log(
                ActivityLog::ACTION_UPDATE,
                "Mengubah status kategori {$category->name} menjadi " . ($category->is_active ? 'Aktif' : 'Nonaktif'),
                $category,
                ['is_active' => $oldActive],
                ['is_active' => $category->is_active],
                ActivityLog::LEVEL_INFO
            );

            return response()->json([
                'success' => true,
                'message' => $category->is_active ? 'Kategori diaktifkan.' : 'Kategori dinonaktifkan.',
                'is_active' => $category->is_active,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status: ' . $e->getMessage(),
            ], 500);
        }
    }
}
