<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArticleComment extends Model
{
    /**
     * Blacklisted words for spam detection (gambling, adult content, etc.)
     */
    protected const BLACKLIST_WORDS = [
        // Gambling
        'judi', 'togel', 'slot', 'gacor', 'maxwin', 'scatter', 'jackpot', 'bet88', 
        'pragmatic', 'pg soft', 'joker123', 'deposit pulsa', 'odds', 'bandar',
        'casino', 'poker', 'taruhan', 'rtp live', 'bocoran slot', 'situs slot',
        
        // Suspicious patterns
        'wa.me', 'bit.ly', 't.me', 'telegram.me', 'hubungi kami', 'klik disini',
        'modal kecil', 'uang gratis', 'bonus member', 'keuntungan besar',
        
        // Script injection patterns (already sanitized, but double check)
        '<script', '</script>', 'javascript:', 'onclick', 'onerror', 'onload',
        '<iframe', '</iframe>', 'vbscript:', 'expression(',
    ];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'article_id',
        'user_id',
        'parent_id',
        'comment_text',
        'status',
        'is_admin_reply',
        'ip_address',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_admin_reply' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto sanitize and check spam on creating
        static::creating(function ($comment) {
            // Sanitize content (strip dangerous tags)
            $comment->comment_text = strip_tags($comment->comment_text, '<p><br><strong><em><u>');
            $comment->comment_text = htmlspecialchars_decode($comment->comment_text);
            
            // Check for spam content
            if (self::isSpamContent($comment->comment_text)) {
                $comment->status = 'spam';
            }
        });

        // Log activity for deletion
        static::deleted(function ($comment) {
            if (class_exists('App\Models\ActivityLog')) {
                \App\Models\ActivityLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'delete_comment',
                    'description' => 'Menghapus komentar: "' . \Illuminate\Support\Str::limit($comment->comment_text, 50) . '"',
                    'subject_type' => Article::class,
                    'subject_id' => $comment->article_id,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'properties' => json_encode([
                        'comment_id' => $comment->id,
                        'comment_preview' => \Illuminate\Support\Str::limit($comment->comment_text, 100),
                        'original_author_id' => $comment->user_id,
                    ]),
                ]);
            }
        });
    }

    /**
     * Check if content contains spam/blacklisted words.
     */
    public static function isSpamContent(string $content): bool
    {
        $lowerContent = strtolower($content);
        
        foreach (self::BLACKLIST_WORDS as $word) {
            if (stripos($lowerContent, strtolower($word)) !== false) {
                return true;
            }
        }
        
        // Check for excessive URLs
        $urlPattern = '/(https?:\/\/[^\s]+)/i';
        preg_match_all($urlPattern, $content, $matches);
        if (count($matches[0]) > 2) {
            return true;
        }
        
        return false;
    }

    /**
     * Sanitize comment text for XSS prevention.
     */
    public static function sanitizeContent(string $content): string
    {
        // Remove script tags and dangerous attributes
        $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $content);
        $content = preg_replace('/<iframe\b[^>]*>(.*?)<\/iframe>/is', '', $content);
        
        // Remove event handlers
        $content = preg_replace('/\s+on\w+\s*=\s*["\'][^"\']*["\']/i', '', $content);
        
        // Remove javascript: and vbscript: protocols
        $content = preg_replace('/javascript\s*:/i', '', $content);
        $content = preg_replace('/vbscript\s*:/i', '', $content);
        
        // Basic HTML entities escape for safe display
        return $content;
    }

    /**
     * Get the article that owns the comment.
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Get the user who wrote the comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent comment (for replies).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(ArticleComment::class, 'parent_id');
    }

    /**
     * Get the replies to this comment.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(ArticleComment::class, 'parent_id')->with('user', 'replies');
    }

    /**
     * Get visible replies only.
     */
    public function visibleReplies(): HasMany
    {
        return $this->hasMany(ArticleComment::class, 'parent_id')
            ->where('status', 'visible')
            ->with('user', 'visibleReplies');
    }

    /**
     * Scope for visible comments only.
     */
    public function scopeVisible($query)
    {
        return $query->where('status', 'visible');
    }

    /**
     * Scope for top-level comments only (no parent).
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'id';
    }

    /**
     * Get formatted time ago.
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }
}
