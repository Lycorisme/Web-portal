<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Display a listing of the articles.
     */
    public function index(Request $request)
    {
        $query = Article::with('author:id,name,email');

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            if ($request->status === 'flagged') {
                $query->rejected();
            } else {
                $query->where('status', $request->status);
            }
        }

        // Search by title or author
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('author', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by category
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category', $request->category);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'updated_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 10);
        $articles = $query->paginate($perPage);

        // Get counts for tabs
        $counts = [
            'all' => Article::count(),
            'published' => Article::published()->count(),
            'draft' => Article::draft()->count(),
            'pending' => Article::pending()->count(),
            'flagged' => Article::rejected()->count(),
        ];

        // Get unique categories
        $categories = Article::select('category')->distinct()->pluck('category');

        return response()->json([
            'success' => true,
            'data' => [
                'articles' => $articles->items(),
                'pagination' => [
                    'current_page' => $articles->currentPage(),
                    'last_page' => $articles->lastPage(),
                    'per_page' => $articles->perPage(),
                    'total' => $articles->total(),
                    'from' => $articles->firstItem(),
                    'to' => $articles->lastItem(),
                ],
                'counts' => $counts,
                'categories' => $categories,
            ],
        ]);
    }

    /**
     * Store a newly created article.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'thumbnail' => 'nullable|string',
            'category' => 'required|string|max:100',
            'status' => 'in:draft,pending,published',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $data['author_id'] = $request->user()->id;
        $data['read_time'] = $this->calculateReadTime($data['content'] ?? '');
        
        // Auto security scan (basic)
        $securityResult = $this->performSecurityScan($data['content'] ?? '', $data['title']);
        $data['security_status'] = $securityResult['status'];
        $data['security_message'] = $securityResult['message'];
        $data['security_detail'] = $securityResult['detail'];

        // If publishing, set published_at
        if (($data['status'] ?? 'draft') === 'published') {
            $data['published_at'] = now();
        }

        $article = Article::create($data);
        $article->load('author:id,name,email');

        return response()->json([
            'success' => true,
            'message' => 'Artikel berhasil dibuat',
            'data' => $article,
        ], 201);
    }

    /**
     * Display the specified article.
     */
    public function show(Article $article)
    {
        $article->load('author:id,name,email');

        return response()->json([
            'success' => true,
            'data' => $article,
        ]);
    }

    /**
     * Update the specified article.
     */
    public function update(Request $request, Article $article)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'thumbnail' => 'nullable|string',
            'category' => 'sometimes|required|string|max:100',
            'status' => 'in:draft,pending,published,rejected',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        // Recalculate read time if content is updated
        if (isset($data['content'])) {
            $data['read_time'] = $this->calculateReadTime($data['content']);
            
            // Re-run security scan
            $securityResult = $this->performSecurityScan($data['content'], $data['title'] ?? $article->title);
            $data['security_status'] = $securityResult['status'];
            $data['security_message'] = $securityResult['message'];
            $data['security_detail'] = $securityResult['detail'];
        }

        // If publishing for first time, set published_at
        if (isset($data['status']) && $data['status'] === 'published' && !$article->published_at) {
            $data['published_at'] = now();
        }

        $article->update($data);
        $article->load('author:id,name,email');

        return response()->json([
            'success' => true,
            'message' => 'Artikel berhasil diperbarui',
            'data' => $article,
        ]);
    }

    /**
     * Remove the specified article.
     */
    public function destroy(Article $article)
    {
        $article->delete();

        return response()->json([
            'success' => true,
            'message' => 'Artikel berhasil dihapus',
        ]);
    }

    /**
     * Bulk delete articles.
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:articles,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $deletedCount = Article::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => "{$deletedCount} artikel berhasil dihapus",
            'deleted_count' => $deletedCount,
        ]);
    }

    /**
     * Bulk update article status.
     */
    public function bulkUpdateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:articles,id',
            'status' => 'required|in:draft,pending,published,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $updateData = ['status' => $request->status];
        
        // If publishing, set published_at for articles that don't have it
        if ($request->status === 'published') {
            Article::whereIn('id', $request->ids)
                   ->whereNull('published_at')
                   ->update(['published_at' => now()]);
        }

        $updatedCount = Article::whereIn('id', $request->ids)->update($updateData);

        return response()->json([
            'success' => true,
            'message' => "{$updatedCount} artikel berhasil diperbarui",
            'updated_count' => $updatedCount,
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

        // Strip HTML tags and count words
        $text = strip_tags($content);
        $wordCount = str_word_count($text);
        
        // Average reading speed: 200 words per minute
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

        // Check for potential XSS/script tags
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

        // Check for suspicious spam patterns in title
        $spamPatterns = [
            '/\bclaim\s+.*\s+prize\b/i',
            '/\bfree\s+money\b/i',
            '/\bclick\s+here\s+now\b/i',
            '/\burgent\b.*\baction\b/i',
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

        // Check for external links (warning level)
        if (preg_match('/<a\b[^>]*href\s*=\s*["\']?https?:\/\/(?!localhost|127\.0\.0\.1)/i', $content)) {
            return [
                'status' => 'warning',
                'message' => 'Review',
                'detail' => 'External links detected',
            ];
        }

        // All checks passed
        return [
            'status' => 'passed',
            'message' => 'Passed',
            'detail' => 'No XSS found',
        ];
    }
}
