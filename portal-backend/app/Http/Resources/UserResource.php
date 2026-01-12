<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $isOwner = $request->user()?->id === $this->id;
        $isAdmin = $request->user()?->canManageUsers();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->when($isOwner || $isAdmin, $this->email),
            'avatar' => $this->avatar,
            'role' => $this->when($isOwner || $isAdmin, $this->role),
            'position' => $this->position,
            'bio' => $this->bio,
            'location' => $this->location,
            'phone' => $this->when($isOwner || $isAdmin, $this->phone),
            'email_verified_at' => $this->when($isOwner || $isAdmin, $this->email_verified_at?->toIso8601String()),
            'articles_count' => $this->whenCounted('articles'),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
