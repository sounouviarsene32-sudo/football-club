<?php

namespace App\Modules\Events\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray(\Illuminate\Http\Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'date' => $this->date?->format('Y-m-d H:i'),
            'location' => $this->location,
            'type' => $this->type,
            'description' => $this->description,
        ];
    }
}
