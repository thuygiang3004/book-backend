<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request = null): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'books' => $this->books->load('comments'),
            'price' => $this->price,
            'status' => $this->status,
            'images' => $this->images,
            'comments' => CommentResource::collection($this->comments),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}
