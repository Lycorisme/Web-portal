<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'comment_text' => $this->comment_text,
            'user' => new UserResource($this->whenLoaded('user')),
            'is_admin_reply' => $this->is_admin_reply,
            'replies' => CommentResource::collection($this->whenLoaded('visibleReplies')),
            'replies_count' => $this->whenCounted('replies'),
            'time_ago' => $this->time_ago,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
