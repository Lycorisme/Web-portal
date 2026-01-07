<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Article extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'thumbnail',
        'category',
        'category_id',
        'read_time',
        'status',
        'security_status',
        'security_message',
        'security_detail',
        'author_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'views',
        'published_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'datetime',
        'read_time' => 'integer',
        'views' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
            
            // Ensure unique slug
            $originalSlug = $article->slug;
            $count = 1;
            while (static::where('slug', $article->slug)->exists()) {
                $article->slug = $originalSlug . '-' . $count;
                $count++;
            }
        });

        static::updating(function ($article) {
            if ($article->isDirty('title') && !$article->isDirty('slug')) {
                $newSlug = Str::slug($article->title);
                $originalSlug = $newSlug;
                $count = 1;
                while (static::where('slug', $newSlug)->where('id', '!=', $article->id)->exists()) {
                    $newSlug = $originalSlug . '-' . $count;
                    $count++;
                }
                $article->slug = $newSlug;
            }
        });
    }

    /**
     * Get the author of the article.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the category of the article.
     */
    public function categoryRelation(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Scope for published articles.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope for draft articles.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope for pending articles.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for rejected/flagged articles.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Get formatted updated time.
     */
    public function getUpdatedAtHumanAttribute(): string
    {
        return $this->updated_at->diffForHumans();
    }

    /**
     * Get formatted read time.
     */
    public function getReadTimeFormattedAttribute(): string
    {
        return $this->read_time . ' menit baca';
    }

    /**
     * Get the route key for the model.
     * Using ID for admin panel operations.
     */
    public function getRouteKeyName(): string
    {
        return 'id';
    }

    /**
     * Get all comments for this article.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(ArticleComment::class);
    }

    /**
     * Get visible comments (excluding spam/hidden).
     */
    public function visibleComments(): HasMany
    {
        return $this->hasMany(ArticleComment::class)
            ->where('status', 'visible')
            ->whereNull('parent_id')
            ->with(['user', 'visibleReplies']);
    }

    /**
     * Get all likes for this article.
     */
    public function likes(): HasMany
    {
        return $this->hasMany(ArticleLike::class);
    }

    /**
     * Get likes count.
     */
    public function getLikesCountAttribute(): int
    {
        return $this->likes()->count();
    }

    /**
     * Get comments count (visible only).
     */
    public function getCommentsCountAttribute(): int
    {
        return $this->comments()->where('status', 'visible')->count();
    }

    /**
     * Get statistics summary for this article.
     */
    public function getStatisticsAttribute(): array
    {
        return [
            'views' => $this->views ?? 0,
            'likes' => $this->likes_count,
            'comments' => $this->comments_count,
            'shares' => 0, // Future feature placeholder
        ];
    }
}
