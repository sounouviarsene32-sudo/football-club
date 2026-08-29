<?php

namespace App\Modules\Trainings\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TrainingResource extends JsonResource
{
    public function toArray(\Illuminate\Http\Request $request): array
    {
        return [
            'id' => $this->id,
            'team_id' => $this->team_id,
            'date' => $this->date?->format('Y-m-d H:i'),
            'location' => $this->location,
            'type' => $this->type,
            'notes' => $this->notes,
        ];
    }
}
