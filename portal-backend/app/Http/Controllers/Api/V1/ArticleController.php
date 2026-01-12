<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Display a listing of published articles (public).
     */
    public function index(Request $request): JsonResponse
    {
        $query = Article::with(['author', 'categoryRelation', 'tags'])
            ->published()
            ->withCount(['likes', 'comments']);

        // Search by title or content
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('categoryRelation', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by tag
        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        // Sorting
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'popular':
                $query->orderByDesc('views');
                break;
            case 'oldest':
                $query->orderBy('published_at', 'asc');
                break;
            default:
                $query->orderByDesc('published_at');
        }

        // Pagination
        $perPage = min($request->get('per_page', 15), 50);
        $articles = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => ArticleResource::collection($articles),
            'meta' => [
                'current_page' => $articles->currentPage(),
                'last_page' => $articles->lastPage(),
                'per_page' => $articles->perPage(),
                'total' => $articles->total(),
            ],
        ]);
    }

    /**
     * Get featured articles (public).
     */
    public function featured(Request $request): JsonResponse
    {
        $limit = min($request->get('limit', 5), 10);
        
        $articles = Article::with(['author', 'categoryRelation'])
            ->published()
            ->withCount(['likes', 'comments'])
            ->orderByDesc('views')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => ArticleResource::collection($articles),
        ]);
    }

    /**
     * Get popular articles (public).
     */
    public function popular(Request $request): JsonResponse
    {
        $limit = min($request->get('limit', 10), 20);
        
        $articles = Article::with(['author', 'categoryRelation'])
            ->published()
            ->withCount(['likes', 'comments'])
            ->orderByDesc('views')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => ArticleResource::collection($articles),
        ]);
    }

    /**
     * Display the specified article by slug (public).
     */
    public function show(string $slug): JsonResponse
    {
        $article = Article::with(['author', 'categoryRelation', 'tags', 'visibleComments.user', 'visibleComments.visibleReplies.user'])
            ->withCount(['likes', 'comments'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        // Increment view count
        $article->increment('views');

        return response()->json([
            'success' => true,
            'data' => new ArticleResource($article),
        ]);
    }

    /**
     * Store a newly created article (authenticated).
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'thumbnail' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'status' => 'in:draft,pending,published',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $data['author_id'] = $request->user()->id;
        $data['read_time'] = $this->calculateReadTime($data['content'] ?? '');

        // Security scan
        $securityResult = $this->performSecurityScan($data['content'] ?? '', $data['title']);
        $data['security_status'] = $securityResult['status'];
        $data['security_message'] = $securityResult['message'];
        $data['security_detail'] = $securityResult['detail'];

        // Check publish permission
        if (($data['status'] ?? 'draft') === 'published') {
            if (!$request->user()->canPublishArticle()) {
                $data['status'] = 'pending';
            } else {
                $data['published_at'] = now();
            }
        }

        $tags = $data['tags'] ?? [];
        unset($data['tags']);

        $article = Article::create($data);
        
        if (!empty($tags)) {
            $article->tags()->sync($tags);
        }

        $article->load(['author', 'categoryRelation', 'tags']);

        // Log activity
        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'create_article',
            'description' => "Membuat artikel via API: {$article->title}",
            'subject_type' => Article::class,
            'subject_id' => $article->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Artikel berhasil dibuat',
            'data' => new ArticleResource($article),
        ], 201);
    }

    /**
     * Update the specified article (authenticated).
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $article = Article::findOrFail($id);

        // Check ownership or admin permission
        if ($article->author_id !== $request->user()->id && !$request->user()->canManageContent()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengedit artikel ini',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'thumbnail' => 'nullable|string',
            'category_id' => 'sometimes|required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'status' => 'in:draft,pending,published,rejected',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        // Recalculate read time if content changed
        if (isset($data['content'])) {
            $data['read_time'] = $this->calculateReadTime($data['content']);
            
            $securityResult = $this->performSecurityScan($data['content'], $data['title'] ?? $article->title);
            $data['security_status'] = $securityResult['status'];
            $data['security_message'] = $securityResult['message'];
            $data['security_detail'] = $securityResult['detail'];
        }

        // Handle publish permission
        if (isset($data['status']) && $data['status'] === 'published') {
            if (!$request->user()->canPublishArticle()) {
                $data['status'] = 'pending';
            } elseif (!$article->published_at) {
                $data['published_at'] = now();
            }
        }

        // Handle tags
        if (isset($data['tags'])) {
            $article->tags()->sync($data['tags']);
            unset($data['tags']);
        }

        $article->update($data);
        $article->load(['author', 'categoryRelation', 'tags']);

        // Log activity
        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'update_article',
            'description' => "Memperbarui artikel via API: {$article->title}",
            'subject_type' => Article::class,
            'subject_id' => $article->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Artikel berhasil diperbarui',
            'data' => new ArticleResource($article),
        ]);
    }

    /**
     * Remove the specified article (authenticated).
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $article = Article::findOrFail($id);

        // Check ownership or admin permission
        if ($article->author_id !== $request->user()->id && !$request->user()->canManageContent()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menghapus artikel ini',
            ], 403);
        }

        $title = $article->title;
        $article->delete();

        // Log activity
        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'delete_article',
            'description' => "Menghapus artikel via API: {$title}",
            'subject_type' => Article::class,
            'subject_id' => $id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Artikel berhasil dihapus',
        ]);
    }

    /**
     * Toggle like on an article (authenticated).
     */
    public function toggleLike(Request $request, string $slug): JsonResponse
    {
        $article = Article::where('slug', $slug)->published()->firstOrFail();
        $user = $request->user();

        $existingLike = $article->likes()->where('user_id', $user->id)->first();

        if ($existingLike) {
            $existingLike->delete();
            $liked = false;
        } else {
            $article->likes()->create(['user_id' => $user->id]);
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $article->likes()->count(),
        ]);
    }

    /**
     * Calculate read time based on content.
     */
    private function calculateReadTime(?string $content): int
    {
        if (empty($content)) {
            return 1;
        }

        $text = strip_tags($content);
        $wordCount = str_word_count($text);
        $readTime = ceil($wordCount / 200);
        
        return max(1, $readTime);
    }

    /**
     * Perform basic security scan on content.
     */
    private function performSecurityScan(?string $content, string $title): array
    {
        if (empty($content)) {
            return [
                'status' => 'pending',
                'message' => 'Belum dipindai',
                'detail' => null,
            ];
        }

        $dangerousPatterns = [
            '/<script\b[^>]*>/i',
            '/javascript:/i',
            '/on\w+\s*=/i',
            '/<iframe\b[^>]*>/i',
            '/<embed\b[^>]*>/i',
        ];

        foreach ($dangerousPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return [
                    'status' => 'danger',
                    'message' => 'Malware',
                    'detail' => 'Script Tag Detected',
                ];
            }
        }

        $spamPatterns = [
            '/\bclaim\s+.*\s+prize\b/i',
            '/\bfree\s+money\b/i',
            '/\bclick\s+here\s+now\b/i',
        ];

        foreach ($spamPatterns as $pattern) {
            if (preg_match($pattern, $title) || preg_match($pattern, $content)) {
                return [
                    'status' => 'danger',
                    'message' => 'Spam Detected',
                    'detail' => 'Suspicious content pattern',
                ];
            }
        }

        if (preg_match('/<a\b[^>]*href\s*=\s*["\']?https?:\/\/(?!localhost|127\.0\.0\.1)/i', $content)) {
            return [
                'status' => 'warning',
                'message' => 'Review',
                'detail' => 'External links detected',
            ];
        }

        return [
            'status' => 'passed',
            'message' => 'Passed',
            'detail' => 'No XSS found',
        ];
    }
}
