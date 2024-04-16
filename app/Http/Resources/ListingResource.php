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
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'books' => $this->books->load('comments'),
            'price' => $this->price,
            'status' => $this->status,
            'images' => $this->images,
            'comments' => $this->comments->each(function ($comment) {
                $comment->setRelation('user', $comment->user()->select('name')->first());
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
