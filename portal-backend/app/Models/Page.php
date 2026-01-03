<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Page extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'featured_image',
        'page_type',
        'template',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_published',
        'show_in_menu',
        'menu_icon',
        'menu_order',
        'author_id',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'show_in_menu' => 'boolean',
        'menu_order' => 'integer',
        'published_at' => 'datetime',
    ];

    /**
     * Get the author of the page.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Scope a query to only include published pages.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope a query to only include pages shown in menu.
     */
    public function scopeInMenu($query)
    {
        return $query->where('show_in_menu', true);
    }

    /**
     * Scope a query to filter by page type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('page_type', $type);
    }

    /**
     * Scope a query to order by menu_order.
     */
    public function scopeMenuOrdered($query)
    {
        return $query->orderBy('menu_order', 'asc');
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the SEO title (meta_title or fallback to title).
     */
    public function getSeoTitleAttribute(): string
    {
        return $this->meta_title ?: $this->title;
    }

    /**
     * Get the featured image URL.
     */
    public function getFeaturedImageUrlAttribute(): ?string
    {
        if (!$this->featured_image) {
            return null;
        }
        
        return str_starts_with($this->featured_image, 'http') 
            ? $this->featured_image 
            : asset('storage/' . ltrim($this->featured_image, '/'));
    }
}
