<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
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
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->when($request->routeIs('api.v1.articles.show'), $this->content),
            'thumbnail' => $this->image_url,
            'category' => new CategoryResource($this->whenLoaded('categoryRelation')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'author' => new UserResource($this->whenLoaded('author')),
            'statistics' => [
                'views' => $this->views ?? 0,
                'likes' => $this->likes_count ?? 0,
                'comments' => $this->comments_count ?? 0,
            ],
            'read_time' => $this->read_time,
            'read_time_formatted' => $this->read_time_formatted,
            'status' => $this->when($request->user()?->canManageContent(), $this->status),
            'meta' => $this->when($request->routeIs('api.v1.articles.show'), [
                'title' => $this->meta_title,
                'description' => $this->meta_description,
                'keywords' => $this->meta_keywords,
            ]),
            'published_at' => $this->published_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
