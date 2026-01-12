<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GalleryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'image_url' => $this->image_url,
            'thumbnail_url' => $this->thumbnail_url,
            'media_type' => $this->media_type,
            'video_url' => $this->video_url,
            'album' => $this->album,
            'event_date' => $this->event_date?->toDateString(),
            'location' => $this->location,
            'is_featured' => $this->is_featured,
            'uploader' => new UserResource($this->whenLoaded('uploader')),
            'published_at' => $this->published_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
