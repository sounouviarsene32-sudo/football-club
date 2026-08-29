<?php

namespace App\Modules\News\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    public function toArray(\Illuminate\Http\Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'published_at' => $this->published_at?->format('Y-m-d H:i'),
            'author_id' => $this->author_id,
        ];
    }
}
