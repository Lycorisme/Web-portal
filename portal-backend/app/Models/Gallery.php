<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gallery extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'image_path',
        'thumbnail_path',
        'media_type',
        'video_url',
        'album',
        'event_date',
        'location',
        'is_featured',
        'is_published',
        'sort_order',
        'uploaded_by',
        'published_at',
        'meta_data',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'sort_order' => 'integer',
        'event_date' => 'date',
        'published_at' => 'datetime',
        'meta_data' => 'array',
    ];

    /**
     * Get the user who uploaded the gallery item.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Scope a query to only include published items.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope a query to only include featured items.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include images.
     */
    public function scopeImages($query)
    {
        return $query->where('media_type', 'image');
    }

    /**
     * Scope a query to only include videos.
     */
    public function scopeVideos($query)
    {
        return $query->where('media_type', 'video');
    }

    /**
     * Scope a query to filter by album.
     */
    public function scopeInAlbum($query, $album)
    {
        return $query->where('album', $album);
    }

    /**
     * Get the full URL for the image.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_path) {
            return null;
        }
        
        return str_starts_with($this->image_path, 'http') 
            ? $this->image_path 
            : asset('storage/' . ltrim($this->image_path, '/'));
    }

    /**
     * Get the full URL for the thumbnail.
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        if (!$this->thumbnail_path) {
            return $this->image_url; // Fallback to main image
        }
        
        return str_starts_with($this->thumbnail_path, 'http') 
            ? $this->thumbnail_path 
            : asset('storage/' . ltrim($this->thumbnail_path, '/'));
    }
}
