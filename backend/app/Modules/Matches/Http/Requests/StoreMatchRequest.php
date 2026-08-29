<?php

namespace App\Modules\Matches\Http\Requests;

use App\Modules\Matches\Http\Requests\ApiRequest;

class StoreMatchRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'home_team_id' => ['required', 'integer', 'exists:teams,id'],
            'away_team_id' => ['required', 'integer', 'exists:teams,id'],
            'competition_id' => ['required', 'integer', 'exists:competitions,id'],
            'date' => ['required', 'date'],
            'venue' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:50'],
            'home_score' => ['nullable', 'integer', 'min:0'],
            'away_score' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
