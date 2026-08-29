<?php

namespace App\Modules\Competitions\Http\Requests;

use App\Modules\Competitions\Http\Requests\ApiRequest;

class UpdateCompetitionRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'season' => ['sometimes', 'string', 'max:50'],
            'start_date' => ['sometimes', 'date'],
            'end_date' => ['sometimes', 'date', 'after:start_date'],
            'type' => ['sometimes', 'string', 'max:50'],
        ];
    }
}
