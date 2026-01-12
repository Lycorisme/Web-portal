<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Article;
use App\Models\ArticleComment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Display comments for an article (public).
     */
    public function index(Request $request, string $articleSlug): JsonResponse
    {
        $article = Article::where('slug', $articleSlug)->published()->firstOrFail();

        $comments = ArticleComment::with(['user', 'visibleReplies.user'])
            ->where('article_id', $article->id)
            ->visible()
            ->topLevel()
            ->withCount('replies')
            ->orderByDesc('created_at')
            ->paginate(min($request->get('per_page', 20), 50));

        return response()->json([
            'success' => true,
            'data' => CommentResource::collection($comments),
            'meta' => [
                'current_page' => $comments->currentPage(),
                'last_page' => $comments->lastPage(),
                'per_page' => $comments->perPage(),
                'total' => $comments->total(),
            ],
        ]);
    }

    /**
     * Store a new comment (authenticated).
     */
    public function store(Request $request, string $articleSlug): JsonResponse
    {
        $article = Article::where('slug', $articleSlug)->published()->firstOrFail();

        $validator = Validator::make($request->all(), [
            'comment_text' => 'required|string|min:3|max:2000',
            'parent_id' => 'nullable|exists:article_comments,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $data['article_id'] = $article->id;
        $data['user_id'] = $request->user()->id;
        $data['ip_address'] = $request->ip();
        $data['status'] = 'visible';
        $data['is_admin_reply'] = $request->user()->canManageContent();

        // Validate parent belongs to same article
        if (!empty($data['parent_id'])) {
            $parent = ArticleComment::find($data['parent_id']);
            if (!$parent || $parent->article_id !== $article->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Komentar induk tidak valid',
                ], 422);
            }
        }

        $comment = ArticleComment::create($data);
        $comment->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil ditambahkan',
            'data' => new CommentResource($comment),
        ], 201);
    }

    /**
     * Update a comment (owner only).
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $comment = ArticleComment::findOrFail($id);

        // Check ownership
        if ($comment->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengedit komentar ini',
            ], 403);
        }

        // Check if comment is within edit window (e.g., 15 minutes)
        if ($comment->created_at->diffInMinutes(now()) > 15) {
            return response()->json([
                'success' => false,
                'message' => 'Waktu edit komentar telah berakhir (maksimal 15 menit)',
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'comment_text' => 'required|string|min:3|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $comment->update($validator->validated());
        $comment->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil diperbarui',
            'data' => new CommentResource($comment),
        ]);
    }

    /**
     * Delete a comment (owner or admin).
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $comment = ArticleComment::findOrFail($id);

        // Check ownership or admin permission
        if ($comment->user_id !== $request->user()->id && !$request->user()->canManageContent()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menghapus komentar ini',
            ], 403);
        }

        // Delete all replies first
        $comment->replies()->delete();
        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil dihapus',
        ]);
    }
}
