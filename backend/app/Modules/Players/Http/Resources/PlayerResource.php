<?php

namespace App\Modules\Players\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlayerResource extends JsonResource
{
    public function toArray(\Illuminate\Http\Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'position' => $this->position,
            'birth_date' => $this->birth_date?->format('Y-m-d'),
            'nationality' => $this->nationality,
            'team_id' => $this->team_id,
        ];
    }
}
