<?php

namespace App\Modules\Matches\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MatchResource extends JsonResource
{
    public function toArray(\Illuminate\Http\Request $request): array
    {
        return [
            'id' => $this->id,
            'home_team_id' => $this->home_team_id,
            'away_team_id' => $this->away_team_id,
            'competition_id' => $this->competition_id,
            'date' => $this->date?->format('Y-m-d H:i'),
            'venue' => $this->venue,
            'status' => $this->status,
            'home_score' => $this->home_score,
            'away_score' => $this->away_score,
        ];
    }
}
