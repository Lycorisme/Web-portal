<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Display a listing of articles.
     */
    public function index(Request $request)
    {
        $categories = Category::active()->ordered()->get();
        return view('articles.index', compact('categories'));
    }

    /**
     * Get filtered articles (AJAX).
     */
    public function getData(Request $request): JsonResponse
    {
        $query = Article::with(['author', 'categoryRelation']);

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
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($request->filled('article_status')) {
            $query->where('status', $request->article_status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Paginate results
        $perPage = $request->get('per_page', 15);
        $articles = $query->paginate($perPage);

        // Transform data for frontend
        $data = $articles->getCollection()->map(function ($article) {
            return [
                'id' => $article->id,
                'title' => $article->title,
                'slug' => $article->slug,
                'excerpt' => $article->excerpt,
                'content' => $article->content,
                'thumbnail' => $article->thumbnail,
                'category_id' => $article->category_id,
                'category_name' => $article->categoryRelation?->name,
                'category_color' => $article->categoryRelation?->color,
                'category_icon' => $article->categoryRelation?->icon,
                'read_time' => $article->read_time,
                'status' => $article->status,
                'author_id' => $article->author_id,
                'author_name' => $article->author?->name ?? 'Admin',
                'author_avatar' => $article->author?->avatar ?? null,
                'views' => $article->views,
                'meta_title' => $article->meta_title,
                'meta_description' => $article->meta_description,
                'meta_keywords' => $article->meta_keywords,
                'published_at' => $article->published_at?->format('d M Y H:i'),
                'created_at' => $article->created_at->format('d M Y H:i'),
                'created_at_human' => $article->created_at->diffForHumans(),
                'updated_at' => $article->updated_at->format('d M Y H:i'),
                'deleted_at' => $article->deleted_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'meta' => [
                'current_page' => $articles->currentPage(),
                'last_page' => $articles->lastPage(),
                'per_page' => $articles->perPage(),
                'total' => $articles->total(),
                'from' => $articles->firstItem(),
                'to' => $articles->lastItem(),
            ],
            'links' => [
                'first' => $articles->url(1),
                'last' => $articles->url($articles->lastPage()),
                'prev' => $articles->previousPageUrl(),
                'next' => $articles->nextPageUrl(),
            ],
        ]);
    }

    /**
     * Display the specified article.
     */
    public function show(Article $article): JsonResponse
    {
        $article->load(['author', 'categoryRelation']);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $article->id,
                'title' => $article->title,
                'slug' => $article->slug,
                'excerpt' => $article->excerpt,
                'content' => $article->content,
                'thumbnail' => $article->thumbnail,
                'category_id' => $article->category_id,
                'category_name' => $article->categoryRelation?->name,
                'category_color' => $article->categoryRelation?->color,
                'category_icon' => $article->categoryRelation?->icon,
                'read_time' => $article->read_time,
                'status' => $article->status,
                'author_id' => $article->author_id,
                'author_name' => $article->author?->name ?? 'Admin',
                'author_avatar' => $article->author?->avatar ?? null,
                'views' => $article->views,
                'meta_title' => $article->meta_title,
                'meta_description' => $article->meta_description,
                'meta_keywords' => $article->meta_keywords,
                'published_at' => $article->published_at?->format('d M Y H:i'),
                'created_at' => $article->created_at->format('d M Y H:i'),
                'updated_at' => $article->updated_at->format('d M Y H:i'),
            ],
        ]);
    }

    /**
     * Store a newly created article.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:articles,slug',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:2048', // Max 2MB, image file
            'category_id' => 'nullable|exists:categories,id',
            'read_time' => 'nullable|integer|min:1',
            'status' => 'required|in:draft,pending,published,rejected',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
        ]);

        try {
            // Security Check
            $securityIssues = $this->checkContentSecurity($request->all());
            if (!empty($securityIssues)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Konten mengandung kata kunci berbahaya atau terlarang!',
                    'errors' => ['content' => $securityIssues]
                ], 422);
            }

            $data = $request->only([
                'title', 'excerpt', 'content', 'category_id', 
                'read_time', 'status', 'meta_title', 'meta_description', 
                'meta_keywords', 'published_at'
            ]);
            
            // Handle Thumbnail Upload
            if ($request->hasFile('thumbnail')) {
                $path = $request->file('thumbnail')->store('articles/thumbnails', 'public');
                $data['thumbnail'] = '/storage/' . $path;
            }

            // Generate slug if not provided
            $data['slug'] = $request->slug ?: Str::slug($request->title);
            
            // Make slug unique if it already exists
            $originalSlug = $data['slug'];
            $counter = 1;
            while (Article::where('slug', $data['slug'])->exists()) {
                $data['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            // Set author (placeholder - will be current user when auth is implemented)
            $data['author_id'] = 1;
            
            // Set read time if not provided
            if (empty($data['read_time']) && !empty($data['content'])) {
                $wordCount = str_word_count(strip_tags($data['content']));
                $data['read_time'] = max(1, ceil($wordCount / 200));
            }

            // Set published_at for published articles
            if ($data['status'] === 'published' && empty($data['published_at'])) {
                $data['published_at'] = now();
            }

            $article = Article::create($data);

            ActivityLog::log(
                ActivityLog::ACTION_CREATE,
                "Membuat berita baru: {$article->title}",
                $article,
                null,
                $article->toArray(),
                ActivityLog::LEVEL_INFO
            );

            return response()->json([
                'success' => true,
                'message' => 'Berita berhasil ditambahkan.',
                'data' => $article,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan berita: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified article.
     */
    public function update(Request $request, Article $article): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:articles,slug,' . $article->id,
            'excerpt' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'thumbnail' => 'nullable', // Allow string (existing URL) or file
            'category_id' => 'nullable|exists:categories,id',
            'read_time' => 'nullable|integer|min:1',
            'status' => 'required|in:draft,pending,published,rejected',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
        ]);

        try {
             // Security Check
            $securityIssues = $this->checkContentSecurity($request->all());
            if (!empty($securityIssues)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Konten mengandung kata kunci berbahaya atau terlarang!',
                    'errors' => ['content' => $securityIssues]
                ], 422);
            }

            $data = $request->only([
                'title', 'excerpt', 'content', 'category_id', 
                'read_time', 'status', 'meta_title', 'meta_description', 
                'meta_keywords', 'published_at'
            ]);
            
            // Handle Thumbnail Upload
            if ($request->hasFile('thumbnail')) {
                $path = $request->file('thumbnail')->store('articles/thumbnails', 'public');
                $data['thumbnail'] = '/storage/' . $path;
            }

            // Generate slug if not provided
            if ($request->filled('slug')) {
                $data['slug'] = $request->slug;
            } else {
                $data['slug'] = Str::slug($request->title);
            }

            // Make slug unique
            $originalSlug = $data['slug'];
            $counter = 1;
            while (Article::where('slug', $data['slug'])->where('id', '!=', $article->id)->exists()) {
                $data['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }

            // Set read time
            if (empty($data['read_time']) && !empty($data['content'])) {
                $wordCount = str_word_count(strip_tags($data['content']));
                $data['read_time'] = max(1, ceil($wordCount / 200));
            }

            // Set published_at
            if ($data['status'] === 'published' && $article->status !== 'published' && empty($data['published_at'])) {
                $data['published_at'] = now();
            }

            $oldValues = $article->getOriginal();
            $article->update($data);
            $newValues = $article->getChanges();

            ActivityLog::log(
                ActivityLog::ACTION_UPDATE,
                "Mengubah berita: {$article->title}",
                $article,
                $oldValues,
                $newValues,
                ActivityLog::LEVEL_INFO
            );

            return response()->json([
                'success' => true,
                'message' => 'Berita berhasil diperbarui.',
                'data' => $article->fresh(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui berita: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check content for potential security issues (scripts, gambling, etc)
     */
    private function checkContentSecurity(array $data): array
    {
        $issues = [];
        $dangerousPatterns = [
            '/<script.*?>.*?<\/script>/is' => 'Tag Script terdeteksi',
            '/javascript:/i' => 'Protokol Javascript terdeteksi',
            '/onclick|onload|onmouseover|onerror/i' => 'Event Handler berbahaya terdeteksi',
            '/gacor|slot|judol|pragmatic|zeus/i' => 'Kata kunci Judi Online terdeteksi',
            '/<iframe.*?>.*?<\/iframe>/is' => 'Tag Iframe terdeteksi (potensi phising)',
        ];

        $fieldsToCheck = ['title', 'excerpt', 'content', 'meta_title', 'meta_description'];

        foreach ($fieldsToCheck as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                foreach ($dangerousPatterns as $pattern => $message) {
                    if (preg_match($pattern, $data[$field])) {
                        $issues[] = "$message pada field $field";
                    }
                }
            }
        }

        return $issues;
    }

    /**
     * Delete an article (soft delete).
     */
    public function destroy(Article $article): JsonResponse
    {
        try {
            $article->delete();

            ActivityLog::log(
                ActivityLog::ACTION_DELETE,
                "Menghapus berita (tong sampah): {$article->title}",
                $article,
                null,
                null,
                ActivityLog::LEVEL_WARNING
            );

            return response()->json([
                'success' => true,
                'message' => 'Berita berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus berita: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk delete articles.
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:articles,id',
        ]);

        try {
            $articles = Article::whereIn('id', $request->ids)->get();
            $count = 0;

            foreach ($articles as $article) {
                $article->delete();
                $count++;
                
                ActivityLog::log(
                    ActivityLog::ACTION_DELETE,
                    "Menghapus berita (massal): {$article->title}",
                    $article,
                    null,
                    null,
                    ActivityLog::LEVEL_WARNING
                );
            }

            return response()->json([
                'success' => true,
                'message' => "{$count} berita berhasil dihapus.",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus berita: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Restore a soft-deleted article.
     */
    public function restore($id): JsonResponse
    {
        try {
            $article = Article::onlyTrashed()->findOrFail($id);
            $article->restore();

            ActivityLog::log(
                ActivityLog::ACTION_RESTORE,
                "Memulihkan berita: {$article->title}",
                $article,
                null,
                null,
                ActivityLog::LEVEL_INFO
            );

            return response()->json([
                'success' => true,
                'message' => 'Berita berhasil dipulihkan.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memulihkan berita: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Force delete an article.
     */
    public function forceDelete($id): JsonResponse
    {
        try {
            $article = Article::withTrashed()->findOrFail($id);
            $oldData = $article->toArray();
            $article->forceDelete();

            ActivityLog::log(
                ActivityLog::ACTION_FORCE_DELETE,
                "Menghapus permanen berita: {$article->title}",
                $article,
                $oldData,
                null,
                ActivityLog::LEVEL_DANGER
            );

            return response()->json([
                'success' => true,
                'message' => 'Berita berhasil dihapus permanen.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus permanen berita: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk restore articles.
     */
    public function bulkRestore(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:articles,id',
        ]);

        try {
            $articles = Article::onlyTrashed()->whereIn('id', $request->ids)->get();
            
            foreach ($articles as $article) {
                $article->restore();

                ActivityLog::log(
                    ActivityLog::ACTION_RESTORE,
                    "Memulihkan berita (massal): {$article->title}",
                    $article,
                    null,
                    null,
                    ActivityLog::LEVEL_INFO
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Berita terpilih berhasil dipulihkan.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memulihkan berita: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk force delete articles.
     */
    public function bulkForceDelete(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:articles,id',
        ]);

        try {
            $articles = Article::withTrashed()->whereIn('id', $request->ids)->get();
            
            foreach ($articles as $article) {
                $oldData = $article->toArray();
                $article->forceDelete();

                ActivityLog::log(
                    ActivityLog::ACTION_FORCE_DELETE,
                    "Menghapus permanen berita (massal): {$article->title}",
                    $article,
                    $oldData,
                    null,
                    ActivityLog::LEVEL_DANGER
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Berita terpilih berhasil dihapus permanen.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus permanen berita: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle article status.
     */
    public function toggleStatus(Article $article, Request $request): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:draft,pending,published,rejected',
        ]);

        try {
            $oldStatus = $article->status;
            $newStatus = $request->status;

            $updateData = ['status' => $newStatus];
            
            // Set published_at when publishing
            if ($newStatus === 'published' && $oldStatus !== 'published') {
                $updateData['published_at'] = now();
            }

            $article->update($updateData);

            ActivityLog::log(
                ActivityLog::ACTION_UPDATE,
                "Mengubah status berita {$article->title} dari {$oldStatus} menjadi {$newStatus}",
                $article,
                ['status' => $oldStatus],
                ['status' => $newStatus],
                ActivityLog::LEVEL_INFO
            );

            return response()->json([
                'success' => true,
                'message' => 'Status berita berhasil diubah.',
                'status' => $article->status,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status: ' . $e->getMessage(),
            ], 500);
        }
    }
}
