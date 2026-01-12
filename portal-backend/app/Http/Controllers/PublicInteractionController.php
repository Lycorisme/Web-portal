<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleLike;
use App\Models\ArticleComment;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class PublicInteractionController extends Controller
{
    /**
     * Toggle like for an article
     */
    public function toggleLike(Request $request, Article $article)
    {
        $user = auth()->user();
        
        $existingLike = ArticleLike::where('user_id', $user->id)
            ->where('article_id', $article->id)
            ->first();

        if ($existingLike) {
            // Unlike
            $existingLike->delete();
            $liked = false;
            $message = 'Like dihapus';
        } else {
            // Like
            ArticleLike::create([
                'user_id' => $user->id,
                'article_id' => $article->id,
            ]);
            $liked = true;
            $message = 'Artikel disukai';

            // Log activity
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'like_article',
                'description' => "Menyukai artikel: {$article->title}",
                'subject_type' => Article::class,
                'subject_id' => $article->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'level' => 'info',
            ]);
        }

        $likesCount = $article->likes()->count();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'liked' => $liked,
                'likes_count' => $likesCount,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Store a new comment
     */
    public function storeComment(Request $request, Article $article)
    {
        // Custom validation for JSON response
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'comment_text' => 'required|string|min:3|max:2000',
        ], [
            'comment_text.required' => 'Komentar tidak boleh kosong.',
            'comment_text.min' => 'Komentar minimal 3 karakter.',
            'comment_text.max' => 'Komentar maksimal 2000 karakter.',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first('comment_text'),
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $user = auth()->user();

        // Rate limiting: 1 comment per 30 seconds
        $throttleKey = 'comment:' . $user->id;
        if (RateLimiter::tooManyAttempts($throttleKey, 1)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Mohon tunggu {$seconds} detik sebelum mengirim komentar lagi.",
                ], 429);
            }
            
            return back()->withErrors([
                'comment_text' => "Mohon tunggu {$seconds} detik sebelum mengirim komentar lagi."
            ])->withInput();
        }

        RateLimiter::hit($throttleKey, 30);

        $comment = ArticleComment::create([
            'article_id' => $article->id,
            'user_id' => $user->id,
            'comment_text' => $request->comment_text,
            'ip_address' => $request->ip(),
            'status' => 'visible', // Will be changed to 'spam' if detected by model
        ]);

        // Log activity
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'add_comment',
            'description' => "Menambahkan komentar pada artikel: {$article->title}",
            'subject_type' => Article::class,
            'subject_id' => $article->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'level' => 'info',
            'properties' => json_encode([
                'comment_id' => $comment->id,
                'comment_preview' => \Illuminate\Support\Str::limit($comment->comment_text, 100),
            ]),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Komentar berhasil ditambahkan',
                'comment' => [
                    'id' => $comment->id,
                    'text' => $comment->comment_text,
                    'user' => [
                        'name' => $user->name,
                        'avatar' => $user->avatar_url,
                    ],
                    'time_ago' => $comment->time_ago,
                ],
            ]);
        }

        return back()->with('success', 'Komentar berhasil ditambahkan');
    }

    /**
     * Store a reply to a comment
     */
    public function storeReply(Request $request, ArticleComment $comment)
    {
        // Custom validation for JSON response
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'comment_text' => 'required|string|min:3|max:2000',
        ], [
            'comment_text.required' => 'Balasan tidak boleh kosong.',
            'comment_text.min' => 'Balasan minimal 3 karakter.',
            'comment_text.max' => 'Balasan maksimal 2000 karakter.',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first('comment_text'),
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $user = auth()->user();

        // Rate limiting
        $throttleKey = 'comment:' . $user->id;
        if (RateLimiter::tooManyAttempts($throttleKey, 1)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Mohon tunggu {$seconds} detik sebelum membalas komentar.",
                ], 429);
            }
            
            return back()->withErrors([
                'comment_text' => "Mohon tunggu {$seconds} detik sebelum membalas komentar."
            ])->withInput();
        }

        RateLimiter::hit($throttleKey, 30);

        $reply = ArticleComment::create([
            'article_id' => $comment->article_id,
            'user_id' => $user->id,
            'parent_id' => $comment->id,
            'comment_text' => $request->comment_text,
            'ip_address' => $request->ip(),
            'status' => 'visible',
        ]);

        // Log activity
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'reply_comment',
            'description' => "Membalas komentar pada artikel: {$comment->article->title}",
            'subject_type' => ArticleComment::class,
            'subject_id' => $comment->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'level' => 'info',
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Balasan berhasil ditambahkan',
                'reply' => [
                    'id' => $reply->id,
                    'text' => $reply->comment_text,
                    'user' => [
                        'name' => $user->name,
                        'avatar' => $user->avatar_url,
                    ],
                    'time_ago' => $reply->time_ago,
                ],
            ]);
        }

        return back()->with('success', 'Balasan berhasil ditambahkan');
    }

    /**
     * Update comment
     */
    public function updateComment(Request $request, ArticleComment $comment)
    {
        if ($comment->user_id !== auth()->id()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.',
                ], 403);
            }
            return back()->with('error', 'Unauthorized action.');
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'comment_text' => 'required|string|min:3|max:2000',
        ], [
            'comment_text.required' => 'Komentar tidak boleh kosong.',
            'comment_text.min' => 'Komentar minimal 3 karakter.',
            'comment_text.max' => 'Komentar maksimal 2000 karakter.',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first('comment_text'),
                ], 422);
            }
            return back()->withErrors($validator);
        }

        $comment->update([
            'comment_text' => $request->comment_text,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Komentar berhasil diperbarui',
                'comment' => [
                    'id' => $comment->id,
                    'text' => $comment->comment_text,
                ],
            ]);
        }

        return back()->with('success', 'Komentar berhasil diperbarui');
    }

    /**
     * Delete comment
     */
    public function deleteComment(Request $request, ArticleComment $comment)
    {
        if ($comment->user_id !== auth()->id()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.',
                ], 403);
            }
            return back()->with('error', 'Unauthorized action.');
        }

        $comment->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Komentar berhasil dihapus',
            ]);
        }

        return back()->with('success', 'Komentar berhasil dihapus');
    }
}
