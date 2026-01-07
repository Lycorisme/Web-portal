<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleComment;
use App\Models\ArticleLike;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ArticleInteractionController extends Controller
{
    /**
     * Rate limit duration in seconds (60 seconds = 1 minute)
     */
    protected const RATE_LIMIT_SECONDS = 60;

    /**
     * Get article statistics (likes, comments, views).
     */
    public function getStatistics(Article $article): JsonResponse
    {
        $comments = $article->comments()
            ->with(['user:id,name,profile_photo'])
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->get();

        // Structure comments with replies
        $commentsWithReplies = $comments->map(function ($comment) {
            return $this->formatComment($comment);
        });

        // Get recent likes with user info
        $recentLikes = $article->likes()
            ->with('user:id,name,profile_photo')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($like) {
                return [
                    'id' => $like->id,
                    'user_id' => $like->user_id,
                    'user_name' => $like->user->name ?? 'User',
                    'user_avatar' => $like->user->profile_photo ?? null,
                    'liked_at' => $like->created_at->format('d M Y, H:i'),
                    'liked_ago' => $like->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'article_id' => $article->id,
                'article_title' => $article->title,
                'statistics' => [
                    'views' => $article->views ?? 0,
                    'likes' => $article->likes()->count(),
                    'comments' => $article->comments()->where('status', 'visible')->count(),
                    'spam_comments' => $article->comments()->where('status', 'spam')->count(),
                ],
                'comments' => $commentsWithReplies,
                'recent_likes' => $recentLikes,
            ],
        ]);
    }

    /**
     * Format a comment with its replies recursively.
     */
    protected function formatComment(ArticleComment $comment): array
    {
        $replies = $comment->replies()
            ->with('user:id,name,profile_photo')
            ->orderBy('created_at', 'asc')
            ->get();

        return [
            'id' => $comment->id,
            'article_id' => $comment->article_id,
            'user_id' => $comment->user_id,
            'user_name' => $comment->user->name ?? 'User',
            'user_avatar' => $comment->user->profile_photo ?? null,
            'comment_text' => $comment->comment_text,
            'status' => $comment->status,
            'is_admin_reply' => $comment->is_admin_reply,
            'ip_address' => $comment->ip_address,
            'created_at' => $comment->created_at->format('d M Y, H:i'),
            'time_ago' => $comment->created_at->diffForHumans(),
            'replies' => $replies->map(function ($reply) {
                return $this->formatComment($reply);
            }),
        ];
    }

    /**
     * Get comments for an article (for detail modal).
     */
    public function getComments(Article $article): JsonResponse
    {
        $comments = $article->comments()
            ->with(['user:id,name,profile_photo'])
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($comment) {
                return $this->formatComment($comment);
            });

        return response()->json([
            'success' => true,
            'data' => $comments,
            'total' => $article->comments()->count(),
            'visible_count' => $article->comments()->where('status', 'visible')->count(),
        ]);
    }

    /**
     * Admin adds an official reply to a comment.
     */
    public function addAdminReply(Request $request, ArticleComment $comment): JsonResponse
    {
        $validated = $request->validate([
            'comment_text' => 'required|string|min:2|max:2000',
        ]);

        // Rate limiting check
        $rateLimitKey = 'admin_reply_' . ($request->user()->id ?? 1);
        if (Cache::has($rateLimitKey)) {
            $remainingSeconds = Cache::get($rateLimitKey) - time();
            return response()->json([
                'success' => false,
                'message' => 'Terlalu cepat! Tunggu ' . $remainingSeconds . ' detik lagi.',
            ], 429);
        }

        // Sanitize content
        $sanitizedText = ArticleComment::sanitizeContent($validated['comment_text']);

        $reply = ArticleComment::create([
            'article_id' => $comment->article_id,
            'user_id' => auth()->id() ?? 1, // Admin ID
            'parent_id' => $comment->id,
            'comment_text' => $sanitizedText,
            'status' => 'visible', // Admin replies always visible
            'is_admin_reply' => true,
            'ip_address' => $request->ip(),
        ]);

        // Set rate limit
        Cache::put($rateLimitKey, time() + self::RATE_LIMIT_SECONDS, self::RATE_LIMIT_SECONDS);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id() ?? 1,
            'action' => 'admin_reply',
            'description' => 'Membalas komentar: "' . Str::limit($comment->comment_text, 50) . '"',
            'subject_type' => Article::class,
            'subject_id' => $comment->article_id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => json_encode([
                'comment_id' => $comment->id,
                'reply_id' => $reply->id,
                'reply_preview' => Str::limit($sanitizedText, 100),
            ]),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Balasan berhasil ditambahkan.',
            'data' => $this->formatComment($reply),
        ]);
    }

    /**
     * Update comment status (hide/show/mark as spam).
     */
    public function updateCommentStatus(Request $request, ArticleComment $comment): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:visible,hidden,spam',
        ]);

        $oldStatus = $comment->status;
        $comment->update(['status' => $validated['status']]);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id() ?? 1,
            'action' => 'update_comment_status',
            'description' => 'Mengubah status komentar dari "' . $oldStatus . '" ke "' . $validated['status'] . '"',
            'subject_type' => Article::class,
            'subject_id' => $comment->article_id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => json_encode([
                'comment_id' => $comment->id,
                'old_status' => $oldStatus,
                'new_status' => $validated['status'],
            ]),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status komentar berhasil diubah.',
            'data' => [
                'id' => $comment->id,
                'status' => $comment->status,
            ],
        ]);
    }

    /**
     * Delete a comment (soft delete by changing status to hidden).
     */
    public function deleteComment(Request $request, ArticleComment $comment): JsonResponse
    {
        $commentPreview = Str::limit($comment->comment_text, 100);
        $articleId = $comment->article_id;
        $commentId = $comment->id;
        $originalAuthorId = $comment->user_id;

        // Soft delete: change status to hidden instead of actual delete
        // This preserves data for audit trail
        $comment->update(['status' => 'hidden']);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id() ?? 1,
            'action' => 'delete_comment',
            'description' => 'Menghapus komentar (soft delete): "' . Str::limit($commentPreview, 50) . '"',
            'subject_type' => Article::class,
            'subject_id' => $articleId,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => json_encode([
                'comment_id' => $commentId,
                'comment_preview' => $commentPreview,
                'original_author_id' => $originalAuthorId,
                'deletion_type' => 'soft_delete',
            ]),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil dihapus.',
        ]);
    }

    /**
     * Restore a hidden comment.
     */
    public function restoreComment(ArticleComment $comment): JsonResponse
    {
        if ($comment->status !== 'hidden') {
            return response()->json([
                'success' => false,
                'message' => 'Komentar ini tidak dalam status tersembunyi.',
            ], 400);
        }

        $comment->update(['status' => 'visible']);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id() ?? 1,
            'action' => 'restore_comment',
            'description' => 'Memulihkan komentar: "' . Str::limit($comment->comment_text, 50) . '"',
            'subject_type' => Article::class,
            'subject_id' => $comment->article_id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'properties' => json_encode([
                'comment_id' => $comment->id,
            ]),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil dipulihkan.',
        ]);
    }

    /**
     * Permanently delete a comment.
     */
    public function forceDeleteComment(Request $request, ArticleComment $comment): JsonResponse
    {
        $commentPreview = Str::limit($comment->comment_text, 100);
        $articleId = $comment->article_id;
        $commentId = $comment->id;

        // Log before deletion
        ActivityLog::create([
            'user_id' => auth()->id() ?? 1,
            'action' => 'force_delete_comment',
            'description' => 'Menghapus permanen komentar: "' . Str::limit($commentPreview, 50) . '"',
            'subject_type' => Article::class,
            'subject_id' => $articleId,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => json_encode([
                'comment_id' => $commentId,
                'comment_preview' => $commentPreview,
                'deletion_type' => 'permanent',
            ]),
        ]);

        // Delete all replies first
        $comment->replies()->delete();
        
        // Then delete the comment
        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil dihapus permanen.',
        ]);
    }
}
