<?php

namespace App\Modules\Matches\Http\Requests;

use App\Modules\Matches\Http\Requests\ApiRequest;

class UpdateMatchRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'home_team_id' => ['sometimes', 'integer', 'exists:teams,id'],
            'away_team_id' => ['sometimes', 'integer', 'exists:teams,id'],
            'competition_id' => ['sometimes', 'integer', 'exists:competitions,id'],
            'date' => ['sometimes', 'date'],
            'venue' => ['nullable', 'string', 'max:255'],
            'status' => ['sometimes', 'string', 'max:50'],
            'home_score' => ['nullable', 'integer', 'min:0'],
            'away_score' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
