<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
            
            // Ensure unique slug
            $originalSlug = $tag->slug;
            $count = 1;
            while (static::where('slug', $tag->slug)->exists()) {
                $tag->slug = $originalSlug . '-' . $count;
                $count++;
            }
        });

        static::updating(function ($tag) {
            if ($tag->isDirty('name') && !$tag->isDirty('slug')) {
                $newSlug = Str::slug($tag->name);
                $originalSlug = $newSlug;
                $count = 1;
                while (static::where('slug', $newSlug)->where('id', '!=', $tag->id)->exists()) {
                    $newSlug = $originalSlug . '-' . $count;
                    $count++;
                }
                $tag->slug = $newSlug;
            }
        });
    }

    /**
     * Get the articles for the tag.
     */
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class);
    }

    /**
     * Get the route key for the model.
     * Using ID for admin panel operations.
     */
    public function getRouteKeyName(): string
    {
        return 'id';
    }
}
